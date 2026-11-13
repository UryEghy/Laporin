<?php
session_start();
include 'koneksi.php';

$pesan = "";
$email_ingat = isset($_COOKIE['email_ingat']) ? $_COOKIE['email_ingat'] : "";

if (isset($_POST['masuk'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        $pesan = "<div class='alert alert-danger'>Email dan password wajib diisi!</div>";
    } else {
        $query = "SELECT id, nama, password, role FROM users WHERE email = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $hasil = $stmt->get_result();
        $user = $hasil->fetch_assoc();

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
                header("Location: user/dashboard.php");
            }
            exit;
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
            $pesan = "<div class='alert alert-danger'>Email atau password salah!</div>";
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Lapor.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100 m-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm p-4 border-0">
                    <h3 class="text-center mb-4 fw-bold">Masuk <b>lapor.in</b></h3>
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
                        <button type="submit" name="masuk" class="btn btn-primary w-100 py-2">Masuk</button>
                    </form>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Belum punya akun? <a href="registrasi.php" class="text-decoration-none">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js">
    </script>
</body>
</html>