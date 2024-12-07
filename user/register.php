<?php
include '../asset/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// Khởi tạo thông báo lỗi (Mảng trống mặc định)
// Khởi tạo thông báo lỗi (Mảng trống mặc định)
$errors = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    'full_name' => '' // Thêm trường full_name vào mảng lỗi
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $full_name = trim($_POST["full_name"]);  // Lấy họ và tên từ form

    // Kiểm tra trường "Họ và Tên" (Không có số và ký tự đặc biệt)
// Kiểm tra trường "Họ và Tên" (Không có số và ký tự đặc biệt, nhưng cho phép dấu tiếng Việt và khoảng trắng)
if (empty($full_name)) {
    $errors['full_name'] = 'Họ và Tên không được để trống.';
} elseif (!preg_match("/^[\p{L}\s]+$/u", $full_name)) {  // Biểu thức cho phép dấu tiếng Việt
    $errors['full_name'] = 'Họ và Tên chỉ được phép chứa chữ cái và khoảng trắng.';
}


    // Kiểm tra username và email trong cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        if ($existingUser['username'] === $username) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại.';
        }
        if ($existingUser['email'] === $email) {
            $errors['email'] = 'Email đã được sử dụng.';
        }
    }

    // Kiểm tra mật khẩu và xác nhận mật khẩu
    if ($password !== $confirm_password) {
        $errors['password'] = 'Mật khẩu không khớp.';
        $errors['confirm_password'] = 'Mật khẩu không khớp.';
    }

    // Nếu không có lỗi, xử lý tiếp
    if (empty(array_filter($errors))) {
        // Tạo mã OTP
        $otp = rand(100000, 999999);

        // Gửi email OTP
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'loc42286@gmail.com';
            $mail->Password = 'yqjskovckgzsywem';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('loc42286@gmail.com', 'DOANPHP');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Mã OTP Xác Nhận';
            $mail->Body = 'Mã OTP của bạn là: <strong>' . $otp . '</strong>';

            $mail->send();

            // Lưu thông tin người dùng vào session
// Lưu thông tin người dùng vào session
session_start();
$_SESSION["otp"] = $otp;
$_SESSION["username"] = $username;
$_SESSION["email"] = $email;
$_SESSION["password"] = $password;
$_SESSION["full_name"] = $full_name;  // Lưu full_name vào session
$_SESSION["otp_time"] = time(); // Lưu thời gian gửi OTP


            // Chuyển hướng đến trang xác nhận OTP
            header("Location: otp.php?email=" . $email);
            exit();
        } catch (Exception $e) {
            echo "Không thể gửi OTP. Lỗi: {$mail->ErrorInfo}";
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
    <title>Đăng Ký</title>
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
            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Đăng Ký</h2>
            <form action="register.php" method="POST">
    <!-- Username -->
    <div class="mb-4">
        <label for="username" class="block text-gray-700 font-medium mb-2">Tên Đăng Nhập:</label>
        <input 
            type="text" 
            id="username" 
            name="username" 
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
            class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['username'] ? 'border-red-500' : '' ?>" 
            required
        >
        <?php if ($errors['username']): ?>
            <p class="text-red-500 text-sm mt-1"><?= $errors['username'] ?></p>
        <?php endif; ?>
    </div>
<!-- Full Name -->
<div class="mb-4">
    <label for="full_name" class="block text-gray-700 font-medium mb-2">Họ và Tên:</label>
    <input 
        type="text" 
        id="full_name" 
        name="full_name" 
        value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" 
        class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['full_name'] ? 'border-red-500' : '' ?>" 
        required
    >
    <?php if ($errors['full_name']): ?>
        <p class="text-red-500 text-sm mt-1"><?= $errors['full_name'] ?></p>
    <?php endif; ?>
</div>

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-2">Email:</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
            class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['email'] ? 'border-red-500' : '' ?>" 
            required
        >
        <?php if ($errors['email']): ?>
            <p class="text-red-500 text-sm mt-1"><?= $errors['email'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="block text-gray-700 font-medium mb-2">Mật Khẩu:</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['password'] ? 'border-red-500' : '' ?>" 
            required
        >
        <?php if ($errors['password']): ?>
            <p class="text-red-500 text-sm mt-1"><?= $errors['password'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Confirm Password -->
    <div class="mb-6">
        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Xác Nhận Mật Khẩu:</label>
        <input 
            type="password" 
            id="confirm_password" 
            name="confirm_password" 
            class="border border-gray-300 px-4 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['confirm_password'] ? 'border-red-500' : '' ?>" 
            required
        >
        <?php if ($errors['confirm_password']): ?>
            <p class="text-red-500 text-sm mt-1"><?= $errors['confirm_password'] ?></p>
        <?php endif; ?>
    </div>

    <button type="submit" class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md w-full transition duration-300">Đăng Ký</button>
</form>



            <div class="login-lable mt-6 text-center text-gray-600">
                <a href="login.php" class="hover:text-gray-900">Đã có tài khoản? Đăng Nhập ngay</a>
            </div>
        </div>
    </main>
</body>
</html>
