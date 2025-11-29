<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/ReaderDAO.php';
global $pdo;
$readerDao = new ReaderDAO($pdo);

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: readers.php');
    exit;
}

$r = $readerDao->findById($id);
if (!$r) {
    flash_set('error', 'Không tìm thấy bạn đọc.');
    header('Location: readers.php');
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $fullname = trim($_POST['fullname'] ?? $r['fullname']);
        $email    = trim($_POST['email'] ?? $r['email']);
        $phone    = trim($_POST['phone'] ?? $r['phone']);

        if ($fullname === '') {
            $err = 'Họ và tên không được để trống.';
        } else {
            $readerDao->update($id, [
                'fullname' => $fullname,
                'email'    => $email,
                'phone'    => $phone,
            ]);
            audit_log('edit_reader', "Edit reader id=$id name=$fullname");
            flash_set('success', 'Cập nhật bạn đọc thành công.');
            header('Location: readers.php');
            exit;
        }
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="assets/css/books.css">

<div class="book-form-container">
  <div class="book-form-card">
    <h2 class="page-title">✏️ Sửa bạn đọc</h2>

    <?php if ($err): ?>
      <p style="color:red;"><?= e($err) ?></p>
    <?php endif; ?>

    <form method="post" class="form-add-reader">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label>Họ và tên <span class="required">*</span></label>
        <input name="fullname" value="<?= e($_POST['fullname'] ?? $r['fullname']) ?>" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= e($_POST['email'] ?? $r['email']) ?>">
      </div>

      <div class="form-group">
        <label>Điện thoại</label>
        <input name="phone" value="<?= e($_POST['phone'] ?? $r['phone']) ?>">
      </div>

      <div class="form-actions">
        <a href="readers.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
