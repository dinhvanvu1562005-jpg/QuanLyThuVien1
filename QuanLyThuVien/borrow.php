<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

global $pdo;

$err = '';

// Lấy danh sách sách còn available > 0
$books = $pdo->query("
    SELECT id, code, title, author, available 
    FROM books 
    WHERE available > 0 
    ORDER BY title
")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bạn đọc
$readers = $pdo->query("
    SELECT id, fullname 
    FROM readers 
    ORDER BY fullname
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $book_id   = intval($_POST['book_id'] ?? 0);
        $reader_id = intval($_POST['reader_id'] ?? 0);
        $borrow_date = trim($_POST['borrow_date'] ?? '');
        $due_date    = trim($_POST['due_date'] ?? '');

        if ($book_id <= 0) {
            $err = 'Vui lòng chọn sách.';
        } elseif ($reader_id <= 0) {
            $err = 'Vui lòng chọn bạn đọc.';
        } elseif ($borrow_date === '' || $due_date === '') {
            $err = 'Vui lòng chọn ngày mượn và hạn trả.';
        } else {
            // Kiểm tra sách còn không
            $stmt = $pdo->prepare("SELECT available FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book || $book['available'] <= 0) {
                $err = 'Sách đã hết số bản có thể mượn.';
            } else {
                // Tạo phiếu mượn: bảng borrow
                $stmt = $pdo->prepare("
                    INSERT INTO borrow (reader_id, user_id, book_id, borrow_date, due_date, status)
                    VALUES (?, ?, ?, ?, ?, 'dang_muon')
                ");
                $stmt->execute([
                    $reader_id,
                    $_SESSION['user_id'] ?? null,
                    $book_id,
                    $borrow_date,
                    $due_date
                ]);

                // Giảm số lượng còn lại trong bảng books
                $upd = $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = ?");
                $upd->execute([$book_id]);

                audit_log('add_borrow', "Borrow book_id=$book_id reader_id=$reader_id");
                flash_set('success', 'Tạo phiếu mượn thành công.');
                header('Location: borrow.php');
                exit;
            }
        }
    }
}

include 'header.php';
?>

<div class="book-form-container">
  <div class="book-form-card">
    <h2 class="page-title">📘 Tạo phiếu mượn</h2>

    <?php if ($err): ?>
      <p style="color:#d00000; margin-bottom:12px;"><?= e($err) ?></p>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <!-- CHỌN SÁCH -->
      <div class="form-group">
        <label>Sách <span class="required">*</span></label>
        <select name="book_id" required>
          <option value="">-- Chọn sách --</option>
          <?php foreach ($books as $b): ?>
            <option value="<?= e($b['id']) ?>"
              <?= (($_POST['book_id'] ?? '') == $b['id']) ? 'selected' : '' ?>>
              <?= e($b['title']) ?>
              (mã: <?= e($b['code']) ?>, còn: <?= e($b['available']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- CHỌN BẠN ĐỌC -->
      <div class="form-group">
        <label>Bạn đọc <span class="required">*</span></label>
        <select name="reader_id" required>
          <option value="">-- Chọn bạn đọc --</option>
          <?php foreach ($readers as $r): ?>
            <option value="<?= e($r['id']) ?>"
              <?= (($_POST['reader_id'] ?? '') == $r['id']) ? 'selected' : '' ?>>
              <?= e($r['fullname']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- NGÀY MƯỢN / HẠN TRẢ -->
      <div class="form-group">
        <label>Ngày mượn <span class="required">*</span></label>
        <input type="date" name="borrow_date"
               value="<?= e($_POST['borrow_date'] ?? date('Y-m-d')) ?>" required>
      </div>

      <div class="form-group">
        <label>Hạn trả <span class="required">*</span></label>
        <input type="date" name="due_date"
               value="<?= e($_POST['due_date'] ?? '') ?>" required>
      </div>

      <div class="form-actions">
        <a href="borrow.php" class="btn-cancel">⬅ Hủy</a>
        <button type="submit" class="btn-save">💾 Lưu phiếu mượn</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>


