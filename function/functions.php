<?php
include('Connection.php');

$koneksi = mysqli_connect($hostname,$username,$password,$database);
if (mysqli_connect_error() == true) {
    die('Gagal terhubung ke database');
    return false;
} else {
    return true;
}

function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// tambah data Pemasukkan
function tambahMasuk($dataMasuk)
{
    global $koneksi;
    $tanggalMasuk = htmlspecialchars($dataMasuk["tanggal"]);
    $keteranganMasuk = htmlspecialchars($dataMasuk["keterangan"]);
    $sumber = htmlspecialchars($dataMasuk["sumber"]);
    $jumlah = htmlspecialchars($dataMasuk["jumlah"]);
    $username = $dataMasuk["username"];

    // query insert data
    $query = "INSERT INTO pemasukkan (id, tanggal, keterangan, sumber, jumlah, username) VALUES (NULL, '$tanggalMasuk', '$keteranganMasuk', '$sumber', '$jumlah', '$username')";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// tambah data Pengeluaran
function tambahKeluar($dataKeluar)
{
    global $koneksi;
    $tanggalKeluar = htmlspecialchars($dataKeluar["tanggal"]);
    $keteranganKeluar = htmlspecialchars($dataKeluar["keterangan"]);
    $keperluan = htmlspecialchars($dataKeluar["keperluan"]);
    $jumlah = htmlspecialchars($dataKeluar["jumlah"]);
    $username = $dataKeluar["username"];

    // query insert data
    $query = "INSERT INTO pengeluaran (id, tanggal, keterangan, keperluan, jumlah, username) VALUES (NULL, '$tanggalKeluar', '$keteranganKeluar', '$keperluan', '$jumlah', '$username')";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// tanggal indonesia
function tgl_indo($tgl)
{
    $tanggal = substr($tgl, 8, 2);
    $nama_bulan = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des");
    $bulan = $nama_bulan[substr($tgl, 5, 2) - 1];
    $tahun = substr($tgl, 0, 4);

    return $tanggal . '-' . $bulan . '-' . $tahun;
}

// fungsi transfer
function transfer($dataTransfer)
{
    global $koneksi;
    $username = $dataTransfer['username'];
    $username2 = $dataTransfer['username2'];
    $tanggal = $dataTransfer['tanggal'];
    $saldoRekening = $dataTransfer['saldoRekening'];
    $jumlah = htmlspecialchars($dataTransfer['jumlah']);
    $jumlahConvert = str_replace('.', '', $jumlah);

    if ($jumlahConvert > $saldoRekening) {
        echo "
                <script>
                    alert('Maaf, saldo anda tidak cukup!');
                </script>
                ";
        return false;
    }
    // query insert data
    $query = "INSERT INTO rekening_masuk(jumlah, tanggal, username) VALUES('$jumlah', '$tanggal', '$username')";
    $query2 = "INSERT INTO rekening_keluar(jumlah, tanggal, username) VALUES('$jumlah', '$tanggal', '$username2')";
    mysqli_query($koneksi, $query);
    mysqli_query($koneksi, $query2);

    return mysqli_affected_rows($koneksi);
}
function getLaporanKeuangan($username, $jenis = 'semua') {
    global $koneksi;

    // Helper untuk format angka rupiah
    function formatRupiah($nilai) {
        return 'Rp ' . number_format($nilai, 0, ',', '.');
    }

    // Fungsi untuk ambil total mingguan/bulanan
    function getTotalPeriode($koneksi, $tabel, $username, $periode) {
        $group = $periode == 'minggu' ? "YEAR(tanggal), WEEK(tanggal)" : "YEAR(tanggal), MONTH(tanggal)";
        $query = "
            SELECT 
                $group AS grup,
                tanggal,
                SUM(CAST(REPLACE(jumlah, '.', '') AS DECIMAL)) as total
            FROM $tabel
            WHERE username = '$username'
            GROUP BY $group
            ORDER BY tanggal ASC
        ";
        return mysqli_query($koneksi, $query);
    }

    // Fungsi baru untuk mendapatkan minggu ke berapa dalam bulan
    function getMingguKeBerapaDiBulan($tanggal) {
        $timestamp = strtotime($tanggal);
        $hari_pertama_bulan = mktime(0, 0, 0, date('n', $timestamp), 1, date('Y', $timestamp));
        $minggu_pertama_bulan = date('W', $hari_pertama_bulan);
        $minggu_tanggal = date('W', $timestamp);
        
        // Hitung minggu ke berapa dalam bulan
        $minggu_ke = $minggu_tanggal - $minggu_pertama_bulan + 1;
        
        // Jika minggu negatif (tahun berbeda), hitung ulang
        if ($minggu_ke <= 0) {
            $minggu_ke = date('W', $timestamp);
        }
        
        // Batasi maksimal minggu ke-5
        if ($minggu_ke > 5) {
            $minggu_ke = 5;
        }
        
        return $minggu_ke;
    }

    $laporan = "";

    if ($jenis == 'mingguan' || $jenis == 'semua') {
        $laporan .= "**📊 Laporan Keuangan per Minggu**\n\n";

        $pemasukanMingguan = getTotalPeriode($koneksi, 'pemasukkan', $username, 'minggu');
        $pengeluaranMingguan = getTotalPeriode($koneksi, 'pengeluaran', $username, 'minggu');

        $laporan .= "**Pemasukan per Minggu:**\n";
        while ($row = mysqli_fetch_assoc($pemasukanMingguan)) {
            $minggu = date('d M Y', strtotime($row['tanggal']));
            $minggu_ke = getMingguKeBerapaDiBulan($row['tanggal']);
            $nama_bulan = date('F Y', strtotime($row['tanggal']));
            $laporan .= "- Minggu ke-$minggu_ke di bulan $nama_bulan ($minggu): " . formatRupiah($row['total']) . "\n";
        }

        $laporan .= "\n**Pengeluaran per Minggu:**\n";
        while ($row = mysqli_fetch_assoc($pengeluaranMingguan)) {
            $minggu = date('d M Y', strtotime($row['tanggal']));
            $minggu_ke = getMingguKeBerapaDiBulan($row['tanggal']);
            $nama_bulan = date('F Y', strtotime($row['tanggal']));
            $laporan .= "- Minggu ke-$minggu_ke di bulan $nama_bulan ($minggu): " . formatRupiah($row['total']) . "\n";
        }

        $laporan .= "\n";
    }

    if ($jenis == 'bulanan' || $jenis == 'semua') {
        $laporan .= "**📅 Laporan Keuangan per Bulan**\n\n";

        $pemasukanBulanan = getTotalPeriode($koneksi, 'pemasukkan', $username, 'bulan');
        $pengeluaranBulanan = getTotalPeriode($koneksi, 'pengeluaran', $username, 'bulan');

        $laporan .= "**Pemasukan per Bulan:**\n";
        while ($row = mysqli_fetch_assoc($pemasukanBulanan)) {
            $bulan = date('F Y', strtotime($row['tanggal']));
            $laporan .= "- Bulan $bulan: " . formatRupiah($row['total']) . "\n";
        }

        $laporan .= "\n**Pengeluaran per Bulan:**\n";
        while ($row = mysqli_fetch_assoc($pengeluaranBulanan)) {
            $bulan = date('F Y', strtotime($row['tanggal']));
            $laporan .= "- Bulan $bulan: " . formatRupiah($row['total']) . "\n";
        }
    }

    return $laporan;
}