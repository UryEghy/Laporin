<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

if (isset($_POST['ubah_status'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];

    $stmt = $connect->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status_baru, $id_laporan);
    $stmt->execute();
    $stmt->close();
    header("Location: laporan.php");
    exit;
}

$query = "SELECT laporan.*, users.nama AS nama_mahasiswa, fasilitas.nama_fasilitas, fasilitas.lokasi 
          FROM laporan 
          JOIN users ON laporan.user_id = users.id 
          JOIN fasilitas ON laporan.fasilitas_id = fasilitas.id 
          ORDER BY laporan.id DESC";
$result = $connect->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="laporan.php">lapor.in (Admin)</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-danger" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-3">Kelola Laporan Kerusakan Fasilitas</h2>
        <p class="text-muted">Daftar seluruh laporan dari mahasiswa dan kontrol status penanganannya.</p>

        <div class="card shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pelapor</th>
                            <th>Fasilitas & Lokasi</th>
                            <th>Judul & Deskripsi</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_mahasiswa']); ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['nama_fasilitas']); ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['lokasi']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['judul']); ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($row['deskripsi']); ?></small>
                                    </td>
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
                                    <td>
                                        <form method="POST" class="d-flex gap-1">
                                            <input type="hidden" name="id_laporan" value="<?= $row['id']; ?>">
                                            <select name="status_baru" class="form-select form-select-sm" style="width: 130px;">
                                                <option value="menunggu" <?= $row['status'] == 'menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="diterima" <?= $row['status'] == 'diterima' ? 'selected' : ''; ?>>Diterima</option>
                                                <option value="diproses" <?= $row['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                            </select>
                                            <button type="submit" name="ubah_status" class="btn btn-sm btn-primary">Ubah</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada laporan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>
</html>