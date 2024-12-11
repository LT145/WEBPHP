<?php
session_start();
include '../asset/connect.php';

// Lấy giá trị id_category từ URL
$id_category = isset($_GET['id_category']) ? (int)$_GET['id_category'] : 0;

try {
    if (isset($_SESSION['user_id'])) {
        // Xử lý yêu cầu AJAX
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_product'])) {
            $productId = $_POST['id_product'];
            $userId = $_SESSION['user_id'];

            try {
                // Kiểm tra xem sản phẩm đã được yêu thích chưa
                $sql = "SELECT 1 FROM yeuthich WHERE id_user = :id_user AND id_product = :id_product";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':id_product', $productId, PDO::PARAM_INT);
                $stmt->execute();
                $isFavorited = $stmt->fetchColumn();

                if ($isFavorited) {
                    // Xóa khỏi danh sách yêu thích
                    $sql = "DELETE FROM yeuthich WHERE id_user = :id_user AND id_product = :id_product";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':id_product', $productId, PDO::PARAM_INT);
                    $stmt->execute();
                    echo 'removed';
                } else {
                    // Thêm vào danh sách yêu thích
                    $sql = "INSERT INTO yeuthich (id_user, id_product) VALUES (:id_user, :id_product)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':id_product', $productId, PDO::PARAM_INT);
                    $stmt->execute();
                    echo 'added';
                }
            } catch (PDOException $e) {
                echo "Lỗi: " . $e->getMessage();
            }
            exit; // Kết thúc xử lý PHP sau khi thêm/xóa yêu thích
        }

        // Lấy danh sách sản phẩm yêu thích của người dùng
        $sql = "SELECT p.id 
                FROM product p
                INNER JOIN yeuthich y ON p.id = y.id_product
                WHERE y.id_user = :id_user";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $favorite_products = $stmt->fetchAll(PDO::FETCH_COLUMN); 
    }

    // Truy vấn sản phẩm thuộc danh mục
    $sql = "SELECT * FROM product WHERE id_category = :id_category";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_category', $id_category, PDO::PARAM_INT);
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
    <title>Danh Mục Sản Phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Thêm animation cho nút yêu thích */
        .heart-animation {
            animation: heartBeat 0.5s ease-in-out; 
        }

        @keyframes heartBeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen"> 

    <?php include('../component/header.php'); ?>

    <main class="flex-grow"> 
        <div class="container mx-auto max-w-4xl">
            <h1 class="text-4xl text-center font-bold my-6">Sản Phẩm Của Danh Mục</h1>
            <div class="grid grid-cols-1 gap-x-3 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="group relative block rounded-md border border-gray-300 shadow-sm hover:shadow-lg transition cursor-pointer"> 
                            <div class="relative">
                                <a href="../DetailProduct/detailproduct.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="aspect-square w-full rounded-t-md object-cover group-hover:opacity-75">
                                </a>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button id="favoriteBtn-<?= htmlspecialchars($product['id']) ?>" data-product-id="<?= htmlspecialchars($product['id']) ?>" class="absolute top-2 right-2 text-2xl <?php if (in_array($product['id'], $favorite_products)) echo 'text-red-500'; else echo 'text-gray-400 hover:text-red-500'; ?>"> 
                                        <i class="fas fa-heart"></i> 
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 flex flex-col"> 
                                <a href="../DetailProduct/detailproduct.php?id=<?= htmlspecialchars($product['id']) ?>">
                                    <h2 class="text-lg font-semibold text-gray-800 self-start"><?= htmlspecialchars($product['name']) ?></h2>
                                    <p class="mt-2 text-sm text-gray-500 line-clamp-3 self-end"><?= htmlspecialchars($product['mota']) ?></p> 
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="col-span-full text-center text-gray-500">Không có sản phẩm trong danh mục này!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include '../component/footer.php'; ?> 

    <script>
        // Lặp qua từng nút yêu thích và thêm event listener
        const favoriteBtns = document.querySelectorAll('[id^="favoriteBtn-"]'); 
        favoriteBtns.forEach(favoriteBtn => {
            favoriteBtn.addEventListener('click', function(event) {
                event.stopPropagation(); 
                const productId = this.dataset.productId;

                fetch('', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_product=${productId}`
                })
                .then(response => response.text())
                .then(result => {
                    if (result === 'added') {
                        this.classList.add('text-red-500', 'heart-animation'); 
                        this.classList.remove('text-gray-400', 'hover:text-red-500');
                    } else if (result === 'removed') {
                        this.classList.remove('text-red-500');
                        this.classList.add('text-gray-400', 'hover:text-red-500', 'heart-animation'); 
                    } else {
                        console.error('Lỗi:', result);
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
            });
        });
    </script>
</body>
</html>
