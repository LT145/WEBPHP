<?php
session_start();
include '../asset/connect.php';

// Khởi tạo thông báo lỗi
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin người dùng từ form
    $usernameOrEmail = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($usernameOrEmail) || empty($password)) {
        $errors[] = 'Vui lòng điền đầy đủ thông tin đăng nhập.';
    } else {
        // Truy vấn cơ sở dữ liệu để kiểm tra người dùng
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :usernameOrEmail OR email = :usernameOrEmail");
        $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Kiểm tra trạng thái tài khoản
            if ($user['status'] === 'locked') {
                $errors[] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ với admin để được hỗ trợ.';
            } else {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['password'])) {
                    // Đăng nhập thành công
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['dob'] = $user['dob'];
                    $_SESSION['gender'] = $user['gender'];
                    $_SESSION['imgavt'] = $user['imgavt'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['fullname'] = $user['fullname'];

                    // Lấy tên cuối
                    $full_name = $user['fullname'];
                    $name_parts = explode(' ', $full_name);
                    $_SESSION['first_name'] = end($name_parts);

                    header("Location: /index.php");
                    exit();
                } else {
                    $errors[] = 'Mật khẩu không đúng.';
                }
            }
        } else {
            $errors[] = 'Tên đăng nhập hoặc email không tồn tại.';
        }
    }
}


$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <header class="bg-black text-white py-4">
        <div class="container mx-auto px-5 flex justify-between items-center">
            <div class="logo">
                <a href="../index.php">
                    <img src="../img/logo/logo.png" alt="" class="w-20 invert">
                </a>
            </div>
            <nav class="navbar-flex space-x-5">
                <a href="../index.php" class="hover:text-gray-300">Trang Chủ</a>
                <a href="/category/index.php" class="hover:text-gray-300">Danh Mục</a>
                <a href="login.php" class="hover:text-gray-300">Đăng Nhập</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-10 py-10">
        <div class="login-container max-w-md mx-auto bg-white p-8 rounded-md shadow-lg overflow-hidden border border-gray-200">
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Đăng Nhập</h2>
            
            <!-- Hiển thị lỗi nếu có -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-md">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-medium mb-2">Tên Đăng Nhập hoặc Email:</label>
                    <input type="text" id="username" name="username" class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Mật Khẩu:</label>
                    <input type="password" id="password" name="password" class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <button type="submit" class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md w-full transition duration-300">Đăng Nhập</button>
            </form>

            <div class="login-lable mt-6 flex justify-between text-gray-600"> 
                <a href="register.php" class="hover:text-gray-900">Đăng Ký Ngay</a>
                <a href="" class="hover:text-gray-900">Quên Mật Khẩu</a>
            </div>
        </div>
    </main>
</body>
</html>
