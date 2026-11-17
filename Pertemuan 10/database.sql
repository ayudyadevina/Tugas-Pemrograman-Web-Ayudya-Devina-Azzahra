-- PPDB Online SMK Nusantara - Bagian 1 (Fitur Dasar / MVP Tahap 1)
CREATE DATABASE IF NOT EXISTS db_ppdb_smk_awal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_ppdb_smk_awal;

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','siswa') NOT NULL DEFAULT 'siswa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE siswa (
  id_siswa INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL UNIQUE,
  nisn CHAR(10) NOT NULL UNIQUE,
  nama_lengkap VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  tempat_lahir VARCHAR(60) NOT NULL,
  tanggal_lahir DATE NOT NULL,
  alamat TEXT NOT NULL,
  asal_sekolah VARCHAR(100) NOT NULL,
  no_hp VARCHAR(15) NOT NULL,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE jurusan (
  id_jurusan INT AUTO_INCREMENT PRIMARY KEY,
  kode_jurusan VARCHAR(10) NOT NULL UNIQUE,
  nama_jurusan VARCHAR(100) NOT NULL,
  deskripsi VARCHAR(255) NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE pendaftaran (
  id_pendaftaran INT AUTO_INCREMENT PRIMARY KEY,
  no_pendaftaran VARCHAR(20) NULL UNIQUE,
  id_siswa INT NOT NULL,
  id_jurusan INT NOT NULL,
  tahun_ajaran VARCHAR(9) NOT NULL,
  tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Menunggu','Diverifikasi','Ditolak') NOT NULL DEFAULT 'Menunggu',
  catatan TEXT NULL,
  UNIQUE KEY uq_siswa_tahun (id_siswa, tahun_ajaran),
  FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE RESTRICT,
  FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan) ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO jurusan (kode_jurusan,nama_jurusan,deskripsi) VALUES
 ('RPL','Rekayasa Perangkat Lunak','Pemrograman, web, dan aplikasi mobile.'),
 ('TKJ','Teknik Komputer dan Jaringan','Jaringan, server, dan keamanan siber.'),
 ('AKL','Akuntansi dan Keuangan Lembaga','Pembukuan, pajak, dan laporan keuangan.'),
 ('TBG','Tata Boga','Kuliner, pastry, dan manajemen dapur.'),
 ('TKR','Teknik Kendaraan Ringan','Servis mesin dan kelistrikan otomotif.');

-- Admin awal: admin@smk.id / admin123 (segera ganti setelah login)
INSERT INTO users (nama,email,password,role) VALUES ('Admin PPDB','admin@smk.id','$2b$10$lAWh7hOqM/1P1xQGyy8Ww.hiL/FJhfD8DkGKdjS6QpymrGJfidcQ2','admin');
