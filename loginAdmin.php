<?php
include 'koneksi.php';

$nama = "Admin Kampus";
$email = "admin@lapor.in";
$password_polos = "admin123";
$password = password_hash($password_polos, PASSWORD_DEFAULT);
$role = "admin";

$query = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $connect->prepare($query);
$stmt->bind_param("ssss", $nama, $email, $password, $role);

if ($stmt->execute()) {
    echo "<h3>Berhasil! Akun Admin sudah dibuat.</h3>";
    echo "Email: <b>admin@lapor.in</b><br>";
    echo "Password: <b>admin123</b><br><br>";
    echo "<i>Silakan hapus file buat_admin.php ini setelah digunakan demi keamanan.</i>";
} else {
    echo "Gagal membuat akun admin: " . $connect->error;
}
$stmt->close();
?>