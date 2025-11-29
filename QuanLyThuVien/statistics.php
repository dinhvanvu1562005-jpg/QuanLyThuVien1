<?php
require_once 'functions.php';
require_login();
// Thống kê chỉ cho thủ thư + admin
require_role(['thuthu', 'admin']);

// === Thống kê cơ bản ===
// books: dùng cột total chứ không phải quantity
$totals = $pdo->query("
    SELECT 
        COUNT(*) AS titles,
        COALESCE(SUM(total), 0) AS copies
    FROM books
")->fetch(PDO::FETCH_ASSOC);

// === Top 10 sách được mượn nhiều nhất ===
// bảng borrow: book_id, reader_id, status,...
$top_books = $pdo->query("
    SELECT 
        b.id,
        b.title,
        COUNT(br.id) AS times_borrowed
    FROM books b
    LEFT JOIN borrow br 
           ON b.id = br.book_id
    GROUP BY b.id, b.title
    ORDER BY times_borrowed DESC, b.title
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// === Top 10 bạn đọc mượn nhiều nhất ===
// readers: dùng fullname chứ không phải name
$top_readers = $pdo->query("
    SELECT 
        r.id,
        r.fullname,
        COUNT(br.id) AS times_borrow
    FROM readers r
    LEFT JOIN borrow br 
           ON r.id = br.reader_id
    GROUP BY r.id, r.fullname
    ORDER BY times_borrow DESC, r.fullname
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">📊 Thống kê thư viện</h2>

  <!-- Tóm tắt nhanh -->
  <div class="stats-summary">
    <div class="stats-box">
      <div class="stats-label">Tổng đầu sách</div>
      <div class="stats-value"><?= e($totals['titles'] ?? 0) ?></div>
    </div>
    <div class="stats-box">
      <div class="stats-label">Tổng số bản</div>
      <div class="stats-value"><?= e($totals['copies'] ?? 0) ?></div>
    </div>
  </div>

  <!-- TOP SÁCH -->
  <h3 style="margin-top: 10px; text-align:left;">🏆 Top sách được mượn nhiều nhất</h3>
  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Tên sách</th>
          <th>Lượt mượn</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($top_books)): ?>
          <tr><td colspan="3" class="no-data">Chưa có dữ liệu mượn sách.</td></tr>
        <?php else: ?>
          <?php foreach ($top_books as $i => $b): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= e($b['title']) ?></td>
              <td><?= e($b['times_borrowed']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- TOP BẠN ĐỌC -->
  <h3 style="margin-top: 30px; text-align:left;">👥 Top bạn đọc mượn nhiều nhất</h3>
  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Tên bạn đọc</th>
          <th>Lượt mượn</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($top_readers)): ?>
          <tr><td colspan="3" class="no-data">Chưa có dữ liệu mượn sách.</td></tr>
        <?php else: ?>
          <?php foreach ($top_readers as $i => $r): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= e($r['fullname']) ?></td>
              <td><?= e($r['times_borrow']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>

