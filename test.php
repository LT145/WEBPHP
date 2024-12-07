<?php
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Kiểm tra password_verify
if (password_verify('password123', $hashedPassword)) {
    echo 'Mật khẩu đúng';
} else {
    echo 'Mật khẩu sai';
}
?>