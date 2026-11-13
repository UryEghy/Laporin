<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa'){
    header("Location: ../login.php");
    exit;
}
$base = "../";
include '../header.php';
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2>Selamat Datang, <b><?= $_SESSION['nama']; ?></b>!</h2>
        <p class="text-muted">Mau melaporkan fasilitas kampus yang rusak hari ini?</p>
    </div>

    <div class="row justify-content-center">
        
        <div class="col-md-5 mb-3">
            <div class="card text-center shadow-sm p-4 h-100">
                <h4>Buat Laporan Baru</h4>
                <p>Ada fasilitas yang rusak? Laporkan detailnya di sini.</p>
                <a href="buat_laporan.php" class="btn btn-primary mt-auto">Buat Laporan</a>
            </div>
        </div>

        <div class="col-md-5 mb-3">
            <div class="card text-center shadow-sm p-4 h-100">
                <h4>Riwayat Laporan</h4>
                <p>Cek status laporan yang sudah pernah kamu buat.</p>
                <a href="riwayat.php" class="btn btn-outline-secondary mt-auto">Lihat Riwayat</a>
            </div>
        </div>

    </div>
</div>

<?php 

include '../footer.php'; 
?>
