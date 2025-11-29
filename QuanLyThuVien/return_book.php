<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BorrowDAO.php';
global $pdo;
$borrowDao = new BorrowDAO($pdo);

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: borrow_list.php');
    exit;
}

// Trả sách
$rec = $borrowDao->returnBook($id, date('Y-m-d'));
if (!$rec) {
    flash_set('error', 'Phiếu mượn không tồn tại hoặc đã trả.');
    header('Location: borrow_list.php');
    exit;
}

// Tăng available cho sách
$pdo->prepare("UPDATE books SET available = available + 1 WHERE id = ?")
    ->execute([$rec['book_id']]);

audit_log(
    'return_book',
    "Return borrow_id=$id book_id=".$rec['book_id']." reader_id=".$rec['reader_id']
);
flash_set('success', 'Trả sách thành công.');
header('Location: borrow_list.php');
exit;

