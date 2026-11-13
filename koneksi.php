<?php
$hostname = "localhost";
$username = "root";
$pasword = "";
$database = "laporin";

$connect = mysqli_connect($hostname, $username, $pasword, $database);

if(!$connect){
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>