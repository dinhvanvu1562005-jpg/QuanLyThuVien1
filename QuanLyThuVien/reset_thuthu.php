<?php
// reset_thuthu.php
require 'config.php';

// Mật khẩu mới bạn muốn dùng
$plain = '123456';

$hash = password_hash($plain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'thuthu'");
$stmt->execute([$hash]);

echo "<p>Đã cập nhật mật khẩu cho user <b>thuthu</b>.</p>";
echo "<p>Mật khẩu mới là: <b>{$plain}</b></p>";
echo "<p>Hash lưu trong DB là:</p>";
echo "<pre>{$hash}</pre>";
