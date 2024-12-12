<?php
session_start();
include '../asset/connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$dob = $_SESSION['dob'];
$gender = $_SESSION['gender'];
$imgavt = $_SESSION['imgavt'];
$role = $_SESSION['role'];
$fullname = $_SESSION['fullname'];

function uploadImageToImgBB($imagePath) {
    $apiKey = 'b2dedb1909d0db83e3589f48a28b7e11'; 
    $url = 'https://api.imgbb.com/1/upload';

    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        return "Không thể đọc tệp ảnh.";
    }

    $data = [
        'image' => base64_encode($imageData),  
        'key' => $apiKey
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if ($response === false) {
        curl_close($ch);
        return 'Lỗi cURL: "' . curl_error($ch) . '"';
    }

    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['data']['url'])) {
        return $responseData['data']['url']; 
    } else {
        return "Lỗi từ ImgBB: " . (isset($responseData['error']['message']) ? $responseData['error']['message'] : 'Không rõ');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fullname = $_POST['fullname'];
    $new_gender = $_POST['gender'];
    $new_dob = $_POST['dob'];

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $profile_img = $_FILES['profile'];
        $imagePath = $profile_img['tmp_name'];

        $imageUrl = uploadImageToImgBB($imagePath);

        if ($imageUrl) {
            $query = "UPDATE user SET fullname = :fullname, gender = :gender, dob = :dob, imgavt = :imgavt WHERE user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':imgavt', $imageUrl);
        } else {
            echo "<script>alert('Đã xảy ra lỗi khi upload ảnh.');</script>";
        }
    } else {
        $query = "UPDATE user SET fullname = :fullname, gender = :gender, dob = :dob WHERE user_id = :user_id";
        $stmt = $conn->prepare($query);
    }

    $stmt->bindParam(':fullname', $new_fullname);
    $stmt->bindParam(':gender', $new_gender);
    $stmt->bindParam(':dob', $new_dob);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['fullname'] = $new_fullname;
        $_SESSION['gender'] = $new_gender;
        $_SESSION['dob'] = $new_dob;
        if (isset($imageUrl)) {
            $_SESSION['imgavt'] = $imageUrl;
        }
        echo "<script>alert('Thông tin đã được cập nhật!'); window.location.href = 'profile.php';</script>"; 
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex-grow: 1;
        }
    </style>
</head>

<body class="bg-gray-100 font-roboto">
    <?php include '../component/header.php'; ?>

    <!-- Main content section -->
    <section class="py-10 my-auto bg-gradient-to-r from-blue-300 to-purple-400 content">
        <div class="lg:w-[80%] md:w-[90%] xs:w-[96%] mx-auto flex gap-4">
            <div class="lg:w-[88%] md:w-[80%] sm:w-[88%] xs:w-full mx-auto shadow-2xl p-4 rounded-xl h-fit self-center bg-white">
                <div class="">
                    <h1 class="lg:text-3xl md:text-2xl sm:text-xl xs:text-xl font-serif font-extrabold mb-2 text-gray-800">
                        Profile
                    </h1>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mx-auto flex justify-center w-[141px] h-[141px] bg-gray-200 rounded-full relative overflow-hidden"
                             style="background-image: url('<?php echo htmlspecialchars($imgavt); ?>'); background-size: cover; background-position: center;">
                            <input type="file" id="imgavt" name="profile" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                   onchange="previewImage(this)" disabled> 
                            <label for="imgavt" class="cursor-pointer"> 
                            </label>
                        </div>

                        <div class="flex lg:flex-row md:flex-col sm:flex-col xs:flex-col gap-2 justify-center w-full">
                            <div class="w-full mb-4 mt-6">
                                <label for="fullname" class="mb-2 text-gray-700">Họ và Tên</label>
                                <input type="text" id="fullname" name="fullname" class="mt-2 p-4 w-full border-2 rounded-lg text-gray-700 border-gray-300 bg-gray-100"
                                       value="<?php echo htmlspecialchars($fullname); ?>" disabled>
                            </div>

                            <div class="w-full mb-4 lg:mt-6">
                                <label for="email" class="mb-2 text-gray-700">Email</label>
                                <input type="text" id="email" name="email" class="mt-2 p-4 w-full border-2 rounded-lg text-gray-700 border-gray-300 bg-gray-100"
                                       value="<?php echo htmlspecialchars($email); ?>" disabled>
                            </div>
                        </div>

                        <div class="flex lg:flex-row md:flex-col sm:flex-col xs:flex-col gap-2 justify-center w-full">
                            <div class="w-full">
                                <h3 class="text-gray-700 mb-2">Giới Tính</h3>
                                <select id="gender" name="gender" class="w-full text-gray-700 border-2 rounded-lg p-4 pl-2 pr-2 border-gray-300 bg-gray-100" disabled>
                                    <option value="Nam" <?php echo ($gender == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="Nữ" <?php echo ($gender == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                </select>
                            </div>

                            <div class="w-full">
                                <h3 class="text-gray-700 mb-2">Ngày Sinh</h3>
                                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" class="text-gray-700 p-4 w-full border-2 rounded-lg border-gray-300 bg-gray-100" disabled>
                            </div>
                        </div>

                        <div class="flex justify-center gap-4 mt-4">
                            <button type="button" id="editButton" onclick="enableEditing()" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md">
                                Thay Đổi
                            </button>
                            <button type="submit" id="saveButton" class="bg-green-500 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-md" style="display:none;">
                                Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include '../component/footer.php'; ?>

    <script>
        function enableEditing() {
            document.getElementById("fullname").disabled = false;
            document.getElementById("gender").disabled = false;
            document.getElementById("dob").disabled = false;
            document.getElementById("editButton").style.display = 'none';
            document.getElementById("saveButton").style.display = 'inline-block';
        }

        function previewImage(input) {
            var file = input.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var preview = document.querySelector('div[style*="background-image"]');
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
