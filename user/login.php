<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user.css">
    
</head>
<body>
<header class="header ">
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
        <h2>Đăng Nhập</h2>
        <form action="login.php" method="POST">
            <label for="username">Tên Đăng Nhập:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mật Khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn-login">Đăng Nhập</button>
        </form>
        <div class="login-lable">
        <a href="register.php">Chưa Có Tài Khoản, Đăng Ký Ngay</a>
        <a href="">Quên Mật Khẩu</a>
        </div>
    </div>
    </main>
</body>
</html>
