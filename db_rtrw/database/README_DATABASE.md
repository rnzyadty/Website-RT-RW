# 📊 DATABASE SISTEM INFORMASI RT/RW

## 📋 Deskripsi
Database MySQL lengkap untuk Sistem Informasi RT/RW Kelurahan Maju Jaya dengan fitur manajemen warga, pengajuan surat, keuangan, pengumuman, aduan, dan kegiatan.

---

## 🗂️ Struktur Database

### **Total: 25 Tabel Utama**

#### 📍 **Master Data (5 Tabel)**
1. `kelurahan` - Data kelurahan
2. `rw` - Data RW (Rukun Warga)
3. `rt` - Data RT (Rukun Tetangga)
4. `kepala_keluarga` - Data Kepala Keluarga (KK)
5. `warga` - Data warga (anggota keluarga)

#### 👤 **User & Authentication (1 Tabel)**
6. `users` - Akun login (warga, RT, RW, admin)

#### 📄 **Pengajuan Surat (2 Tabel)**
7. `jenis_surat` - Master jenis surat
8. `pengajuan_surat` - Data pengajuan surat dari warga

#### 💰 **Keuangan (4 Tabel)**
9. `kategori_keuangan` - Master kategori transaksi
10. `iuran_warga` - Data iuran bulanan warga
11. `kas_rt` - Buku kas RT
12. `kas_rw` - Buku kas RW

#### 📢 **Pengumuman & Aduan (2 Tabel)**
13. `pengumuman` - Pengumuman dari RT/RW
14. `aduan` - Aduan/aspirasi dari warga

#### 🎉 **Kegiatan (3 Tabel)**
15. `kegiatan` - Data kegiatan RT/RW
16. `galeri` - Dokumentasi foto/video kegiatan
17. `rapat` - Data rapat RT/RW

#### ✅ **Manajemen (3 Tabel)**
18. `kehadiran_rapat` - Absensi rapat
19. `tugas_rt` - To-do list untuk RT
20. `log_aktivitas` - Audit trail sistem

---

## 🚀 Cara Install Database

### **1. Persiapan**
Pastikan sudah terinstall:
- MySQL Server (versi 5.7 atau lebih baru)
- MySQL Workbench / phpMyAdmin / Command Line

### **2. Import Database**

#### **Menggunakan MySQL Command Line:**
```bash
# Login ke MySQL
mysql -u root -p

# Import database
source "c:\Users\Lenovo\Downloads\website pemerintahan\website pemerintahan\database\db_rtrw.sql"

# Atau jika sudah di dalam MySQL:
mysql> source c:/Users/Lenovo/Downloads/website pemerintahan/website pemerintahan/database/db_rtrw.sql;
```

#### **Menggunakan MySQL Workbench:**
1. Buka MySQL Workbench
2. Klik **Server** → **Data Import**
3. Pilih **Import from Self-Contained File**
4. Browse file `db_rtrw.sql`
5. Klik **Start Import**

#### **Menggunakan phpMyAdmin:**
1. Buka phpMyAdmin
2. Pilih tab **Import**
3. Klik **Choose File** dan pilih `db_rtrw.sql`
4. Klik **Go**

### **3. Verifikasi**
```sql
-- Cek database sudah dibuat
SHOW DATABASES;

-- Gunakan database
USE db_rtrw;

-- Cek tabel
SHOW TABLES;

-- Cek data sample
SELECT * FROM users;
SELECT * FROM warga;
```

---

## 🔐 Credential Login (Data Sample)

Database sudah dilengkapi dengan data sample untuk testing:

| Role     | Username         | Password     | Keterangan                |
|----------|------------------|--------------|---------------------------|
| **Admin** | admin           | admin123     | Full access ke sistem     |
| **RW**   | rw05             | password123  | Ketua RW 05               |
| **RT**   | rt01             | password123  | Ketua RT 01               |
| **RT**   | rt02             | password123  | Ketua RT 02               |
| **RT**   | rt03             | password123  | Ketua RT 03               |
| **RT**   | rt04             | password123  | Ketua RT 04               |
| **RT**   | rt05             | password123  | Ketua RT 05               |
| **Warga** | budi_santoso    | password123  | Warga RT 05               |
| **Warga** | siti_nurhaliza  | password123  | Warga RT 05               |
| **Warga** | ahmad_gunawan   | password123  | Warga RT 05               |
| **Warga** | dina_nurhayati  | password123  | Warga RT 05               |
| **Warga** | eko_prasetyo    | password123  | Warga RT 05               |

⚠️ **PENTING:** Password menggunakan SHA2 hash untuk demo. Di production gunakan bcrypt!

---

## 📊 Data Sample yang Sudah Tersedia

