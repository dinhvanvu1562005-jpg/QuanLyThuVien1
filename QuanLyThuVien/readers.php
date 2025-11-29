<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/ReaderDAO.php';
global $pdo;
$readerDao = new ReaderDAO($pdo);

$keyword = trim($_GET['q'] ?? '');
$readers = $readerDao->search($keyword);

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">👥 Quản lý bạn đọc</h2>

  <form method="get" class="search-bar">
    <input
      type="text"
      name="q"
      class="search-input"
      placeholder="Tìm họ tên / email / số điện thoại"
      value="<?= e($keyword) ?>"
    >
    <button type="submit" class="btn btn-primary">Tìm</button>
    <a href="reader_add.php" class="btn btn-success">➕ Thêm bạn đọc</a>
  </form>

  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Họ và tên</th>
          <th>Email</th>
          <th>Điện thoại</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($readers)): ?>
        <tr><td colspan="5" class="no-data">Không có bạn đọc nào.</td></tr>
      <?php else: ?>
        <?php foreach ($readers as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['fullname']) ?></td>
            <td><?= e($r['email']) ?></td>
            <td><?= e($r['phone']) ?></td>
            <td class="actions">
              <a href="reader_edit.php?id=<?= e($r['id']) ?>" class="btn-icon edit">✏️</a>
              <a href="reader_delete.php?id=<?= e($r['id']) ?>"
                 class="btn-icon delete"
                 onclick="return confirm('Xóa bạn đọc này?');">🗑</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>
