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

// Lấy giá trị id_category từ URL
$id_category = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;

// Truy vấn sản phẩm thuộc danh mục
$sql = "SELECT * FROM product WHERE id_category = $id_category";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
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
                                    <a class="nav-link" href="/user/login.php">Đăng Nhập</a>
                                </li>
                </div>
    </header>

    <main>
        <div class="container">
            <h1>Sản Phẩm Của Danh Mục</h1>
            <div class="container-box">
                <?php
                if ($result->num_rows > 0) {
                    // Duyệt qua từng dòng dữ liệu của sản phẩm trong danh mục
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="box-product">';
                        echo '<img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                        echo '<p>' . htmlspecialchars($row['mota']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Không có sản phẩm trong danh mục này!</p>';
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
            </div>
        </div>
    </footer>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
