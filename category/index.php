<?php
// Thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "congthucphache";

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn dữ liệu từ bảng category
$sql = "SELECT * FROM category";
$result = $conn->query($sql);

// Bắt đầu xuất HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Nấu Ăn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
    <link rel="stylesheet" href="index.css">
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
                <a class="nav-link" href="index.php">Danh Mục</a>
            </li>
            <li class="nav-item mx-5">
                <a class="nav-link" href="/user/login.php">Đăng Nhập</a>
            </li>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>Danh Sách Các Công Thức Pha Chế</h1>
            <div class="container-box">
                <?php
                if ($result->num_rows > 0) {
                    // Duyệt qua từng dòng dữ liệu
                    while ($row = $result->fetch_assoc()) {
                        // Thêm liên kết tới trang chi tiết danh mục
                        echo '<a href="/ListProduct/index.php?id_category=' . $row['id'] . '" class="box-product">';
                        echo '<img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<h2>Cách Làm ' . htmlspecialchars($row['name']) . '</h2>';
                        echo '<p>' . htmlspecialchars($row['mota']) . '</p>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>Không có dữ liệu!</p>';
                }
                ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <h2>LK BARTENDER</h2>
                    </a>
                    <p class="footer-text">
                        Khơi nguồn sáng tạo, nâng tầm nghệ thuật pha chế!
                    </p>
                </div>
                <div class="footer-contact">
                    <h4 class="contact-title">Liên Hệ Ngay</h4>
                    <ul>
                        <li class="contact-item">
                            <ion-icon name="call-outline"></ion-icon>
                            <a href="tel:+01123456790" class="contact-link">+84 332345957</a>
                        </li>
                        <li class="contact-item">
                            <ion-icon name="mail-outline"></ion-icon>
                            <a href="mailto:info.tourly.com" class="contact-link">dh52111258@student.stu.edu.vn</a>
                        </li>
                        <li class="contact-item">
                            <ion-icon name="location-outline"></ion-icon>
                            <address>20 Sư Vạn Hạnh, Phường 9, Quận 5, TPHCM</address>
                        </li>
                    </ul>
                </div>
                <div class="footer-form">
                    <p class="form-text">
                        Nếu Có Vấn Đề Gì Cần Hỗ Trợ Hãy Điền Thông Tin <br> Vào Đây:
                    </p>
                    <input type="text" name="email" id="email" class="input-field" placeholder="Nhập Vào Email Của bạn" required>
                    <input type="text" name="message" id="message" class="input-field" placeholder="Vấn Đề Của Bạn:" required>
                    <button onclick="SendMail()" class="btn btn-secondary">Gửi</button>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
