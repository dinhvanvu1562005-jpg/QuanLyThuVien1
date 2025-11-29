<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BookDAO.php';
global $pdo;
$bookDao = new BookDAO($pdo);

// Lấy id
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: books.php');
    exit;
}

// Lấy thông tin sách
$b = $bookDao->findById($id);
if (!$b) {
    flash_set('error', 'Không tìm thấy sách.');
    header('Location: books.php');
    exit;
}

// Lấy thể loại
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$err  = '';

// Xử lý submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $code        = trim($_POST['code'] ?? $b['code']);
        $title       = trim($_POST['title'] ?? $b['title']);
        $author      = trim($_POST['author'] ?? $b['author']);
        $category_id = $_POST['category_id'] ?: null;
        $total       = max(0, intval($_POST['total'] ?? $b['total']));

        if ($code === '' || $title === '' || $author === '') {
            $err = 'Mã sách, tiêu đề, tác giả không được để trống.';
        } else {
            // cập nhật available theo chênh lệch total
            $available = $b['available'] + ($total - $b['total']);
            if ($available < 0) $available = 0;

            $data = [
                'code'        => $code,
                'title'       => $title,
                'author'      => $author,
                'category_id' => $category_id,
                'total'       => $total,
                'available'   => $available,
                'cover'       => $b['cover'] ?? null,
            ];

            $bookDao->update($id, $data);
            audit_log('edit_book', "Edited book id=$id title=$title");
            flash_set('success', 'Cập nhật sách thành công.');
            header('Location: books.php');
            exit;
        }
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="assets/css/books.css">

<div class="book-form-container">
  <div class="book-form-card">
    <h2 class="page-title">✏️ Sửa thông tin sách</h2>

    <?php if ($err): ?>
      <p style="color:red;"><?= e($err) ?></p>
    <?php endif; ?>

    <form method="post" class="form-add-book">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label>Mã sách <span class="required">*</span></label>
        <input name="code" value="<?= e($_POST['code'] ?? $b['code']) ?>" required>
      </div>

      <div class="form-group">
        <label>Tiêu đề <span class="required">*</span></label>
        <input name="title" value="<?= e($_POST['title'] ?? $b['title']) ?>" required>
      </div>

      <div class="form-group">
        <label>Tác giả <span class="required">*</span></label>
        <input name="author" value="<?= e($_POST['author'] ?? $b['author']) ?>" required>
      </div>

      <div class="form-group">
        <label>Thể loại</label>
        <select name="category_id">
          <option value="">-- Chọn thể loại --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= e($c['id']) ?>"
              <?= (($_POST['category_id'] ?? $b['category_id']) == $c['id']) ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Tổng số bản</label>
        <input type="number" name="total" min="0"
               value="<?= e($_POST['total'] ?? $b['total']) ?>">
        <small>Hiện còn: <?= e($b['available']) ?> bản. Hệ thống sẽ tự điều chỉnh khi bạn thay đổi tổng.</small>
      </div>

      <div class="form-actions">
        <a href="books.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
