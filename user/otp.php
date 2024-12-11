<?php
session_start(); // Bắt đầu session

// Lấy email từ URL nếu có
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Lấy thời gian OTP và tính thời gian còn lại
$otp_time = $_SESSION["otp_time"] ?? time(); // Lấy thời gian OTP từ session (nếu có), nếu không lấy thời gian hiện tại
$current_time = time();
$otp_expiry_time = 300; // 5 phút cho OTP
$remaining_time = $otp_expiry_time - ($current_time - $otp_time); // Thời gian còn lại cho OTP
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận OTP</title>
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
        <div class="otp-container max-w-md mx-auto bg-white p-8 rounded-md shadow-lg overflow-hidden border border-gray-200"> 
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Nhập Mã OTP Gửi Đến Email</h2> 
            <p class="text-gray-700 mb-4">Email bạn đã đăng ký: <strong class="font-medium"><?php echo htmlspecialchars($email); ?></strong></p>
            <p class="text-gray-700 mb-4">Thời gian còn lại để nhập OTP: <strong id="countdown"><?= gmdate("i:s", $remaining_time) ?></strong></p>

            <form method="POST"> 
                <div class="mb-4">
                    <label for="otp" class="block text-gray-700 font-medium mb-2">Mã OTP:</label>
                    <input type="text" id="otp" name="otp" 
                        class="border border-gray-400 px-3 py-2 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required pattern="[0-9]{6}" maxlength="6"> 
                    <span class="error-message text-red-500 text-sm"></span> 
                </div>
                <button type="submit" class="bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md w-full transition duration-300">Xác Nhận OTP</button>
            </form>
            <div class="mt-6 text-center text-gray-600">
                <a href="register.php" class="hover:text-gray-900">Quay lại đăng ký</a>
            </div>
        </div>
    </main>

    <script>
        // Khởi tạo thời gian còn lại (tính từ PHP)
        let remainingTime = <?= $remaining_time ?>;
        const countdownElement = document.getElementById('countdown');

        // Cập nhật đếm ngược mỗi giây
        const countdownInterval = setInterval(function() {
            if (remainingTime > 0) {
                remainingTime--; // Giảm thời gian
                countdownElement.textContent = new Date(remainingTime * 1000).toISOString().substr(14, 5); // Hiển thị lại thời gian
            } else {
                countdownElement.textContent = '00:00'; // Nếu hết thời gian
                clearInterval(countdownInterval); // Dừng đếm ngược
            }
        }, 1000);
    </script>
</body>
</html>
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED); 
ini_set('display_errors', 0); // Tắt hiển thị lỗi trên trình duyệt
// Nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy OTP từ form
    $otp_entered = $_POST["otp"];
    $email = $_SESSION["email"]; // Lấy email từ session

    // Kiểm tra OTP
    if ($otp_entered == $_SESSION["otp"]) {
        // OTP đúng, tiến hành lưu thông tin người dùng vào CSDL
        // Lấy các thông tin từ session
$username = $_SESSION["username"];
$email = $_SESSION["email"];
$password = $_SESSION["password"];
$full_name = $_SESSION["full_name"];  // Lấy full_name từ session
$role = "user";
$dob = $_SESSION["dob"];  // Lấy full_name từ session
$gender = $_SESSION["gender"];
$imgavt = "https://i.ibb.co/fX09cRC/image.png";
$status = "active ";

// Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    include '../asset/connect.php'; // Kết nối đến CSDL

    // Lưu thông tin người dùng vào CSDL (thêm full_name)
    $stmt = $conn->prepare("INSERT INTO user (username, email, password, fullname, role, dob, gender, imgavt, status) VALUES (:username, :email, :password, :fullname, :role, :dob, :gender, :imgavt, :status)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':fullname', $full_name);  // Thêm bindParam cho full_name
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':dob', $dob);  // Thêm bindParam cho full_name
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':imgavt', $imgavt);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Thông báo thành công
    echo "Đăng ký thành công!";

    // Xóa session OTP và thông tin người dùng
    unset($_SESSION["otp"]);
    unset($_SESSION["username"]);
    unset($_SESSION["email"]);
    unset($_SESSION["password"]);
    unset($_SESSION["full_name"]);
    unset($_SESSION["dob"]);
    unset($_SESSION["gender"]);  

    // Chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null; // Đóng kết nối

    } else {
        echo "Mã OTP không chính xác!";
    }
}

?>
