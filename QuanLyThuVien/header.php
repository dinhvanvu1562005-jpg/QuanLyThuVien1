<?php
require_once 'functions.php';
require_login();

$user  = current_user();
$role  = $user['role'] ?? '';
// Lấy tên file hiện tại, vd: books.php, book_add.php...
$active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống quản lý thư viện</title>
    <!-- thêm ?v=2 để tránh cache CSS -->
    <link rel="stylesheet" href="assets/style.css?v=2">
</head>
<body>

<?php if ($role === 'thuthu'): ?>
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
            Xin chào <?= e($user['fullname']) ?> (Thủ thư)
            <a href="logout.php" class="logout-link">🚪 Đăng xuất</a>
        </div>
    </div>

<?php else: ?>
    <div class="top-nav">
        <a href="books.php"
           class="menu-item <?= $active === 'books.php' ? 'active-menu' : '' ?>">
            📚 Sách
        </a>
        <a href="borrow.php"
           class="menu-item <?= $active === 'borrow.php' ? 'active-menu' : '' ?>">
            📖 Mượn/Trả
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
        <a href="logout.php" class="logout-link">Đăng xuất</a>
    </div>
<?php endif; ?>

<main>
