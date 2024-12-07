<?php
$servername = "localhost"; 
$db_username = "root"; // Đổi tên biến
$db_password = ""; // Đổi tên biến
$dbname = "congthucphache";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
  // Thiết lập PDO error mode thành exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Kết nối thành công"; 
} catch(PDOException $e) {
  echo "Lỗi kết nối: " . $e->getMessage();
}
?>