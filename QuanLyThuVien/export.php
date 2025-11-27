<?php
require_once 'functions.php';
require_login();

$type = $_GET['type'] ?? 'books';
if ($type === 'books') {
    $q = trim($_GET['q'] ?? '');
    if ($q !== '') {
        $stmt = $pdo->prepare("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id=c.id WHERE b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ? ORDER BY b.title");
        $like = "%$q%";
        $stmt->execute([$like,$like,$like]);
    } else {
        $stmt = $pdo->query("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id=c.id ORDER BY b.title");
    }
    $rows = $stmt->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=books.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Title','Author','Category','Total','Available','ISBN']);
    foreach($rows as $r){
        fputcsv($out, [$r['id'],$r['title'],$r['author'],$r['category'],$r['total'],$r['available'],$r['isbn']]);
    }
    fclose($out);
    exit;
}

// You can extend to export borrow/readers/etc similarly.
