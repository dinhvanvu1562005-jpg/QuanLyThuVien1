<?php
require_once 'functions.php';
require_login();
require_role(['thuthu', 'admin']);

// Tìm kiếm theo tên sách / tác giả
$keyword = trim($_GET['q'] ?? '');
$params  = [];

$sql = "SELECT b.*, c.name AS category_name
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id";

if ($keyword !== '') {
    $sql .= " WHERE b.title LIKE :kw OR b.author LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

$sql .= " ORDER BY b.title ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="delete-wrapper">
  <h2 class="delete-title">🗑 Xóa sách</h2>

  <!-- Ô tìm kiếm chính -->
  <form method="get" class="delete-search-main">
    <input
      type="text"
      name="q"
      placeholder="Nhập tên sách / tác giả để lọc"
      value="<?= e($keyword) ?>"
    >
    <button type="submit">Lọc</button>
  </form>

  <!-- Bảng danh sách sách để xóa -->
  <div class="table-wrapper">
    <table class="styled-table delete-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Tiêu đề</th>
          <th>Tác giả</th>
          <th>Thể loại</th>
          <th>Số lượng</th>
          <th>Còn</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($books)): ?>
        <tr>
          <td colspan="7" class="no-data">Không có sách nào.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($books as $i => $b): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($b['title']) ?></td>
            <td><?= e($b['author']) ?></td>
            <td><?= e($b['category_name']) ?></td>
            <td><?= e($b['total']) ?></td>
            <td><?= e($b['available']) ?></td>
            <td>
              <a href="book_delete.php?id=<?= e($b['id']) ?>"
                 class="btn-icon delete"
                 onclick="return confirm('Bạn chắc chắn muốn xóa sách này?')">
                🗑 Xóa
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="delete-note">
    ⚠️ <b>Lưu ý:</b> Sách đang có phiếu mượn chưa trả sẽ <b>không thể xóa</b>.
  </p>
</div>

<?php include 'footer.php'; ?>