### ✅ **Data yang Sudah Diisi:**
- 1 Kelurahan (Maju Jaya)
- 1 RW (RW 05) dengan 5 RT
- 5 Kepala Keluarga di RT 05
- 17 Warga (anggota keluarga)
- 12 User (login credentials)
- 7 Jenis Surat
- 3 Pengajuan Surat (berbagai status)
- 7 Kategori Keuangan
- Data iuran warga Oktober 2024 - Januari 2025
- 6 Transaksi kas RT 05
- 3 Pengumuman
- 3 Aduan warga
- 3 Kegiatan
- 5 Tugas RT

### 📈 **Statistik Data Sample:**
- Total RT: 5 RT
- Total KK di RT 05: 5 KK
- Total Warga di RT 05: 17 jiwa
- Total Pengajuan Surat: 3 permohonan
- Total Iuran Januari 2025: Belum bayar semua (5 KK)

---

## 🔗 Koneksi Database ke Aplikasi

### **PHP (MySQLi)**
```php
<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_rtrw';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
```

### **PHP (PDO)**
```php
<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_rtrw';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

### **Node.js (MySQL)**
```javascript
const mysql = require('mysql');

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'db_rtrw'
});

connection.connect((err) => {
  if (err) throw err;
  console.log('Connected to database!');
});
```

### **Python (MySQL Connector)**
```python
import mysql.connector

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="db_rtrw"
)

cursor = db.cursor()
print("Connected to database!")
```

---

## 📋 Query Penting

### **1. Login User**
```sql
-- Validasi login
SELECT u.*, w.nama_lengkap, rt.nomor_rt, rw.nomor_rw
FROM users u
LEFT JOIN warga w ON u.id_warga = w.id_warga
LEFT JOIN rt ON u.id_rt = rt.id_rt
LEFT JOIN rw ON u.id_rw = rw.id_rw
WHERE u.username = 'budi_santoso' 
  AND u.password = SHA2('password123', 256)
  AND u.status = 'aktif';
```

### **2. Lihat Pengajuan Surat Warga**
```sql
-- Pengajuan surat milik warga tertentu
SELECT ps.*, js.nama_surat, w.nama_lengkap
FROM pengajuan_surat ps
JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
JOIN warga w ON ps.id_warga = w.id_warga
WHERE w.id_warga = 1
ORDER BY ps.tanggal_pengajuan DESC;
```

### **3. Lihat Iuran Warga**
```sql
-- Riwayat iuran per KK
SELECT iw.*, kk.nama_kepala_keluarga
FROM iuran_warga iw
JOIN kepala_keluarga kk ON iw.id_kk = kk.id_kk
WHERE iw.id_kk = 1
ORDER BY iw.tahun DESC, iw.bulan DESC;
```

### **4. Dashboard RT - Pengajuan Pending**
```sql
-- Surat yang menunggu validasi RT
SELECT ps.*, w.nama_lengkap, js.nama_surat, kk.alamat, kk.nomor_kk
FROM pengajuan_surat ps
JOIN warga w ON ps.id_warga = w.id_warga
JOIN kepala_keluarga kk ON w.id_kk = kk.id_kk
JOIN jenis_surat js ON ps.id_jenis_surat = js.id_jenis_surat
WHERE kk.id_rt = 5 
  AND ps.status_pengajuan = 'pending'
ORDER BY ps.tanggal_pengajuan ASC;
```

### **5. Dashboard RW - Rekap per RT**
```sql
-- Statistik warga per RT
SELECT * FROM view_statistik_warga_per_rt;

-- Rekap iuran per RT
SELECT * FROM view_rekap_iuran_per_rt 
WHERE bulan = 1 AND tahun = 2025;

-- Status surat per RT
SELECT * FROM view_status_surat_per_rt;
```

### **6. Kas RT - Saldo Akhir**
```sql
-- Hitung saldo kas RT
CALL sp_hitung_saldo_kas_rt(5);

-- Atau manual:
SELECT 
    SUM(CASE WHEN jenis_transaksi = 'pemasukan' THEN nominal ELSE -nominal END) AS saldo_akhir
