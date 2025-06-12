<?php 
    session_start();
    require "function/functions.php";
    
    // session dan cookie multilevel user
    if(isset($_COOKIE['login'])) {
        if ($_COOKIE['level'] == 'user') {
            $_SESSION['login'] = true;
            $ambilNama = $_COOKIE['login'];
        } 
        
        elseif ($_COOKIE['level'] == 'admin') {
            $_SESSION['login'] = true;
            header('Location: administrator');
        }
    } 

    elseif ($_SESSION['level'] == 'user') {
        $ambilNama = $_SESSION['user'];
    } 
    
    else {
        if ($_SESSION['level'] == 'admin') {
            header('Location: administrator');
            exit;
        }
    }
    // === AJAX request laporan ===
if (isset($_GET['laporan']) && isset($_GET['jenis'])) {
    $jenis = $_GET['jenis']; // nilai: 'mingguan', 'bulanan', atau 'semua'
    $username = $ambilNama;

    $laporan = getLaporanKeuangan($username, $jenis);

    echo json_encode(['laporan' => nl2br($laporan)]); // bisa dipakai untuk output AJAX
    exit;
}

    if(empty($_SESSION['login'])) {
        header('Location: login');
        exit;
    } 
    
 // === Dashboard total pemasukan & pengeluaran ===
$totalPemasukan = query("SELECT jumlah FROM pemasukkan WHERE username = '$ambilNama'");
$totalPengeluaran = query("SELECT jumlah FROM pengeluaran WHERE username = '$ambilNama'");
    
    foreach ( $totalPemasukan as $rowMasuk ) {
        $hargaMasuk[] = $rowMasuk["jumlah"];
        $convertHarga = str_replace('.', '', $hargaMasuk);
        $totalMasuk = array_sum($convertHarga);
    }

    foreach ( $totalPengeluaran as $rowKeluar ) {
        $hargaKeluar[] = $rowKeluar["jumlah"];
        $convertHarga2 = str_replace('.', '', $hargaKeluar);
        $totalKeluar = array_sum($convertHarga2);
    }

    global $totalMasuk, $totalKeluar;
    $saldo = $totalMasuk - $totalKeluar;
    $saldoFix = number_format($saldo, 0, ',', '.'); 

    $month = date('m');
    $day = date('d');
    $year = date('Y');
    
    $today = $year . '-' . $month . '-' . $day;


    // saldo rekening
    global $totalRekIn, $totalRekOut;
    $saldoRek = $totalRekIn - $totalRekOut;
    $saldoRekFix = number_format($saldoRek, 0, ',', '.');
    $no = 1;

    // get no rekening
    $query = "SELECT * FROM users WHERE username = '$ambilNama'";
    $ambilQuery = mysqli_query($koneksi, $query);
    $ambilData = mysqli_fetch_assoc($ambilQuery);

