<?php
require_once 'functions.php';
require_login();
require_role(['capthe','admin']);

require_once 'dao/CardDAO.php';

global $pdo;
$cardDAO = new CardDAO($pdo);

$id      = intval($_GET['id'] ?? 0);
$action  = $_GET['action'] ?? '';
$status  = null;

if ($id && in_array($action, ['lock', 'unlock'], true)) {
    $card = $cardDAO->getById($id);
    if ($card) {
        if ($action === 'lock') {
            $status = 'locked';
        } else {
            $status = 'active';
        }

        if ($status !== null) {
            $cardDAO->updateStatus($id, $status);
            audit_log('card_status', "Change card_id=$id status=$status");
            flash_set('success', 'Cập nhật trạng thái thẻ thành công.');
        }
    } else {
        flash_set('error', 'Không tìm thấy thẻ.');
    }

    header('Location: capthe_cards.php');
    exit;
}

// Nếu không có id/action → đưa về danh sách
header('Location: capthe_cards.php');
exit;
