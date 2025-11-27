<?php
require_once 'functions.php';
require_login();
// Nếu muốn chỉ admin xem được thì dùng thêm:
// require_role(['admin']);

$q = trim($_GET['q'] ?? '');

// Lấy nhật ký + tên user
$sql = "
    SELECT a.*, u.username
    FROM audit_log a
    LEFT JOIN users u ON a.user_id = u.id
";
$params = [];

if ($q !== '') {
    $sql .= " WHERE a.action LIKE :kw OR a.detail LIKE :kw OR u.username LIKE :kw";
    $params[':kw'] = "%$q%";
}

$sql .= " ORDER BY a.created_at DESC LIMIT 500";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

include 'header.php';
?>
<h2>Nhật ký hệ thống</h2>

<form method="get" style="margin-bottom: 15px;">
  <input name="q"
         placeholder="Tìm action/chi tiết/user"
         value="<?= e($q) ?>">
  <button type="submit">Tìm</button>
  <a href="audit_log.php">Làm mới</a>
</form>

<table border="1" cellpadding="6" cellspacing="0">
  <thead>
    <tr>
      <th>#</th>
      <th>User</th>
      <th>Action</th>
      <th>Chi tiết</th>
      <th>Thời gian</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($logs as $l): ?>
    <tr>
      <td><?= e($l['id']) ?></td>
      <td><?= e($l['username']) ?></td>
      <td><?= e($l['action']) ?></td>
      <td><?= e($l['detail']) ?></td>
      <td><?= e($l['created_at']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
