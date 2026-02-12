# 🔧 Panduan Command MySQL di PowerShell

## ⚠️ Penting: Perbedaan PowerShell vs CMD/Bash

PowerShell **TIDAK** mendukung operator `<` untuk input redirection seperti di Linux/CMD.

### ❌ SALAH (Tidak Bekerja di PowerShell):
```powershell
mysql -u root -p < db_rtrw.sql
```

### ✅ BENAR (Cara PowerShell):
```powershell
Get-Content "db_rtrw.sql" | mysql -u root -p
```

---

## 📍 Lokasi MySQL di Sistem Anda

MySQL terdeteksi di: **`C:\xampp\mysql\bin\mysql.exe`**

Jika command `mysql` tidak dikenali, gunakan path lengkap:
```powershell
& "C:\xampp\mysql\bin\mysql.exe"
```

---

## 🚀 Command Penting

### 1. **Import Database**
```powershell
# Dengan password
Get-Content "c:\Users\Lenovo\Downloads\website pemerintahan\website pemerintahan\database\db_rtrw.sql" | & "C:\xampp\mysql\bin\mysql.exe" -u root -p

# Tanpa password (default XAMPP)
Get-Content "c:\Users\Lenovo\Downloads\website pemerintahan\website pemerintahan\database\db_rtrw.sql" | & "C:\xampp\mysql\bin\mysql.exe" -u root
```

### 2. **Cek Database yang Ada**
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "SHOW DATABASES;"
```

### 3. **Cek Tabel di Database**
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE db_rtrw; SHOW TABLES;"
```

### 4. **Cek Data User (Login Credentials)**
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE db_rtrw; SELECT username, role FROM users;"
```

### 5. **Cek Statistik Warga per RT**
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE db_rtrw; SELECT * FROM view_statistik_warga_per_rt;"
```

### 6. **Login ke MySQL Console**
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root
```
Setelah masuk, Anda bisa menjalankan query:
```sql
USE db_rtrw;
SELECT * FROM warga;
```

### 7. **Backup Database**
```powershell
& "C:\xampp\mysql\bin\mysqldump.exe" -u root db_rtrw | Out-File -FilePath "backup_db_rtrw_$(Get-Date -Format 'yyyyMMdd').sql" -Encoding utf8
```

### 8. **Restore Database dari Backup**
```powershell
Get-Content "backup_db_rtrw.sql" | & "C:\xampp\mysql\bin\mysql.exe" -u root db_rtrw
```

---

## 🎯 Menambahkan MySQL ke PATH (Opsional)

Agar bisa menggunakan command `mysql` langsung tanpa path lengkap:

### Cara 1: Temporary (Hanya untuk sesi PowerShell saat ini)
```powershell
$env:Path += ";C:\xampp\mysql\bin"
```

Setelah itu bisa langsung:
```powershell
mysql -u root
```

### Cara 2: Permanent (Berlaku selamanya)
1. Buka **System Properties** → **Environment Variables**
2. Di **System Variables**, pilih **Path** → **Edit**
3. Klik **New** dan tambahkan: `C:\xampp\mysql\bin`
4. Klik **OK** semua
5. **Restart PowerShell**

---

## 📊 Quick Test Query

```powershell
# Test koneksi dan data
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "
USE db_rtrw;
SELECT 'Database Status' as Info, COUNT(*) as 'Total Users' FROM users
UNION ALL
SELECT 'Total Warga', COUNT(*) FROM warga
UNION ALL
SELECT 'Total KK', COUNT(*) FROM kepala_keluarga
UNION ALL
SELECT 'Total RT', COUNT(*) FROM rt;
"
```

---

## 🔐 Status Import Database Anda

✅ **Database berhasil dibuat**: `db_rtrw`  
✅ **Total tabel**: 24 tabel + 4 VIEW  
✅ **Data sample**: Sudah terisi  
✅ **User login**: 12 user sudah ada  
✅ **Statistik RT 05**: 5 KK, 17 warga

### Login Credentials yang Tersedia:
| Username         | Password     | Role   |
|------------------|--------------|--------|
| admin            | admin123     | admin  |
| rw05             | password123  | rw     |
| rt01-rt05        | password123  | rt     |
| budi_santoso     | password123  | warga  |
| siti_nurhaliza   | password123  | warga  |
| ahmad_gunawan    | password123  | warga  |

---

## 💡 Tips PowerShell

### 1. Copy Output ke Clipboard
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE db_rtrw; SELECT * FROM users;" | Set-Clipboard
```

### 2. Export ke CSV
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE db_rtrw; SELECT * FROM warga;" | Out-File -FilePath "warga.csv" -Encoding utf8
```

### 3. Jalankan Multiple Commands
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e @"
USE db_rtrw;
SELECT COUNT(*) as total_warga FROM warga;
SELECT COUNT(*) as total_kk FROM kepala_keluarga;
SELECT COUNT(*) as total_pengajuan FROM pengajuan_surat;
"@
```

---

## 🆘 Troubleshooting

### Error: "mysql is not recognized"
**Solusi**: Gunakan path lengkap `C:\xampp\mysql\bin\mysql.exe`

### Error: "Access denied for user"
**Solusi**: Cek username dan password. Untuk XAMPP default, root biasanya tanpa password.

### Error: "Unknown database 'db_rtrw'"
**Solusi**: Database belum diimport. Jalankan command import di atas.

### XAMPP MySQL Tidak Jalan
**Solusi**: 
```powershell
# Start MySQL via XAMPP Control Panel atau:
Start-Process "C:\xampp\xampp-control.exe"
```

---

## 📚 Referensi

- [README_DATABASE.md](README_DATABASE.md) - Dokumentasi database lengkap
- [config.php](config.php) - Contoh koneksi PHP
- [api.php](api.php) - REST API endpoint

---

**Last Updated**: 30 Desember 2025
