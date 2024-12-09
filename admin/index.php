<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Admin</title>
  <link href="dist/output.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>   
</head>
<body>

  <!-- Tăng kích thước padding của container -->
  <div class="container mx-auto ">
    <h1 class="text-3xl font-bold mt-10 mb-4 text-center">Trang Quản Trị</h1>

    <!-- Hàng 1 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 mb-6">
      <!-- Quản lý user -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Quản lý User</h2>
        <a href="users.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Xem danh sách User
        </a>
      </div>

      <!-- Quản lý admin -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Quản lý Admin</h2>
        <a href="admin.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Xem danh sách Admin
        </a>
      </div>

      <!-- Tạo Tài Khoản Admin -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Tạo Tài Khoản Admin</h2>
        <a href="create_admin.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Tạo tài khoản
        </a>
      </div>

      <!-- Quay về Trang Chủ -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Quay về Trang Chủ</h2>
        <a href="../index.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Về trang chủ
        </a>
      </div>
    </div>

    <!-- Hàng 2 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
      <!-- Quản lý bài viết -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Quản lý Bài Viết</h2>
        <a href="posts.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Xem danh sách bài viết
        </a>
      </div>

      <!-- Bài Viết được đóng góp -->
      <div class="bg-white shadow-md rounded-lg p-4 text-center">
        <h2 class="text-xl font-semibold mb-2">Bài Viết Được Đóng Góp</h2>
        <a href="contributed_posts.php" class="bg-black hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg text-lg">
          Xem bài viết đóng góp
        </a>
      </div>
    </div>

  </div>

</body>
</html>
