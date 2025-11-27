<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

global $pdo;

$err = '';
$today = date('Y-m-d');

// Lấy danh sách sách
$books = $pdo->query("
    SELECT id, title, author, available, cover 
    FROM books 
    ORDER BY title
")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách bạn đọc
$readers = $pdo->query("
    SELECT id, fullname, email, phone 
    FROM readers 
    ORDER BY fullname
")->fetchAll(PDO::FETCH_ASSOC);

// Nếu đi từ màn tìm kiếm sách sang (books.php?book_id=..)
$selectedBookId = intval($_GET['book_id'] ?? 0);

// Xử lý lưu phiếu mượn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $book_id   = intval($_POST['book_id'] ?? 0);
        $reader_id = intval($_POST['reader_id'] ?? 0);
        $borrow_date = $_POST['borrow_date'] ?: $today;
        $due_date    = $_POST['due_date'] ?? '';

        // Lưu lại để form hiển thị lại nếu lỗi
        $selectedBookId = $book_id;

        if ($book_id <= 0 || $reader_id <= 0) {
            $err = 'Vui lòng chọn sách và bạn đọc.';
        } elseif ($due_date === '') {
            $err = 'Vui lòng nhập hạn trả.';
        } else {
            // Kiểm tra tồn tại sách + còn số lượng
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $err = 'Sách không tồn tại.';
            } elseif ((int)$book['available'] <= 0) {
                $err = 'Sách đã hết bản để mượn.';
            } else {
                // Thêm phiếu mượn
                $stmt = $pdo->prepare("
                    INSERT INTO borrow (reader_id, user_id, book_id, borrow_date, due_date, status, fine)
                    VALUES (?, ?, ?, ?, ?, 'dang_muon', 0)
                ");
                $stmt->execute([
                    $reader_id,
                    $_SESSION['user_id'] ?? null,
                    $book_id,
                    $borrow_date,
                    $due_date
                ]);

                // Giảm số bản còn lại
                $upd = $pdo->prepare("
                    UPDATE books 
                    SET available = available - 1 
                    WHERE id = ?
                ");
                $upd->execute([$book_id]);

                audit_log('add_borrow', "Create borrow for book_id=$book_id reader_id=$reader_id");
                flash_set('success', 'Tạo phiếu mượn thành công.');
                header('Location: borrow.php');
                exit;
            }
        }
    }
}

include 'header.php';
?>

<link rel="stylesheet" href="assets/style.css?v=2">

<div class="borrow-wrapper">

  <div class="borrow-card">
    <!-- Cột trái: thông tin sách -->
    <div class="borrow-book-column">
      <h2 class="borrow-title">📚 Tạo phiếu mượn</h2>

      <?php
      // Tìm sách đang chọn để hiển thị thông tin
      $currentBook = null;
      if ($selectedBookId) {
          foreach ($books as $b) {
              if ((int)$b['id'] === $selectedBookId) {
                  $currentBook = $b;
                  break;
              }
          }
      }
      ?>

      <div class="borrow-book-box">
        <?php if ($currentBook && !empty($currentBook['cover'])): ?>
          <div class="borrow-cover">
            <img src="uploads/books/<?= e($currentBook['cover']) ?>" alt="Bìa sách">
          </div>
        <?php else: ?>
          <div class="borrow-cover borrow-cover-placeholder">
            📖
          </div>
        <?php endif; ?>

        <div class="borrow-book-info">
          <?php if ($currentBook): ?>
            <div class="borrow-book-title"><?= e($currentBook['title']) ?></div>
            <div class="borrow-book-meta">
              Tác giả: <b><?= e($currentBook['author']) ?></b><br>
              Còn: <b><?= (int)$currentBook['available'] ?></b> bản trong kho
            </div>
          <?php else: ?>
            <div class="borrow-book-title">Chưa chọn sách</div>
            <div class="borrow-book-meta">
              Hãy chọn một cuốn sách ở khung bên phải.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <p class="borrow-note">
        💡 Mẹo: Nên chọn hạn trả trước <b>7–14 ngày</b> kể từ ngày mượn.
      </p>
    </div>

    <!-- Cột phải: form -->
    <div class="borrow-form-column">

      <?php if ($err): ?>
        <div class="borrow-alert error"><?= e($err) ?></div>
      <?php endif; ?>

      <form method="post" class="borrow-form">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <!-- Chọn sách -->
        <div class="form-row">
          <label>Sách</label>
          <select name="book_id" required>
            <option value="">-- Chọn sách --</option>
            <?php foreach ($books as $b): ?>
              <option value="<?= e($b['id']) ?>"
                <?= ($selectedBookId == $b['id']) ? 'selected' : '' ?>>
                <?= e($b['title']) ?> (còn: <?= (int)$b['available'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Chọn bạn đọc -->
        <div class="form-row">
          <label>Bạn đọc</label>
          <select name="reader_id" required>
            <option value="">-- Chọn bạn đọc --</option>
            <?php foreach ($readers as $r): ?>
              <option value="<?= e($r['id']) ?>"
                <?= (isset($_POST['reader_id']) && $_POST['reader_id'] == $r['id']) ? 'selected' : '' ?>>
                <?= e($r['fullname']) ?> 
                <?php if ($r['phone']): ?> (<?= e($r['phone']) ?>)<?php endif; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-row-inline">
          <div class="form-row">
            <label>Ngày mượn</label>
            <input type="date" name="borrow_date"
                   value="<?= e($_POST['borrow_date'] ?? $today) ?>">
          </div>

          <div class="form-row">
            <label>Hạn trả</label>
            <input type="date" name="due_date"
                   value="<?= e($_POST['due_date'] ?? '') ?>" required>
          </div>
        </div>

        <div class="borrow-actions">
          <a href="borrow.php" class="btn-cancel">← Hủy</a>
          <button type="submit" class="btn-save-green">💾 Lưu phiếu mượn</button>
        </div>
      </form>

    </div>
  </div>

</div>

<?php include 'footer.php'; ?>
