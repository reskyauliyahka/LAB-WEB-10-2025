-- Membuat database
CREATE DATABASE IF NOT EXISTS db_manajemen_proyek;
USE db_manajemen_proyek;

-- 1. Tabel users
-- Password disarankan menggunakan hashing (misalnya, bcrypt).
-- Di sini menggunakan 'password123' untuk semua user, di-hash dengan PASSWORD() atau setara di aplikasi.
-- Dalam contoh ini, kita akan menggunakan 'password_hash('password123', PASSWORD_DEFAULT)' di PHP.
-- Di sini kita hanya menyimpan nilai string biasa untuk kemudahan seed,
-- ASUMSI: 'password123' akan di-hash saat dimasukkan ke DB di aplikasi nyata.
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    -- Kolom password harusnya cukup besar untuk menyimpan hash (misal 255)
    password VARCHAR(255) NOT NULL,
    role ENUM('Super Admin', 'Project Manager', 'Team Member') NOT NULL,
    -- project_manager_id untuk Team Member, merujuk ke id Project Manager mereka
    project_manager_id INT(11) NULL,
    -- Menambahkan indeks untuk kolom FOREIGN KEY
    KEY project_manager_id_idx (project_manager_id),
    -- Definisi FOREIGN KEY
    CONSTRAINT fk_manager_id
        FOREIGN KEY (project_manager_id)
        REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- 2. Tabel projects
CREATE TABLE projects (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_proyek VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    -- manager_id merujuk ke id Project Manager di tabel users
    manager_id INT(11) NOT NULL,
    KEY manager_id_idx (manager_id),
    CONSTRAINT fk_project_manager
        FOREIGN KEY (manager_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- 3. Tabel tasks
CREATE TABLE tasks (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_tugas VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    status ENUM('belum', 'proses', 'selesai') DEFAULT 'belum',
    -- project_id merujuk ke id proyek di tabel projects
    project_id INT(11) NOT NULL,
    -- assigned_to merujuk ke id Team Member di tabel users
    assigned_to INT(11) NOT NULL,
    KEY project_id_idx (project_id),
    KEY assigned_to_idx (assigned_to),
    CONSTRAINT fk_task_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_task_assigned_to
        FOREIGN KEY (assigned_to)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Data Awal (Seed Data)
-- Gunakan password_hash() di PHP untuk password yang aman.
-- ASUMSI: password hash untuk 'password123' adalah string panjang tertentu.
-- Di sini, saya menggunakan contoh hash (harus diganti dengan hash yang sebenarnya saat testing).
-- Jika menggunakan PHP, jalankan: echo password_hash('password123', PASSWORD_DEFAULT);
SET @admin_pass = '$2y$10$AQacsn6dEcVo65Bamc7jq.jF4Y156Pcu9xB5PHh9ImLz1O9jXqgmW'; 
SET @pm_pass = '$2y$10$TR.XWOQ0Pj6Tw0uHoR9JouYQSkptNqMGbADZoEqveaLrD/Kv0pFle';
SET @tm_pass = '$2y$10$xeDbA3MCdMufaGlMmXc3c.tB2olOZ9gCxAj.7MFQLup9i/QHFRCk6';

-- Insert Super Admin
INSERT INTO users (username, password, role) VALUES ('admin', @admin_pass, 'Super Admin');
SET @admin_id = LAST_INSERT_ID();

-- Insert Project Manager
INSERT INTO users (username, password, role) VALUES ('pm_budi', @pm_pass, 'Project Manager');
SET @pm_id = LAST_INSERT_ID();

-- Insert Team Member, di bawah Project Manager 'pm_budi'
INSERT INTO users (username, password, role, project_manager_id) VALUES ('tm_andi', @tm_pass, 'Team Member', @pm_id);
SET @tm_id = LAST_INSERT_ID();

-- Insert Contoh Proyek oleh Project Manager 'pm_budi'
INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id)
VALUES ('Website E-Commerce', 'Pengembangan platform penjualan online baru', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), @pm_id);
SET @project_id = LAST_INSERT_ID();

-- Insert Contoh Tugas pada proyek tersebut, ditugaskan ke Team Member 'tm_andi'
INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to)
VALUES ('Desain Basis Data', 'Buat skema database awal', 'proses', @project_id, @tm_id);


-- admin	admin123
-- pm_sita	pmsita123
-- tm_nadia	tmnadia123