<?php
require_once 'functions.php';
require_login();
require_role(['capthe','admin']);

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">🔒 Khóa / Mở thẻ bạn đọc</h2>

  <p>Trang này dùng để khóa hoặc mở thẻ bạn đọc (hiện tại là placeholder, chưa có xử lý).</p>
</div>

<?php include 'footer.php'; ?>
