<?php
// Hàm upload ảnh lên ImgBB
function uploadImageToImgBB($imagePath) {
    $apiKey = 'b2dedb1909d0db83e3589f48a28b7e11'; // Thay thế bằng API Key của bạn
    $url = 'https://api.imgbb.com/1/upload';

    // Tạo cURL request
    $ch = curl_init();

    // Cấu hình cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Tắt xác minh SSL (có thể cải thiện nếu chứng chỉ SSL không đúng)

    // Đọc nội dung tệp ảnh
    $imageData = file_get_contents($imagePath);
    if (!$imageData) {
        return "Không thể đọc tệp ảnh.";
    }

    // Tạo dữ liệu POST cho cURL
    $data = [
        'image' => base64_encode($imageData),  // Mã hóa ảnh thành base64
        'key' => $apiKey
    ];

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Gửi request và nhận phản hồi
    $response = curl_exec($ch);

    // Kiểm tra lỗi cURL
    if ($response === false) {
        return 'Lỗi cURL: "' . curl_error($ch) . '"';
    }

    curl_close($ch);

    // Giải mã JSON và lấy URL ảnh
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['url'])) {
        return $responseData['data']['url']; // Trả về URL ảnh trên ImgBB
    } else {
        return "Lỗi từ ImgBB: " . $responseData['error']['message'];
    }
}

// Kiểm tra nếu có tệp ảnh được upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    // Lấy tệp ảnh
    $image = $_FILES['image'];

    // Kiểm tra nếu tệp hợp lệ
    if ($image['error'] == 0) {
        // Lấy đường dẫn tệp ảnh
        $imagePath = $image['tmp_name'];

        // Gọi hàm upload ảnh
        $imageUrl = uploadImageToImgBB($imagePath);

        // Hiển thị kết quả
        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            echo "Ảnh đã được upload thành công! URL: <a href='$imageUrl' target='_blank'>$imageUrl</a>";
        } else {
            echo "Đã xảy ra lỗi khi upload ảnh. Lỗi: " . $imageUrl;
        }
    } else {
        // Nếu có lỗi khi tải ảnh lên
        echo "Lỗi khi tải ảnh lên. Mã lỗi: " . $image['error'];
    }
} else {
    echo "Không có tệp ảnh nào được upload.";
}
?>
