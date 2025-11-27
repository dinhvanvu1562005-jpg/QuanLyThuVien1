<?php
require_once 'functions.php';
require_login();
require_role(['thuthu', 'admin']);

global $pdo;

$err = '';
$success = '';

// Lấy danh sách sách
$sql = "SELECT b.*, c.name AS category_name 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.id
        ORDER BY b.title ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách thể loại
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Sách đang chọn để sửa
$currentId = intval($_GET['id'] ?? 0);

// Xử lý lưu chỉnh sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $currentId = intval($_POST['id'] ?? 0);
        if ($currentId <= 0) {
            $err = 'Không xác định được sách cần sửa.';
        } else {
            // Lấy thông tin hiện tại của sách để tính lại available
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$currentId]);
            $oldBook = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$oldBook) {
                $err = 'Sách không tồn tại.';
            } else {
                $title       = trim($_POST['title'] ?? '');
                $author      = trim($_POST['author'] ?? '');
                $category_id = $_POST['category_id'] ?: null;
                $total       = max(0, intval($_POST['total'] ?? 0));
                $isbn        = trim($_POST['isbn'] ?? '');

                if ($title === '') {
                    $err = 'Tên sách không được để trống.';
                } elseif ($author === '') {
                    $err = 'Tác giả không được để trống.';
                } else {
                    // Tính lại available: giữ số lượng đang mượn
                    $diff      = $total - $oldBook['total'];
                    $available = $oldBook['available'] + $diff;
                    if ($available < 0) $available = 0;

                    $update = $pdo->prepare(
                        "UPDATE books 
                         SET title = ?, author = ?, category_id = ?, total = ?, available = ?, isbn = ?
                         WHERE id = ?"
                    );
                    $update->execute([
                        $title,
                        $author,
                        $category_id,
                        $total,
                        $available,
                        $isbn,
                        $currentId
                    ]);

                    audit_log('edit_book', "Edit book id=$currentId title=$title");
                    $success = 'Cập nhật thông tin sách thành công.';
                }
            }
        }
    }
}

// Sau khi submit xong, lấy lại thông tin sách đang chọn
$currentBook = null;
if ($currentId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$currentId]);
    $currentBook = $stmt->fetch(PDO::FETCH_ASSOC);
}

include 'header.php';
?>

<div class="edit-wrapper">

  <h2 class="edit-title">Giao diện chỉnh sửa sách</h2>

  <!-- ===== DANH SÁCH SÁCH ===== -->
  <h3 class="edit-section-title">Danh sách sách</h3>

  <div class="table-wrapper">
    <table class="styled-table simple-table">
      <thead>
        <tr>
          <th>Tên</th>
          <th>Tác giả</th>
          <th>Thể loại</th>
          <th>Số lượng</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($books)): ?>
        <tr>
          <td colspan="5" class="no-data">Chưa có sách nào trong hệ thống.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($books as $b): ?>
          <tr<?= ($currentId == $b['id']) ? ' class="row-active"' : '' ?>>
            <td><?= e($b['title']) ?></td>
            <td><?= e($b['author']) ?></td>
            <td><?= e($b['category_name']) ?></td>
            <td><?= e($b['total']) ?></td>
            <td>
              <a href="book_edit_list.php?id=<?= e($b['id']) ?>" class="btn-small">
                Sửa
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- ===== FORM SỬA THÔNG TIN SÁCH ===== -->
  <h3 class="edit-section-title">Sửa thông tin sách</h3>

  <?php if ($err): ?>
    <p class="edit-msg error"><?= e($err) ?></p>
  <?php elseif ($success): ?>
    <p class="edit-msg success"><?= e($success) ?></p>
  <?php endif; ?>

  <?php if (!$currentBook): ?>
    <p>Vui lòng chọn một cuốn sách trong danh sách bên trên để sửa.</p>
  <?php else: ?>
    <form method="post" class="edit-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= e($currentBook['id']) ?>">

      <div class="form-row">
        <label>Tên sách</label>
        <input type="text" name="title"
               value="<?= e($_POST['title'] ?? $currentBook['title']) ?>" required>
      </div>

      <div class="form-row">
        <label>Tác giả</label>
        <input type="text" name="author"
               value="<?= e($_POST['author'] ?? $currentBook['author']) ?>" required>
      </div>

      <div class="form-row">
        <label>Thể loại</label>
        <select name="category_id">
          <option value="">-- Chọn thể loại --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= e($c['id']) ?>"
              <?= (($_POST['category_id'] ?? $currentBook['category_id']) == $c['id']) ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <label>Số lượng</label>
        <input type="number" min="0" name="total"
               value="<?= e($_POST['total'] ?? $currentBook['total']) ?>">
      </div>

      <div class="form-row">
        <label>ISBN</label>
        <input type="text" name="isbn"
               value="<?= e($_POST['isbn'] ?? $currentBook['isbn']) ?>">
      </div>

      <div class="form-actions">
        <a href="book_edit_list.php" class="btn-cancel">Hủy</a>
        <button type="submit" class="btn-save">Lưu</button>
      </div>
    </form>
  <?php endif; ?>

</div>

<?php include 'footer.php'; ?>
