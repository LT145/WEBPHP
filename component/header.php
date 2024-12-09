<header class="bg-black text-white py-4">
    <div class="container mx-auto px-5 flex justify-between items-center">
        <!-- Logo -->
        <div class="logo">
            <a href="../index.php">
                <img src="../img/logo/logo.png" alt="" class="w-20 invert">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-row items-center space-x-5">
            <a href="../index.php" class="hover:text-gray-300">Trang Chủ</a>
            <a href="/category/index.php" class="hover:text-gray-300">Danh Mục</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Nếu người dùng đã đăng nhập -->
                <?php 
                    // Lấy tên người dùng (chỉ lấy phần cuối của full name)
                    $fullName = $_SESSION['first_name'];
                    $role = $_SESSION['role']; // Lấy role của người dùng
                ?>
                <div class="relative group">
                    <!-- Hiển thị tên người dùng -->
                    <a href="profile.php" class="hover:text-gray-300">Xin chào, <?= htmlspecialchars($fullName) ?></a>

                    <!-- Menu con hiển thị khi hover -->
                    <div class="absolute left-0 top-[-2px] hidden group-hover:block bg-white shadow-lg p-3 rounded-md mt-2 w-40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out">
    <a href="../user/profile.php" class="block text-gray-700 mb-2 hover:text-blue-500">
        <i class="fas fa-user mr-2"></i> Thông tin
    </a>
    <a href="../user/logout.php" class="block text-gray-700 mb-2 hover:text-red-500">
        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
    </a>
    <?php if ($role == 'admin'): ?>
        <!-- Nếu role là admin, hiển thị thêm liên kết quản lý -->
        <a href="../admin/index.php" class="block text-gray-700 mt-2 hover:text-green-500">
            <i class="fas fa-cogs mr-2"></i> Quản Lý
        </a>
    <?php endif; ?>
</div>

                </div>
            <?php else: ?>
                <!-- Nếu chưa đăng nhập -->
                <a href="../user/login.php" class="hover:text-gray-300">Đăng Nhập</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
