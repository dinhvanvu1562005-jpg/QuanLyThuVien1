<?php
require_once 'functions.php';
require_login();
require_role(['capthe']); // chỉ bộ phận cấp thẻ vào được

include 'header.php';
?>

<div class="content-container">
    <h2 class="page-title">🎫 Quản lý thẻ bạn đọc</h2>
    <p>Đây là màn hình quản lý thẻ cho bộ phận cấp thẻ. Bạn sẽ thiết kế nội dung chi tiết sau.</p>
</div>

<?php include 'footer.php'; ?>
