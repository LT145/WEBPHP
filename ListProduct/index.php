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
                        <a href="../DetailProduct/detailproduct.php?id=<?= htmlspecialchars($product['id']) ?>" class="group relative block rounded-md border border-gray-300 shadow-sm hover:shadow-lg transition cursor-pointer">
                            <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="aspect-square w-full rounded-t-md object-cover group-hover:opacity-75">
                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></h2>
                                <p class="mt-2 text-sm text-gray-500 line-clamp-3"><?= htmlspecialchars($product['mota']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="col-span-full text-center text-gray-500">Không có sản phẩm trong danh mục này!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php
    include '../component/footer.php';
    ?>
</body>

</html>

<?php
// Đóng kết nối
$conn = null;
?>