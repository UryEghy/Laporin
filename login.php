<?php
session_start();
include 'koneksi.php';

$pesan = "";
$email_ingat = isset($_COOKIE['email_ingat']) ? $_COOKIE['email_ingat'] : "";

if (isset($_POST['masuk'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        $pesan = "<div class='alert alert-denger'>Email dan password wajib diisi!</d>";
    } else {
        $query = "SELECT id, nama, password, role FROM users WHERE email = ?";
        $start = $connect->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $hasil = $stmt->get_result();
        $user = hasil->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

        if (isset($_POST['ingat'])) {
            setcookie("email_ingat", $email, time() + (30 * 24 * 60 * 60), "/");
        } else {
            setcookie("email_ingat", "", time() - 3600, "/");
        }
        if ($user['role'] == 'admin') {
            header("Location: admin/laporan.php");
        } else {
            header("Location: admin/dashboard.php");
        }
        exit;
        } else {
            $pesan = "<div class='alert alert-denger'>Email atau password salah!</div";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Lapor.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm p-4">
                    <h3 class="text-center mb-4">Masuk <b>lapor.in</b></h3>
                    <?= $pesan; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_ingat); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="ingat" class="form-check-input" id="ingat" <?= $email_ingat != "" ? "checked" : ""; ?>>
                            <label class="form-check-label" for="ingat">Ingat Email Saya</label>
                        </div>
                        <button type="submit" name="masuk" class="btn btn-primary w-100">Masuk</button>
                    </form>
                    <div class="mt-3 text-center">
                        Belum punya akun? <a href="registrasi.php">Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>