?>


                        
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/icon.png">
    <title>Catatankas - Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/apapun.css">
    <link rel="stylesheet" href="css/apabae.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
          body { background-color: #f8f9fa; }
        .dashboard-wrapper { padding: 2rem; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.rentang {
    padding-bottom: 75px;
}
.inul {
            background-color: #f8f9fa;
        }

        .chat-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            transition: background 0.3s, transform 0.3s;
        }

        .chat-icon:hover {
            transform: scale(1.1);
            background: linear-gradient(135deg, #00f2fe, #4facfe);
        }

        .chat-popup {
            position: fixed;
            bottom: 90px;
            right: 30px;
            background: #ffffff;
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            cursor: pointer;
            animation: fadeIn 1s ease-in-out;
            z-index: 10001;
        }

        .chat-container {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 25px;
            width: 360px;
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            z-index: 9999;
            flex-direction: column;
        }

        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .chat-header h4 {
            margin: 0;
            font-size: 18px;
            color: #007bff;
        }

        .chat-box {
            height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-behavior: smooth;
        }

        .chat-box::-webkit-scrollbar {
            width: 0px;
        }

        .message {
            padding: 10px 15px;
            border-radius: 25px;
            font-size: 14px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .user-message {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            align-self: flex-end;
            margin-left: auto;
            text-align: right;
        }

        .bot-message {
            background: #e0f7fa;
            color: #333;
            align-self: flex-start;
        }

        .chat-input {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .chat-input button {
            padding: 0 20px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            border: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .chat-input button:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe);
        }

        .loading-animation {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 30px;
            padding: 8px;
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: #00f2fe;
            border-radius: 50%;
            animation: bounce 1.2s infinite ease-in-out both;
        }

        .dot:nth-child(1) {
            animation-delay: -0.24s;
        }

        .dot:nth-child(2) {
            animation-delay: -0.12s;
        }

        .dot:nth-child(3) {
            animation-delay: 0;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }
        #logout-link {
    display: flex;
    align-items: center;
    gap: 5px;
    float: right;
    margin-top: 5px;
    margin-right: 10px;
}

#logout-link i,
#logout-link p {
    float: none !important;
    margin: 0;
}

    </style>
</head>

<body>
    <audio id="background-audio" hidden>
    <source src="audio/Selamat datang di Da.m4a" type="audio/mpeg">
    Browsermu tidak mendukung tag audio.
</audio>
    <div class="header">
        <img src="img/icon.png" width="25px" height="25px" class="float-left logo-fav">
        <h3 class="text-secondary font-weight-bold float-left logo">CatatanKeuangan</h3>
      
        <a href="logout">
            <div class="logout" id="logout-link">
                  <p class="float-right logout">Logout</p>
                <i class="fas fa-sign-out-alt float-right log"></i>
            </div>
        </a>
    </div>

    <div class="sidebar">
        <nav>
            <ul>
                <li class="rentang">
                    <img src="img/user2.png" class="img-fluid profile float-left" width="60px">
                    <h5 class="admin"><?= substr($ambilNama, 0, 7) ?></h5>
                    <div class="online online2">
                        <p class="float-right ontext">Online</p>
                        <div class="on float-right"></div>
                    </div>
                </li>
                <!-- fungsi slide -->
                <script>
                    $(document).ready(function () {
                        $("#flip").click(function () {
                            $("#panel").slideToggle("medium");
                            $("#panel2").slideToggle("medium");
                        });
                        $("#flip2").click(function () {
                            $("#panel3").slideToggle("medium");
                            $("#panel4").slideToggle("medium");
                        });
                    });
                </script>
                <!-- dashboard -->
                 
                <a href="dashboard" style="text-decoration: none;">
                    <li class="aktif" style="border-left: 5px solid #306bff;">
                        <div>
                            <span class="fas fa-tachometer-alt"></span>
                            <span>Dashboard</span>
                        </div>
                    </li>
                </a>

                <!-- data -->
                <li class="klik" id="flip" style="cursor:pointer;">
                    <div>
                        <span class="fas fa-database"></span>
                        <span>Data Harian</span>
                        <i class="fas fa-caret-right float-right" style="line-height: 20px;"></i>
                    </div>
                </li>

                <a href="pemasukkan" class="linkAktif">
                    <li id="panel" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-file-invoice-dollar"></i></span>
                            <span>Data Pemasukan</span>
                        </div>
                    </li>
                </a>

                <a href="pengeluaran" class="linkAktif">
                    <li id="panel2" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-hand-holding-usd"></i></span>
                            <span>Data Pengeluaran</span>
                        </div>
                    </li>
                </a>
                <!-- data -->

                <!-- Input -->
                <li class="klik2" id="flip2" style="cursor:pointer;">
                    <div>
                        <span class="fas fa-plus-circle"></span>
                        <span>Input Data</span>
                        <i class="fas fa-caret-right float-right" style="line-height: 20px;"></i>
                    </div>
                </li>

                <a href="tambahPemasukkan" class="linkAktif">
                    <li id="panel3" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-file-invoice-dollar"></i></span>
                            <span>Pemasukan</span>
                        </div>
                    </li>
                </a>

                <a href="tambahPengeluaran" class="linkAktif">
                    <li id="panel4" style="display: none;">
                        <div style="margin-left: 20px;">
                            <span><i class="fas fa-hand-holding-usd"></i></span>
                            <span>Pengeluaran</span>
                        </div>
                    </li>
                </a>
                <!-- Input -->

                <!-- laporan -->
                <a href="laporan" style="text-decoration: none;">
                    <li>
                        <div>
                            <span><i class="fas fa-clipboard-list"></i></span>
                            <span>Laporan</span>
                        </div>
                    </li>
                </a>
                 <!-- laporan -->
                        <a href="prediksi" style="text-decoration: none;">
    <li>
        <div>
            <span><i class="fas fa-chart-line"></i></span>
            <span>Prediksi Arus Kas</span>
        </div>
    </li>
</a>

                <!-- change icon -->
                <script>
                    $(".klik").click(function () {
                        $(this).find('i').toggleClass('fa-caret-up fa-caret-right');
                        if ($(".klik").not(this).find("i").hasClass("fa-caret-right")) {
                            $(".klik").not(this).find("i").toggleClass('fa-caret-up fa-caret-right');
                        }
                    });
                    $(".klik2").click(function () {
                        $(this).find('i').toggleClass('fa-caret-up fa-caret-right');
                        if ($(".klik2").not(this).find("i").hasClass("fa-caret-right")) {
                            $(".klik2").not(this).find("i").toggleClass('fa-caret-up fa-caret-right');
                        }
                    });
                </script>
                <!-- change icon -->
            </ul>
        </nav>
    </div>

    <div class="main-content khusus">
        <div class="konten khusus2">
            <div class="konten_dalem khusus3">
                <h2 class="heade" style="color: #4b4f58;">Dashboard</h2>
                <hr style="margin-top: -2px;">
                <div class="container" id="container" style="border: none;">
                    <div class="row tampilCardview" id="row">

                        <div class="col-md-4 jarak">
                            <div class="card card-stats card-warning" style="background: #347ab8;">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-wallet ikon"></i>
                                            </div>
                                        </div>
                                        <div class="col-7 d-flex align-items-center tulisan">
                                            <div class="numbers">
                                                <p class="card-category ket head">Saldo kas</p>
                                                <h4 class="card-title ket total">Rp. <?=$saldoFix;?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 jarak">
                            <a href="tambahPengeluaran" style="text-decoration: none;">
                                <div class="card card-stats card-warning" style="background: #d95350;">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fa fa-file-invoice-dollar ikon"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center tulisan">
                                                <div class="numbers">
                                                    <p class="card-category ket head">Pengeluaran</p>
                                                    <?php foreach ($totalPengeluaran as $row) : ?>
                                                    <?php
                                                        $hargaPengeluaran[] = $row["jumlah"];
                                                        $hargaConvert = str_replace('.', '', $hargaPengeluaran);
                                                        $totalPeng = array_sum($hargaConvert);
                                                        $hasilHargaPengeluaran = number_format($totalPeng, 0, ',', '.');   
                                                    ?>                                     
                                                    <?php endforeach; ?>

                                                    <?php global $hasilHargaPengeluaran;
                                                    if ( $hasilHargaPengeluaran != "" ) : ?>
                                                    <h4 class="card-title ket total">Rp. <?= $hasilHargaPengeluaran; ?></h4>
                                                    <?php else : ?>
                                                    <h4 class="card-title ket total">Rp. 0</h4>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="overlay" style="background: #e45351;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-plus-circle ikon2"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center">
                                                <p class="tulisan">Tambah Data</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 jarak">
                            <a href="tambahPemasukkan" style="text-decoration: none;">
                                <div class="card card-stats card-warning" style="background: #5db85b;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fa fa-hand-holding-usd ikon"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center tulisan">
                                                <div class="numbers">
                                                    <p class="card-category ket head">Pemasukan</p>
                                                    <?php foreach ($totalPemasukan as $row) : ?>
                                                        <?php
                                                            $hargaPemasukkan[] = $row["jumlah"];
                                                            $hargaConvert = str_replace('.', '', $hargaPemasukkan);
                                                            $totalPem = array_sum($hargaConvert);
                                                            $hasilHarga = number_format($totalPem, 0, ',', '.');    
                                                        ?>     
                                                    <?php endforeach ?>

                                                    <?php global $hasilHarga;
                                                    if ( $hasilHarga != "" ) : ?>
                                                    <h4 class="card-title ket total">Rp. <?= $hasilHarga ?> </h4>
                                                    <?php else : ?>
                                                    <h4 class="card-title ket total">Rp. 0 </h4>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="overlay">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-plus-circle ikon2"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 d-flex align-items-center">
                                                <p class="tulisan">Tambah Data</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Judul & Chatbot Section -->
                    <div class="col-md-12 mt-4">
                        <h4 style="color: #4b4f58;">Laporan Mingguan dan Bulanan dengan Chatbot</h4>
                        <div class="chat-tools mb-2">
                            <button class="btn btn-primary btn-sm" onclick="getLaporan('mingguan')">Laporan Mingguan</button>
                            <button class="btn btn-info btn-sm" onclick="getLaporan('bulanan')">Laporan Bulanan</button>
                        </div>
                        <div id="response" class="chat-box"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="username" value="<?= $ambilNama ?>">
    <input type="hidden" id="saldoRekening" value="<?= $saldoRek ?>">

    <!-- Modal Kelola rekening -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Kelola Rekening</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- isi form -->
                <div class="modal-body">
                    <p>No rekening anda : </p>
                    <h5 style="margin-top: -10px; margin-bottom: 13px;"><b><?= $ambilData['no_rek'] ?></b></h5>
                    <p style="margin-bottom: 5px;">Tentukan aksi : </p>
                    <button class="btn btn-info" id="openBtn" data-dismiss="modal">Isi saldo rekening</button>
                    <button class="btn btn-success" id="openBtn4" data-dismiss="modal">Transfer ke akun lain</button>
                    <button class="btn btn-danger" id="openBtn2" data-dismiss="modal" style="margin-top: 4px;">Cairkan ke saldo kas</button>
                </div>

                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Kelola rekening -->

    <!-- Modal dana masuk -->
    <div class="modal fade" id="myModal2" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Dana masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" value="<?= $today ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlahRek">Jumlah nominal</label>
                        <input type="text" class="form-control" id="jumlahRek" onkeydown="return numbersonly(this, event);"
                                onkeyup="javascript:tandaPemisahTitik(this);" required>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambahRek">Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dana masuk -->
    
    <!-- Modal dana keluar -->
    <div class="modal fade" id="myModal3" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Dana keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggalRekOut" value="<?= $today ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlahRekOut">Jumlah nominal</label>
                        <input type="text" class="form-control" id="jumlahRekOut" onkeydown="return numbersonly(this, event);"
                                onkeyup="javascript:tandaPemisahTitik(this);" required>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambahRekOut">Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dana keluar -->
    
    <!-- Modal history -->
    <div class="modal fade" id="myModal4" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Riwayat transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <script type="text/javascript" src="js/pisahTitik.js"></script>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                        <tr>
                            <th>No.</th>
                            <th>Kode transaksi</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                            <th>Tanggal</th>
                        </tr>
                        <?php foreach($rekeningMasuk as $row) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $row['kode'] ?></td>
                                <td><?= $row['jumlah'] ?></td>
                                <td><?= $row['aksi'] ?></td>
                                <td><?= $row['tanggal'] ?></td>
                            </tr>
                            <?php $no++ ?>
                        <?php endforeach; ?>

                        <?php foreach($rekeningKeluar as $row) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $row['kode'] ?></td>
                                <td><?= $row['jumlah'] ?></td>
                                <td><?= $row['aksi'] ?></td>
                                <td><?= $row['tanggal'] ?></td>
                            </tr>
                            <?php $no++ ?>
                        <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal history -->

    <!-- Modal transfer -->
    <div class="modal fade" id="myModal5" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- isi form -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jumlahRekOut">No rekening</label>
                        <input type="text" class="form-control" id="no_rek" required>
                    </div>
                </div>
                <!-- footer form -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary tambah_norek">Cari</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal transfer -->

    <!-- banyak modal -->
    <script>
        $('#openBtn').click(function () {
            $('#myModal2').modal({
                show: true
            });
        })
        $('#openBtn2').click(function () {
            $('#myModal3').modal({
                show: true
            });
        })
        $('#openBtn3').click(function () {
            $('#myModal4').modal({
                show: true
            });
        })
        $('#openBtn4').click(function () {
            $('#myModal5').modal({
                show: true
            });
        })
    </script>

    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: ["Saldo masuk", "Saldo keluar"],
                datasets: [{
                    label: 'Data rekening',
                    data: [
                        <?= $totalRekIn ?>, 
                        <?= $totalRekOut ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    document.getElementById("logout-link").addEventListener("click", function(event) {
        event.preventDefault(); // Mencegah langsung berpindah halaman
        
        swal({
            title: "Apakah Anda yakin?",
            text: "Anda akan keluar dari akun!",
            icon: "warning",
            buttons: ["Batal", "Logout"],
            dangerMode: true,
        }).then((willLogout) => {
            if (willLogout) {
                window.location.href = "logout"; // Arahkan ke halaman logout jika user setuju
            }
        });
    });
</script>
<script>
let chatHistory = [
    {
        role: "system",
        content: "Jawablah semua pertanyaan dalam bahasa Indonesia. Jika user meminta laporan keuangan, berikan ringkasan dan saran berdasarkan data yang diberikan. Gaya bahasa bisa formal atau santai, sesuai gaya user."
    }
];

async function getLaporan(jenis) {
    const responseDiv = document.getElementById('response');

    // Tampilkan permintaan user
    const userMessage = document.createElement('div');
    userMessage.className = 'message user-message';
    userMessage.textContent = `Minta laporan ${jenis}`;
    responseDiv.appendChild(userMessage);
    responseDiv.scrollTop = responseDiv.scrollHeight;

    // Loading animasi
    const loadingMessage = document.createElement('div');
    loadingMessage.className = 'message bot-message loading-animation';
    loadingMessage.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
    responseDiv.appendChild(loadingMessage);
    responseDiv.scrollTop = responseDiv.scrollHeight;

    try {
        // 1. Ambil data laporan dari PHP (database)
        const res = await fetch(`dashboard.php?laporan=1&jenis=${jenis}`);
        const result = await res.json();
        const laporanText = result.laporan || 'Data tidak tersedia';

        // 2. Tambahkan ke chat history dan kirim ke OpenRouter
        chatHistory.push({ 
    role: "user", 
    content: `Berikut ini adalah data laporan keuangan ${jenis === 'mingguan' ? 'per minggu' : jenis === 'bulanan' ? 'per bulan' : 'lengkap'}:\n\n${laporanText}\n\nTolong bantu jelaskan laporan ini...` 
});

        const aiResponse = await fetch("https://openrouter.ai/api/v1/chat/completions", {
            method: "POST",
            headers: {
                Authorization: "Bearer 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",
                "HTTP-Referer": "https://www.sitename.com",
                "X-Title": "SiteName",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                model: "meta-llama/llama-4-maverick:free",
                messages: chatHistory
            })
        });

        const aiData = await aiResponse.json();
        responseDiv.removeChild(loadingMessage);

        const content = aiData.choices?.[0]?.message?.content || "AI tidak memberikan balasan.";
        chatHistory.push({ role: "assistant", content });

        // 3. Tampilkan jawaban AI
        const botMessage = document.createElement('div');
        botMessage.className = 'message bot-message';
        botMessage.innerHTML = marked.parse(content);
        responseDiv.appendChild(botMessage);
        responseDiv.scrollTop = responseDiv.scrollHeight;

    } catch (error) {
        responseDiv.removeChild(loadingMessage);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'message bot-message';
        errorMessage.textContent = 'Gagal memproses laporan: ' + error.message;
        responseDiv.appendChild(errorMessage);
    }
}
</script>
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
  
    <script src="js/bootstrap.js"></script>
    <script src="js/kirimNoRek.js"></script>
    <script src="ajax/js/tambahRekeningIn.js"></script>
    <script src="ajax/js/tambahRekeningOut.js"></script>
</body>

</html>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
