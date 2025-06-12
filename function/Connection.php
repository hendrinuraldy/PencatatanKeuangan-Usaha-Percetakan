<?php
// $hostname = 'localhost';
// $username = 'sibb8757';
// $password = 'M41hpwg4TZTi82';
// $database = 'sibb8757_keuangan';

$hostname = 'sql103.hstn.me';
$username = 'mseet_39065365';
$password = 'AHXSQTbBJUK3';
$database = 'mseet_39065365_db_keuangan';


$koneksi= mysqli_connect($hostname,$username,$password,$database);
if(!$koneksi){
    // echo "gagal";
}
?>