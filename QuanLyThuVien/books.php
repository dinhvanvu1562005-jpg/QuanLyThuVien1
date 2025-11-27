<?php
require_once 'functions.php';
require_login();
require_role(['thuthu', 'admin']); // chỉ thủ thư + admin mới xem được

global $pdo;

// Lấy từ khóa tìm kiếm
$keyword = trim($_GET['q'] ?? '');
$params  = [];

$sql = "SELECT b.*, c.name AS category_name
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id";

if ($keyword !== '') {
    $sql .= " WHERE b.title  LIKE :kw
              OR b.author   LIKE :kw
              OR b.code     LIKE :kw";
    $params[':kw'] = '%' . $keyword . '%';
}

$sql .= " ORDER BY b.title ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gọi header (có menu, <html>, <body>, <main>...)
include 'header.php';
?>

<link rel="stylesheet" href="assets/css/books.css">

<div class="content-container">
  <h2 class="page-title">🔍 Tìm kiếm sách</h2>

  <!-- Form tìm kiếm -->
  <form method="get" class="search-bar">
    <input
      type="text"
      name="q"
      class="search-input"
      placeholder="Nhập tên sách / tác giả / mã sách để tìm"
      value="<?= e($keyword) ?>"
    >
    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
  </form>

  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
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
        <tr>
          <td colspan="7" class="no-data">Không tìm thấy sách nào.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($books as $i => $b): ?>
          <tr>
            <!-- STT -->
            <td><?= $i + 1 ?></td>

            <!-- Cột tiêu đề + ảnh bìa + mô tả -->
            <td class="book-title-cell">
              <div class="book-title-wrap">
                <?php if (!empty($b['cover'])): ?>
                  <img
                    src="uploads/books/<?= e($b['cover']) ?>"
                    alt="Bìa sách"
                    class="book-thumb"
                  >
                <?php else: ?>
                  <div class="book-thumb book-thumb-placeholder">
                    📚
                  </div>
                <?php endif; ?>

                <div class="book-text">
                  <div class="book-title-main">
                    <?= e($b['title']) ?>
                  </div>
                  <div class="book-meta">
                    Mã: <?= e($b['code'] ?? '') ?>
                    <?php if (!empty($b['author'])): ?>
                      · Tác giả: <?= e($b['author']) ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </td>

            <!-- Các cột còn lại -->
            <td><?= e($b['author']) ?></td>
            <td><?= e($b['category_name']) ?></td>
            <td><?= e($b['total']) ?></td>
            <td><?= e($b['available']) ?></td>

            <td class="actions">
              <a
                href="borrow_add.php?book_id=<?= e($b['id']) ?>"
                class="btn-icon borrow"
                title="Tạo phiếu mượn"
              >
                📖
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'footer.php'; ?>
