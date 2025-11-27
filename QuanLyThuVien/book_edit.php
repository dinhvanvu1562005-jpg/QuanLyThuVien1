<?php
require_once 'functions.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: books.php');
    exit;
}

// Lấy thông tin sách
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$b) {
    header('Location: books.php');
    exit;
}

// Lấy danh sách thể loại
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $title       = trim($_POST['title'] ?? '');
        $author      = trim($_POST['author'] ?? '');
        $category_id = $_POST['category_id'] ?: null;
        $total       = max(0, intval($_POST['total'] ?? $b['total']));
        $isbn        = trim($_POST['isbn'] ?? '');

        if ($title === '') {
            $err = 'Tiêu đề không được để trống.';
        } else {
            // cập nhật available nếu tổng thay đổi
            $available = $b['available'] + ($total - $b['total']);
            if ($available < 0) {
                $available = 0;
            }

            $stmt = $pdo->prepare("
                UPDATE books
                SET title = ?, author = ?, category_id = ?, total = ?, available = ?, isbn = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $author, $category_id, $total, $available, $isbn, $id]);

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
              <?= ((isset($_POST['category_id']) && $_POST['category_id'] == $c['id'])
                   || (!isset($_POST['category_id']) && $b['category_id'] == $c['id']))
                   ? 'selected' : '' ?>>
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

      <div class="form-group">
        <label>ISBN</label>
        <input name="isbn" value="<?= e($_POST['isbn'] ?? $b['isbn']) ?>">
      </div>

      <div class="form-actions">
        <a href="books.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
