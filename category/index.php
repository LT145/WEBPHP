<?php
session_start();
include '../asset/connect.php';

try {
    // Lấy danh sách các danh mục
    $sql = "SELECT * FROM category";
    $stmt = $conn->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    $categories = []; // Nếu có lỗi, để danh sách danh mục rỗng
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
</head>

<body>
<?php
include('../component/header.php'); // Điều chỉnh đường dẫn đến header.php
?>


    <main>
<div class="container mx-auto max-w-4xl">
    <h1 class="text-4xl text-center font-bold my-6">Danh Sách Các Công Thức Pha Chế</h1>
    <div class="grid grid-cols-1 gap-x-3 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <?php if (!empty($categories)) : ?>
            <?php foreach ($categories as $category) : ?>
                <a href="/ListProduct/index.php?id_category=<?= htmlspecialchars($category['id']) ?>" class="group relative block rounded-md border border-gray-300 shadow-sm hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($category['img']) ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="aspect-square w-full rounded-t-md object-cover group-hover:opacity-75">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($category['name']) ?></h2>
                        <p class="mt-2 text-sm text-gray-500 line-clamp-3"><?= htmlspecialchars($category['mota']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="col-span-full text-center text-gray-500">Không có dữ liệu!</p>
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
                <div class="footer-form">
                    <p class="form-text text-sm mb-4">
                        Nếu Có Vấn Đề Gì Cần Hỗ Trợ Hãy Điền Thông Tin <br> Vào Đây:
                    </p>
                    <form>
                        <input type="email" name="email" id="email" class="input-field bg-white text-sm p-3 rounded-full mb-2 w-full" placeholder="Nhập Email Của bạn" required>
                        <textarea name="message" id="message" class="input-field bg-white text-sm p-3 rounded-full mb-2 w-full" placeholder="Vấn Đề Của Bạn" required></textarea>
                        <button type="submit" class="btn btn-secondary bg-gray-500 text-white p-3 rounded-full w-full cursor-pointer">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>