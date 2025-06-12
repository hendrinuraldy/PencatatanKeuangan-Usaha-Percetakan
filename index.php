<?php 
    session_start();
    require "function/functions.php";
    
    if ( isset($_SESSION["login"]) ) {
        header("Location: dashboard");
        exit;
    } elseif(isset($_COOKIE['login'])) {
        header("Location: dashboard");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Catatan Keuangan</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/icon.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css1/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css1/style.css" rel="stylesheet">
</head>

<body>
<audio id="background-audio" hidden>
    <source src="audio/_Selamat datang di W (1).m4a" type="audio/mpeg">
    Browsermu tidak mendukung tag audio.
</audio>
    <div class="container-fluid bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="m-0">Catatan<span class="m-0">Keuangan</span></h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                    <div class="navbar-nav ms-auto py-0">
                             <a href="#home" class="nav-item nav-link active">Beranda</a>
                             <a href="#features" class="nav-link js-scroll-trigger">Informasi</a>
                            
                             <a href="login.php" class="nav-link js-scroll-trigger">Login</a>
                </div>

                    </div>

                </div>
            </nav>

            <div class="container-fluid py-5 bg-primary hero-header mb-5" id="home">
                <div class="container my-5 py-5 px-lg-5">
                    <div class="row g-5 py-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="text-white mb-4 animated zoomIn">Selamat Datang di CatatanKeuangan</h1>
                            <p class="text-white pb-3 animated zoomIn">Aplikasi Catatan Keuangan yang menarik karena
                                terdapat chatbot AI dan prediksi arus kas dengan AI</p>
                            <a href="login.php"
                                class="btn btn-light py-sm-3 px-sm-5 rounded-pill me-3 animated slideInLeft">Daftar
                                Disini</a>

                        </div>
                        <div class="col-lg-6 text-center text-lg-start">
                            <img class="img-fluid" src="img1/keuangan.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        <!-- Full Screen Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content" style="background: rgba(29, 29, 39, 0.7);">
                    <div class="modal-header border-0">
                        <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center justify-content-center">
                        <div class="input-group" style="max-width: 600px;">
                            <input type="text" class="form-control bg-transparent border-light p-3"
                                placeholder="Type search keyword">
                            <button class="btn btn-light px-4"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Full Screen Search End -->


        <!-- About Start -->
        <div class="container-fluid py-5" id="features">
            <div class="container px-lg-5">
                <!-- Section 1: Text Left, Image Right -->
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="section-title position-relative mb-4 pb-2">
                            <h6 class="position-relative text-primary ps-4">Informasi</h6>
                            <h2 class="mt-2">Transaksi Harian</h2>
                        </div>
                        <p class="mb-4">Kami memberikan fitur transaksi harian yang akan menampilkan data
                            harian yang bisa mempermudah anda dalam mengelola keuangan pribadi. dan data keuangan anda
                            akan
                            tersimpan dengan aman di dalam aplikasi ini.</p>

                    </div>
                    <div class="col-lg-4">
                        <img class="img-fluid wow zoomIn" data-wow-delay="0.5s" src="img1/catat.png">
                    </div>
                </div>

                <!-- Section 2: Image Left, Text Right -->
                <div class="row g-5 align-items-center mt-3">
                    <div class="col-lg-4">
                        <img class="img-fluid wow zoomIn" data-wow-delay="0.5s" src="img1/prioritas.png">
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="section-title position-relative mb-4 pb-2">
                            <h6 class="position-relative text-primary ps-4">Informasi</h6>
                            <h2 class="mt-2">Solusi Keuangan</h2>
                        </div>
                        <p class="mb-4">Kami menawarkan solusi keuangan dengan fitur yang lengkap dan mudah digunakan,
                            membantu Anda mencatat serta memprediksi arus kas secara efektif.</p>

                    </div>
                </div>

                <!-- Section 3: Text Left, Image Right -->
                <div class="row g-5 align-items-center mt-3">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="section-title position-relative mb-4 pb-2">
                            <h6 class="position-relative text-primary ps-4">Informasi</h6>
                            <h2 class="mt-2">Monitoring Keuangan</h2>
                        </div>
                        <p class="mb-4">Monitoring keuangan tentunya sangat diperlukan untuk mengelola pengeluaran dan
                            pemasukan kita. kami menyediakan dashboard yang berisi beberapa fitur, seperti saldo, total
                            uang yang masuk dan keluar, dan rekening.</p>

                    </div>
                    <div class="col-lg-4">
                        <img class="img-fluid wow zoomIn" data-wow-delay="0.5s" src="img1/menabung.png">
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->
        <!-- Footer Start -->
        <div class="container-fluid bg-primary text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-3">
                        <h5 class="text-white mb-4">Main</h5>
                        <a class="btn btn-link" href="#home">Beranda</a>
                        <a class="btn btn-link" href="#features">Informasi</a>
                        <a class="btn btn-link" href="#about">Kontak</a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <h5 class="text-white mb-4">Others</h5>
                        <a class="btn btn-link" href="faq">FAQ</a>

                    </div>
                    <div class="col-md-6 col-lg-3">
                        <h5 class="text-white mb-4">Informasi</h5>
                        <p style="text-align: justify;">Aplikasi CatatanKeuangan sudah dilengkapi dengan fitur menarik yang
                            dapat mempermudah user
                            mengelola keuangannya.</p>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <h5 class="text-white mb-4">Follow Us</h5>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="https://www.facebook.com"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.instagram.com"><i
                                    class="fab fa-instagram"></i></a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="mb-0 mt-2">Raldy &copy; 2025 All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->



        <!-- Back to Top -->
       
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var audio = document.getElementById("background-audio");
        var playPromise = audio.play();

        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log("Autoplay prevented, waiting for user interaction.");
                document.addEventListener("click", () => {
                    audio.play();
                }, { once: true });
            });
        }

        // Menghapus event listener setelah audio selesai diputar
        audio.addEventListener("ended", function() {
            console.log("Audio selesai diputar.");
            audio.remove(); // Hapus elemen audio agar tidak bisa diputar ulang
        });
    });
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/682c0a726392a3190c7818b5/1irm0hkht';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
    <!-- Template Javascript -->
    <script src="js1/main.js"></script>
   
</body>

</html>