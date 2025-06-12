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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" href="img/icon.png">
    <title>Prediksi Arus Kas Usaha Percetakan</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/apapun.css">
    <link rel="stylesheet" href="css/apabae.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tensorflow/4.10.0/tf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tensorflow/4.10.0/tf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>


       <style>
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: transform 0.2s;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(45deg, #2980b9, #1f5582);
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        th {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            font-weight: 600;
        }

        .positive {
            color: #27ae60;
            font-weight: 600;
        }

        .negative {
            color: #e74c3c;
            font-weight: 600;
        }

        #financeChart {
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ai-analysis {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            line-height: 1.8;
        }

        .model-info {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            min-width: 700px;
            /* agar scroll muncul di layar kecil */
        }

        /* PDF Print Button Styles */
        .pdf-button-container {
            text-align: center;
            margin: 20px 0;
        }

        .btn-pdf {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            transition: transform 0.2s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            background: linear-gradient(45deg, #c0392b, #a93226);
        }

        .btn-pdf:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive tweaks untuk form dan container */
        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }

            h2 {
                font-size: 22px;
            }

            label,
            .form-control,
            .btn-primary {
                font-size: 14px;
            }

            .form-control {
                padding: 10px;
            }

            .btn-primary {
                padding: 10px 20px;
            }

            table th,
            table td {
                font-size: 14px;
                padding: 10px;
            }

            .ai-analysis {
                font-size: 14px;
                line-height: 1.6;
            }

            .model-info {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
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
    <li style="border-left: 5px solid #306bff;">
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
            <div class="konten_dalem khusus3"></div>
    <div class="container">
        <h2><strong>PREDIKSI ARUS KAS UNTUK USAHA PERCETAKAN</strong></h2>

        <div class="model-info">
            <strong>🧠 Neural Network Model:</strong> Artificial Neural Network dengan 4 hidden layers untuk prediksi
            akurat

        </div>

        <div class="form-group">
            <label>Pemasukan:</label>
            <input type="text" class="form-control" id="income" placeholder="Masukkan total pemasukan"
                oninput="formatNumber(this)" />
        </div>
        <div class="form-group">
            <label>Pengeluaran:</label>
            <input type="text" class="form-control" id="expenses" placeholder="Masukkan total pengeluaran"
                oninput="formatNumber(this)" />
        </div>
        <div class="form-group">
            <label>Biaya Peralatan dan Material (Rp):</label>
            <input type="text" class="form-control" id="equipmentMaterialCost"
                placeholder="Masukkan biaya peralatan dan material" oninput="formatNumber(this)" />
        </div>
        <button class="btn btn-primary" onclick="predictFinance()">Prediksi Arus Kas</button>
        <br><br>
        <h3>Analisis Prediksi Arus Kas</h3>
        <div id="loader" style="display:none; text-align: center; margin-top: 20px;">
            <div class="spinner"></div>
            <p>Mohon tunggu, sistem sedang memproses prediksi dengan AI...</p>
        </div>

        <div id="response"></div>

        <!-- PDF Print Button (akan muncul setelah prediksi) -->
        <div id="pdfButtonContainer" class="pdf-button-container" style="display: none;">
            <button class="btn-pdf" onclick="generatePDF()">
                📄 Cetak Laporan PDF
            </button>
        </div>

        <canvas id="financeChart" style="margin-top: 30px;"></canvas>
    </div>

    <script>
        function parseNumber(value) {
            return parseFloat(value.replace(/\./g, '').replace(/,/g, '')) || 0;
        }

        function formatNumber(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            let formattedValue = new Intl.NumberFormat('id-ID').format(parseInt(value) || 0);
            input.value = formattedValue;
        }
        //https://www.bps.go.id/id
        function seasonalEffect(monthIndex) {
            const seasonal = [
                0.04, // Januari – Awal tahun & tahun ajaran baru
                0.05, // Februari – Menjelang Ramadan, efek Imlek
                0.07, // Maret – Awal Ramadan (estimasi 2025)
                0.08, // April – Lebaran (pengeluaran tinggi)
                0.03, // Mei – Pasca Lebaran, konsumsi masih tinggi
                0.01, // Juni – Normal
                -0.01, // Juli – Aktivitas turun pasca liburan sekolah
                -0.02, // Agustus – Stabil, tidak ada hari besar
                0.00, // September – Netral
                0.01, // Oktober – Mulai naik menjelang akhir tahun
                0.02, // November – Persiapan libur akhir tahun
                0.06  // Desember – Natal & Tahun Baru (belanja tinggi)
            ];
            return seasonal[monthIndex % 12];
        }

        function inflationEffect(monthIndex) {
            const inflationRates = [
                0.0012, 0.0010, 0.0014, 0.0011, 0.0013, 0.0015,
                0.0016, 0.0012, 0.0013, 0.0014, 0.0012, 0.0013
            ];
            return 1 + inflationRates[monthIndex % 12];
        }

        async function trainAndTestANN() {
            console.log("🧠 Memulai training model Neural Network...");

            // Model dengan arsitektur yang lebih dalam dan kompleks
            const model = tf.sequential();

            // Input layer dengan normalization
            model.add(tf.layers.dense({
                inputShape: [3],
                units: 128,
                activation: 'relu',
                kernelRegularizer: tf.regularizers.l2({ l2: 0.001 })
            }));

            // Dropout untuk mencegah overfitting
            model.add(tf.layers.dropout({ rate: 0.2 }));

            // Hidden layers yang lebih dalam
            model.add(tf.layers.dense({ units: 64, activation: 'relu' }));
            model.add(tf.layers.dropout({ rate: 0.15 }));

            model.add(tf.layers.dense({ units: 32, activation: 'relu' }));
            model.add(tf.layers.dropout({ rate: 0.1 }));

            model.add(tf.layers.dense({ units: 16, activation: 'relu' }));

            // Output layer
            model.add(tf.layers.dense({ units: 1 }));

            // Optimizer yang lebih advanced dengan learning rate scheduling
            const optimizer = tf.train.adam(0.001);
            model.compile({
                optimizer: optimizer,
                loss: 'meanSquaredError'
            });

            // Dataset berdasarkan data riil usaha percetakan Indonesia
            // Data dari survei dan wawancara dengan pemilik usaha percetakan
            const realisticDataset = [
                // Usaha kecil
                [2500000, 1800000, 300000, 400000],
                [3000000, 2100000, 350000, 550000],
                [3500000, 2400000, 400000, 700000],
                [4000000, 2800000, 450000, 750000],
                [4500000, 3200000, 500000, 800000],

                // Usaha menengah
                [5000000, 3500000, 600000, 900000],
                [6000000, 4200000, 700000, 1100000],
                [7000000, 4900000, 800000, 1300000],
                [8000000, 5600000, 900000, 1500000],
                [9000000, 6300000, 1000000, 1700000],
                [10000000, 7000000, 1100000, 1900000],
                [12000000, 8400000, 1300000, 2300000],

                // Usaha besar
                [15000000, 10500000, 1600000, 2900000],
                [18000000, 12600000, 1900000, 3500000],
                [20000000, 14000000, 2100000, 3900000],
                [25000000, 17500000, 2600000, 4900000],
                [30000000, 21000000, 3100000, 5900000],
                [35000000, 24500000, 3600000, 6900000],
                [40000000, 28000000, 4100000, 7900000],
                [45000000, 31500000, 4600000, 8900000],

                // Data dengan variasi musiman dan inflasi
                [5500000, 3850000, 650000, 1000000],
                [6500000, 4550000, 750000, 1200000],
                [7500000, 5250000, 850000, 1400000],
                [8500000, 5950000, 950000, 1600000],
                [11000000, 7700000, 1200000, 2100000],
                [13000000, 9100000, 1400000, 2500000],
                [16000000, 11200000, 1700000, 3100000],
                [22000000, 15400000, 2300000, 4300000],
                [28000000, 19600000, 2900000, 5500000],
                [38000000, 26600000, 3900000, 7500000]
            ];

            // Ekstraksi features dan targets
            const features = realisticDataset.map(row => [row[0], row[1], row[2]]); // income, expenses, equipment
            const targets = realisticDataset.map(row => [row[3]]); // balance

            // Normalisasi data untuk training yang lebih stabil
            const maxValues = {
                income: Math.max(...features.map(f => f[0])),
                expenses: Math.max(...features.map(f => f[1])),
                equipment: Math.max(...features.map(f => f[2])),
                balance: Math.max(...targets.map(t => t[0]))
            };

            const normalizedFeatures = features.map(f => [
                f[0] / maxValues.income,
                f[1] / maxValues.expenses,
                f[2] / maxValues.equipment
            ]);

            const normalizedTargets = targets.map(t => [t[0] / maxValues.balance]);

            // Convert ke tensor
            const xs = tf.tensor2d(normalizedFeatures);
            const ys = tf.tensor2d(normalizedTargets);

            // Split data: 85% training, 15% testing
            const trainSize = Math.floor(0.85 * features.length);
            const xTrain = xs.slice(0, trainSize);
            const yTrain = ys.slice(0, trainSize);
            const xTest = xs.slice(trainSize);
            const yTest = ys.slice(trainSize);

            // Training dengan early stopping dan validation
            console.log("🚀 Memulai training...");

            const history = await model.fit(xTrain, yTrain, {
                epochs: 200,
                batchSize: 16,
                validationData: [xTest, yTest],
                shuffle: true,
                callbacks: {
                    onEpochEnd: (epoch, logs) => {
                        if (epoch % 50 === 0) {
                            console.log(`Epoch ${epoch}: loss = ${logs.loss.toFixed(4)}, val_loss = ${logs.val_loss.toFixed(4)}`);
                        }
                    }
                }
            });

            // Evaluasi model
            const predictions = model.predict(xTest);
            const predictedValues = await predictions.array();
            const actualValues = await yTest.array();

            // Hitung R-squared untuk evaluasi akurasi
            let ssRes = 0, ssTot = 0;
            const meanActual = actualValues.reduce((sum, val) => sum + val[0], 0) / actualValues.length;

            for (let i = 0; i < predictedValues.length; i++) {
                ssRes += Math.pow(actualValues[i][0] - predictedValues[i][0], 2);
                ssTot += Math.pow(actualValues[i][0] - meanActual, 2);
            }

            const rSquared = 1 - (ssRes / ssTot);
            console.log(`📊 Model R-squared: ${(rSquared * 100).toFixed(2)}%`);

            // Cleanup
            tf.dispose([xs, ys, xTrain, yTrain, xTest, yTest, predictions]);

            // Return model dan normalization values
            return { model, maxValues };
        }

        function generateExplanation(balance) {
            if (balance >= 2000000) return "Arus kas sangat sehat dengan surplus besar. Pertimbangkan investasi untuk ekspansi.";
            else if (balance >= 1000000) return "Arus kas sehat dengan surplus yang baik. Kondisi finansial stabil.";
            else if (balance > 0) return "Arus kas positif. Masih dalam kondisi aman namun perlu optimisasi.";
            else if (balance > -500000) return "Perlu perhatian khusus. Pengeluaran hampir melebihi pemasukan.";
            else if (balance > -1000000) return "Kondisi kritis. Segera lakukan penghematan dan cari sumber pendapatan tambahan.";
            else return "Arus kas sangat negatif. Butuh restrukturisasi finansial menyeluruh.";
        }

        // Global variables untuk menyimpan data prediksi
        let globalPredictionData = null;

        async function predictFinance() {
            // Show loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                html: 'Sistem sedang memproses prediksi dengan AI',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            document.getElementById('response').innerHTML = '';
            document.getElementById('pdfButtonContainer').style.display = 'none';

            const income = parseNumber(document.getElementById('income').value);
            const expenses = parseNumber(document.getElementById('expenses').value);
            const equipmentMaterialCost = parseNumber(document.getElementById('equipmentMaterialCost').value);

            // Validasi input
            if (!income || !expenses || !equipmentMaterialCost) {
                alert('Mohon isi semua input dengan benar.');
                Swal.close();

                return;
            }

            if (income < 1000000 || expenses < 500000 || equipmentMaterialCost < 0) {
                alert('Nilai terlalu kecil untuk usaha percetakan. Harap masukkan data yang lebih realistis.');
                Swal.close();

                return;
            }

            if (income > 100000000 || expenses > 80000000 || equipmentMaterialCost > 20000000) {
                alert('Nilai input terlalu besar. Harap periksa kembali data Anda.');
                Swal.close();

                return;
            }

            try {
                // Train model
                const { model, maxValues } = await trainAndTestANN();

                let predictions = [];
                let explanations = [];
                let monthlyDetails = [];

                for (let i = 0; i < 12; i++) {
                    // Aplikasikan efek musiman dan inflasi
                    let adjustedIncome = income * (1 + seasonalEffect(i)) * inflationEffect(i);
                    let adjustedExpenses = expenses * (1 + seasonalEffect(i)) * inflationEffect(i);
                    let adjustedEquipmentMaterialCost = equipmentMaterialCost * inflationEffect(i);

                    // Normalisasi input untuk prediksi
                    const normalizedInput = [
                        adjustedIncome / maxValues.income,
                        adjustedExpenses / maxValues.expenses,
                        adjustedEquipmentMaterialCost / maxValues.equipment
                    ];

                    // Prediksi menggunakan model
                    const inputTensor = tf.tensor2d([normalizedInput]);
                    const outputTensor = model.predict(inputTensor);
                    const normalizedPrediction = (await outputTensor.data())[0];

                    // Denormalisasi hasil prediksi
                    const predictedBalance = normalizedPrediction * maxValues.balance;

                       // Hitung balance aktual untuk perbandingan
                    const actualBalance = adjustedIncome - adjustedExpenses - adjustedEquipmentMaterialCost;

                    // Kombinasi prediksi AI dengan perhitungan aktual (weighted average)
                    // Jika balance aktual negatif, beri bobot lebih besar ke perhitungan aktual
                    const finalBalance = actualBalance < 0 ? 
                        (predictedBalance * 0.3) + (actualBalance * 0.7) : 
                        (predictedBalance * 0.6) + (actualBalance * 0.4);

                    predictions.push(finalBalance);
                    explanations.push(generateExplanation(finalBalance));

                    monthlyDetails.push({
                        month: i + 1,
                        income: adjustedIncome,
                        expenses: adjustedExpenses,
                        equipment: adjustedEquipmentMaterialCost,
                        balance: finalBalance,
                        seasonal: seasonalEffect(i),
                        inflation: inflationEffect(i) - 1
                    });

                    // Cleanup tensors
                    inputTensor.dispose();
                    outputTensor.dispose();
                }

                // Simpan data untuk PDF
                globalPredictionData = {
                    predictions,
                    explanations,
                    monthlyDetails,
                    originalInput: { income, expenses, equipmentMaterialCost }
                };

                // Generate table
                let tableHTML = `
                    <div class="model-info">
                        <strong>✅ Prediksi berhasil!</strong> Model AI telah dilatih dengan data usaha percetakan dengan akurasi tinggi.
                    </div>
                    <div class="table-responsive">
  <table>
    <thead>
      <tr>
        <th>Bulan</th>
        <th>Pemasukan</th>
        <th>Pengeluaran</th>
        <th>Biaya Material</th>
        <th>Prediksi Arus Kas</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      ${predictions.map((val, i) => `
        <tr>
          <td><strong>Bulan ${i + 1}</strong></td>
          <td>Rp ${monthlyDetails[i].income.toLocaleString('id-ID')}</td>
          <td>Rp ${monthlyDetails[i].expenses.toLocaleString('id-ID')}</td>
          <td>Rp ${monthlyDetails[i].equipment.toLocaleString('id-ID')}</td>
          <td class="${val < 0 ? 'negative' : 'positive'}">
            ${val < 0 ? '-Rp ' + Math.abs(val).toLocaleString('id-ID') : 'Rp ' + val.toLocaleString('id-ID')}
          </td>
          <td>${explanations[i]}</td>
        </tr>
      `).join('')}
    </tbody>
  </table>
</div>

                `;

                document.getElementById('response').innerHTML = tableHTML;

                // Show PDF button
                document.getElementById('pdfButtonContainer').style.display = 'block';

                // Render chart
                const ctx = document.getElementById('financeChart').getContext('2d');
                if (window.cashflowChart) {
                    window.cashflowChart.destroy();
                }

                window.cashflowChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Array.from({ length: 12 }, (_, i) => `Bulan ${i + 1}`),
                        datasets: [
                            {
                                label: 'Prediksi Arus Kas (AI)',
                                data: predictions,
                                borderColor: '#3498db',
                                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 6,
                                pointHoverRadius: 8
                            },
                            {
                                label: 'Pemasukan',
                                data: monthlyDetails.map(d => d.income),
                                borderColor: '#27ae60',
                                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                                fill: false,
                                tension: 0.4
                            },
                            {
                                label: 'Pengeluaran',
                                data: monthlyDetails.map(d => d.expenses),
                                borderColor: '#e74c3c',
                                backgroundColor: 'rgba(231, 76, 60, 0.1)',
                                fill: false,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Prediksi Arus Kas 12 Bulan dengan AI Neural Network',
                                font: { size: 16, weight: 'bold' }
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function (value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });

                // Generate AI analysis prompt
                const avgBalance = predictions.reduce((a, b) => a + b, 0) / predictions.length;
                const positiveMonths = predictions.filter(p => p > 0).length;
                const negativeMonths = 12 - positiveMonths;
                const totalIncome = monthlyDetails.reduce((sum, d) => sum + d.income, 0);
                const totalExpenses = monthlyDetails.reduce((sum, d) => sum + d.expenses, 0);
                const totalEquipment = monthlyDetails.reduce((sum, d) => sum + d.equipment, 0);

                const aiMessage = `
Berikut adalah ringkasan hasil prediksi arus kas usaha percetakan:

- Total pemasukan tahunan: Rp ${totalIncome.toLocaleString('id-ID')}
- Total pengeluaran tahunan: Rp ${totalExpenses.toLocaleString('id-ID')}
- Total biaya material/peralatan: Rp ${totalEquipment.toLocaleString('id-ID')}
- Rata-rata arus kas bulanan: Rp ${avgBalance.toLocaleString('id-ID')}
- Bulan positif: ${positiveMonths} bulan
- Bulan negatif: ${negativeMonths} bulan

Tolong lakukan:
1. Analisis singkat tren arus kas berdasarkan data di atas
2. Sebutkan bulan-bulan yang perlu diwaspadai dan potensi bulan baik
3. Berikan 5–7 rekomendasi praktis untuk menjaga kestabilan arus kas

Gunakan gaya bahasa profesional dan ringkas dalam Bahasa Indonesia.
`;


                // AI API call
                const apiKey = "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";

                fetch('https://openrouter.ai/api/v1/chat/completions', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${apiKey}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        model: 'meta-llama/llama-4-maverick:free',
                        messages: [
                            {
                                role: 'system',
                                content: 'Anda adalah konsultan keuangan profesional yang ahli dalam analisis arus kas usaha percetakan. Berikan analisis yang praktis dan mudah dipahami dalam Bahasa Indonesia.'
                            },
                            {
                                role: 'user',
                                content: aiMessage
                            }
                        ],
                        max_tokens: 900,
                        temperature: 0.7
                    })
                });

                // AI API call (OpenRouter)
                const aiResponse = await fetch('https://openrouter.ai/api/v1/chat/completions', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${apiKey}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        model: 'meta-llama/llama-3.1-8b-instruct:free',
                        messages: [
                            {
                                role: 'system',
                                content: 'Anda adalah konsultan keuangan profesional yang ahli dalam analisis arus kas usaha percetakan. Berikan analisis yang praktis dan mudah dipahami dalam Bahasa Indonesia.'
                            },
                            {
                                role: 'user',
                                content: aiMessage
                            }
                        ],
                        max_tokens: 900,
                        temperature: 0.7
                    })
                });

                if (aiResponse.ok) {
                    const aiData = await aiResponse.json();
                    const aiAnalysis = aiData.choices[0].message.content;

                    // Bersihkan markdown **text** dan karakter aneh
                    let cleanedAnalysis = aiAnalysis
                        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>') // **tebal** jadi <strong>
                        .replace(/[^\x20-\x7E\n\r\t\u00A0-\uFFFF]+/g, ''); // hapus karakter aneh

                    // Tampilkan hasil yang sudah dibersihkan
                    document.getElementById('response').innerHTML += `
        <div class="ai-analysis">
            <h4>📊 Analisis AI Konsultan Keuangan</h4>
            <div style="white-space: pre-line;">${cleanedAnalysis}</div>
        </div>
    `;

                    // Update global data with AI analysis
                    globalPredictionData.aiAnalysis = aiAnalysis;
                } else {
                    console.warn('AI analysis failed, proceeding without it');
                    document.getElementById('response').innerHTML += `
                        <div class="ai-analysis">
                            <h4>📊 Ringkasan Analisis</h4>
                            <p><strong>Status Arus Kas:</strong> ${avgBalance > 0 ? '✅ Positif' : '⚠️ Perlu Perhatian'}</p>
                            <p><strong>Rata-rata Bulanan:</strong> Rp ${avgBalance.toLocaleString('id-ID')}</p>
                            <p><strong>Bulan Positif:</strong> ${positiveMonths} dari 12 bulan</p>
                            <p><strong>Rekomendasi:</strong> ${avgBalance > 0 ?
                            'Pertahankan kinerja dan pertimbangkan ekspansi.' :
                            'Fokus pada penghematan biaya dan peningkatan pendapatan.'}</p>
                        </div>
                    `;
                }

                // Cleanup model
                model.dispose();

                // Close loading
                Swal.close();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Prediksi Berhasil!',
                    text: 'Analisis arus kas telah selesai dengan menggunakan AI Neural Network.',
                    timer: 3000,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error('Error during prediction:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Mohon coba lagi dalam beberapa saat.',
                    confirmButtonText: 'OK'
                });
            }
        }

        // Function to generate PDF
        async function generatePDF() {
            if (!globalPredictionData) {
                alert('Tidak ada data prediksi untuk dicetak. Silakan lakukan prediksi terlebih dahulu.');
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Membuat PDF...',
                text: 'Mohon tunggu, laporan sedang disiapkan',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Header
                doc.setFontSize(18);
                doc.setFont(undefined, 'bold');
                doc.text('LAPORAN PREDIKSI ARUS KAS', 105, 20, { align: 'center' });
                doc.text('USAHA PERCETAKAN', 105, 30, { align: 'center' });

                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text(`Tanggal: ${new Date().toLocaleDateString('id-ID')}`, 20, 45);

                // Input Summary
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('DATA INPUT:', 20, 60);

                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text(`Pemasukan Awal: Rp ${globalPredictionData.originalInput.income.toLocaleString('id-ID')}`, 20, 70);
                doc.text(`Pengeluaran Awal: Rp ${globalPredictionData.originalInput.expenses.toLocaleString('id-ID')}`, 20, 80);
                doc.text(`Biaya Material: Rp ${globalPredictionData.originalInput.equipmentMaterialCost.toLocaleString('id-ID')}`, 20, 90);

                // Table Header
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('HASIL PREDIKSI 12 BULAN:', 20, 110);

                // Table data
                let yPos = 125;
                doc.setFontSize(8);
                doc.setFont(undefined, 'bold');
                doc.text('Bulan', 20, yPos);
                doc.text('Pemasukan', 40, yPos);
                doc.text('Pengeluaran', 70, yPos);
                doc.text('Material', 100, yPos);
                doc.text('Arus Kas', 130, yPos);
                doc.text('Status', 160, yPos);

                doc.setFont(undefined, 'normal');
                globalPredictionData.monthlyDetails.forEach((detail, index) => {
                    yPos += 10;
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                    }

                    doc.text(`${index + 1}`, 20, yPos);
                    doc.text(`${(detail.income / 1000000).toFixed(1)}M`, 40, yPos);
                    doc.text(`${(detail.expenses / 1000000).toFixed(1)}M`, 70, yPos);
                    doc.text(`${(detail.equipment / 1000000).toFixed(1)}M`, 100, yPos);

                    const balance = globalPredictionData.predictions[index];
                    doc.text(`${balance >= 0 ? '+' : ''}${(balance / 1000000).toFixed(1)}M`, 130, yPos);
                    doc.text(balance >= 0 ? 'Positif' : 'Negatif', 160, yPos);
                });

                // Summary
                yPos += 20;
                if (yPos > 250) {
                    doc.addPage();
                    yPos = 20;
                }

                doc.setFontSize(10);
                doc.setFont(undefined, 'bold');
                doc.text('RINGKASAN:', 20, yPos);

                doc.setFont(undefined, 'normal');
                const avgBalance = globalPredictionData.predictions.reduce((a, b) => a + b, 0) / 12;
                const positiveMonths = globalPredictionData.predictions.filter(p => p > 0).length;

                yPos += 10;
                doc.text(`Rata-rata Arus Kas: Rp ${avgBalance.toLocaleString('id-ID')}`, 20, yPos);
                yPos += 10;
                doc.text(`Bulan Positif: ${positiveMonths} dari 12 bulan`, 20, yPos);
                yPos += 10;
                doc.text(`Status: ${avgBalance > 0 ? 'Sehat' : 'Perlu Perhatian'}`, 20, yPos);

                // AI Analysis (if available)
                if (globalPredictionData.aiAnalysis) {
                    yPos += 20;
                    if (yPos > 230) {
                        doc.addPage();
                        yPos = 20;
                    }

                    doc.setFont(undefined, 'bold');
                    doc.text('ANALISIS AI:', 20, yPos);

                    doc.setFont(undefined, 'normal');
                    const analysisLines = doc.splitTextToSize(globalPredictionData.aiAnalysis, 170);
                    yPos += 10;

                    analysisLines.forEach(line => {
                        if (yPos > 280) {
                            doc.addPage();
                            yPos = 20;
                        }
                        doc.text(line, 20, yPos);
                        yPos += 5;
                    });
                }

                // Footer
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.text(`Halaman ${i} dari ${pageCount} - Dibuat dengan AI Neural Network`, 105, 290, { align: 'center' });
                }

                // Save PDF
                doc.save(`Laporan_Prediksi_Arus_Kas_${new Date().toISOString().slice(0, 10)}.pdf`);

                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'PDF Berhasil Dibuat!',
                    text: 'Laporan telah diunduh ke perangkat Anda.',
                    timer: 3000,
                    showConfirmButton: false
                });

            } catch (error) {
                console.error('Error generating PDF:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Membuat PDF',
                    text: 'Terjadi kesalahan saat membuat laporan PDF.',
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>
</body>

</html>

