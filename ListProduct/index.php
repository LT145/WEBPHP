<?php
session_start();
include '../asset/connect.php'; // Đảm bảo kết nối bằng PDO

// Lấy giá trị id_category từ URL
$id_category = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;

// Truy vấn sản phẩm thuộc danh mục
try {
    $sql = "SELECT * FROM product WHERE id_category = :id_category";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_category', $id_category, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    $products = []; // Nếu có lỗi, để danh sách sản phẩm rỗng
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include('../component/header.php'); ?>

    <main>
        <div class="container mx-auto max-w-4xl">
            <h1 class="text-4xl text-center font-bold my-6">Sản Phẩm Của Danh Mục</h1>
            <div class="grid grid-cols-1 gap-x-3 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="group relative block rounded-md border border-gray-300 shadow-sm hover:shadow-lg transition">
                            <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="aspect-square w-full rounded-t-md object-cover group-hover:opacity-75">
                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></h2>
                                <p class="mt-2 text-sm text-gray-500 line-clamp-3"><?= htmlspecialchars($product['mota']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="col-span-full text-center text-gray-500">Không có sản phẩm trong danh mục này!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="mt-6">
        <div class="footer-top bg-black text-gray-300 pt-6 pb-2">
            <div class="container mx-auto max-w-5xl flex gap-10">
                <div class="footer-brand">
                    <a href="#" class="logo text-white text-2xl font-bold no-underline">
                        <h2>LK BARTENDER</h2>
                    </a>
                    <p class="footer-text text-sm leading-7">
                        Khơi nguồn sáng tạo, nâng tầm nghệ thuật pha chế!
                    </p>
                </div>
                <div class="footer-contact">
                    <h4 class="contact-title text-white font-medium relative mb-6">Liên Hệ Ngay
                        <div class="absolute bottom-[-10px] left-0 w-24 h-1 bg-white"></div>
                    </h4>
                    <ul>
                        <li class="contact-item flex items-center gap-3 mb-2">
                            <ion-icon name="call-outline"></ion-icon>
                            <a href="tel:+84332345957" class="contact-link text-sm no-underline text-gray-300 hover:text-white">+84 332345957</a>
                        </li>
                        <li class="contact-item flex items-center gap-3 mb-2">
                            <ion-icon name="mail-outline"></ion-icon>
                            <a href="mailto:dh52111258@student.stu.edu.vn" class="contact-link text-sm no-underline text-gray-300 hover:text-white">dh52111258@student.stu.edu.vn</a>
                        </li>
                        <li class="contact-item flex items-center gap-3">
                            <ion-icon name="location-outline"></ion-icon>
                            <address class="text-sm text-gray-300">20 Sư Vạn Hạnh, Phường 9, Quận 5, TPHCM</address>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>

<?php
// Đóng kết nối
$conn = null;
?>
