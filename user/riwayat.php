<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

$base = "../";
include '../header.php';

$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$user_id = $_SESSION['user_id'];

if ($filter_status != "") {
    $query = "SELECT laporan.*, fasilitas.nama_fasilitas, fasilitas.lokasi 
              FROM laporan 
              JOIN fasilitas ON laporan.fasilitas_id = fasilitas.id 
              WHERE laporan.user_id = ? AND laporan.status = ? 
              ORDER BY laporan.id DESC";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("is", $user_id, $filter_status);
} else {
    $query = "SELECT laporan.*, fasilitas.nama_fasilitas, fasilitas.lokasi 
              FROM laporan 
              JOIN fasilitas ON laporan.fasilitas_id = fasilitas.id 
              WHERE laporan.user_id = ? 
              ORDER BY laporan.id DESC";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2 class="mb-3">Riwayat Laporan Saya</h2>
    <p class="text-muted">Pantau perkembangan status perbaikan fasilitas yang kamu laporkan di sini.</p>

    <div class="mb-4">
        <span class="fw-bold me-2">Filter Status:</span>
        <a href="riwayat.php" class="btn btn-sm <?= $filter_status == '' ? 'btn-dark' : 'btn-outline-dark'; ?>">Semua</a>
        <a href="riwayat.php?status=menunggu" class="btn btn-sm <?= $filter_status == 'menunggu' ? 'btn-warning' : 'btn-outline-warning'; ?>">Menunggu</a>
        <a href="riwayat.php?status=diterima" class="btn btn-sm <?= $filter_status == 'diterima' ? 'btn-info' : 'btn-outline-info'; ?>">Diterima</a>
        <a href="riwayat.php?status=diproses" class="btn btn-sm <?= $filter_status == 'diproses' ? 'btn-primary' : 'btn-outline-primary'; ?>">Diproses</a>
        <a href="riwayat.php?status=selesai" class="btn btn-sm <?= $filter_status == 'selesai' ? 'btn-success' : 'btn-outline-success'; ?>">Selesai</a>
    </div>

    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Fasilitas & Lokasi</th>
                        <th>Judul Laporan</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_fasilitas']); ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['lokasi']); ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                <td>
                                    <?php 
                                        $badge = 'secondary';
                                        if ($row['status'] == 'menunggu') $badge = 'warning';
                                        elseif ($row['status'] == 'diterima') $badge = 'info';
                                        elseif ($row['status'] == 'diproses') $badge = 'primary';
                                        elseif ($row['status'] == 'selesai') $badge = 'success';
                                    ?>
                                    <span class="badge bg-<?= $badge; ?>"><?= ucfirst($row['status']); ?></span>
                                </td>
                                <td><small><?= $row['created_at']; ?></small></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat laporan kerusakan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$stmt->close();
include '../footer.php'; 
?>