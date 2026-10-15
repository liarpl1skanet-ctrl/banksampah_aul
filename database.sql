CREATE DATABASE IF NOT EXISTS banksampah_aul CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE banksampah_aul;

DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS pengepul;
DROP TABLE IF EXISTS sampah;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','nasabah','user','pengepul') NOT NULL DEFAULT 'user',
    saldo DECIMAL(12,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE sampah (
    id_sampah INT AUTO_INCREMENT PRIMARY KEY,
    jenis_sampah VARCHAR(100) NOT NULL,
    harga_per_kg DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok_kg DECIMAL(10,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE pengepul (
    id_pengepul INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengepul VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_sampah INT NOT NULL,
    id_pengepul INT NULL,
    tipe ENUM('setor','jual','tarik') NOT NULL DEFAULT 'setor',
    berat_kg DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_rp DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
    tanggal DATE NOT NULL,
    CONSTRAINT fk_transaksi_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_transaksi_sampah FOREIGN KEY (id_sampah) REFERENCES sampah(id_sampah) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_transaksi_pengepul FOREIGN KEY (id_pengepul) REFERENCES pengepul(id_pengepul) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO user (nama,username,password,role,saldo) VALUES
('Administrator','admin','123','admin',0),
('Aulia Rahmawati','aul','123','nasabah',15000),
('Pengguna Umum','user','123','user',0),
('Pengepul Demo','pengepul','123','pengepul',0);

INSERT INTO sampah (jenis_sampah,harga_per_kg,stok_kg) VALUES
('Botol Plastik',3000,25),
('Kardus',2500,40),
('Kertas',2000,30);

INSERT INTO pengepul (nama_pengepul,username,password,alamat,no_hp) VALUES
('Pengepul Jaya','pengepul1','123','Magetan','081234567890');

INSERT INTO transaksi (id_user,id_sampah,id_pengepul,tipe,berat_kg,total_rp,status,tanggal) VALUES
(2,1,1,'setor',5,15000,'disetujui',CURDATE()),
(2,2,1,'setor',3,12000,'disetujui',DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
(2,3,1,'setor',4,12000,'menunggu',DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(3,1,1,'setor',2,14000,'ditolak',DATE_SUB(CURDATE(), INTERVAL 7 DAY));
