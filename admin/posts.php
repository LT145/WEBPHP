<?php
// Bao gồm file kết nối và hàm upload ảnh
include('../asset/connect.php');
include('../asset/uploadImage.php');  // Gọi file chứa hàm upload ảnh

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

    // Kiểm tra nếu checkbox "user" được chọn
    $role = isset($_POST['user']) ? 'user' : '';

    // Kiểm tra nếu có tệp ảnh được upload
    if ($img['error'] == 0) {
        // Lấy đường dẫn tệp ảnh
        $imagePath = $img['tmp_name'];

        // Gọi hàm upload ảnh
        $imageUrl = uploadImageToImgBB($imagePath);

        // Kiểm tra nếu upload thành công và trả về URL hợp lệ
        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            // Truy vấn để thêm bài viết vào cơ sở dữ liệu
            $query = "INSERT INTO product (name, img, nguyenlieu, cachlam, mota, id_category, role) 
                      VALUES (:name, :img, :nguyenlieu, :cachlam, :mota, :id_category, :role)";
            $stmt = $conn->prepare($query);

            // Liên kết tham số với giá trị
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':img', $imageUrl); // Lưu URL ảnh
            $stmt->bindParam(':nguyenlieu', $nguyenlieu);
            $stmt->bindParam(':cachlam', $cachlam);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->bindParam(':role', $role);

            // Thực thi truy vấn và kiểm tra kết quả
            if ($stmt->execute()) {
                // Nếu thành công, chuyển hướng hoặc thông báo thành công
                echo "<script>alert('Thêm bài viết thành công!'); window.location = 'index.php';</script>";
            } else {
                // Nếu thất bại, thông báo lỗi
                echo "<script>alert('Có lỗi xảy ra khi thêm bài viết!');</script>";
            }
        } else {
            // Nếu không thể upload ảnh, thông báo lỗi
            echo "<script>alert('Đã xảy ra lỗi khi upload ảnh. Lỗi: " . $imageUrl . "');</script>";
        }
    } else {
        // Nếu có lỗi khi tải ảnh lên
        echo "<script>alert('Lỗi khi tải ảnh lên. Mã lỗi: " . $img['error'] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm bài viết</title>
  <link href="dist/output.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>   
</head>
<body>

  <div class="container mx-auto p-4">
    <!-- Nút quay về -->
    <div class="mb-4">
      <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Quay về
      </a>
    </div>

    <h1 class="text-2xl font-bold mb-4">Thêm bài viết</h1>

    <form method="post" enctype="multipart/form-data">
      <div class="mb-4">
        <label for="name" class="block text-gray-700 font-bold mb-2">Tên bài viết:</label>
        <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>

      <div class="mb-4">
        <label for="img" class="block text-gray-700 font-bold mb-2">Chọn ảnh:</label>
        <input type="file" id="img" name="img" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>

      <div class="mb-4">
        <label for="nguyenlieu" class="block text-gray-700 font-bold mb-2">Nguyên liệu:</label>
        <textarea id="nguyenlieu" name="nguyenlieu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
      </div>

      <div class="mb-4">
        <label for="cachlam" class="block text-gray-700 font-bold mb-2">Cách làm:</label>
        <textarea id="cachlam" name="cachlam" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
      </div>

      <div class="mb-4">
        <label for="mota" class="block text-gray-700 font-bold mb-2">Mô tả:</label>
        <textarea id="mota" name="mota" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
      </div>

      <div class="mb-4">
        <label for="id_category" class="block text-gray-700 font-bold mb-2">Danh mục:</label>
        <select id="id_category" name="id_category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Thêm bài viết</button>
    </form>
  </div>

</body>
</html>
