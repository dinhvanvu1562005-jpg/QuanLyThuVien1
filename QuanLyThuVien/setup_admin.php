<?php
// setup_admin.php
require_once 'config.php';

$username = 'admin';
$pass = 'admin123'; // đổi ngay sau khi tạo
$fullname = 'Admin';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetchColumn() > 0) {
    echo "User $username đã tồn tại. Xóa file này sau khi dùng.";
    exit;
}
$hash = password_hash($pass, PASSWORD_DEFAULT);
$pdo->prepare("INSERT INTO users (username, password, fullname, role) VALUES (?, ?, ?, ?)")
    ->execute([$username, $hash, $fullname, 'admin']);
echo "Admin đã được tạo. Username: $username, Password: $pass. Xóa hoặc đổi file này!";
