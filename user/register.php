<?php
include '../asset/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// Khởi tạo thông báo lỗi (Mảng trống mặc định)
$errors = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    'full_name' => '',
    'dob' => '',
    'gender' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $full_name = trim($_POST["full_name"]);
    $dob = trim($_POST["dob"]);
    $gender = trim($_POST["gender"]);

    // Kiểm tra trường "Họ và Tên" (Không có số và ký tự đặc biệt)
    if (empty($full_name)) {
        $errors['full_name'] = 'Họ và Tên không được để trống.';
    } elseif (!preg_match("/^[\p{L}\s]+$/u", $full_name)) {
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

    // Kiểm tra trường "Ngày Sinh"
    if (empty($dob)) {
        $errors['dob'] = 'Ngày sinh không được để trống.';
    }

    // Kiểm tra trường "Giới Tính"
    if (empty($gender)) {
        $errors['gender'] = 'Giới tính không được để trống.';
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
            session_start();
            $_SESSION["otp"] = $otp;
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;
            $_SESSION["full_name"] = $full_name;
            $_SESSION["dob"] = $dob;
            $_SESSION["gender"] = $gender;
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
    
<?php include('../asset/loadingpage.php'); ?>
<?php include('../component/header.php'); ?>

<main class="container mx-auto px-5 py-5">
    <div class="login-container max-w-2xl mx-auto bg-white p-6 rounded-md shadow-lg overflow-hidden border border-gray-200">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Đăng Ký</h2>
        <form action="register.php" method="POST" class="space-y-4" onsubmit="showLoading(event)"> <!-- Giảm khoảng cách giữa các hàng -->
            <div class="mb-3"> <!-- Giảm margin-bottom -->
                <label for="username" class="block text-gray-700 font-medium mb-1">Tên Đăng Nhập:</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['username'] ? 'border-red-500' : '' ?>"
                    required>
                <?php if ($errors['username']): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['username'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3"> <!-- Giảm margin-bottom -->
                <label for="full_name" class="block text-gray-700 font-medium mb-1">Họ và Tên:</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                    class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['full_name'] ? 'border-red-500' : '' ?>"
                    required>
                <?php if ($errors['full_name']): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['full_name'] ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3"> <!-- Giảm khoảng cách giữa các ô -->
                <div class="mb-3">
                    <label for="dob" class="block text-gray-700 font-medium mb-1">Ngày Sinh:</label>
                    <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['dob'] ? 'border-red-500' : '' ?>" required>
                    <?php if ($errors['dob']): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['dob'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="gender" class="block text-gray-700 font-medium mb-1">Giới Tính:</label>
                    <select id="gender" name="gender" class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['gender'] ? 'border-red-500' : '' ?>" required>
                        <option value="Nam" <?= ($_POST['gender'] ?? '') == 'Nam' ? 'selected' : '' ?>>Nam</option>
                        <option value="Nữ" <?= ($_POST['gender'] ?? '') == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                    </select>
                    <?php if ($errors['gender']): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['gender'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3"> <!-- Giảm margin-bottom -->
                <label for="email" class="block text-gray-700 font-medium mb-1">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['email'] ? 'border-red-500' : '' ?>" required>
                <?php if ($errors['email']): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3"> <!-- Giảm margin-bottom -->
                <label for="password" class="block text-gray-700 font-medium mb-1">Mật Khẩu:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['password'] ? 'border-red-500' : '' ?>"
                    required>
                <?php if ($errors['password']): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['password'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3"> <!-- Giảm margin-bottom -->
                <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Xác Nhận Mật Khẩu:</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="border border-gray-300 px-3 py-2 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= $errors['confirm_password'] ? 'border-red-500' : '' ?>"
                    required>
                <?php if ($errors['confirm_password']): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['confirm_password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-300" id="submitBtn">Đăng Ký</button>
        </form>
    </div>
</main>

<script>
    function showLoading(event) {
        // Đảm bảo rằng form sẽ được submit
        const form = event.target;

        // Disable nút submit và các hành động khác
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Đang Xử Lý...";
        form.style.pointerEvents = "none";
    }
</script>


</body>
</html>
