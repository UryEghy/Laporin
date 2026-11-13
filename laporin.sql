CREATE DATABASE IF NOT EXISTS laporin
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE laporin;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,      -- UNIQUE: email tidak boleh kembar
    password VARCHAR(255) NOT NULL,          -- 255 karena hasil password_hash panjang
    role ENUM('mahasiswa','admin') NOT NULL DEFAULT 'mahasiswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_fasilitas VARCHAR(100) NOT NULL,
    lokasi VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                    -- siapa yang melapor
    fasilitas_id INT NOT NULL,               -- fasilitas yang rusak
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,          -- hanya nama file foto
    status ENUM('menunggu','diterima','diproses','selesai') NOT NULL DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (fasilitas_id) REFERENCES fasilitas(id) ON DELETE RESTRICT
);