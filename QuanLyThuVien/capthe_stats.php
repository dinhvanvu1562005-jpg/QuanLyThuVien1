<?php
require_once 'functions.php';
require_login();
require_role(['capthe','admin']);

require_once 'dao/CardDAO.php';

global $pdo;
$cardDAO = new CardDAO($pdo);

$stats  = $cardDAO->getStats();
$cards  = $cardDAO->getAll();   // nếu muốn liệt kê thêm ở dưới

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">📊 Thống kê thẻ bạn đọc</h2>

  <div class="stat-box" style="display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap;">
    <div class="info-card">
      <h3>Tổng số thẻ</h3>
      <p style="font-size: 28px; font-weight:700;"><?= e($stats['total_cards'] ?? 0) ?></p>
    </div>
    <div class="info-card">
      <h3>Thẻ đang hoạt động</h3>
      <p style="font-size: 28px; font-weight:700; color:#0a0;">
        <?= e($stats['active_cards'] ?? 0) ?>
      </p>
    </div>
    <div class="info-card">
      <h3>Thẻ bị khóa</h3>
      <p style="font-size: 28px; font-weight:700; color:#c00;">
        <?= e($stats['locked_cards'] ?? 0) ?>
      </p>
    </div>
  </div>

  <h3 style="margin-top:30px;">Danh sách thẻ (rút gọn)</h3>
  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Mã thẻ</th>
          <th>Bạn đọc</th>
          <th>Ngày cấp</th>
          <th>Hạn sử dụng</th>
          <th>Trạng thái</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($cards)): ?>
        <tr><td colspan="6" class="no-data">Chưa có thẻ nào.</td></tr>
      <?php else: ?>
        <?php foreach ($cards as $i => $c): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($c['card_code']) ?></td>
            <td><?= e($c['reader_name']) ?></td>
            <td><?= e($c['issue_date']) ?></td>
            <td><?= e($c['expire_date']) ?></td>
            <td><?= e($c['status']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>
