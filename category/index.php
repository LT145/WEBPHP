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

<body class="flex flex-col min-h-screen"> <!-- Thêm flex và min-h-screen vào thẻ body -->
    <?php
    include('../component/header.php'); // Điều chỉnh đường dẫn đến header.php
    ?>

    <main class="flex-grow"> <!-- Thêm flex-grow vào thẻ main để nội dung chiếm không gian còn lại -->
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

    <?php
    include '../component/footer.php'; // Đảm bảo đường dẫn footer đúng
    ?>
</body>

</html>
