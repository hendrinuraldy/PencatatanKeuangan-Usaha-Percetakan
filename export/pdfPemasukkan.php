<?php 
function fetchData()
{
     $hasil = '';
     require '../function/functions.php';
     $jenis = $_GET['jenis'];
     $tanggalAwal = $_GET['awal'];
     $tanggalAkhir = $_GET['akhir'];
     $username = $_GET['username'];
     $sql = "SELECT * FROM $jenis WHERE username = '$username' AND (tanggal BETWEEN '$tanggalAwal' AND '$tanggalAkhir')";
     $result = mysqli_query($koneksi, $sql);
     $no = 1;

     while ($row = mysqli_fetch_assoc($result)) {
          // masukin nilai ke variabel
          $jumlah = $row["jumlah"];
          // konversi string nilai ke int + split
          $jumlahConvert = str_replace('.', '', $jumlah);
          $hasilJumlah = number_format ($jumlahConvert, 2, ',', '.');
          $hasil .= '
          <tr>
               <td>' . $no . '</td>
               <td>' . $row["tanggal"] . '</td>
               <td>' . $row["keterangan"] . '</td>
               <td>' . $row["sumber"] . '</td>
               <td>' . $hasilJumlah . '</td>
          </tr>
          ';
          $jumlahe[] = $row["jumlah"];
          $jumlahConvert = str_replace('.', '', $jumlahe);
          $totali = array_sum($jumlahConvert);
          $hasilJumlah2 = number_format($totali, 2, ',', '.');
          $no++;
     }
     $hasil .= '
     <tr>
          <td colspan="4" style="text-align: center;">Total Pemasukkan</td>
          <td>' . $hasilJumlah2 . '</td>
     </tr>
     ';
     return $hasil;
}

if (isset($_POST["pdf"])) {
     require_once('../tcpdf/tcpdf.php');
     $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
     $obj_pdf->SetCreator(PDF_CREATOR);
     $obj_pdf->SetTitle("Laporan Pemasukkan Keuangan");
 
     // Konfigurasi PDF
     $obj_pdf->SetHeaderData('', '', '', '');
     $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
     $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
     $obj_pdf->SetDefaultMonospacedFont('helvetica');
     $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
     $obj_pdf->SetMargins(15, 10, 15);
     $obj_pdf->setPrintHeader(false);
     $obj_pdf->setPrintFooter(false);
     $obj_pdf->SetAutoPageBreak(true, 10);
     $obj_pdf->SetFont('helvetica', '', 11);
     $obj_pdf->AddPage();
 
     // Header (Kop Surat)
     $content = '
     <style>
         .kop {
             text-align: center;
             font-family: helvetica;
         }
         .judul-laporan {
             font-size: 16pt;
             font-weight: bold;
             margin-top: 10px;
         }
         .subjudul {
             font-size: 11pt;
         }
         .garis {
             border-top: 2px solid #000;
             margin-top: 10px;
             margin-bottom: 15px;
         }
     </style>
 
     <div class="kop">
         <h2 style="margin: 0;">Catatan Keuangan Sejahtera</h2>
     
         <div class="garis"></div>
         <p class="judul-laporan">LAPORAN PEMASUKKAN KEUANGAN</p>
         <p class="subjudul">Tanggal Cetak: ' . date("d-m-Y") . '</p>
     </div><br/>
     ';
 
     // Tabel isi
     $content .= '  
     <table border="1" cellspacing="0" cellpadding="3">  
         <tr style="background-color: #f2f2f2; font-weight: bold;">  
             <th width="5%">No.</th>   
             <th width="14%">Tanggal</th>
             <th width="28%">Keterangan Pemasukkan</th>
             <th width="28%">Sumber Pemasukkan</th>
             <th width="25%">Jumlah Pemasukkan</th>
         </tr>  
     ';
     
     $content .= fetchData(); // Pastikan fungsi fetchData() terdefinisi
     $content .= '</table>';
 
     // Output ke PDF
     $obj_pdf->writeHTML($content, true, false, true, false, '');
     $obj_pdf->Output('Laporan_Pemasukkan_Keuangan.pdf', 'I');
 }
 
?>