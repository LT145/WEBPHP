<?php
include '../asset/connect.php';

// Lọc theo danh mục và sắp xếp theo ID
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'; // Mặc định sắp xếp theo ID tăng dần
$search_query = isset($_GET['search']) ? $_GET['search'] : ''; // Tìm kiếm sản phẩm

// Truy vấn lấy dữ liệu từ bảng `product`, liên kết với bảng `category` để lấy tên danh mục
try {
    $sql = "SELECT p.id AS id_product, p.name, ca.name AS category_name
            FROM product p
            JOIN category ca ON p.id_category = ca.id";

    // Nếu có lọc theo danh mục
    if ($category_filter > 0) {
        $sql .= " WHERE p.id_category = :category_filter";
    }

    // Nếu có tìm kiếm
    if ($search_query != '') {
        $sql .= $category_filter > 0 ? " AND p.name LIKE :search_query" : " WHERE p.name LIKE :search_query";
    }

    // Thêm điều kiện sắp xếp theo ID
    $sql .= " ORDER BY p.id " . $sort_order;

    $stmt = $conn->prepare($sql);

    // Nếu có lọc theo danh mục, gắn tham số vào câu lệnh SQL
    if ($category_filter > 0) {
        $stmt->bindParam(':category_filter', $category_filter, PDO::PARAM_INT);
    }

    // Nếu có tìm kiếm, gắn tham số tìm kiếm vào câu lệnh SQL
    if ($search_query != '') {
        $stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    $products = [];
}

// Lấy danh sách danh mục để hiển thị trong dropdown
try {
    $category_sql = "SELECT id, name FROM category";
    $category_stmt = $conn->prepare($category_sql);
    $category_stmt->execute();
    $categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn danh mục: " . $e->getMessage();
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <script>
function updateUrl() {
    var category = document.getElementById('category').value;
    var sortOrder = document.getElementById('sort_order').value;
    var search = document.getElementById('search').value;
    var url = new URL(window.location.href);

    // Cập nhật tham số trên URL
    url.searchParams.set('category', category);
    url.searchParams.set('sort_order', sortOrder);
    url.searchParams.set('search', search);

    // Lưu lại URL mà không reload trang
    history.pushState(null, '', url.toString());

    // Gửi một yêu cầu GET đến URL mới mà không tải lại trang
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.body.innerHTML = html; // Cập nhật lại nội dung trang
        });
}

    </script>
</head>

<body class="bg-gray-50">

    <div class="container mx-auto p-6">
        <!-- Nút Quay lại và Nút Thêm Bài Viết (Cùng hàng, căn trái và phải) -->
        <div class="mb-6 flex justify-between">
            <!-- Nút Quay lại -->
            <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>

            <!-- Nút Thêm Bài Viết -->
            <a href="posts.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Thêm Bài Viết
            </a>
        </div>

        <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Danh sách Sản Phẩm</h1>

        <!-- Lọc theo danh mục, thanh tìm kiếm và sắp xếp (Dùng onchange) -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <label for="category" class="text-gray-600 font-semibold mr-2">Lọc theo danh mục:</label>
                <select id="category" class="px-4 py-2 border rounded" onchange="updateUrl()">
                    <option value="0">Tất cả danh mục</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['id'] ?>" <?= ($category['id'] == $category_filter) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Thanh tìm kiếm -->
<!-- Thanh tìm kiếm với nút Tìm kiếm nằm kế nhau -->
<div class="flex items-center w-1/3 mx-4">
    <!-- Thanh tìm kiếm -->
    <input type="text" id="search" class="px-4 py-2 border rounded w-full" placeholder="Tìm sản phẩm..."
           value="<?= htmlspecialchars($search_query) ?>">

    <!-- Nút Tìm kiếm -->
    <button onclick="updateUrl()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
        Tìm 
    </button>
</div>



            <div>
                <label for="sort_order" class="text-gray-600 font-semibold mr-2">Sắp xếp theo ID:</label>
                <select id="sort_order" class="px-4 py-2 border rounded" onchange="updateUrl()">
                    <option value="ASC" <?= ($sort_order == 'ASC') ? 'selected' : '' ?>>Tăng dần</option>
                    <option value="DESC" <?= ($sort_order == 'DESC') ? 'selected' : '' ?>>Giảm dần</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Tên Sản Phẩm</th>
                        <th class="px-4 py-2">Danh Mục</th>
                        <th class="px-4 py-2">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($product['id_product']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td class="border px-4 py-2">
                                    <a href="view_product.php?id=<?php echo htmlspecialchars($product['id_product']); ?>"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="border px-4 py-2 text-center text-red-500">Không có sản phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
