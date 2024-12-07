<?php
// Lấy email từ URL
$email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận OTP</title>
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
    <div class="otp-container">
        <h2>Nhập Mã OTP Gửi Đến Email</h2>
        <p>Email bạn đã đăng ký: <strong><?php echo htmlspecialchars($email); ?></strong></p>

        <form action="verify_otp.php" method="POST">
            <label for="otp">Mã OTP:</label>
            <input type="text" id="otp" name="otp" required>

            <button type="submit" class="btn-verify">Xác Nhận OTP</button>
        </form>
        <div class="login-lable">
            <a href="register.php">Quay lại đăng ký</a>
        </div>
    </div>
</main>
</body>
</html>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';


// Tạo mã OTP ngẫu nhiên
$otp = rand(100000, 999999);

// Lấy email từ URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Tạo một đối tượng PHPMailer
$mail = new PHPMailer(true);

try {
    // Cấu hình server SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Dùng server SMTP của Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'loc42286@gmail.com'; // Thay bằng email của bạn
    $mail->Password = 'yqjskovckgzsywem';  // Thay bằng mật khẩu email của bạn
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';


    // Người gửi và người nhận
    $mail->setFrom('loc42286@gmail.com', 'DOANPHP');  // Thay bằng email và tên của bạn
    $mail->addAddress($email); // Thêm người nhận (email đã nhập)

    // Nội dung email
    $mail->isHTML(true);
    $mail->Subject = 'Mã OTP Xác Nhận';
    $mail->Body    = 'Mã OTP của bạn là: <strong>' . $otp . '</strong>';

    // Gửi email
    $mail->send();
    echo 'OTP đã được gửi đến email của bạn.';
} catch (Exception $e) {
    echo "Không thể gửi OTP. Lỗi: {$mail->ErrorInfo}";
}
?>
