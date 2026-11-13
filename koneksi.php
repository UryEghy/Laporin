<?php
$hostname = "localhost";
$username = "root";
$pasword = "";
<<<<<<< HEAD
$database = "Laporin";
=======
$database = "laporin";
>>>>>>> e8014ba7cdbc5ce99543a7a17c882e3a075fa77f

$connect = mysqli_connect($hostname, $username, $pasword, $database);

if(!$connect){
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>