<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="../index.php">
            <img src="../img/logo/logo.png" alt="">
        </a>
    </div>
    <div class="navbar-flex">
        <li class="nav-item active mx-5">
            <a class="nav-link" href="../index.php">Trang Chủ</a>   
        </li>
        <li class="nav-item mx-5">
            <a class="nav-link" href="/category/index.php">Danh Mục</a>
        </li>
        <li class="nav-item mx-5">
            <a class="nav-link" href="login.php">Đăng Nhập</a>
        </li>
    </div>
</header>

<main>
    <div class="login-container">
        <h2>Đăng Ký</h2>
        <form action="register.php" method="POST">
            <label for="username">Tên Đăng Nhập:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mật Khẩu:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Xác Nhận Mật Khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" class="btn-login">Đăng Ký</button>
        </form>
        <div class="login-lable">
            <a href="login.php">Đã có tài khoản? Đăng Nhập ngay</a>
        </div>
    </div>
</main>
</body>
</html>
<?php
// Đoạn mã PHP xử lý đăng ký (ví dụ)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu và xác nhận mật khẩu
    if ($password === $confirm_password) {
        // Xử lý đăng ký tại đây, ví dụ lưu vào database

        // Sau khi đăng ký thành công, chuyển hướng đến trang OTP và truyền email
        header("Location: otp.php?email=" . urlencode($email));
        exit();
    } else {
        echo "Mật khẩu không khớp!";
    }
}
?>
