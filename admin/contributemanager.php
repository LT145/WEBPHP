<?php
include '../asset/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contributeId = $_POST['contributeId'];
    $newStatus = $_POST['newStatus'];

    try {
        $sql = "UPDATE contribute SET status = :status WHERE id_contribute = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $contributeId);
        $stmt->execute();
    } catch (PDOException $e) {
    }
}

try {
    // Chuẩn bị truy vấn SQL ban đầu
    $sql = "SELECT c.*, u.fullname, ca.name AS category_name
            FROM contribute c
            JOIN user u ON c.id_user = u.user_id
            JOIN category ca ON c.id_category = ca.id";

    // Kiểm tra xem có tham số tìm kiếm hay không
    if (isset($_GET['search']) && isset($_GET['search_by'])) {
        $search = $_GET['search'];
        $searchBy = $_GET['search_by'];

        // Thêm điều kiện WHERE dựa trên lựa chọn tìm kiếm
        switch ($searchBy) {
            case 'id':
                $sql .= " WHERE c.id_contribute = :search";
                break;
            case 'name':
                $sql .= " WHERE c.name LIKE :search";
                break;
            case 'fullname':
                $sql .= " WHERE u.fullname LIKE :search";
                break;
            case 'category':
                $sql .= " WHERE ca.name LIKE :search";
                break;
        }
    }

    // Thêm sắp xếp theo trạng thái nếu có
    if (isset($_GET['sort_by'])) {
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'pending';
        $sql .= " ORDER BY FIELD(c.status, '$sortBy', 'approved', 'rejected')";
    }

    $stmt = $conn->prepare($sql);

    // Gán giá trị cho tham số tìm kiếm nếu có
    if (isset($_GET['search']) && isset($_GET['search_by'])) {
        if ($searchBy === 'id') {
            $stmt->bindParam(':search', $search);
        } else {
            $search = '%' . $search . '%';
            $stmt->bindParam(':search', $search);
        }
    }

    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $contributes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài viết đóng góp</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <button onclick="window.location.href='index.php'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            Quay lại
        </button>
        <h1 class="text-2xl font-bold mb-4">Danh sách bài viết đóng góp</h1>

        <div class="flex flex-col md:flex-row justify-between items-center">
            <form method="get" autocomplete="off" class="mr-4 mb-2 md:mb-0">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                    Hiển thị toàn bộ
                </button>
            </form>

            <form method="get" autocomplete="off" class="mr-4 mb-2 md:mb-0">
                <div class="flex flex-col md:flex-row">
                    <input type="text" name="search" class="border border-gray-400 rounded px-2 py-1 mb-2 md:mb-0" placeholder="Tìm kiếm...">
                    <select name="search_by" class="border border-gray-400 rounded px-2 py-1 ml-2 mb-2 md:mb-0">
                        <option value="id">ID</option>
                        <option value="name">Tên bài viết</option>
                        <option value="fullname">Người đóng góp</option>
                        <option value="category">Danh mục</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2">Tìm</button>
                </div>
            </form>

            <form method="get" autocomplete="off" onchange="this.submit()">
                <div class="flex items-center">
                    <label for="sort_by" class="mr-2">Sắp xếp theo trạng thái:</label>
                    <select name="sort_by" id="sort_by" class="border border-gray-400 rounded px-2 py-1">
                        <option value="pending" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'pending') echo 'selected'; ?>>Chờ duyệt</option>
                        <option value="approved" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'approved') echo 'selected'; ?>>Đã duyệt</option>
                        <option value="rejected" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'rejected') echo 'selected'; ?>>Từ chối</option>
                    </select>
                </div>
            </form>
        </div>


        <div class="overflow-x-auto mt-4">
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Tên bài viết</th>
                        <th class="px-4 py-2">Người đóng góp</th>
                        <th class="px-4 py-2">Danh mục</th>
                        <th class="px-4 py-2">Trạng thái</th>
                        <th class="px-4 py-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contributes as $contribute): ?>
                        <tr>
                            <form method="post" autocomplete="off">
                                <td class="border px-4 py-2"><?php echo $contribute['id_contribute']; ?></td>
                                <td class="border px-4 py-2"><?php echo $contribute['name']; ?></td>
                                <td class="border px-4 py-2"><?php echo $contribute['fullname']; ?></td>
                                <td class="border px-4 py-2"><?php echo $contribute['category_name']; ?></td>
                                <td class="border px-4 py-2">
                                    <?php if ($contribute['status'] == 'pending'): ?>
                                        <span class="text-yellow-500">Chờ duyệt</span>
                                    <?php elseif ($contribute['status'] == 'approved'): ?>
                                        <span class="text-green-500">Đã duyệt</span>
                                    <?php elseif ($contribute['status'] == 'rejected'): ?>
                                        <span class="text-red-500">Từ chối</span>
                                    <?php endif; ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="view_contribute.php?id=<?php echo $contribute['id_contribute']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>