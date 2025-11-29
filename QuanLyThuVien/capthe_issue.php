<?php
require_once 'functions.php';
require_login();
require_role(['capthe','admin']);

require_once 'dao/CardDAO.php';

global $pdo;
$cardDAO = new CardDAO($pdo);

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) {
        $err = 'Token không hợp lệ.';
    } else {
        $reader_id   = intval($_POST['reader_id'] ?? 0);
        $card_code   = trim($_POST['card_code'] ?? '');
        $issue_date  = $_POST['issue_date'] ?: date('Y-m-d');
        $expire_date = $_POST['expire_date'] ?: null;

        if (!$reader_id || $card_code === '') {
            $err = 'Vui lòng chọn bạn đọc và nhập mã thẻ.';
        } else {
            $card_id = $cardDAO->create($reader_id, $card_code, $issue_date, $expire_date);
            audit_log('card_issue', "Issue card_id=$card_id for reader_id=$reader_id");
            flash_set('success', 'Cấp thẻ thành công.');
            header('Location: capthe_cards.php');
            exit;
        }
    }
}

// danh sách bạn đọc chưa có thẻ
$readers = $cardDAO->getReadersWithoutCard();

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">➕ Cấp thẻ bạn đọc</h2>

  <?php if ($err): ?>
    <p style="color:red;"><?= e($err) ?></p>
  <?php endif; ?>

  <form method="post" class="form-add-book" style="max-width: 520px; margin: 0 auto;">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="form-group">
      <label>Bạn đọc <span class="required">*</span></label>
      <select name="reader_id" required>
        <option value="">-- Chọn bạn đọc --</option>
        <?php foreach ($readers as $r): ?>
          <option value="<?= e($r['id']) ?>">
            <?= e($r['fullname']) ?> (<?= e($r['email'] ?: $r['phone']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (empty($readers)): ?>
        <small>Hiện tất cả bạn đọc đã có thẻ.</small>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label>Mã thẻ <span class="required">*</span></label>
      <input type="text" name="card_code" required>
      <small>Có thể dùng mã tự quy ước, ví dụ: TV-2025-0001.</small>
    </div>

    <div class="form-group">
      <label>Ngày cấp</label>
      <input type="date" name="issue_date" value="<?= date('Y-m-d') ?>">
    </div>

    <div class="form-group">
      <label>Hạn sử dụng</label>
      <input type="date" name="expire_date">
    </div>

    <div class="form-actions">
      <a href="capthe_cards.php" class="btn-cancel">⬅ Hủy</a>
      <button type="submit" class="btn-save">💾 Lưu cấp thẻ</button>
    </div>
  </form>
</div>

<?php include 'footer.php'; ?>
