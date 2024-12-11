<?php
session_start();
include '../asset/connect.php';

// Lấy id sản phẩm từ URL
$id_product = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin sản phẩm
try {
    $sql = "SELECT * FROM product WHERE id = :id_product";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Không tìm thấy sản phẩm!";
        exit;
    }
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    exit;
}

// Lấy category_id của sản phẩm hiện tại
$category_id = $product['id_category'];

// Truy vấn danh sách sản phẩm cùng loại (không giới hạn số lượng)
try {
    $sql = "SELECT id, name, img FROM product WHERE id_category = :id_category AND id != :id_product";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_category', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    exit;
}

$nguyenlieu = $product['nguyenlieu'];
$nguyenlieu_array = explode(".", $nguyenlieu);

// Kiểm tra xem sản phẩm đã được yêu thích chưa
$isFavorited = false;
if (isset($_SESSION['user_id'])) {
    try {
        $userId = $_SESSION['user_id'];
        $sql = "SELECT 1 FROM yeuthich WHERE id_user = :id_user AND id_product = :id_product";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->execute();
        $isFavorited = $stmt->fetchColumn();
    } catch (PDOException $e) {
        // Xử lý lỗi nếu cần
    }
}

// Xử lý yêu cầu AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && isset($_POST['id_product'])) {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include('../component/header.php'); ?>

    <main class="container mx-auto max-w-7xl py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden relative">
            <div class="p-6">
                <h1 class="text-4xl font-bold mb-4 text-gray-800 w-full text-center flex items-center justify-center"> 
                    <?= htmlspecialchars($product['name']) ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button id="favoriteBtn" data-product-id="<?= htmlspecialchars($product['id']) ?>" class="ml-4 text-2xl <?php if ($isFavorited) echo 'text-red-500'; else echo 'text-gray-400 hover:text-red-500'; ?>">
                            <i class="fas fa-heart"></i> 
                        </button>
                    <?php endif; ?>
                </h1>

                <div class="mo-ta-box mb-6 px-2">
                    <p class="text-gray-600 text-justify"><?= htmlspecialchars($product['mota']) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 ">
                <div class="overflow-hidden">
                    <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover md:h-auto rounded-br-lg drop-shadow-2xl">
                    <div class="absolute bottom-0 right-0 w-24 h-24 bg-white rounded-tl-full transform rotate-45 -translate-x-1/2 translate-y-1/2"></div>
                </div>
                <div class="p-6">
                    <div class="nguyen-lieu-box mb-6">
                        <h2 class="text-xl font-semibold mb-2 text-gray-700 text-justify">Nguyên Liệu:</h2>
                        <ul class="list-disc pl-5 text-gray-600 text-justify">
                            <?php
                            $nguyenlieu_count = count($nguyenlieu_array);
                            for ($i = 0; $i < $nguyenlieu_count; $i++):
                                $nguyenlieu_item = trim($nguyenlieu_array[$i]);
                                if (!empty($nguyenlieu_item)):
                                    // Tách số lượng và tên nguyên liệu
                                    $parts = explode(" ", $nguyenlieu_item);
                                    $quantity = $parts[0];
                                    $name = implode(" ", array_slice($parts, 1));
                            ?>
                                    <li class="mb-2">
                                        <span class="font-medium"><?= htmlspecialchars($quantity) ?></span> <span><?= htmlspecialchars($name) ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    </div>

                    <div class="cach-lam-box mb-2">
                        <h2 class="text-xl font-semibold text-gray-700 text-justify">Cách Làm:</h2>
                        <?php
                        // Tách các bước cách làm bằng ký tự xuống dòng
                        $cachlam_steps = explode("\n", $product['cachlam']);

                        // Hiển thị từng bước với số thứ tự và in đậm
                        foreach ($cachlam_steps as $index => $step):
                            $step = trim($step);
                            // Kiểm tra xem bước có chứa chữ "bước" hay không
                            if (preg_match('/bước\s*\d+:\s*/i', $step)) {
                                // Nếu có, thay thế bằng thẻ span in đậm
                                $step = preg_replace('/bước\s*\d+:\s*/i', '<span class="font-bold">Bước ' . ($index + 1) . ':</span> ', $step);
                            } else {
                                // Nếu không, thêm thẻ span in đậm vào đầu bước
                                $step = '<span class="font-bold">Bước ' . ($index + 1) . ':</span> ' . $step;
                            }
                            if (!empty($step)):
                        ?>
                                <p class="text-gray-600 whitespace-pre-line text-justify">
                                    <?= $step ?>
                                </p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="related-products mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Sản phẩm cùng loại</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($related_products as $related_product): ?>
                        <div class="swiper-slide">
                            <a href="?id=<?= htmlspecialchars($related_product['id']) ?>">
                                <img src="<?= htmlspecialchars($related_product['img']) ?>" alt="<?= htmlspecialchars($related_product['name']) ?>" class="w-[300px] h-[200px] object-cover mx-auto rounded-lg">
                                <p class="text-center text-gray-700 mt-2 mb-8"><?= htmlspecialchars($related_product['name']) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });

        const favoriteBtn = document.getElementById('favoriteBtn');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const isFavorited = this.classList.contains('text-red-500');
                fetch('', { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_product=${productId}`
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'added') {
                            this.classList.add('text-red-500');
                            this.classList.remove('text-gray-400', 'hover:text-red-500');
                        } else if (result === 'removed') {
                            this.classList.remove('text-red-500');
                            this.classList.add('text-gray-400', 'hover:text-red-500');
                        } else {
                            console.error('Lỗi:', result);
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });
        }
    </script>

    <?php include '../component/footer.php'; ?>
</body>
</html>