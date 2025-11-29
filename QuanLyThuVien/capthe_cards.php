<?php
require_once 'functions.php';
require_login();
require_role(['capthe', 'admin']);

require_once 'dao/CardDAO.php';

global $pdo;
$cardDAO = new CardDAO($pdo);
$cards   = $cardDAO->getAll();

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">🎫 Quản lý thẻ bạn đọc</h2>

  <div class="search-bar" style="justify-content: space-between;">
    <div>
      <strong>Danh sách thẻ đã cấp</strong>
    </div>
    <div>
      <a href="capthe_issue.php" class="btn btn-success">➕ Cấp thẻ mới</a>
      <a href="capthe_stats.php" class="btn btn-primary">📊 Thống kê</a>
    </div>
  </div>

  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Mã thẻ</th>
          <th>Bạn đọc</th>
          <th>Email</th>
          <th>Điện thoại</th>
          <th>Ngày cấp</th>
          <th>Hết hạn</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($cards)): ?>
        <tr>
          <td colspan="9" class="no-data">Chưa có thẻ nào được cấp.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($cards as $i => $c): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($c['card_code']) ?></td>
            <td><?= e($c['reader_name']) ?></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['phone']) ?></td>
            <td><?= e($c['issue_date']) ?></td>
            <td><?= e($c['expire_date']) ?></td>
            <td>
              <?php if ($c['status'] === 'active'): ?>
                <span style="color: #0a0; font-weight:600;">Đang hoạt động</span>
              <?php elseif ($c['status'] === 'locked'): ?>
                <span style="color: #c00; font-weight:600;">Đã khóa</span>
              <?php else: ?>
                <?= e($c['status']) ?>
              <?php endif; ?>
            </td>
            <td class="actions">
              <a href="capthe_update.php?id=<?= e($c['id']) ?>" class="btn-icon edit" title="Sửa">✏️</a>
              <?php if ($c['status'] === 'active'): ?>
                <a href="capthe_lock.php?id=<?= e($c['id']) ?>&action=lock"
                   class="btn-icon delete"
                   onclick="return confirm('Khóa thẻ này?');"
                   title="Khóa thẻ">🔒</a>
              <?php else: ?>
                <a href="capthe_lock.php?id=<?= e($c['id']) ?>&action=unlock"
                   class="btn-icon edit"
                   onclick="return confirm('Mở khóa thẻ này?');"
                   title="Mở khóa thẻ">🔓</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>
