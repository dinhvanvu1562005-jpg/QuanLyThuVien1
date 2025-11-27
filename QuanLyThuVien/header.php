<?php
require_once 'functions.php';
require_login();

$user   = current_user() ?? [];
$role   = $user['role'] ?? '';
// Tên file hiện tại, ví dụ: books.php, capthe_cards.php...
$active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống quản lý thư viện</title>
    <!-- ?v=3 để tránh cache CSS -->
    <link rel="stylesheet" href="assets/style.css?v=3">
</head>
<body>

<?php if ($role === 'thuthu'): ?>
    <!-- ========== MENU CHO THỦ THƯ ========== -->
    <div class="top-nav">
        <div class="nav-left">
            <a href="book_add.php"
               class="menu-item <?= $active === 'book_add.php' ? 'active-menu' : '' ?>">
                ➕ Nhập sách mới
            </a>

            <a href="book_edit_list.php"
               class="menu-item <?= $active === 'book_edit_list.php' ? 'active-menu' : '' ?>">
                ✏️ Sửa thông tin sách
            </a>

            <a href="book_delete_list.php"
               class="menu-item <?= $active === 'book_delete_list.php' ? 'active-menu' : '' ?>">
                🗑 Xóa sách
            </a>

            <a href="books.php"
               class="menu-item <?= $active === 'books.php' ? 'active-menu' : '' ?>">
                🔍 Tìm kiếm sách
            </a>

            <a href="readers.php"
               class="menu-item <?= $active === 'readers.php' ? 'active-menu' : '' ?>">
                👥 Quản lý bạn đọc
            </a>

            <a href="borrow.php"
               class="menu-item <?= $active === 'borrow.php' ? 'active-menu' : '' ?>">
                📖 Quản lý mượn – trả
            </a>
        </div>

        <div class="nav-right">
            Xin chào <?= e($user['fullname'] ?? '') ?> (Thủ thư)
            <a href="logout.php" class="logout-link">🚪 Đăng xuất</a>
        </div>
    </div>

<?php elseif ($role === 'capthe'): ?>
    <!-- ========== MENU CHO BỘ PHẬN CẤP THẺ ========== -->
    <div class="top-nav">
        <div class="nav-left">
            <a href="capthe_cards.php"
               class="menu-item <?= $active === 'capthe_cards.php' ? 'active-menu' : '' ?>">
                🎫 Quản lý thẻ bạn đọc
            </a>

            <a href="capthe_issue.php"
               class="menu-item <?= $active === 'capthe_issue.php' ? 'active-menu' : '' ?>">
                ➕ Cấp thẻ
            </a>

            <a href="capthe_update.php"
               class="menu-item <?= $active === 'capthe_update.php' ? 'active-menu' : '' ?>">
                ✏️ Cập nhật thông tin thẻ
            </a>

            <a href="capthe_lock.php"
               class="menu-item <?= $active === 'capthe_lock.php' ? 'active-menu' : '' ?>">
                🔒 Khóa / Mở thẻ
            </a>

            <a href="capthe_stats.php"
               class="menu-item <?= $active === 'capthe_stats.php' ? 'active-menu' : '' ?>">
                📊 Thống kê thẻ
            </a>
        </div>

        <div class="nav-right">
            Xin chào <?= e($user['fullname'] ?? '') ?> (Bộ phận cấp thẻ)
            <a href="logout.php" class="logout-link">🚪 Đăng xuất</a>
        </div>
    </div>

<?php elseif ($role === 'admin'): ?>
    <!-- ========== MENU CHO QUẢN TRỊ VIÊN ========== -->
    <div class="top-nav">
        <div class="nav-left">
            <a href="books.php"
               class="menu-item <?= $active === 'books.php' ? 'active-menu' : '' ?>">
                📚 Sách
            </a>
            <a href="borrow.php"
               class="menu-item <?= $active === 'borrow.php' ? 'active-menu' : '' ?>">
                📖 Mượn / Trả
            </a>
            <a href="readers.php"
               class="menu-item <?= $active === 'readers.php' ? 'active-menu' : '' ?>">
                👥 Bạn đọc
            </a>
            <a href="categories.php"
               class="menu-item <?= $active === 'categories.php' ? 'active-menu' : '' ?>">
                📂 Thể loại
            </a>
            <a href="statistics.php"
               class="menu-item <?= $active === 'statistics.php' ? 'active-menu' : '' ?>">
                📊 Thống kê
            </a>
            <a href="audit_log.php"
               class="menu-item <?= $active === 'audit_log.php' ? 'active-menu' : '' ?>">
                📝 Nhật ký
            </a>
        </div>

        <div class="nav-right">
            Xin chào <?= e($user['fullname'] ?? '') ?> (Quản trị viên)
            <a href="logout.php" class="logout-link">🚪 Đăng xuất</a>
        </div>
    </div>

<?php else: ?>
    <!-- ========== ROLE KHÔNG RÕ → CHỈ CHO ĐĂNG XUẤT ========== -->
    <div class="top-nav">
        <div class="nav-left">
            <span class="menu-item">Tài khoản không có quyền truy cập menu.</span>
        </div>
        <div class="nav-right">
            <a href="logout.php" class="logout-link">🚪 Đăng xuất</a>
        </div>
    </div>
<?php endif; ?>

<main>

