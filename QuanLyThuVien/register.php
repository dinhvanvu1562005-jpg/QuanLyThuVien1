<?php
require_once 'functions.php';
require_once 'dao/UserDAO.php';

global $pdo;
$userDAO = new UserDAO($pdo);

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($fullname === '' || ($email === '' && $phone === '') || $password === '' || $confirm === '') {
        $err = 'Vui lòng nhập đầy đủ thông tin.';
    } elseif ($password !== $confirm) {
        $err = 'Mật khẩu xác nhận không khớp.';
    } else {
        // Kiểm tra trùng email / sđt qua DAO
        if ($userDAO->existsEmailOrPhone($email ?: null, $phone ?: null)) {
            $err = 'Email hoặc số điện thoại đã tồn tại.';
        } else {
            // Mặc định username = phone hoặc email
            $username = $phone !== '' ? $phone : $email;

            // Tạo tài khoản mới, role mặc định là 'thuthu'
            $userDAO->createWithContact(
                $fullname,
                $email ?: null,
                $phone ?: null,
                $username,
                $password,
                'thuthu'
            );

            $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký tài khoản</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* Chống autofill đổi màu nền */
    input:-webkit-autofill {
      box-shadow: 0 0 0px 1000px white inset !important;
      -webkit-text-fill-color: #000 !important;
    }
  </style>
</head>

<body class="login-page">
  <div class="login-page">
    <div class="login-box">
      <div class="login-header">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="logo" class="logo">
        <h1>Tạo tài khoản</h1>
      </div>

      <?php if ($err): ?>
        <div class="flash error"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="flash success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <input type="text"
               name="fullname"
               placeholder="Họ và tên"
               required
               autocomplete="off"
               value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">

        <div class="signup-method">
          <label>
            <input type="radio" name="signup_method" value="email" checked>
            Đăng ký bằng Email
          </label>
          <label>
            <input type="radio" name="signup_method" value="phone">
            Đăng ký bằng Số điện thoại
          </label>
        </div>

        <input type="email"
               id="emailField"
               name="email"
               placeholder="Email"
               autocomplete="off"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <input type="text"
               id="phoneField"
               name="phone"
               placeholder="Số điện thoại"
               style="display:none;"
               autocomplete="off"
               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

        <input type="password" name="password" placeholder="Mật khẩu" required autocomplete="new-password">
        <input type="password" name="confirm" placeholder="Xác nhận mật khẩu" required autocomplete="new-password">

        <button type="submit">Đăng ký</button>
      </form>

      <p style="margin-top:10px;">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
  </div>

  <script src="assets/register.js"></script>
</body>
</html>

