<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BookDAO.php';
global $pdo;
$bookDao = new BookDAO($pdo);

// Lấy danh sách thể loại
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$err  = '';

// XỬ LÝ FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $code        = trim($_POST['code'] ?? '');
        $title       = trim($_POST['title'] ?? '');
        $author      = trim($_POST['author'] ?? '');
        $category_id = $_POST['category_id'] ?: null;
        $total       = max(1, intval($_POST['total'] ?? 1));
        $cover       = null;

        if ($code === '' || $title === '' || $author === '') {
            $err = 'Mã sách, tiêu đề, tác giả không được để trống.';
        } else {
            // check trùng mã
            $check = $pdo->prepare("SELECT COUNT(*) FROM books WHERE code = ?");
            $check->execute([$code]);
            if ($check->fetchColumn() > 0) {
                $err = 'Mã sách đã tồn tại, hãy nhập mã khác.';
            }
        }

        // upload ảnh (nếu có)
        if (!$err && !empty($_FILES['cover']['name'])) {
            $target_dir = "uploads/books/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $ext     = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed)) {
                $err = 'Chỉ chấp nhận ảnh .jpg, .jpeg, .png, .gif, .webp';
            } else {
                $filename    = uniqid('book_') . '.' . $ext;
                $target_file = $target_dir . $filename;
                if (move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)) {
                    $cover = $filename;
                } else {
                    $err = 'Không thể tải ảnh bìa lên.';
                }
            }
        }

        if (!$err) {
            $data = [
                'code'        => $code,
                'title'       => $title,
                'author'      => $author,
                'category_id' => $category_id,
                'total'       => $total,
                'available'   => $total,
                'cover'       => $cover,
            ];

            $newId = $bookDao->insert($data);
            audit_log('add_book', "Added book id=$newId code=$code title=$title");
            flash_set('success', 'Thêm sách mới thành công.');
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
    <h2 class="page-title">📘 Thêm sách mới</h2>

    <?php if ($err): ?>
      <p style="color:red;"><?= e($err) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form-add-book">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label>Mã sách <span class="required">*</span></label>
        <input name="code" value="<?= e($_POST['code'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Tiêu đề <span class="required">*</span></label>
        <input name="title" value="<?= e($_POST['title'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Tác giả <span class="required">*</span></label>
        <input name="author" value="<?= e($_POST['author'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Thể loại</label>
        <select name="category_id">
          <option value="">-- Chọn thể loại --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= e($c['id']) ?>"
              <?= (($_POST['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Tổng số bản <span class="required">*</span></label>
        <input type="number" name="total" min="1"
               value="<?= e($_POST['total'] ?? 1) ?>" required>
      </div>

      <div class="form-group">
        <label>Ảnh bìa sách</label>
        <input type="file" name="cover" accept="image/*" onchange="previewImage(event)">
        <div id="coverPreview" class="preview-box"></div>
      </div>

      <div class="form-actions">
        <a href="books.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu</button>
      </div>
    </form>
  </div>
</div>

<script>
function previewImage(event) {
  const preview = document.getElementById('coverPreview');
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.innerHTML = `<img src="${e.target.result}" alt="Ảnh xem trước">`;
    };
    reader.readAsDataURL(file);
  } else {
    preview.innerHTML = '';
  }
}
</script>

<?php include 'footer.php'; ?>


