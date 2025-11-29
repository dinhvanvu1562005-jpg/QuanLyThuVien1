<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BorrowDAO.php';
require_once __DIR__ . '/dao/BookDAO.php';
require_once __DIR__ . '/dao/ReaderDAO.php';

global $pdo;
$borrowDao = new BorrowDAO($pdo);
$bookDao   = new BookDAO($pdo);
$readerDao = new ReaderDAO($pdo);

$err = '';

// Lấy danh sách sách còn available > 0
$books = $pdo->query("
    SELECT id, code, title, available
    FROM books
    WHERE available > 0
    ORDER BY title ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Danh sách bạn đọc
$readers = $pdo->query("
    SELECT id, fullname, code
    FROM readers
    ORDER BY fullname ASC
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $book_id     = intval($_POST['book_id'] ?? 0);
        $reader_id   = intval($_POST['reader_id'] ?? 0);
        $borrow_date = $_POST['borrow_date'] ?: date('Y-m-d');
        $due_date    = $_POST['due_date']    ?: date('Y-m-d', strtotime('+7 days'));

        if (!$book_id || !$reader_id) {
            $err = 'Vui lòng chọn sách và bạn đọc.';
        } else {
            // Tạo phiếu mượn
            $borrowId = $borrowDao->createBorrow($book_id, $reader_id, $borrow_date, $due_date);

            // Trừ số lượng còn lại của sách
            $pdo->prepare("UPDATE books SET available = available - 1 WHERE id = ?")
                ->execute([$book_id]);

            audit_log('create_borrow', "borrow_id=$borrowId book_id=$book_id reader_id=$reader_id");
            flash_set('success', 'Tạo phiếu mượn thành công.');
            header('Location: borrow_list.php');
            exit;
        }
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="assets/style.css?v=4">

<div class="book-form-container">
  <div class="book-form-card">

    <!-- Tiêu đề giống form thêm sách -->
    <h2 class="page-title">
      📘 Tạo phiếu mượn sách
    </h2>

    <!-- Hai nút tắt nhanh bên trên giống style đẹp -->
    <div style="display:flex; justify-content:center; gap:10px; margin-bottom:18px;">
      <a href="borrow_list.php" class="btn btn-outline btn-small-pill">
        📋 Danh sách phiếu mượn
      </a>
      <a href="readers.php" class="btn btn-outline btn-small-pill">
        👥 Quản lý bạn đọc
      </a>
    </div>

    <!-- Hộp lưu ý nhỏ ở giữa -->
    <div style="
        background:#fff7e6;
        border:1px solid #ffd27f;
        border-radius:10px;
        padding:10px 14px;
        margin-bottom:20px;
        font-size:14px;
    ">
      <strong>⚠ Lưu ý:</strong>
      Hãy kiểm tra kỹ tên sách và họ tên bạn đọc trước khi lưu phiếu mượn để tránh nhầm lẫn.
    </div>

    <?php if ($err): ?>
      <p style="color:#d00000; margin-bottom:10px;"><?= e($err) ?></p>
    <?php endif; ?>

    <!-- FORM – layout y như form thêm sách -->
    <form method="post" class="form-add-book">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label for="book_id">Sách <span class="required">*</span></label>
        <select name="book_id" id="book_id" required>
          <option value="">-- Chọn sách --</option>
          <?php foreach ($books as $b): ?>
            <option value="<?= e($b['id']) ?>"
              <?= (isset($_POST['book_id']) && $_POST['book_id'] == $b['id']) ? 'selected' : '' ?>>
              <?= e($b['title']) ?> (còn: <?= e($b['available']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="reader_id">Bạn đọc <span class="required">*</span></label>
        <select name="reader_id" id="reader_id" required>
          <option value="">-- Chọn bạn đọc --</option>
          <?php foreach ($readers as $r): ?>
            <option value="<?= e($r['id']) ?>"
              <?= (isset($_POST['reader_id']) && $_POST['reader_id'] == $r['id']) ? 'selected' : '' ?>>
              <?= e($r['fullname']) ?> <?= $r['code'] ? '(' . e($r['code']) . ')' : '' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="borrow_date">Ngày mượn <span class="required">*</span></label>
        <input
          type="date"
          id="borrow_date"
          name="borrow_date"
          value="<?= e($_POST['borrow_date'] ?? date('Y-m-d')) ?>"
          required
        >
      </div>

      <div class="form-group">
        <label for="due_date">Hạn trả <span class="required">*</span></label>
        <input
          type="date"
          id="due_date"
          name="due_date"
          value="<?= e($_POST['due_date'] ?? '') ?>"
          required
        >
      </div>

      <div class="book-form-actions">
        <a href="borrow_list.php" class="btn-outline">⬅ Danh sách phiếu mượn</a>
        <button type="submit" class="btn-success">💾 Lưu phiếu mượn</button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
