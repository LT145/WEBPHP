<?php
include '../asset/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $userId = $_POST['userId'];
  $newStatus = $_POST['newStatus'];

  try {
    $sql = "UPDATE user SET status = :status WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $newStatus);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

  } catch(PDOException $e) {
  }
}

try {
  // Chuẩn bị truy vấn SQL ban đầu
  $sql = "SELECT * FROM user WHERE role = 'user'";

  // Kiểm tra xem có tham số tìm kiếm hay không
  if (isset($_GET['search']) && isset($_GET['search_by'])) {
    $search = $_GET['search'];
    $searchBy = $_GET['search_by'];

    // Thêm điều kiện WHERE dựa trên lựa chọn tìm kiếm
    switch ($searchBy) {
      case 'id':
        $sql .= " AND user_id = :search"; 
        break;
      case 'username':
        $sql .= " AND username LIKE :search";
        break;
      case 'fullname':
        $sql .= " AND fullname LIKE :search";
        break;
      case 'email':
        $sql .= " AND email LIKE :search";
        break;
    }
  }

  // Thêm sắp xếp theo trạng thái nếu có
  if (isset($_GET['sort_by'])) {
    $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'active'; // Mặc định sắp xếp theo "Hoạt động"
    // Sắp xếp theo thứ tự "Đã khóa" trước, "Hoạt động" sau
    if ($sortBy == 'locked') {
      $sql .= " ORDER BY FIELD(status, 'locked', 'active')";
    } else {
      $sql .= " ORDER BY FIELD(status, 'active', 'locked')";
    }
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
  $users = $stmt->fetchAll();
} catch(PDOException $e) {
  echo "Lỗi truy vấn: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý người dùng</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-4">
    <button onclick="window.location.href='index.php'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
      Quay lại
    </button>
    <h1 class="text-2xl font-bold mb-4">Danh sách người dùng</h1>

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
            <option value="username">Tên đăng nhập</option>
            <option value="fullname">Họ và tên</option>
            <option value="email">Email</option>
          </select>
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2">Tìm</button>
        </div>
      </form>

      <form method="get" autocomplete="off" onchange="this.submit()"> 
        <div class="flex items-center"> 
          <label for="sort_by" class="mr-2">Sắp xếp theo trạng thái:</label>
          <select name="sort_by" id="sort_by" class="border border-gray-400 rounded px-2 py-1">
            <option value="locked" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'locked') echo 'selected'; ?>>Đã khóa</option>
            <option value="active" <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'active') echo 'selected'; ?>>Hoạt động</option>
          </select>
        </div>
      </form>
    </div>


    <div class="overflow-x-auto mt-4"> 
      <table class="table-auto w-full">
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Tên đăng nhập</th>
            <th class="px-4 py-2">Họ và tên</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Ngày sinh</th>
            <th class="px-4 py-2">Giới tính</th>
            <th class="px-4 py-2">Trạng thái</th>
            <th class="px-4 py-2">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <form method="post" autocomplete="off"> 
                <td class="border px-4 py-2"><?php echo $user['user_id']; ?></td>
                <td class="border px-4 py-2"><?php echo $user['username']; ?></td>
                <td class="border px-4 py-2"><?php echo $user['fullname']; ?></td>
                <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                <td class="border px-4 py-2"><?php echo $user['dob']; ?></td>
                <td class="border px-4 py-2"><?php echo $user['gender']; ?></td>
                <td class="border px-4 py-2">
                  <?php if ($user['status'] == 'active'): ?>
                    <span class="text-green-500">Hoạt động</span>
                  <?php elseif ($user['status'] == 'locked'): ?>
                    <span class="text-red-500">Đã khóa</span>
                  <?php endif; ?>
                </td>
                <td class="border px-4 py-2">
                  <input type="hidden" name="userId" value="<?php echo $user['user_id']; ?>">
                  <?php if ($user['status'] == 'active'): ?>
                    <button type="submit" name="newStatus" value="locked" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                      Khóa
                    </button>
                  <?php elseif ($user['status'] == 'locked'): ?>
                    <button type="submit" name="newStatus" value="active" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                      Mở Khóa
                    </button>
                  <?php endif; ?>
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