<?php
require_once 'functions.php';
require_role(['thuthu', 'admin']);

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: borrow.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM borrow WHERE id = ? AND status = 'borrowed'");
$stmt->execute([$id]);
$rec = $stmt->fetch();
if (!$rec) {
    $_SESSION['flash'] = 'Phiếu mượn không tồn tại hoặc đã trả.';
    header('Location: borrow.php'); exit;
}

$pdo->prepare("UPDATE borrow SET status='returned', return_date = ? WHERE id = ?")
    ->execute([date('Y-m-d'), $id]);

$pdo->prepare("UPDATE books SET available = available + 1 WHERE id = ?")->execute([$rec['book_id']]);

audit_log('return_book', "Return borrow_id=$id book_id=".$rec['book_id']." by reader_id=".$rec['reader_id']);
$_SESSION['flash'] = 'Trả sách thành công.';
header('Location: borrow.php');
exit;

