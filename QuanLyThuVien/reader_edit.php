<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

echo "Trang sửa bạn đọc đang xây dựng. ID = " . e($_GET['id'] ?? '');
