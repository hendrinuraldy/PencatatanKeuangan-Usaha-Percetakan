<?php
session_start();
include 'function/Connection.php'; // Pastikan koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = "Password tidak cocok!";
    } elseif (strlen($new_password) < 6) {
        $message = "Password harus minimal 6 karakter!";
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Periksa token dalam database
        $query = "SELECT * FROM users WHERE token_ganti_password = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update password dalam database
            $update = "UPDATE users SET password = ?, token_ganti_password = NULL WHERE token_ganti_password = ?";
            $stmt = $koneksi->prepare($update);
            $stmt->bind_param("ss", $hashed_password, $token);
            $stmt->execute();

            $message = "Password berhasil diubah! Silakan <a href='login.php'>login</a>.";
        } else {
            $message = "Token tidak valid atau sudah digunakan!";
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
    <title>Ganti Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
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
        .input-group {
            position: relative;
            width: 100%;
            margin: 12px 0;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
            font-size: 16px;
        }
        input:focus {
            border-color: #6e8efb;
            box-shadow: 0px 0px 10px rgba(110, 142, 251, 0.5);
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #6e8efb;
        }
        button {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(135deg, #5a7bec, #9560d1);
            box-shadow: 0px 5px 15px rgba(110, 142, 251, 0.4);
        }
        .message {
            margin-top: 15px;
            padding: 12px;
            color: white;
            border-radius: 8px;
            font-weight: bold;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Ganti Password</h2>
    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'Gagal') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
        
        <label>Password Baru:</label>
        <div class="input-group">
            <input type="password" name="new_password" id="new_password" required>
            <span class="toggle-password" onclick="togglePassword('new_password')">👁</span>
        </div>
        
        <label>Konfirmasi Password:</label>
        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" required>
            <span class="toggle-password" onclick="togglePassword('confirm_password')">👁</span>
        </div>
        
        <button type="submit">Ganti Password</button>
    </form>
</div>

<script>
    function togglePassword(id) {
        var input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>

</body>
</html>
