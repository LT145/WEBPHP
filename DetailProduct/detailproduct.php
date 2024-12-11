<?php
session_start();
include '../asset/connect.php'; // Đảm bảo kết nối bằng PDO

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
$nguyenlieu = $product['nguyenlieu'];
$nguyenlieu_array = explode(".", $nguyenlieu);
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
                <h1 class="text-4xl font-bold mb-4 text-gray-800 w-full text-center"><?= htmlspecialchars($product['name']) ?></h1> 

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
    </main>

    <?php
    include '../component/footer.php';
    ?>
</body>

</html>