<?php
require_once 'functions.php';
require_login();
require_role(['capthe']);   // chỉ bộ phận cấp thẻ

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">🎫 Cấp thẻ bạn đọc</h2>

  <p>Form này sẽ dùng để tạo thẻ mới cho bạn đọc (sau này bạn thêm xử lý DB).</p>

  <form method="post">
    <div class="form-group">
      <label>Mã bạn đọc</label>
      <input type="text" name="reader_code">
    </div>

    <div class="form-group">
      <label>Ngày cấp</label>
      <input type="date" name="issue_date" value="<?= date('Y-m-d') ?>">
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Lưu cấp thẻ</button>
    </div>
  </form>
</div>

<?php include 'footer.php'; ?>
