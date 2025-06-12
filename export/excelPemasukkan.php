<?php 
// koneksi ke databse
require '../function/functions.php';

// Pastikan tidak ada output sebelum header
ob_clean(); // Bersihkan output buffer

if (isset($_POST['excel'])) {
    $jenis = $_GET['jenis'];
    $tanggalAwal = $_GET['awal'];
    $tanggalAkhir = $_GET['akhir'];
    $username = $_GET['username'];
    
    // Gunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM {$jenis} WHERE username = ? AND tanggal BETWEEN ? AND ?");
    $stmt->bind_param("sss", $username, $tanggalAwal, $tanggalAkhir);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $no = 1;
    $output = '';

    if ($result->num_rows > 0) {
        // Set headers dengan lebih spesifik
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="Laporan_Pemasukkan_' . date('Y-m-d') . '.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        
        // Tambahkan BOM untuk UTF-8
        echo "\xEF\xBB\xBF";
        
        $output .= '
            <table border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <th>No.</th>   
                    <th>Tanggal</th>
                    <th>Keterangan Pemasukkan</th>
                    <th>Sumber Pemasukkan</th>
                    <th>Jumlah Pemasukkan</th>
                </tr>
        ';
        
        $jumlahe = array();
        while ($row = $result->fetch_assoc()) {
            // masukin nilai ke variabel
            $jumlah = $row["jumlah"];
            // konversi string nilai ke int + split
            $jumlahConvert = str_replace('.', '', $jumlah);
            $hasilJumlah = number_format($jumlahConvert, 2, ',', '.');
            
            $output .= '
            <tr>
                <td>' . $no . '</td>
                <td>' . htmlspecialchars($row["tanggal"]) . '</td>
                <td>' . htmlspecialchars($row["keterangan"]) . '</td>
                <td>' . htmlspecialchars($row["sumber"]) . '</td>
                <td>' . $hasilJumlah . '</td>
            </tr>
            ';
            
            $jumlahe[] = $row["jumlah"];
            $no++;
        }
        
        // Hitung total
        $jumlahConvert = array();
        foreach($jumlahe as $j) {
            $jumlahConvert[] = str_replace('.', '', $j);
        }
        $totali = array_sum($jumlahConvert);
        $hasilJumlah2 = number_format($totali, 2, ',', '.');
        
        $output .= '
            <tr>
                <td colspan="4" style="text-align: center; font-weight: bold;">Total Pemasukkan</td>
                <td style="font-weight: bold;">' . $hasilJumlah2 . '</td>
            </tr>
        ';
        $output .= '</table>';
        
        echo $output;
        exit(); // Pastikan tidak ada output lain setelah ini
    } else {
        // Jika tidak ada data, redirect atau tampilkan pesan
        header('Location: ../index.php?message=no_data');
        exit();
    }
} else {
    // Jika tidak ada POST request, redirect
    header('Location: ../index.php');
    exit();
}
?>