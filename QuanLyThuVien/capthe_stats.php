<?php
require_once 'functions.php';
require_login();
require_role(['capthe','admin']);

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">📊 Thống kê thẻ bạn đọc</h2>

  <p>Trang này dùng để xem thống kê số thẻ đã cấp, thẻ còn hiệu lực, thẻ bị khóa, v.v. (placeholder).</p>
</div>

<?php include 'footer.php'; ?>


