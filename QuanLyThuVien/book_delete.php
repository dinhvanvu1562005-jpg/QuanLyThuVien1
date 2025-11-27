<?php
require_once 'functions.php';
require_login();

$id = intval($_GET['id'] ?? 0);

if ($id) {
    // Kiểm tra xem có phiếu mượn chưa trả không
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow WHERE book_id = ? AND status = 'borrowed'");
    $stmt->execute([$id]);

    if ($stmt->fetchColumn() > 0) {
        flash_set('error', 'Không thể xóa: sách đang có phiếu mượn chưa trả.');
    } else {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);

        audit_log('delete_book', "Deleted book id=$id");
        flash_set('success', 'Xóa sách thành công.');
    }
}

header('Location: books.php');
exit;
