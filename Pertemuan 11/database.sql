CREATE DATABASE IF NOT EXISTS db_ppdb_smk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_ppdb_smk;

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
  kuota INT NOT NULL DEFAULT 30,
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
  status ENUM('Menunggu','Diverifikasi','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu',
  catatan TEXT NULL,
  UNIQUE KEY uq_siswa_tahun (id_siswa, tahun_ajaran),
  FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE RESTRICT,
  FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE berkas (
  id_berkas INT AUTO_INCREMENT PRIMARY KEY,
  id_pendaftaran INT NOT NULL,
  jenis_berkas VARCHAR(30) NOT NULL,
  nama_file VARCHAR(80) NOT NULL,
  nama_asli VARCHAR(150) NOT NULL,
  tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_berkas (id_pendaftaran, jenis_berkas),
  FOREIGN KEY (id_pendaftaran) REFERENCES pendaftaran(id_pendaftaran) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE riwayat_status (
  id_riwayat INT AUTO_INCREMENT PRIMARY KEY,
  id_pendaftaran INT NOT NULL,
  status_sebelumnya VARCHAR(20) NULL,
  status_baru VARCHAR(20) NOT NULL,
  catatan TEXT NULL,
  id_admin INT NULL,
  tanggal_perubahan DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pendaftaran) REFERENCES pendaftaran(id_pendaftaran) ON DELETE CASCADE,
  FOREIGN KEY (id_admin) REFERENCES users(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabel tambahan untuk Pengaturan (tahun ajaran, periode, pengumuman)
CREATE TABLE pengaturan (
  kunci VARCHAR(30) PRIMARY KEY,
  nilai VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

INSERT INTO pengaturan VALUES
 ('tahun_ajaran','2026/2027'),('buka','2026-10-01'),('tutup','2026-12-15'),
 ('tgl_pengumuman','2026-12-20'),('pengumuman_aktif','0');

INSERT INTO jurusan (kode_jurusan,nama_jurusan,kuota,deskripsi) VALUES
 ('RPL','Rekayasa Perangkat Lunak',36,'Pemrograman, web, dan aplikasi mobile.'),
 ('TKJ','Teknik Komputer dan Jaringan',36,'Jaringan, server, dan keamanan siber.'),
 ('AKL','Akuntansi dan Keuangan Lembaga',36,'Pembukuan, pajak, dan laporan keuangan.'),
 ('TBG','Tata Boga',32,'Kuliner, pastry, dan manajemen dapur.'),
 ('TKR','Teknik Kendaraan Ringan',32,'Servis mesin dan kelistrikan otomotif.');

-- Admin awal: admin@smk.id / admin123 (segera ganti setelah login)
INSERT INTO users (nama,email,password,role) VALUES
 ('Admin PPDB','admin@smk.id','$2b$10$lAWh7hOqM/1P1xQGyy8Ww.hiL/FJhfD8DkGKdjS6QpymrGJfidcQ2','admin');
