<?php
// Cấu hình kết nối MySQL đúng với phpMyAdmin của bạn
$host    = '127.0.0.1';
$port    = 3308;                 // đúng cổng MySQL bạn đang dùng
$dbname  = 'QuanLyThuVien';      // đúng với tên database bạn đang xem
$user    = 'root';
$pass    = '';                   // mặc định của XAMPP

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("❌ Kết nối CSDL thất bại: " . $e->getMessage());
}
?>



