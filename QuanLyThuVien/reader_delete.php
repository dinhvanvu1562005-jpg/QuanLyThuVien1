<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

global $pdo;
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM readers WHERE id = ?");
    $stmt->execute([$id]);
    audit_log('delete_reader', "Delete reader id=$id");
    flash_set('success', 'Đã xóa bạn đọc.');
}

header('Location: readers.php');
exit;