FROM kas_rt
WHERE id_rt = 5;
```

### **7. Laporan Iuran Bulanan**
```sql
-- Laporan iuran bulan tertentu
CALL sp_laporan_iuran_bulanan(1, 2025, 5);
```

---

## 🔍 View yang Tersedia

Database dilengkapi dengan 4 VIEW untuk mempermudah query:

### 1. **view_statistik_warga_per_rt**
Menampilkan jumlah KK dan warga per RT
```sql
SELECT * FROM view_statistik_warga_per_rt;
```

### 2. **view_rekap_iuran_per_rt**
Rekap iuran bulanan per RT
```sql
SELECT * FROM view_rekap_iuran_per_rt 
WHERE bulan = 1 AND tahun = 2025;
```

### 3. **view_status_surat_per_rt**
Status pengajuan surat per RT
```sql
SELECT * FROM view_status_surat_per_rt;
```

### 4. **view_rekap_keuangan_rt**
Rekap pemasukan dan pengeluaran RT
```sql
SELECT * FROM view_rekap_keuangan_rt 
WHERE id_rt = 5;
```

---

## ⚡ Stored Procedures

### 1. **sp_hitung_saldo_kas_rt**
Menghitung saldo kas RT
```sql
CALL sp_hitung_saldo_kas_rt(5);
```

### 2. **sp_laporan_iuran_bulanan**
Generate laporan iuran per bulan
```sql
CALL sp_laporan_iuran_bulanan(1, 2025, 5);
-- Parameter: (bulan, tahun, id_rt)
```

---

## 🛠️ Fitur Database

### ✅ **Fitur Keamanan:**
- Password di-hash (SHA2 untuk demo)
- Foreign key constraints
- Cascade delete untuk data terkait
- Soft delete dengan status field
- Log aktivitas (audit trail)

### ✅ **Fitur Performa:**
- Index pada kolom yang sering di-query
- View untuk query kompleks
- Stored procedures untuk operasi rutin

### ✅ **Fitur Integritas:**
- UNIQUE constraint untuk NIK, username, nomor KK
- CHECK constraint untuk validasi data
- DEFAULT values untuk field penting
- Timestamp otomatis (created_at, updated_at)

---

## 📝 Notes Penting

### **1. Password Hashing**
⚠️ Database ini menggunakan SHA2 untuk demo. Di production gunakan:
- **PHP:** `password_hash()` dan `password_verify()`
- **bcrypt** atau **Argon2** untuk hashing yang lebih aman

### **2. File Upload**
Field `file_path`, `bukti_bayar`, `foto_bukti`, dll hanya menyimpan nama file atau path.
Implementasi upload file dilakukan di application level.

### **3. Backup Database**
```bash
# Backup database
mysqldump -u root -p db_rtrw > backup_db_rtrw.sql

# Restore database
mysql -u root -p db_rtrw < backup_db_rtrw.sql
```

### **4. Tambah Data Warga Baru**
```sql
-- 1. Insert Kepala Keluarga
INSERT INTO kepala_keluarga (id_rt, nomor_kk, nama_kepala_keluarga, alamat, rt_rw, kelurahan, kecamatan, kota, provinsi, telepon) 
VALUES (5, '3271234567890006', 'Nama KK', 'Alamat Lengkap', '05/05', 'Maju Jaya', 'Kec. Sejahtera', 'Kota Harapan', 'Provinsi Maju', '081234567890');

-- 2. Insert Warga (anggota keluarga)
INSERT INTO warga (id_kk, nik, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, pendidikan_terakhir, pekerjaan, status_perkawinan, status_dalam_keluarga)
VALUES (6, '3271010101900018', 'Nama Warga', 'Jakarta', '1990-01-01', 'Laki-laki', 'Islam', 'S1', 'Wiraswasta', 'Kawin', 'Kepala Keluarga');

-- 3. Buat akun user (opsional)
INSERT INTO users (username, password, role, id_warga, status)
VALUES ('username_baru', SHA2('password123', 256), 'warga', 18, 'aktif');
```

### **5. Generate Iuran Bulanan Otomatis**
```sql
-- Generate iuran untuk semua KK di RT 05 untuk bulan Februari 2025
INSERT INTO iuran_warga (id_kk, bulan, tahun, nominal, jenis_iuran, status_bayar)
SELECT id_kk, 2, 2025, 50000.00, 'Iuran Rutin', 'belum'
FROM kepala_keluarga
WHERE id_rt = 5;
```

---

## 🔧 Troubleshooting

### **Error: Access Denied**
```sql
-- Buat user baru dan berikan hak akses
CREATE USER 'rtrw_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON db_rtrw.* TO 'rtrw_user'@'localhost';
FLUSH PRIVILEGES;
```

### **Error: Table doesn't exist**
Pastikan sudah import file SQL dengan benar. Jalankan ulang import.

### **Error: Foreign key constraint fails**
Pastikan data parent (RT, KK, dll) sudah ada sebelum insert data child.

---

## 📞 Support & Dokumentasi

Untuk pertanyaan lebih lanjut, silakan cek:
- [README.md](../README.md) - Dokumentasi sistem lengkap
- [DOKUMENTASI.md](../DOKUMENTASI.md) - Sitemap & wireframe
- [QUICK_START_GUIDE.md](../QUICK_START_GUIDE.md) - Panduan cepat

---

## 📄 License
Database ini dibuat untuk keperluan edukasi dan demo sistem RT/RW.

---

**Database Version:** 1.0  
**Last Updated:** 30 Desember 2025  
**Author:** System Administrator RT/RW
