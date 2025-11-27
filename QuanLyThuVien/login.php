<?php
require_once 'functions.php';

// Nếu đã đăng nhập thì chuyển sang trang chính
if (is_logged_in()) {
    header('Location: books.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $err = 'Vui lòng nhập tên đăng nhập và mật khẩu.';
    } else {
        global $pdo;

        // Cho phép đăng nhập bằng username, email hoặc số điện thoại
        $stmt = $pdo->prepare("
            SELECT * FROM users 
            WHERE username = ? OR phone = ? OR email = ?
            LIMIT 1
        ");
        $stmt->execute([$username, $username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Lưu session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'] ?? null;

            audit_log('login', "User logged in: " . $user['username']);

            // Điều hướng theo vai trò
            switch ($user['role']) {
                case 'thuthu':
                    // PHẦN CỦA BẠN – thủ thư
                    header('Location: borrow.php');
                    break;

                case 'thukho':
                    header('Location: books.php');          // ví dụ: trang quản lý kho sách
                    break;

                case 'capthe':
                    header('Location: readers.php');        // ví dụ: trang cấp thẻ độc giả
                    break;

                case 'taivu':
                    header('Location: statistics.php');     // ví dụ: trang thống kê, tài vụ
                    break;

                case 'admin':
                default:
                    header('Location: books.php');          // admin vào trang chung
                    break;
            }
            exit;
        } else {
            $err = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập hệ thống thư viện</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
  <div class="login-page">
    <div class="login-box">
      <div class="login-header">
        <!-- Logo nhỏ gọn, chuẩn hệ thống thư viện -->
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="logo" class="logo">
        <h1>Đăng nhập hệ thống</h1>
      </div>

      <?php if ($err): ?>
        <div class="flash error"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form id="loginForm" method="post">
        <input type="text"
               name="username"
               placeholder="Tên đăng nhập / Email / Số điện thoại"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               required>

        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
          <span id="togglePass" class="toggle-password">👁</span>
        </div>

        <button type="submit" id="loginBtn">Đăng nhập</button>
      </form>

      <p style="margin-top:10px;">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
  </div>

  <script src="assets/login.js"></script>
</body>
</html>

