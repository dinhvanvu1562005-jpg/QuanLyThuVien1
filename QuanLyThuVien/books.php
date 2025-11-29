<?php
require_once 'functions.php';
require_login();
require_role(['thuthu', 'admin']);

require_once __DIR__ . '/dao/BookDAO.php';
global $pdo;
$bookDao = new BookDAO($pdo);

// CONTROLLER
$keyword = trim($_GET['q'] ?? '');
$books   = $bookDao->search($keyword);

// VIEW
include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">🔍 Tìm kiếm sách</h2>

  <form method="get" class="search-bar">
    <input
      type="text"
      name="q"
      class="search-input"
      placeholder="Nhập tên sách / tác giả"
      value="<?= e($keyword) ?>"
    >
    <button type="submit" class="btn btn-primary">Tìm</button>
    <a href="book_add.php" class="btn btn-success">➕ Nhập sách mới</a>
  </form>

  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Mã sách</th>
          <th>Tiêu đề</th>
          <th>Tác giả</th>
          <th>Thể loại</th>
          <th>Tổng</th>
          <th>Còn</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($books)): ?>
        <tr><td colspan="8" class="no-data">Không có sách nào.</td></tr>
      <?php else: ?>
        <?php foreach ($books as $i => $b): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($b['code'] ?? '') ?></td>
            <td><?= e($b['title']) ?></td>
            <td><?= e($b['author']) ?></td>
            <td><?= e($b['category_name'] ?? '-') ?></td>
            <td><?= e($b['total']) ?></td>
            <td><?= e($b['available']) ?></td>
            <td class="actions">
              <a href="book_edit.php?id=<?= e($b['id']) ?>" class="btn-icon edit">✏️</a>
              <a href="book_delete.php?id=<?= e($b['id']) ?>"
                 class="btn-icon delete"
                 onclick="return confirm('Xóa sách này?');">🗑</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>

