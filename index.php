<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webnauan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn dữ liệu món ăn nổi bật
$sql = "SELECT Title, Image FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Nấu Ăn</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
</style>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header class="header ">
                 <div class="logo">
                    <a href="#">
                        <img src="./img/logo/logo.png" alt="">
                    </a>
                 </div>
                <div class="navbar-flex">
                                <li class="nav-item active mx-5">
                                    <a class="nav-link" href="index.php">Trang Chủ</a>   
                                </li>
                                <li class="nav-item mx-5">
                                    <a class="nav-link" href="./category/index.php">Danh Mục</a>
                                </li>
                                <li class="nav-item mx-5">
                                    <a class="nav-link" href="./user/login.php">Đăng Nhập</a>
                                </li>

                </div>
    </header>
    <main>
        <div class="container">
            <div class="page-inner">
                <h1>Công Thức Pha Chế Đồ Uống</h1>
                <p>Chuyên mục công thức pha chề đồ uống của website Dạy Pha Chế Á Âu cung cấp đa dạng cách làm các loại thức uống từ không cồn (nước ép trái cây, sinh tố, cafe, sữa chua…), pha chế đồ uống có cồn (cocktail, mocktail, mojito…) đến cách pha chế trà sữa, làm kem… Đồng thời, chuyên mục thường xuyên cập nhật những công thức đồ uống đang HOT hiện nay. Hi vọng chuyên mục sẽ giúp bạn trau dồi thêm kinh nghiệm pha chế và học được nhiều cách làm đồ uống hấp dẫn, mới lạ.</p>
            </div>
            <div class="container-product">
                <div class="list-product">
                    <span>CÁC LOẠI ĐỒ UỐNG CÓ CỒN</span>
                    <div class="slideshow-container">
                        <div class="slide-wrapper">
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 1">
                            <p class="caption">Slide 1</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 2">
                            <p class="caption">Slide 2</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 3">
                            <p class="caption">Slide 3</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 4">
                            <p class="caption">Slide 4</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 5">
                            <p class="caption">Slide 5</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 6">
                            <p class="caption">Slide 6</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 7">
                            <p class="caption">Slide 7</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 8">
                            <p class="caption">Slide 8</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 9">
                            <p class="caption">Slide 9</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 10">
                            <p class="caption">Slide 10</p>
                            </div>
                        </div>
                        <button class="prev-btn" onclick="prevSlide()">❮</button>
                        <button class="next-btn" onclick="nextSlide()">❯</button>
                    </div>
                </div>
            </div>
            <div class="container-product">
                <div class="list-product">
                    <span>CÁC LOẠI ĐỒ UỐNG CÓ CỒN</span>
                    <div class="slideshow-container">
                        <div class="slide-wrapper">
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 1">
                            <p class="caption">Slide 1</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 2">
                            <p class="caption">Slide 2</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 3">
                            <p class="caption">Slide 3</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 4">
                            <p class="caption">Slide 4</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 5">
                            <p class="caption">Slide 5</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 6">
                            <p class="caption">Slide 6</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 7">
                            <p class="caption">Slide 7</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 8">
                            <p class="caption">Slide 8</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 9">
                            <p class="caption">Slide 9</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 10">
                            <p class="caption">Slide 10</p>
                            </div>
                        </div>
                        <button class="prev-btn" onclick="prevSlide()">❮</button>
                        <button class="next-btn" onclick="nextSlide()">❯</button>
                    </div>
                </div>
            </div>
            <div class="container-product">
                <div class="list-product">
                    <span>CÁC LOẠI ĐỒ UỐNG CÓ CỒN</span>
                    <div class="slideshow-container">
                        <div class="slide-wrapper">
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 1">
                            <p class="caption">Slide 1</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 2">
                            <p class="caption">Slide 2</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 3">
                            <p class="caption">Slide 3</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 4">
                            <p class="caption">Slide 4</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 5">
                            <p class="caption">Slide 5</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 6">
                            <p class="caption">Slide 6</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 7">
                            <p class="caption">Slide 7</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 8">
                            <p class="caption">Slide 8</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 9">
                            <p class="caption">Slide 9</p>
                            </div>
                            <div class="slide">
                            <img src="https://fakeimg.pl/300/" alt="Slide 10">
                            <p class="caption">Slide 10</p>
                            </div>
                        </div>
                        <button class="prev-btn" onclick="prevSlide()">❮</button>
                        <button class="next-btn" onclick="nextSlide()">❯</button>
                    </div>
                </div>
            </div>
            <div class="container-orther">
            <h1>CÁC CÔNG THỨC KHÁC</h1>
            <div class="container-box">
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            <div class="box-product">
                <img src="https://fakeimg.pl/300/" alt="">
                <p>productname</p>
            </div>
            </div>
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
    <script src="code.js"></script>
</body>
</html>
