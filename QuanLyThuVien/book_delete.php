<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BookDAO.php';
global $pdo;
$bookDao = new BookDAO($pdo);

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: books.php');
    exit;
}

// Kiểm tra sách đang được mượn không
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM borrow
    WHERE book_id = ? AND status IN ('dang_muon','qua_han')
");
$stmt->execute([$id]);

if ($stmt->fetchColumn() > 0) {
    flash_set('error', 'Không thể xóa: sách đang có phiếu mượn chưa trả.');
} else {
    $bookDao->delete($id);
    audit_log('delete_book', "Deleted book id=$id");
    flash_set('success', 'Xóa sách thành công.');
}

header('Location: books.php');
exit;
