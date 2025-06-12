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

	if(empty($_SESSION['login'])) {
		header('Location: login');
		exit;
	} 

	$month = date('m');
	$day = date('d');
	$year = date('Y');
	
	$today = $year . '-' . $month . '-' . $day;

	$pemasukkan = query("SELECT * FROM pemasukkan WHERE tanggal = '$today' AND username = '$ambilNama'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="shortcut icon" href="img/icon.png">
	<title>CatatanKeuangan - Pemasukan</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
		crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
	<link rel="stylesheet" href="css/styler.css?v=1.0">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/sweetalert.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
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
</style>
<body>
	<div class="header">
		<img src="img/icon.png" width="25px" height="25px" class="float-left logo-fav">
		<h3 class="text-secondary font-weight-bold float-left logo">CatatanKeuangan</h3>
		<!-- <h3 class="text-secondary float-left logo2">- Qu</h3> -->
		<a href="logout">
			<div class="logout" id="logout-link">
					<i class="fas fa-sign-out-alt float-right log"></i>
					<p class="float-right logout">Logout</p>
			</div>
		</a>
	</div>
	<div class="chat-popup" id="chatPopup">
        <span><strong> Tanyakan pada asisten virtual dengan chatbot di sini!</strong></span>
        <span class="close-popup" onclick="closePopup()">&times;</span>
    </div>

    <div class="chat-icon" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </div>

    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <h4>Asisten Virtual dengan ChatBot</h4>
            <button class="btn btn-danger btn-sm" onclick="toggleChat()">X</button>
        </div>
        <div class="chat-box" id="response"></div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Ketik pesan..." class="form-control"
                onkeypress="handleKeyPress(event)" />
            <button class="btn btn-success" onclick="sendMessage()">Kirim</button>
        </div>
    </div>
	<div class="sidebar">
		<nav>
			<ul>
					<li>
						<img src="img/user2.png" class="img-fluid profile float-left" width="60px">
						<h5 class="admin"><?= substr($ambilNama, 0, 7) ?></h5>
						<div class="online online2">
							<p class="float-right ontext">Online</p>
							<div class="on float-right"></div>
						</div>
					</li>
					<!-- fungsi slide -->
					<script>
						$(document).ready(function(){
						$("#flip").click(function(){
							$("#panel").slideToggle("medium");
							$("#panel2").slideToggle("medium");
						});
						$("#flip2").click(function(){
							$("#panel3").slideToggle("medium");
							$("#panel4").slideToggle("medium");
						});
					});
					</script>

					<!-- dashboard -->
					<a href="dashboard" style="text-decoration: none;">
						<li>
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
							<i class="fas fa-caret-up float-right" style="line-height: 20px;"></i>
						</div>
					</li>

					<a href="pemasukkan" class="linkAktif">
						<li id="panel" class="aktif" style="border-left: 5px solid #306bff;">
							<div style="margin-left: 20px;">
									<span><i class="fas fa-file-invoice-dollar"></i></span>
									<span>Data Pemasukkan</span>
							</div>
						</li>
					</a>

					<a href="pengeluaran" class="linkAktif">
						<li id="panel2">
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
									<span>Pemasukkan</span>
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
					<h2 class="head" style="color: #4b4f58;">Pemasukkan</h2>
					<hr style="margin-top: -2px;">

					<!-- bagian filter dan pencarian -->
					<div class="row cari-filter">
						<div class="col-lg-5">
							<table class="tabel-data">
									<tr>
										<td><label>Pilih tanggal</label></td>
										<td style="width: 71%">
											<input type="date" value="<?= $today ?>" class="form-control filter" id="filter">
										</td>
									</tr>
							</table>
							
						</div>
						<div class="col-lg-4">
							<input type="hidden" id="username" value="<?= $ambilNama ?>">
						</div>

						<div class="col-lg-3">
							<div class="input-group">
									<input type="text" name="cari" class="form-control border-right-0 cari" id="keyword" placeholder="Search" autocomplete="off">
									<div class="input-group-append">
										<span class="input-group-text bg-white border-left-0 icone"><i class="fa fa-search"></i></span>
									</div>
							</div>
						</div>
					</div>
					<!-- bagian filter dan pencarian -->
					
					<!-- bagian isi tabel -->
					<div class="headline">
						<h5>Data Pemasukkan</h5>
					</div>
					<div class="container" id="container">
						<div class="row tampil" id="row">
							<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-sm table-hover table-striped table-bordered">
											<tr>
													<th>No.</th>
													<th>Tanggal</th>
													<th>Keterangan Pemasukkan</th>
													<th>Sumber Pemasukkan</th>
													<th>Jumlah Pemasukkan</th>
													<th>Aksi</th>
											</tr>
											<?php $i = 1; ?>
											<?php foreach ($pemasukkan as $row) : ?>
											<tr class="show" id="<?= $row['id'] ?>">
													<td> <?= $i ?> </td>
													<td data-target="tanggal"><?= htmlspecialchars($row['tanggal']) ?></td>
													<td data-target="keterangan"><?= htmlspecialchars($row['keterangan']) ?></td>
													<td data-target="sumber"><?= htmlspecialchars($row['sumber']) ?></td>
													<td data-target="jumlahMasuk"><?= htmlspecialchars($row['jumlah']) ?></td>
													<td>
													<a href="#" id="<?= $row["id"]; ?>" class="btn btn-info delete">
   															 <i class="fas fa-trash-alt"></i>
													</a>
														<a href="#" data-id="<?= $row['id']; ?>" data-role="update" class="btn btn-outline-secondary" id="openBtn">
															<i class="fas fa-edit"></i>
														</a>
													</td>
											</tr>
											<?php
													$jumlah2[] = $row["jumlah"];
													$jumlahConvert = str_replace('.', '', $jumlah2);
													$totali = array_sum($jumlahConvert);
													$hasilJumlah = number_format($totali, 0, ',', '.');
											?>
											<?php $i++ ?>
											<?php endforeach; ?>
											
											<?php if(isset($jumlah2) != null) :?>
											<tr>
													<td colspan="4">Total Pemasukkan</td>
													<td id="total" data-target="total"><?= $hasilJumlah ?></td>
											</tr>
											<?php endif; ?>

										</table>
									</div>
							</div>
						</div>
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-primary btn2" data-toggle="modal" data-target="#exampleModalCenter">
							<i class=" fas fa-hand-holding-usd"></i> Tambah Data
						</button>
					</div>
					<!-- bagian isi tabel -->
					
			</div>
		</div>
	</div>

	<!-- Modal Tambah Data -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Tambah Data Pemasukkan</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<!-- isi form -->
					<div class="modal-body">
						<script type="text/javascript" src="js/pisahTitik.js"></script>
						<div class="form-group">
							<label>Masukkan Tanggal</label>
							<input type="date" value="<?= $today ?>" name="tanggal" class="form-control" id="tanggalTambah" required>
						</div>
						<div class="form-group">
							<label>Masukkan Keterangan Pemasukkan</label>
							<input type="text" name="keterangan" class="form-control" id="keteranganTambah" required>
						</div>
						<div class="form-group">
							<label>Masukkan Sumber Pemasukkan</label>
							<select name="sumber" class="form-control" id="sumberTambah">
									<option>Jasa Cetak</option>
									<option>Jasa Printing</option>
									<option>Jasa Fotocopy</option>
									<option>Penjualan Produk Percetakan</option>
									<option>Produksi Souvenir Merchandise</option>
									<option>Pendapatan Lain-lain</option>
							</select>
						</div>
						<div class="form-group">
							<label>Masukkan Jumlah Pemasukkan</label>
							<input type="text" id="jumlahTambah" name="jumlah" class="form-control" onkeydown="return numbersonly(this, event);"
									onkeyup="javascript:tandaPemisahTitik(this);" required>
						</div>
					</div>
					<!-- footer form -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<button type="submit" class="btn btn-primary tambahin" onclick="showSuccessAlert()">Tambah</button>
					</div>
			</div>
		</div>
	</div>
	<!-- Modal Tambah Data -->

	<!-- Modal edit data -->
	<div class="modal fade" id="myModal2" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalCenterTitle">Ubah Data Pemasukkan</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!-- isi form -->
					<div class="modal-body">
						<input type="hidden" id="userId" class="form-control">
						<div class="form-group">
							<label for="tanggal">Tanggal</label>
							<input type="date" class="form-control" id="tanggal" required>
						</div>
						<div class="form-group">
							<label for="keterangan">Keterangan Pemasukkan</label>
							<input type="text" class="form-control" id="keterangan" required>
						</div>
						<div class="form-group">
							<label for="sumber">Sumber Pemasukkan</label>
							<select class="form-control" id="sumber">
									<option>Jasa Cetak</option>
									<option>Jasa Printing</option>
									<option>Jasa Fotocopy</option>
									<option>Penjualan Produk Percetakan</option>
									<option>Produksi Souvenir Merchandise</option>
									<option>Pendapatan Lain-lain</option>
							</select>
						</div>
						<div class="form-group">
							<label for="jumlahMasuk">Jumlah Pemasukkan</label>
							<input type="text" class="form-control" id="jumlahMasuk" onkeydown="return numbersonly(this, event);"
										onkeyup="javascript:tandaPemisahTitik(this);" required>
						</div>
					</div>
					<!-- footer form -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
						<a href="#" id="save" class="btn btn-primary">simpan</a>
					</div>
			</div>
		</div>
	</div>
	<!-- Modal edit data -->

	<!-- double modal -->
	<script>
		$('#openBtn').click(function () {
			$('#myModal2').modal({
					show: true
			});
		})
	</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="js/bootstrap.js"></script>
	<script src="ajax/js/filterPemasukkan.js"></script>
	<script src="ajax/js/tambahPemasukkan.js"></script>
	<script src="ajax/js/deletePemasukkan.js"></script>
	<script src="ajax/js/cariPemasukkan.js"></script>
	<script src="ajax/js/updatePemasukkan.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
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
$(document).ready(function () {
    // Event delegation for dynamically loaded elements
    $(document).on("click", ".delete", function (e) {
        e.preventDefault();

        var filter = $('#filter').val();
        var username = $('#username').val();
        var element = $(this);
        var del_id = element.attr("id");
        var info = { id: del_id };  // Use object notation for data

        Swal.fire({
            title: "Peringatan!",
            text: "Yakin ingin menghapus data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d9534f",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "function/deletePemasukkan.php",
                    data: info,
                    success: function (response) {
                        Swal.fire("Deleted!", "Data telah dihapus.", "success");
                        $(".tampil").load("ajax/tampilPemasukkanDel?filterSend=" + filter + '&username=' + username);
                    },
                    error: function () {
                        Swal.fire("Error!", "Terjadi kesalahan.", "error");
                    }
                });
            }
        });
    });
});
</script>
<script>
        let chatHistory = [
            {
                "role": "system",
                "content": "Jawablah semua pertanyaan dalam bahasa Indonesia. Sesuaikan gaya bahasa dengan gaya pengguna. Jika pengguna menggunakan bahasa santai, balaslah dengan santai. Jika pengguna menggunakan bahasa formal, balaslah secara formal. Jawablah dengan informasi akurat berdasarkan pengetahuan hingga tahun 2025. Jika pertanyaan tentang lokasi, tempat, atau waktu yang mungkin berubah setelah 2025, jawablah dengan menyebutkan bahwa informasi bisa saja tidak sepenuhnya terbaru, dan sarankan pengguna untuk memverifikasi ke sumber resmi atau terkini. Prioritaskan kejujuran dan realisme dalam jawaban."
            }
        ];

        function closePopup() {
            document.getElementById('chatPopup').style.display = 'none';
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function toggleChat() {
            const chatContainer = document.getElementById('chatContainer');
            const responseDiv = document.getElementById('response');

            if (chatContainer.style.display === 'block') {
                chatContainer.style.display = 'none';
            } else {
                chatContainer.style.display = 'block';

                if (!responseDiv.querySelector('.bot-greeting')) {
                    const botGreeting = document.createElement('div');
                    botGreeting.className = 'message bot-message bot-greeting';
                    botGreeting.textContent = 'Ada yang bisa dibantu?';
                    responseDiv.appendChild(botGreeting);
                    responseDiv.scrollTop = responseDiv.scrollHeight;
                }
            }
        }

        async function sendMessage() {
            const input = document.getElementById('userInput');
            const responseDiv = document.getElementById('response');
            const messageText = input.value.trim();
            if (!messageText) return;

            // Tampilkan pesan user
            const userMessage = document.createElement('div');
            userMessage.className = 'message user-message';
            userMessage.textContent = messageText;
            responseDiv.appendChild(userMessage);
            input.value = '';
            responseDiv.scrollTop = responseDiv.scrollHeight;

            // Tambahkan pesan user ke chat history
            chatHistory.push({ role: 'user', content: messageText });

            // Tampilkan loading
            const loadingMessage = document.createElement('div');
            loadingMessage.className = 'message bot-message loading-animation';
            loadingMessage.innerHTML = `
                <div class="loading-animation">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            `;
            responseDiv.appendChild(loadingMessage);
            responseDiv.scrollTop = responseDiv.scrollHeight;

            try {
                const response = await fetch('https://openrouter.ai/api/v1/chat/completions', {
                    method: 'POST',
                    headers: {
                        Authorization: 'Bearer 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',
                        'HTTP-Referer': 'https://www.sitename.com',
                        'X-Title': 'SiteName',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        model: 'meta-llama/llama-4-maverick:free',
                        messages: chatHistory, // <-- kirim seluruh history
                    }),
                });

                const data = await response.json();
                responseDiv.removeChild(loadingMessage);

                const botContent = data.choices?.[0]?.message?.content || 'No response received.';

                // Tampilkan jawaban AI
                const botMessage = document.createElement('div');
                botMessage.className = 'message bot-message';
                botMessage.innerHTML = marked.parse(botContent);
                responseDiv.appendChild(botMessage);
                responseDiv.scrollTop = responseDiv.scrollHeight;

                // Tambahkan jawaban bot ke chat history
                chatHistory.push({ role: 'assistant', content: botContent });

            } catch (error) {
                responseDiv.removeChild(loadingMessage);
                const errorMessage = document.createElement('div');
                errorMessage.className = 'message bot-message';
                errorMessage.textContent = 'Error: ' + error.message;
                responseDiv.appendChild(errorMessage);
                responseDiv.scrollTop = responseDiv.scrollHeight;
            }
        }
    </script>
<script>
    function showSuccessAlert() {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil ditambahkan.',
            icon: 'success',
            confirmButtonText: 'Oke',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }
</script>

</body>

</html>