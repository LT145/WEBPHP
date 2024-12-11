<?php
session_start();
include '../asset/connect.php'; 

if (!isset($_SESSION['user_id'])) {
  header("Location: ../user/login.php"); 
  exit();
}

try {
  $userId = $_SESSION['user_id'];

  $sql = "SELECT p.* 
          FROM product p
          INNER JOIN yeuthich y ON p.id = y.id_product
          WHERE y.id_user = :id_user"; 

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo "Lỗi truy vấn: " . $e->getMessage();
  $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sản Phẩm Yêu Thích</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.tailwindcss.com" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen">
  <?php include('../component/header.php'); ?> 

  <main class="flex-grow">
    <div class="container mx-auto max-w-4xl">
      <h1 class="text-4xl text-center font-bold my-6">Sản Phẩm Yêu Thích</h1>
      <div class="grid grid-cols-1 gap-x-3 gap-y-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <?php if (!empty($products)) : ?>
          <?php foreach ($products as $product) : ?>
            <a href="../DetailProduct/detailproduct.php?id=<?= htmlspecialchars($product['id']) ?>" class="group relative block rounded-md border border-gray-300 shadow-sm hover:shadow-lg transition cursor-pointer">
              <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="aspect-square w-full rounded-t-md object-cover group-hover:opacity-75">
              <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($product['name']) ?></h2>
                <p class="mt-2 text-sm text-gray-500 line-clamp-3"><?= htmlspecialchars($product['mota']) ?></p>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else : ?>
          <p class="col-span-full text-center text-gray-500">Bạn chưa có sản phẩm yêu thích nào!</p> 
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../component/footer.php'; ?>
</body>
</html>
