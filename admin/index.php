<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .container {
      padding: 3rem;
    }
    .admin-box {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .admin-box:hover {
      transform: translateY(-5px);
      box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
    }
    .admin-box i {
      color: #2D3748; /* Màu icon xám tối */
    }
    .admin-box:hover i {
      color: #4A5568; /* Đổi màu icon khi hover sang màu xám đậm hơn */
    }
    .button {
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .button:hover {
      background-color: #4A5568; /* Màu nút trầm */
      transform: scale(1.05);
    }
  </style>
</head>
<body class="bg-gray-50">

  <div class="container mx-auto">
    <h1 class="text-4xl font-bold mt-10 mb-6 text-center text-gray-800">Trang Quản Trị</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center"> 
          <i class="fa-solid fa-users mr-2 text-gray-500"></i> Quản lý User
        </h2>
        <a href="usermanager.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Xem danh sách User
        </a>
      </div>

      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
          <i class="fa-solid fa-user-shield mr-2 text-gray-500"></i> Quản lý Admin
        </h2>
        <a href="adminmanager.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Xem danh sách Admin
        </a>
      </div>

      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
          <i class="fa-solid fa-user-plus mr-2 text-gray-500"></i> Tạo Tài Khoản Admin
        </h2>
        <a href="create_admin.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Tạo tài khoản
        </a>
      </div>

      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
          <i class="fa-solid fa-house mr-2 text-gray-500"></i> Quay về Trang Chủ
        </h2>
        <a href="../index.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Về trang chủ
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
          <i class="fa-solid fa-newspaper mr-2 text-gray-500"></i> Quản lý Bài Viết
        </h2>
        <a href="productmanager.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Xem danh sách bài viết
        </a>
      </div>

      <div class="admin-box bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl">
        <h2 class="text-2xl font-semibold mb-4 flex items-center justify-center">
          <i class="fa-solid fa-hands-helping mr-2 text-gray-500"></i> Bài Viết Được Đóng Góp
        </h2>
        <a href="contributemanager.php" class="button bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg text-lg">
          Xem bài viết đóng góp
        </a>
      </div>
    </div>
  </div>
</body>
</html>
