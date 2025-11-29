<?php
require_once 'functions.php';
require_login();
require_role(['thuthu','admin']);

require_once __DIR__ . '/dao/BorrowDAO.php';
global $pdo;
$borrowDao = new BorrowDAO($pdo);

// status: dang_muon | qua_han | da_tra | all
$status = $_GET['status'] ?? 'dang_muon';
if (!in_array($status, ['dang_muon','qua_han','da_tra','all'], true)) {
    $status = 'dang_muon';
}

$rows = $borrowDao->listByStatus($status);

include 'header.php';
?>

<div class="content-container">
  <h2 class="page-title">📖 Danh sách phiếu mượn</h2>

  <div class="search-bar" style="justify-content: space-between;">
    <div>
      <a href="?status=dang_muon"
         class="btn <?= $status === 'dang_muon' ? 'btn-primary' : 'btn-outline' ?>">
        Đang mượn
      </a>
      <a href="?status=qua_han"
         class="btn <?= $status === 'qua_han' ? 'btn-primary' : 'btn-outline' ?>">
        Quá hạn
      </a>
      <a href="?status=da_tra"
         class="btn <?= $status === 'da_tra' ? 'btn-primary' : 'btn-outline' ?>">
        Đã trả
      </a>
      <a href="?status=all"
         class="btn <?= $status === 'all' ? 'btn-primary' : 'btn-outline' ?>">
        Tất cả
      </a>
    </div>

    <a href="borrow.php" class="btn btn-success">➕ Tạo phiếu mượn mới</a>
  </div>

  <div class="table-wrapper">
    <table class="styled-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Sách</th>
          <th>Bạn đọc</th>
          <th>Ngày mượn</th>
          <th>Hạn trả</th>
          <th>Ngày trả</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="8" class="no-data">Chưa có phiếu mượn nào.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($r['book_title']) ?></td>
            <td><?= e($r['reader_name']) ?></td>
            <td><?= e($r['borrow_date']) ?></td>
            <td><?= e($r['due_date']) ?></td>
            <td><?= e($r['return_date'] ?? '-') ?></td>
            <td>
              <?php
                if ($r['status'] === 'dang_muon') echo 'Đang mượn';
                elseif ($r['status'] === 'qua_han') echo 'Quá hạn';
                else echo 'Đã trả';
              ?>
            </td>
            <td class="actions">
              <?php if ($r['status'] !== 'da_tra'): ?>
                <a href="return_book.php?id=<?= e($r['id']) ?>"
                   class="btn-icon borrow"
                   onclick="return confirm('Xác nhận trả sách?');">
                  ✅ Trả sách
                </a>
              <?php else: ?>
                <span style="font-size: 13px; color:#777;">Đã trả</span>
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
