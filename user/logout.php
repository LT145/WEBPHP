<?php
session_start(); // Bắt đầu session

// Xóa toàn bộ dữ liệu session
session_unset();
session_destroy();
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './index.php';
header("Location: $redirect_url"); 
exit();
?>
