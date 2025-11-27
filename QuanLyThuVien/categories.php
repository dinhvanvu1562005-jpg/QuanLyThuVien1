<?php
require_once 'functions.php';
require_login();

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    include 'header.php';
    ?>
    <h2>Thể loại</h2>
    <a href="categories.php?action=add">Thêm thể loại</a>
    <table>
      <thead><tr><th>#</th><th>Tên</th><th>Hành động</th></tr></thead>
      <tbody>
      <?php foreach($cats as $c): ?>
        <tr>
          <td><?= e($c['id']) ?></td>
          <td><?= e($c['name']) ?></td>
          <td>
            <a href="categories.php?action=edit&id=<?= e($c['id']) ?>">Sửa</a> |
            <a href="categories.php?action=delete&id=<?= e($c['id']) ?>" onclick="return confirm('Xóa thể loại?')">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php include 'footer.php';
    exit;
}

if ($action === 'add') {
    $err = '';
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        if (!check_csrf($_POST['csrf'] ?? '')) $err='Token không hợp lệ.';
        else {
            $name = trim($_POST['name'] ?? '');
            if ($name==='') $err='Tên không được rỗng';
            else {
                $pdo->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
                audit_log('add_category', "Added category: $name");
                $_SESSION['flash'] = 'Thêm thể loại thành công.';
                header('Location: categories.php');
                exit;
            }
        }
    }
    include 'header.php';
    ?>
    <h2>Thêm thể loại</h2>
    <?php if($err): ?><p style="color:red"><?= e($err) ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label>Tên: <input name="name" value="<?= e($_POST['name'] ?? '') ?>"></label><br><br>
      <button type="submit">Lưu</button>
      <a href="categories.php">Hủy</a>
    </form>
    <?php include 'footer.php';
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $c = $stmt->fetch();
    if (!$c) header('Location: categories.php');

    $err = '';
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        if (!check_csrf($_POST['csrf'] ?? '')) $err='Token không hợp lệ.';
        else {
            $name = trim($_POST['name'] ?? '');
            if ($name==='') $err='Tên không được rỗng';
            else {
                $pdo->prepare("UPDATE categories SET name=? WHERE id=?")->execute([$name,$id]);
                audit_log('edit_category', "Edited category id=$id");
                $_SESSION['flash'] = 'Cập nhật thành công.';
                header('Location: categories.php');
                exit;
            }
        }
    }

    include 'header.php';
    ?>
    <h2>Sửa thể loại</h2>
    <?php if($err): ?><p style="color:red"><?= e($err) ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label>Tên: <input name="name" value="<?= e($_POST['name'] ?? $c['name']) ?>"></label><br><br>
      <button type="submit">Lưu</button>
      <a href="categories.php">Hủy</a>
    </form>
    <?php include 'footer.php';
    exit;
}

if ($action === 'delete') {
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        // set category_id of books to NULL
        $pdo->prepare("UPDATE books SET category_id = NULL WHERE category_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        audit_log('delete_category', "Deleted category id=$id");
        $_SESSION['flash'] = 'Xóa thể loại thành công.';
    }
    header('Location: categories.php');
    exit;
}
