<?php ob_start(); ?>

<!DOCTYPE html>
<html>

<head>
<link rel="shortcut icon" href="img/icon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

</html>

<?php
include 'Connection.php';

// Fungsi generate nomor rekening
function acak($panjang)
{
    $karakter = '1234567890';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter) - 1);
        $string .= $karakter[$pos]; // Perbaikan: Menggunakan square brackets []
    }
    return $string;
}

function register($dataRegister)
{
    global $koneksi;

    $email = htmlspecialchars(stripcslashes($dataRegister['email-registrasi']));
    $username = htmlspecialchars(stripcslashes($dataRegister['username-registrasi']));
    $password = mysqli_real_escape_string($koneksi, htmlspecialchars($dataRegister['password-registrasi']));
    $passwordConfirm = mysqli_real_escape_string($koneksi, htmlspecialchars($dataRegister['password-confirm']));

    $cekUser = mysqli_query($koneksi, "SELECT email, username FROM users WHERE email = '$email' OR username = '$username'");

    // Cek username dan email
    if (mysqli_num_rows($cekUser) > 0) {
        echo "
            <script>
                swal('Maaf','Username / email telah dipakai!','info');
            </script>
        ";
        return false;
    }

    // Cek konfirmasi password
    if ($password != $passwordConfirm) {
        echo "
            <script>
                swal('Maaf', 'Password konfirmasi harus sama','info');
            </script>
        ";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
   
    $sukses = mysqli_query($koneksi, "INSERT INTO users (email, username, password ) VALUES ('$email', '$username', '$password' )");

    if ($sukses) {
        echo "
            <script>
                swal('Berhasil','Akun anda berhasil didaftarkan!','success');
            </script>
        ";
    } else {
        echo "
            <script>
                swal('Maaf','Akun anda gagal didaftarkan','warning');
            </script>
        ";
        return false;
    }

    return mysqli_affected_rows($koneksi);
}

function login($dataLogin)
{
    global $koneksi;

    $email = $dataLogin['user-email'];
    $username = $dataLogin['user-email'];
    $password = $dataLogin['password-login'];

    $cekUser = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");

    if (mysqli_num_rows($cekUser) === 1) {
        $hasil = mysqli_fetch_assoc($cekUser);

        if (password_verify($password, $hasil["password"])) {
            if ($hasil['status'] == 'aktif') {

                session_start(); // Pastikan session dimulai sebelum menggunakannya
                $_SESSION['user'] = $hasil['username'];
                $_SESSION['level'] = $hasil['level'];
                $_SESSION['login'] = true;

                if ($hasil['level'] == 'admin') {
                    header('Location: administrator');
                    exit();
                } elseif ($hasil['level'] == 'user') {
                    header('Location: dashboard');
                    exit();
                }

                if (isset($_POST['rememberme'])) {
                    setcookie('login', $hasil['username'], time() + 3600, "/");
                    setcookie('level', $hasil['level'], time() + 3600, "/");
                    setcookie('id', $hasil['id_user'], time() + 3600, "/");
                    setcookie('key', hash('sha256', $hasil['username']), time() + 3600, "/");
                }
            } else {
                echo "
                    <script>
                        swal('Maaf','Akun anda dinonaktifkan oleh admin!','info');
                    </script>
                ";
                return false;
            }
        } else {
            echo "
                <script>
                    swal('Maaf','Username / password salah!','warning');
                </script>
            ";
            return false;
        }
    } else {
        echo "
            <script>
                swal('Maaf','Username / password salah!','warning');
            </script>
        ";
        return false;
    }

    return mysqli_affected_rows($koneksi);
}

?>
