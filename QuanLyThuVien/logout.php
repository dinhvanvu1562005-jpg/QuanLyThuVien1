<?php
require_once 'functions.php';
if (is_logged_in()) {
    audit_log('logout', 'User logged out');
}
session_unset();
session_destroy();
header('Location: login.php');
exit;
