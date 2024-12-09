<?php
session_start();
include '../asset/connect.php'; // Bao gồm file kết nối database

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    exit();
}

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$dob = $_SESSION['dob'];
$gender = $_SESSION['gender'];
$imgavt = $_SESSION['imgavt'];
$role = $_SESSION['role'];
$fullname = $_SESSION['fullname'];

// Hàm upload ảnh lên ImgBB
function uploadImageToImgBB($imagePath) {
    $apiKey = 'b2dedb1909d0db83e3589f48a28b7e11'; // Thay thế bằng API Key của bạn
    $url = 'https://api.imgbb.com/1/upload';

    // Đọc nội dung tệp ảnh
    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        return "Không thể đọc tệp ảnh.";
    }

    // Tạo dữ liệu POST cho cURL
    $data = [
        'image' => base64_encode($imageData),  // Mã hóa ảnh thành base64
        'key' => $apiKey
    ];

    // Tạo cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Tắt SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Gửi request và nhận phản hồi
    $response = curl_exec($ch);

    // Kiểm tra lỗi cURL
    if ($response === false) {
        curl_close($ch);
        return 'Lỗi cURL: "' . curl_error($ch) . '"';
    }

    curl_close($ch);

    // Giải mã JSON và lấy URL ảnh
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['url'])) {
        return $responseData['data']['url']; // Trả về URL ảnh trên ImgBB
    } else {
        return "Lỗi từ ImgBB: " . (isset($responseData['error']['message']) ? $responseData['error']['message'] : 'Không rõ');
    }
}

// Xử lý khi người dùng muốn lưu thông tin mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fullname = $_POST['fullname'];
    $new_gender = $_POST['gender'];
    $new_dob = $_POST['dob'];

    // Kiểm tra xem người dùng có thay đổi ảnh hay không
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $profile_img = $_FILES['profile'];
        $imagePath = $profile_img['tmp_name'];

        // Gọi hàm upload ảnh lên ImgBB
        $imageUrl = uploadImageToImgBB($imagePath);

        if ($imageUrl) {
            // Cập nhật ảnh đại diện vào cơ sở dữ liệu
            $query = "UPDATE user SET fullname = :fullname, gender = :gender, dob = :dob, imgavt = :imgavt WHERE user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':imgavt', $imageUrl);
        } else {
            echo "<script>alert('Đã xảy ra lỗi khi upload ảnh.');</script>";
        }
    } else {
        // Nếu không có ảnh mới thì chỉ cập nhật thông tin cá nhân
        $query = "UPDATE user SET fullname = :fullname, gender = :gender, dob = :dob WHERE user_id = :user_id";
        $stmt = $conn->prepare($query);
    }

    // Cập nhật thông tin người dùng
    $stmt->bindParam(':fullname', $new_fullname);
    $stmt->bindParam(':gender', $new_gender);
    $stmt->bindParam(':dob', $new_dob);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Thực thi câu truy vấn và kiểm tra lỗi
    if ($stmt->execute()) {
        $_SESSION['fullname'] = $new_fullname;
        $_SESSION['gender'] = $new_gender;
        $_SESSION['dob'] = $new_dob;
        if (isset($imageUrl)) {
            $_SESSION['imgavt'] = $imageUrl;
        }
        echo "<script>alert('Thông tin đã được cập nhật!'); window.location.href = 'profile.php';</script>"; // Điều hướng về trang profile
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "<script>alert('Lỗi khi cập nhật thông tin: " . $errorInfo[2] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Cá Nhân</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<script>
    // Chức năng thay đổi thông tin
    function enableEditing() {
        document.getElementById("fullname").disabled = false;
        document.getElementById("gender").disabled = false;
        document.getElementById("dob").disabled = false;
        document.getElementById("saveButton").disabled = false;
        document.getElementById("editButton").style.display = 'none';
        document.getElementById("saveButton").style.display = 'block';
        document.getElementById("imgavt").disabled = false; 
        document.getElementById("imgavt").style.cursor = 'pointer';
        // Tắt readonly cho fullname và date of birth
        document.getElementById("fullname").removeAttribute('readonly');
        document.getElementById("dob").removeAttribute('readonly');
    }
</script>

<body class="bg-gray-100 font-roboto">
    <?php include '../component/header.php'; ?>

    <section class="py-10 my-auto dark:bg-gray-900">
        <div class="lg:w-[80%] md:w-[90%] xs:w-[96%] mx-auto flex gap-4">
            <div class="lg:w-[88%] md:w-[80%] sm:w-[88%] xs:w-full mx-auto shadow-2xl p-4 rounded-xl h-fit self-center dark:bg-gray-800/40">
                <div class="">
                    <h1 class="lg:text-3xl md:text-2xl sm:text-xl xs:text-xl font-serif font-extrabold mb-2 dark:text-white">
                        Profile
                    </h1>
                    <form method="POST" enctype="multipart/form-data">
                        <!-- Avatar image container with a clickable input file -->
                        <div class="mx-auto flex justify-center w-[141px] h-[141px] bg-blue-300/20 rounded-full relative"
                            style="background-image: url('<?php echo htmlspecialchars($imgavt); ?>'); background-size: cover; background-position: center;">
                            <input type="file" id="imgavt" class="absolute inset-0 w-full h-full opacity-0 cursor-initial" disabled>
                        </div>

                        <!-- Input fields for fullname, gender, dob -->
                        <div class="flex lg:flex-row md:flex-col sm:flex-col xs:flex-col gap-2 justify-center w-full">
                            <div class="w-full mb-4 mt-6">
                                <label for="fullname" class="mb-2 dark:text-gray-300">Họ và Tên</label>
                                <input type="text" id="fullname" name="fullname" class="mt-2 p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800"
                                    value="<?php echo htmlspecialchars($fullname); ?>" disabled>
                            </div>

                            <div class="w-full mb-4 lg:mt-6">
                                <label for="email" class="mb-2 dark:text-gray-300">Email</label>
                                <input type="text" id="email" name="email" class="mt-2 p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800"
                                    value="<?php echo htmlspecialchars($email); ?>" disabled>
                            </div>
                        </div>

                        <div class="flex lg:flex-row md:flex-col sm:flex-col xs:flex-col gap-2 justify-center w-full">
                            <div class="w-full">
                                <h3 class="dark:text-gray-300 mb-2">Giới Tính</h3>
                                <select id="gender" name="gender" class="w-full text-grey border-2 rounded-lg p-4 pl-2 pr-2 dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800" disabled>
                                    <option value="Nam" <?php echo ($gender == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="Nữ" <?php echo ($gender == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                </select>
                            </div>

                            <div class="w-full">
                                <h3 class="dark:text-gray-300 mb-2">Ngày Sinh</h3>
                                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" class="text-grey p-4 w-full border-2 rounded-lg dark:text-gray-200 dark:border-gray-600 dark:bg-gray-800" disabled>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-center gap-4 mt-4">
                            <button type="button" id="editButton" onclick="enableEditing()" class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-md">
                                Thay Đổi
                            </button>
                            <button type="submit" id="saveButton" class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-md" style="display:none;">
                                Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
