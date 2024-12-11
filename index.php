<?php
session_start(); // Đảm bảo gọi session_start() ở đầu file
include './asset/connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Nấu Ăn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
</head>

<body>
    <?php
    include('./component/header.php'); // Điều chỉnh đường dẫn đến header.php
    ?>

    <main class="container mx-auto px-5 py-10">
        <div class="page-inner text-center">
            <h1 class="text-3xl font-bold mb-5">Công Thức Pha Chế Đồ Uống</h1>
            <p class="text-justify">Chuyên mục công thức pha chề đồ uống của website Dạy Pha Chế Á Âu cung cấp đa dạng cách làm các loại thức uống từ không cồn (nước ép trái cây, sinh tố, cafe, sữa chua…), pha chế đồ uống có cồn (cocktail, mocktail, mojito…) đến cách pha chế trà sữa, làm kem… Đồng thời, chuyên mục thường xuyên cập nhật những công thức đồ uống đang HOT hiện nay. Hi vọng chuyên mục sẽ giúp bạn trau dồi thêm kinh nghiệm pha chế và học được nhiều cách làm đồ uống hấp dẫn, mới lạ.</p>
        </div>

        <div class="container-product mt-10">
            <div class="list-product">
                <span class="text-xl font-semibold">CÁC LOẠI ĐỒ UỐNG CÓ CỒN</span>
                <div class="slideshow-container relative w-full overflow-hidden mt-5">
                    <div class="slide-wrapper flex transition-transform duration-500 ease-in-out w-[300%]">
                        <?php
                        // Hiển thị các slide (thay thế bằng dữ liệu từ database)
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="slide w-1/3 flex-shrink-0 px-5 py-3 text-center">';
                                echo '<img src="' . $row["hinhanh"] . '" alt="' . $row["tencongthuc"] . '" class="w-full rounded-md">';
                                echo '<p class="caption text-sm text-gray-500 mt-2">' . $row["tencongthuc"] . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo "Không có kết quả";
                        }
                        ?>
                    </div>
                    <button class="prev-btn absolute top-1/2 left-5 transform -translate-y-1/2 bg-black/50 text-white border-none px-5 py-3 cursor-pointer rounded-md hover:bg-black/80" onclick="prevSlide()">❮</button>
                    <button class="next-btn absolute top-1/2 right-5 transform -translate-y-1/2 bg-black/50 text-white border-none px-5 py-3 cursor-pointer rounded-md hover:bg-black/80" onclick="nextSlide()">❯</button>
                </div>
            </div>
        </div>

        <div class="container-orther mt-10">
            <h1 class="text-xl font-semibold text-center">CÁC CÔNG THỨC KHÁC</h1>
            <div class="container-box flex flex-wrap gap-5 justify-between mt-5">
                <?php
                // Hiển thị các công thức khác (thay thế bằng dữ liệu từ database)
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="box-product w-1/3">';
                        echo '<img src="' . $row["hinhanh"] . '" alt="' . $row["tencongthuc"] . '" class="w-full rounded-md">';
                        echo '<p class="text-center mt-2">' . $row["tencongthuc"] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "Không có kết quả";
                }
                ?>
            </div>
        </div>
    </main>
    <?php
    include './component/footer.php';
    ?>
    <script src="code.js"></script>
</body>

</html>