<?php
session_start();
include '../asset/connect.php';
include('../asset/uploadImage.php');

// Lấy id_contribute từ URL
$id_contribute = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kiểm tra nếu id_contribute hợp lệ
if (!$id_contribute) {
    echo "ID bài viết không hợp lệ!";
    exit;
}

// Truy vấn để lấy thông tin bài viết từ bảng contribute
$query = "SELECT * FROM contribute WHERE id_contribute = :id_contribute";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_contribute', $id_contribute, PDO::PARAM_INT);
$stmt->execute();
$contribute = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu bài viết tồn tại
if (!$contribute) {
    echo "Không tìm thấy bài viết!";
    exit;
}

// Truy vấn để lấy tất cả danh mục từ bảng categories
$query = "SELECT * FROM category";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $img = $_FILES['img']; // Lấy file ảnh từ form
    $nguyenlieu = $_POST['nguyenlieu'];
    $cachlam = $_POST['cachlam'];
    $mota = $_POST['mota'];
    $id_category = $_POST['id_category'];

    // Xử lý ảnh nếu có ảnh mới được upload
    if ($img['error'] == 0) {
        $imagePath = $img['tmp_name'];
        $imageUrl = uploadImageToImgBB($imagePath);
    } else {
        // Nếu không có ảnh mới, giữ nguyên URL ảnh cũ
        $imageUrl = $contribute['img']; 
    }

    // Kiểm tra nếu upload/lấy URL ảnh thành công
    if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        // Truy vấn để cập nhật bài viết trong cơ sở dữ liệu
        $query = "UPDATE contribute SET 
                    name = :name, 
                    img = :img, 
                    nguyenlieu = :nguyenlieu, 
                    cachlam = :cachlam, 
                    mota = :mota, 
                    id_category = :id_category 
                  WHERE id_contribute = :id_contribute";
        $stmt = $conn->prepare($query);

        // Liên kết tham số với giá trị
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':img', $imageUrl); // Lưu URL ảnh
        $stmt->bindParam(':nguyenlieu', $nguyenlieu);
        $stmt->bindParam(':cachlam', $cachlam);
        $stmt->bindParam(':mota', $mota);
        $stmt->bindParam(':id_category', $id_category);
        $stmt->bindParam(':id_contribute', $id_contribute, PDO::PARAM_INT);

        // Thực thi truy vấn và kiểm tra kết quả
        if ($stmt->execute()) {
            // Nếu thành công, chuyển hướng hoặc thông báo thành công
            echo "<script>alert('Cập nhật bài viết thành công!'); window.location = 'view_contribute.php?id=$id_contribute';</script>"; 
        } else {
            // Nếu thất bại, thông báo lỗi
            echo "<script>alert('Có lỗi xảy ra khi cập nhật bài viết!');</script>";
        }
    } else {
        // Nếu không thể upload ảnh, thông báo lỗi
        echo "<script>alert('Đã xảy ra lỗi khi upload ảnh. Lỗi: " . $imageUrl . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>   
</head>
<body>

    <div class="container mx-auto p-4">
        <div class="mb-4">
            <a href="manage_contribute.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay về
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-4">Sửa bài viết</h1>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên bài viết:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($contribute['name']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="img" class="block text-gray-700 font-bold mb-2">Chọn ảnh:</label>
                <input type="file" id="img" name="img" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="nguyenlieu" class="block text-gray-700 font-bold mb-2">Nguyên liệu:</label>
                <textarea id="nguyenlieu" name="nguyenlieu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= htmlspecialchars($contribute['nguyenlieu']) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="cachlam" class="block text-gray-700 font-bold mb-2">Cách làm:</label>
                <textarea id="cachlam" name="cachlam" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= htmlspecialchars($contribute['cachlam']) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="mota" class="block text-gray-700 font-bold mb-2">Mô tả:</label>
                <textarea id="mota" name="mota" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= htmlspecialchars($contribute['mota']) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="id_category" class="block text-gray-700 font-bold mb-2">Danh mục:</label>
                <select id="id_category" name="id_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $contribute['id_category']) echo 'selected'; ?>> 
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cập nhật bài viết</button> 
        </form>
    </div>
</body>
</html>