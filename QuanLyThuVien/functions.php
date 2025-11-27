<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kiểm tra đã đăng nhập chưa
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Bắt buộc đăng nhập
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Lấy thông tin user hiện tại
 */
function current_user() {
    global $pdo;

    if (!is_logged_in()) return null;

    static $user = null;
    if ($user === null) {
        $stmt = $pdo->prepare("SELECT id, username, fullname, role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $user;
}

/**
 * Yêu cầu user phải có 1 trong các role cho phép
 */
function require_role(array $allowedRoles) {
    require_login();

    $user = current_user();
    if (!$user || !in_array($user['role'], $allowedRoles, true)) {
        http_response_code(403);
        echo "Bạn không có quyền truy cập chức năng này.";
        exit;
    }
}

/**
 * Ghi nhật ký thao tác
 */
function audit_log($action, $detail = null) {
    global $pdo;

    // Nếu chưa có kết nối PDO thì bỏ qua
    if (!isset($pdo) || !$pdo) {
        return;
    }

    $user_id = $_SESSION['user_id'] ?? null;

    if ($detail !== null && $detail !== '') {
        $action = $action . ' - ' . $detail;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
        $stmt->execute([$user_id, $action]);
    } catch (PDOException $e) {
        // Nếu MySQL "has gone away" hoặc lỗi khác -> bỏ qua, không cho trang bị crash
        // Có thể log ra file nếu muốn, nhưng không echo ra màn hình
        // file_put_contents('audit_error.log', $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}

/**
 * Escape HTML
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF token
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function check_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Flash message — KHÔNG BAO GIỜ LỖI LẠI
 */
function flash_set($key, $message) {

    // ⚠ Sửa lỗi: nếu 'flash' không phải mảng → reset lại
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][$key] = $message;
}

function flash_get($key) {
    if (isset($_SESSION['flash']) && is_array($_SESSION['flash']) && isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}
?>

