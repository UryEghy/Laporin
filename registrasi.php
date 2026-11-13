<?php
session_start();
include 'koneksi.php';

$pesan = "";

if (isset($_POST['daftar'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = "mahasiswa";

    if ($nama == "" || $email == "" || $password == "") {
        $pesan = "<div class='alert alert-danger'>Semua kolom wajib diisi!</div>";
    } else {
        $cek_query = "SELECT id FROM users WHERE email = ?";
        $stmt_cek = $connect->prepare($cek_query);
        $stmt_cek->bind_param("s", $email);
        $stmt_cek->execute();
        $stmt_cek->store_result();

        if ($stmt_cek->num_rows > 0) {
            $pesan = "<div class='alert alert-danger'>Email sudah terdaftar! Gunakan email lain.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt =$connect->prepare($insert_query);
            $stmt->bind_param("ssss", $nama,$email, $hashed_password,$role);

            if ($stmt->execute()) {
                $pesan = "<div class='alert alert-success'>Daftar berhasil! Silakan <a href='login.php'>Login</a>.</div>";
            } else {
                $pesan = "<div class='alert alert-danger'>Gagal mendaftar. Coba lagi.</div>";
            }
            $stmt->close();
        }
        $stmt_cek->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Lapor.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm p-4">
                    <h3 class="text-center mb-4">Daftar Akun <b>lapor.in</b></h3>
                    <?= $pesan; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Mahasiswa</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <button type="submit" name="daftar" class="btn btn-primary w-100">Daftar</button>
                    </form>
                    <div class="mt-3 text-center">
                        Sudah punya akun? <a href="login.php">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <script src="script.js"></script>
</body>
</html>
