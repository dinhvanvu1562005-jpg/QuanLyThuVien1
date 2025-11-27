<?php
require_once 'functions.php';
require_login();

// === Thống kê cơ bản ===
$totals = $pdo->query("
    SELECT 
        COUNT(*) AS titles, 
        SUM(quantity) AS copies 
    FROM books
")->fetch(PDO::FETCH_ASSOC);

// === Top 10 sách được mượn nhiều nhất ===
$top_books = $pdo->query("
    SELECT 
        b.id, 
        b.title, 
        COUNT(br.id) AS times_borrowed
    FROM books b
    LEFT JOIN borrow br ON b.id = br.book_id
    GROUP BY b.id
    ORDER BY times_borrowed DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// === Top 10 bạn đọc mượn nhiều nhất ===
$top_readers = $pdo->query("
    SELECT 
        r.id, 
        r.name, 
        COUNT(br.id) AS times_borrow
    FROM readers r
    LEFT JOIN borrow br ON r.id = br.reader_id
    GROUP BY r.id
    ORDER BY times_borrow DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h2>📊 Thống kê thư viện</h2>

<p>
  <strong>Tổng đầu sách:</strong> <?= e($totals['titles'] ?? 0) ?> |
  <strong>Tổng số bản:</strong> <?= e($totals['copies'] ?? 0) ?>
</p>

<h3>🏆 Top sách được mượn nhiều nhất</h3>
<table>
  <thead>
    <tr><th>#</th><th>Tên sách</th><th>Lượt mượn</th></tr>
  </thead>
  <tbody>
    <?php foreach ($top_books as $b): ?>
      <tr>
        <td><?= e($b['id']) ?></td>
        <td><?= e($b['title']) ?></td>
        <td><?= e($b['times_borrowed']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>👥 Top bạn đọc mượn nhiều nhất</h3>
<table>
  <thead>
    <tr><th>#</th><th>Tên bạn đọc</th><th>Lượt mượn</th></tr>
  </thead>
  <tbody>
    <?php foreach ($top_readers as $r): ?>
      <tr>
        <td><?= e($r['id']) ?></td>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['times_borrow']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
