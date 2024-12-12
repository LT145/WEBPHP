<header class="bg-black text-white py-4 relative"> 
  <div class="container mx-auto px-5 flex justify-between items-center">
    <div class="logo">
      <a href="../index.php">
        <img src="../img/logo/logo.png" alt="Logo" class="w-20 invert">
      </a>
    </div>

    <nav class="flex items-center space-x-5">
      <a href="../index.php" class="hover:text-gray-300">Trang Chủ</a>
      <a href="/category/index.php" class="hover:text-gray-300">Danh Mục</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <?php 
          $fullName = $_SESSION['first_name'];
          $role = $_SESSION['role']; 
        ?>
        <div class="relative">
          <div class="hidden lg:block relative group">
            <a href="#" class="hover:text-gray-300">Xin chào, <?= htmlspecialchars($fullName) ?></a>
            <div class="absolute left-0 top-[0px] mt-1 hidden group-hover:block bg-white shadow-lg p-3 rounded-md w-40 transition-all duration-300 ease-in-out">
              <a href="../user/profile.php" class="block text-gray-700 mb-2 hover:text-blue-500">
                <i class="fas fa-user mr-2"></i> Thông tin
              </a>
              <a href="../favorites/favorites.php" class="block text-gray-700 mb-2 hover:text-yellow-500"> 
                <i class="fas fa-heart mr-2"></i> Yêu Thích 
              </a>
              <?php if ($role == 'admin'): ?>
                <a href="../admin/index.php" class="block text-gray-700 mb-2 hover:text-green-500">
                  <i class="fas fa-cogs mr-2"></i> Quản Lý
                </a>
              <?php endif; ?>
              <a href="../user/logout.php" class="block text-gray-700 hover:text-red-500">
                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
              </a>
            </div>
          </div>

          <button id="hamburgerToggle" class="lg:hidden text-2xl focus:outline-none"> 
            <i class="fas fa-bars"></i>
          </button>
        </div>
      <?php else: ?>
        <a href="../user/login.php" class="hover:text-gray-300">Đăng Nhập</a>
      <?php endif; ?>
    </nav>
  </div>

  <?php if (isset($_SESSION['user_id'])): ?>
    <div id="mobileMenu" class="mobile-menu absolute top-full left-0 w-full bg-black text-white py-5 transform -translate-y-full transition-all duration-700 ease-in-out hidden">
      <div class="mobile-menu-content px-5 space-y-3">
        <p class="text-lg font-semibold">Xin chào, <?= htmlspecialchars($fullName) ?></p>
        <a href="../user/profile.php" class="block text-gray-300 py-2 hover:bg-gray-800 hover:text-blue-400">
          <i class="fas fa-user mr-2"></i> Thông tin
        </a>
        <a href="../favorites/favorites.php" class="block text-gray-300 py-2 hover:bg-gray-800 hover:text-yellow-400">
          <i class="fas fa-heart mr-2"></i> Yêu Thích
        </a>
        <?php if ($role == 'admin'): ?>
          <a href="../admin/index.php" class="block text-gray-300 py-2 hover:bg-gray-800 hover:text-green-400">
            <i class="fas fa-cogs mr-2"></i> Quản Lý
          </a>
        <?php endif; ?>
        <a href="../user/logout.php" class="block text-gray-300 py-2 hover:bg-gray-800 hover:text-red-400">
          <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
        </a>
      </div>
    </div>
  <?php endif; ?>
</header>
<script>
  // Toggle mobile menu
const hamburgerToggle = document.getElementById('hamburgerToggle');
const mobileMenu = document.getElementById('mobileMenu');

if (hamburgerToggle) {
  hamburgerToggle.addEventListener('click', function () {
    mobileMenu.classList.toggle('hidden'); // Hiển thị hoặc ẩn menu
    if (mobileMenu.classList.contains('hidden')) {
      mobileMenu.classList.add('-translate-y-full');
      mobileMenu.classList.remove('translate-y-0');
    } else {
      mobileMenu.classList.remove('-translate-y-full');
      mobileMenu.classList.add('translate-y-0');
    }
  });
}

</script>