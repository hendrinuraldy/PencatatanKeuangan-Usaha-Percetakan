<?php
session_start();
include 'function/Connection.php'; // Pastikan koneksi database

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Pastikan setelah install PHPMailer

$message = ""; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        $message = "Email tidak valid!";
    } else {
        // Cek apakah email ada di database
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(32)); // Generate token unik
            
            // Simpan token di database
            $update = "UPDATE users SET token_ganti_password = ? WHERE email = ?";
            $stmt = $koneksi->prepare($update);
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();

            // Kirim email dengan PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Konfigurasi SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'raldyhend@gmail.com'; // Ganti dengan email Anda
                $mail->Password   = 'zjui rdrw bwya cops'; // Ganti dengan App Password dari Google
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Pengaturan email
                $mail->setFrom('raldyhend@gmail.com', 'Website CatatanKeuangan');
                $mail->addAddress($email);
                $mail->Subject = 'Reset Password Anda';
                
                $reset_link = "https://pencatatankeuangan.zya.me/keuangan/ganti_password.php?token=" . urlencode($token);
                
                // Format email dengan HTML
                $mail->isHTML(true);
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; text-align: center;'>
                        <h2>Permintaan Reset Password</h2>
                        <p>Halo, saya menerima permintaan untuk mereset password akun Anda. Jika Anda tidak meminta ini, abaikan email ini.</p>
                        <p>Jika Anda ingin mengatur ulang password Anda, silakan klik tombol di bawah ini:</p>
                        <a href='$reset_link' style='display: inline-block; padding: 12px 25px; font-size: 16px; color: #fff; background: #6e8efb; text-decoration: none; border-radius: 8px;'>Reset Password</a>
                        <p>Terima kasih,</p>
                        <p><strong>Website CatatanKeuangan</strong></p>
                    </div>
                ";

                $mail->send();
                $message = "Link reset password telah dikirim ke email Anda. Silakan cek juga folder Spam jika tidak menemukan email di kotak masuk.";
            } catch (Exception $e) {
                $message = "Gagal mengirim email: " . $mail->ErrorInfo;
            }
        } else {
            $message = "Email tidak ditemukan.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
<link rel="shortcut icon" href="img/icon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
     body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #6e8efb, #a777e3);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 380px;
    animation: fadeIn 0.8s ease-in-out;
}

h2 {
    color: #333;
    margin-bottom: 15px;
}

input[type="email"], button {
    width: 80%;
    padding: 14px;
    margin: 12px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

input[type="email"] {
    background: #f9f9f9;
    box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1);
}

input[type="email"]:focus {
    border-color: #6e8efb;
    box-shadow: 0px 0px 10px rgba(110, 142, 251, 0.5);
}

button {
    background: linear-gradient(135deg, #6e8efb, #a777e3);
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: linear-gradient(135deg, #5a7bec, #9560d1);
    box-shadow: 0px 5px 15px rgba(110, 142, 251, 0.4);
    transform: scale(1.05);
}

.message {
    margin-top: 15px;
    padding: 12px;
    color: white;
    border-radius: 8px;
    display: none;
    font-weight: bold;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    animation: fadeIn 0.5s ease-in-out;
}
.message.show {
    display: block;
    opacity: 1;
}


.success {
    background: #28a745;
    box-shadow: 0px 5px 15px rgba(40, 167, 69, 0.4);
}

.error {
    background: #dc3545;
    box-shadow: 0px 5px 15px rgba(220, 53, 69, 0.4);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes buttonHover {
    from { transform: scale(1); }
    to { transform: scale(1.05); }
}


    </style>
</head>
<body>

<div class="container">
<img src="https://cdn-icons-png.flaticon.com/512/6146/6146581.png" alt="Reset Password" style="width:150px; margin-bottom: 20px;">
<h2>Reset Password</h2>
<form method="post">
    <label>Masukkan Email:</label><br>
    <input type="email" name="email" required><br><br>
    <button type="submit">Kirim</button>
</form>


    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'Gagal') !== false ? 'error' : 'success'; ?>" id="message-box">
            <?php echo $message; ?>
        </div>
        <script>
            document.getElementById('message-box').style.display = 'block';
            setTimeout(() => {
                document.getElementById('message-box').style.display = 'none';
            }, 5000);
        </script>
    <?php endif; ?>
</div>
<script>
    setTimeout(() => {
        const messageBox = document.getElementById('message-box');
        if (messageBox) {
            messageBox.classList.add('show');
            setTimeout(() => {
                messageBox.classList.remove('show');
            }, 20000);
        }
    }, 500);
</script>

</body>
</html>
