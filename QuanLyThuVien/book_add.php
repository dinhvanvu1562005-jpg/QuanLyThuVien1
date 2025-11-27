<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

global $pdo;

// Lấy danh sách thể loại
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        // ----- LẤY DỮ LIỆU TỪ FORM -----
        $code        = trim($_POST['code'] ?? '');        // Mã sách
        $title       = trim($_POST['title'] ?? '');
        $author      = trim($_POST['author'] ?? '');
        $category_id = $_POST['category_id'] ?: null;
        $total       = max(1, intval($_POST['total'] ?? 1));  // Tổng số bản (>=1)
        $cover       = null;

        // ----- KIỂM TRA -----
        if ($code === '') {
            $err = 'Mã sách không được để trống.';
        } elseif ($title === '' || $author === '') {
            $err = 'Tiêu đề và tác giả không được để trống.';
        } else {
            // Kiểm tra trùng mã sách
            $check = $pdo->prepare("SELECT COUNT(*) FROM books WHERE code = ?");
            $check->execute([$code]);
            if ($check->fetchColumn() > 0) {
                $err = 'Mã sách đã tồn tại, hãy nhập mã khác.';
            }
        }

        // ----- UPLOAD ẢNH BÌA (NẾU CÓ) -----
        if (!$err && !empty($_FILES['cover']['name'])) {
            $target_dir = "uploads/books/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $ext     = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];

            if (!in_array($ext, $allowed)) {
                $err = "Chỉ chấp nhận ảnh .jpg, .jpeg, .png, .gif, .webp";
            } else {
                $filename    = uniqid('book_') . '.' . $ext;
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)) {
                    $cover = $filename;
                } else {
                    $err = "Không thể tải ảnh bìa lên.";
                }
            }
        }

        // ----- LƯU VÀO CSDL -----
        if (!$err) {
            $available = $total; // lúc mới nhập: còn = tổng

            // CÁC CỘT: code, title, author, category_id, total, available, cover
            $stmt = $pdo->prepare("
                INSERT INTO books (code, title, author, category_id, total, available, cover)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $code,
                $title,
                $author,
                $category_id,
                $total,
                $available,
                $cover
            ]);

            audit_log('add_book', "Added book: $title (code=$code, id=" . $pdo->lastInsertId() . ")");
            flash_set('success', 'Thêm sách mới thành công.');
            header('Location: books.php');
            exit;
        }
    }
}

include 'header.php';
?>

<div class="book-form-container">
  <div class="book-form-card">
    <h2 class="page-title">📘 Thêm sách mới</h2>

    <?php if ($err): ?>
      <p style="color:#d00000; margin-bottom:12px;"><?= e($err) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <!-- MÃ SÁCH -->
      <div class="form-group">
        <label>Mã sách <span class="required">*</span></label>
        <input name="code" value="<?= e($_POST['code'] ?? '') ?>" required>
      </div>

      <!-- TIÊU ĐỀ -->
      <div class="form-group">
        <label>Tiêu đề <span class="required">*</span></label>
        <input name="title" value="<?= e($_POST['title'] ?? '') ?>" required>
      </div>

      <!-- TÁC GIẢ -->
      <div class="form-group">
        <label>Tác giả <span class="required">*</span></label>
        <input name="author" value="<?= e($_POST['author'] ?? '') ?>" required>
      </div>

      <!-- THỂ LOẠI -->
      <div class="form-group">
        <label>Thể loại</label>
        <select name="category_id">
          <option value="">-- Chọn thể loại --</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= e($c['id']) ?>"
              <?= (isset($_POST['category_id']) && $_POST['category_id'] == $c['id']) ? 'selected' : '' ?>>
              <?= e($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- TỔNG SỐ BẢN -->
      <div class="form-group">
        <label>Tổng số bản <span class="required">*</span></label>
        <input type="number" name="total" min="1"
               value="<?= e($_POST['total'] ?? 1) ?>" required>
      </div>

      <!-- ẢNH BÌA -->
      <div class="form-group">
        <label>Ảnh bìa sách</label>
        <input type="file" name="cover" accept="image/*">
      </div>

      <div class="form-actions">
        <a href="books.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
