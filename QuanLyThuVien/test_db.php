<?php
require 'config.php';

$stmt = $pdo->query("SELECT id, username, phone, role FROM users");
$data = $stmt->fetchAll();

echo "<pre>";
print_r($data);
echo "</pre>";
?>
