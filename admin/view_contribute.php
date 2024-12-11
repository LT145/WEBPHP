<?php
session_start();
include '../asset/connect.php';

// Lấy id bài viết từ URL
$id_contribute = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Xử lý yêu cầu cập nhật trạng thái
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contributeId']) && isset($_POST['action'])) {
    $contributeId = $_POST['contributeId'];
    $newStatus = $_POST['action'];

    try {
        // Nếu duyệt bài viết, thêm bài viết vào bảng product
        if ($newStatus == 'approved') {
            // Lấy thông tin bài viết từ bảng contribute
            $sql = "SELECT * FROM contribute WHERE id_contribute = :id_contribute";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_contribute', $contributeId, PDO::PARAM_INT);
            $stmt->execute();
            $contributeData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Chèn dữ liệu vào bảng product
            $sql = "INSERT INTO product (name, img, nguyenlieu, cachlam, mota, id_category) 
                    VALUES (:name, :img, :nguyenlieu, :cachlam, :mota, :id_category)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $contributeData['name']);
            $stmt->bindParam(':img', $contributeData['img']);
            $stmt->bindParam(':nguyenlieu', $contributeData['nguyenlieu']);
            $stmt->bindParam(':cachlam', $contributeData['cachlam']);
            $stmt->bindParam(':mota', $contributeData['mota']);
            $stmt->bindParam(':id_category', $contributeData['id_category']);
            $stmt->execute();
        }

        // Cập nhật trạng thái bài viết trong bảng contribute
        $sql = "UPDATE contribute SET status = :status WHERE id_contribute = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $contributeId);
        $stmt->execute();

        // Hiển thị thông báo cập nhật trạng thái thành công và chuyển hướng
        echo "<script>
                alert('Cập nhật trạng thái bài viết thành công!'); 
                window.location = 'contributemanager.php'; 
              </script>";
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi: " . $e->getMessage() . "');</script>";
    }
}

// Truy vấn thông tin bài viết
try {
    $sql = "SELECT c.*, u.fullname, ca.name AS category_name
            FROM contribute c
            JOIN user u ON c.id_user = u.user_id
            JOIN category ca ON c.id_category = ca.id
            WHERE c.id_contribute = :id_contribute";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_contribute', $id_contribute, PDO::PARAM_INT);
    $stmt->execute();
    $contribute = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contribute) {
        echo "Không tìm thấy bài viết!";
        exit;
    }
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    exit;
}

$nguyenlieu = $contribute['nguyenlieu'];
$nguyenlieu_array = explode(".", $nguyenlieu);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài viết đóng góp</title>
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
                    <?= htmlspecialchars($contribute['name']) ?>
                </h1>

                <div class="mo-ta-box mb-6 px-2">
                    <p class="text-gray-600 text-justify"><?= htmlspecialchars($contribute['mota']) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 ">
                <div class="overflow-hidden">
                    <img src="<?= htmlspecialchars($contribute['img']) ?>" alt="<?= htmlspecialchars($contribute['name']) ?>" class="w-full h-full object-cover md:h-auto rounded-br-lg drop-shadow-2xl">
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
                        $cachlam_steps = explode("\n", $contribute['cachlam']);

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

        <div class="mt-8 flex justify-center gap-4">
            <a href="contributemanager.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay về
            </a>

            <?php if ($contribute['status'] == 'pending'): ?>
                <a href="edit_contribute.php?id=<?= $contribute['id_contribute']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Sửa
                </a>
                <form method="post" autocomplete="off">
                    <input type="hidden" name="contributeId" value="<?= $contribute['id_contribute']; ?>">
                    <button type="submit" name="action" value="approved" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Duyệt
                    </button>
                    <button type="submit" name="action" value="rejected" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Từ chối
                    </button>
                </form>
            <?php elseif ($contribute['status'] == 'rejected'): ?>
                <a href="edit_contribute.php?id=<?= $contribute['id_contribute']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Sửa
                </a>
            <?php endif; ?>
        </div>
    </main>

    <?php include '../component/footer.php'; ?>
</body>

</html>