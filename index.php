<?php
session_start();
include './asset/connect.php';

try {
    // Truy vấn các sản phẩm có id_category = 1
    $sql = "SELECT * FROM product WHERE id_category = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Nấu Ăn</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        /* Ẩn loader khi dữ liệu được tải xong */
    </style>
</head>

<body class="flex flex-col min-h-screen">

<?php include('./asset/loadingpage.php'); ?>
    <?php include('./component/header.php'); ?>

    <main class="container mx-auto px-5 py-10 flex-1">
        <div class="page-inner text-center">
            <h1 class="text-3xl font-bold mb-5">Công Thức Pha Chế Đồ Uống</h1>
            <p class="text-justify">Chuyên mục công thức pha chế đồ uống của website Dạy Pha Chế Á Âu cung cấp đa dạng cách làm các loại thức uống từ không cồn (nước ép trái cây, sinh tố, cafe, sữa chua…), pha chế đồ uống có cồn (cocktail, mocktail, mojito…) đến cách pha chế trà sữa, làm kem… Đồng thời, chuyên mục thường xuyên cập nhật những công thức đồ uống đang HOT hiện nay. Hi vọng chuyên mục sẽ giúp bạn trau dồi thêm kinh nghiệm pha chế và học được nhiều cách làm đồ uống hấp dẫn, mới lạ.</p>
        </div>

        <div class="container-product mt-10">
            <div class="list-product">
                <span class="text-xl font-semibold">CÁC LOẠI ĐỒ UỐNG CÓ CỒN</span>
                <div class="swiper mySwiper mt-5">
                    <div class="swiper-wrapper">
                        <?php foreach ($products as $product): ?>
                            <div class="swiper-slide py-3 text-center">
                                <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full rounded-md">
                                <p class="caption text-xl font-medium text-gray-800 mt-2 mb-8"><?= htmlspecialchars($product['name']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </main>

    <?php include('./component/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Tắt loader khi sản phẩm đã được tải xong

        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 5,
            spaceBetween: 30,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                500: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 50,
                },
            },
        });
    </script>

</body>

</html>
