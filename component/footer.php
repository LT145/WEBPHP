<footer class="bg-black text-white  pt-10 pb-6">
    <div class="container mx-auto px-5 flex flex-col md:flex-row md:space-x-10 space-y-10 md:space-y-0">
        <!-- Thương hiệu -->
        <div class="flex-1">
            <a href="#" class="text-2xl font-bold">
                <h2>LK BARTENDER</h2>
            </a>
            <p class="text-sm leading-7 mt-4">
                Khơi nguồn sáng tạo, nâng tầm nghệ thuật pha chế!
            </p>
        </div>

        <!-- Liên hệ -->
        <div class="flex-1">
            <h4 class="font-medium text-lg relative mb-6">
                Liên Hệ Ngay
                <span class="absolute bottom-0 left-0 w-24 h-1 bg-white mt-1"></span>
            </h4>
            <ul class="space-y-4">
                <li class="flex items-center gap-3">
                    <ion-icon name="call-outline" class="text-xl"></ion-icon>
                    <a href="tel:+84332345957" class="text-sm hover:underline">+84 332345957</a>
                </li>
                <li class="flex items-center gap-3">
                    <ion-icon name="mail-outline" class="text-xl"></ion-icon>
                    <a href="mailto:dh52111258@student.stu.edu.vn" class="text-sm hover:underline">dh52111258@student.stu.edu.vn</a>
                </li>
                <li class="flex items-center gap-3">
                    <ion-icon name="location-outline" class="text-xl"></ion-icon>
                    <address class="text-sm not-italic">20 Sư Vạn Hạnh, Phường 9, Quận 5, TPHCM</address>
                </li>
            </ul>
        </div>

        <!-- Form hỗ trợ -->
        <!-- <div class="flex-1">
            <p class="text-sm mb-4">
                Nếu Có Vấn Đề Gì Cần Hỗ Trợ Hãy Điền Thông Tin vào Đây:
            </p>
            <form method="POST" autocomplete="off" class="space-y-4">
                <input type="email" name="email" id="email" class="bg-white text-sm text-black px-5 py-3 rounded-full w-full" placeholder="Nhập Vào Email Của bạn" required>
                <input type="text" name="message" id="message" class="bg-white text-sm text-black px-5 py-3 rounded-full w-full" placeholder="Vấn Đề Của Bạn:" required>
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white text-sm px-5 py-3 rounded-full w-full">
                    Gửi
                </button>
            </form>

        </div> -->
    </div>
</footer>
<?php
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require __DIR__ . '/../vendor/autoload.php'; 

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = trim($_POST['email']);
//     $message = trim($_POST['message']);

//     if (empty($email) || empty($message)) {
//         echo 'Vui lòng điền đầy đủ thông tin.';
//         exit();
//     }

//     $mail = new PHPMailer(true);

//     try {
//         $mail->isSMTP();
//         $mail->Host = 'smtp.gmail.com';
//         $mail->SMTPAuth = true;
//         $mail->Username = 'loc42286@gmail.com'; 
//         $mail->Password = 'yqjskovckgzsywem';  
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail->Port = 587;
//         $mail->CharSet = 'UTF-8';

//         $mail->setFrom('loc42286@gmail.com', 'LK Bartender Support'); 
//         $mail->addAddress($email); 
//         $mail->addReplyTo('loc42286@gmail.com', 'Support Team');

//         $mail->isHTML(true);
//         $mail->Subject = 'Hỗ trợ từ LK Bartender';
//         $mail->Body = '<p>Chúng tôi đã nhận được vấn đề từ bạn:</p><p>' . nl2br(htmlspecialchars($message)) . '</p>';

//         $mail->send();
//         echo 'Gửi email thành công! Chúng tôi sẽ phản hồi sớm nhất.';
//     } catch (Exception $e) {
//         echo 'Không thể gửi email. Lỗi: ' . $mail->ErrorInfo;
//     }
// } else {
//     echo 'Yêu cầu không hợp lệ.';
// }
?>