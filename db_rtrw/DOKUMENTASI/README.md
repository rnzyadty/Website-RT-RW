# Website RT/RW - Sistem Informasi Warga

Website realistis untuk RT 05 RW 05 Kelurahan Maju Jaya. Dirancang untuk memudahkan komunikasi dan administrasi warga dengan fitur yang disesuaikan untuk setiap role (Warga, RT, RW).

---

## 🎯 Fitur Utama

### ✅ Sistem Login Satu Pintu
- 3 Role berbeda: **Warga**, **RT**, **RW**
- Validasi username & password
- Session management dengan localStorage
- Demo credentials sudah tersedia untuk testing

### 🏠 Dashboard Warga
- **Status Pengajuan Surat**: Track surat (pending, selesai, ditolak)
- **Riwayat Iuran**: Lihat riwayat pembayaran dan saldo
- **Pengumuman Terbaru**: Update penting dari RT/RW
- **Form Aduan**: Kirim keluhan, aspirasi, atau laporan
- **Interface**: Sederhana, user-friendly, fokus pada kebutuhan pribadi

### 🏢 Dashboard RT
- **Daftar Pengajuan Surat**: Validasi permohonan dari warga
- **Manajemen Aduan**: Track dan tandai status keluhan warga
- **Buku Kas RT**: Catat pemasukan dan pengeluaran
- **Data Warga**: Lihat jumlah KK dan total jiwa
- **Tugas Harian**: Checklist prioritas pekerjaan hari ini
- **Interface**: Banyak list & status, fokus pada "apa yang harus dikerjakan"

### 🏛️ Dashboard RW
- **Rekap Permohonan**: Ringkasan dari semua RT
- **Keuangan per RT**: Monitor kesehatan keuangan setiap RT
- **Statistik Warga**: Jumlah KK per RT
- **Validasi Laporan**: Approval laporan bulanan RT
- **Interface**: Ringkasan & rekap, grafik ringan, fokus monitoring

### 📄 Halaman Publik (Tanpa Login)
- **Beranda**: Informasi umum RT/RW
- **Profil & Struktur**: Data pengurus dan visi-misi
- **Pengumuman**: Daftar lengkap pengumuman publik
- **Galeri Kegiatan**: Dokumentasi acara dan kegiatan

---

## 📁 Struktur File

```
website-rtrw/
├── index.html                      # Halaman Login
├── assets/
│   ├── style.css                   # CSS umum (warna, typography, layout)
│   └── dashboard.css               # CSS khusus dashboard
├── pages/
│   ├── dashboard-warga.html        # Dashboard Warga
│   ├── dashboard-rt.html           # Dashboard RT
│   ├── dashboard-rw.html           # Dashboard RW
│   ├── beranda.html                # Halaman Publik - Beranda
│   ├── profil.html                 # Halaman Publik - Profil
│   ├── pengumuman.html             # Halaman Publik - Pengumuman
│   └── galeri.html                 # Halaman Publik - Galeri
└── DOKUMENTASI.md                  # Dokumentasi lengkap
```

---

## 🚀 Cara Menggunakan

### 1. **Buka Website**
- Buka file `index.html` dengan browser (Chrome, Firefox, Safari, Edge)
- Atau simpan ke server web dan akses via URL

### 2. **Login**
Gunakan salah satu akun demo:

**Sebagai Warga:**
- Username: `budi` | Password: `12345`
- Username: `siti` | Password: `12345`
- Username: `ahmad` | Password: `12345`

**Sebagai RT:**
- Username: `rahmat` | Password: `12345`
- Username: `siti_rw` | Password: `12345`

**Sebagai RW:**
- Username: `suryanto` | Password: `12345`
- Username: `hendra` | Password: `12345`

### 3. **Jelajahi Fitur**
- Setiap role akan diarahkan ke dashboard yang berbeda
- Gunakan menu untuk navigasi antar halaman
- Coba interact dengan button dan form

---

## 🎨 Desain & Styling

### Warna Utama
- **Hijau Tua**: `#2D4A3C` - Warna primary/brand
- **Coklat Tua**: `#8B7355` - Warna accent
- **Krem**: `#F5F3EF` - Background light
- **Abu-abu**: `#6B6B6B` - Text color

### Font
- System fonts: Segoe UI, Roboto, Arial
- Readable, simple, tidak dekoratif

### Layout
- Tidak simetris berlebihan
- Generous spacing
- Seperti papan pengumuman RT nyata

---

## 📊 Data & Session

### Data Demo
Semua data tersimpan di **localStorage** browser:
- `userSession`: Data user yang login
- `aduanList`: Daftar aduan yang dikirim warga

### Cara Reset Data
```javascript
localStorage.clear(); // Clear semua data
```

---

## 🔧 Customization

### Mengubah Nama RT/RW
Edit di file HTML: `<h1>RT 05 KELURAHAN MAJU JAYA</h1>`

### Mengubah Warna Brand
Edit CSS variables di `assets/style.css`:
```css
:root {
  --warna-utama: #2D4A3C;  /* Ubah ke warna Anda */
  --warna-accent: #8B7355;
  /* ... */
}
```

### Menambah User Baru
Edit array `demoUsers` di `index.html`:
```javascript
const demoUsers = {
  warga: [
    { username: 'budi', password: '12345', name: 'Budi Santoso', ... },
    { username: 'user-baru', password: 'password', name: 'Nama Baru', ... }
  ],
  // ...
}
```

### Menambah Pengumuman
Edit HTML di `pages/pengumuman.html` dengan struktur yang sama

---

## 🔐 Keamanan (Penting!)

⚠️ **UNTUK DEVELOPMENT ONLY**

Saat ini:
- Password disimpan plain text (TIDAK AMAN)
- Session di localStorage (bisa diakses JavaScript)
- Tidak ada enkripsi

**Untuk Production, harus ditambahkan:**
- Backend API (Node.js, Laravel, PHP)
- Database (MySQL, PostgreSQL)
- Password hashing (bcrypt)
- JWT/Session tokens yang aman
- HTTPS
- Validation & authentication proper

---

## 🚀 Upgrade ke Backend

### Langkah-langkah
1. **Setup Backend** (Node.js + Express, Laravel, atau PHP)
2. **Buat API Endpoint**:
   - POST `/api/login` - Validasi login
   - GET `/api/user` - Ambil data user
   - GET `/api/pengajuan` - Ambil daftar pengajuan
   - POST `/api/aduan` - Submit aduan baru
   - Dll.

3. **Ganti fetch data**:
   ```javascript
   // Sebelum (localStorage)
   const user = JSON.parse(localStorage.getItem('userSession'));
   
   // Sesudah (API)
   const response = await fetch('/api/user', {
     headers: { 'Authorization': `Bearer ${token}` }
   });
   const user = await response.json();
   ```

4. **Database schema** (contoh):
   ```sql
   CREATE TABLE users (
     id INT PRIMARY KEY,
     username VARCHAR(100),
     password VARCHAR(255),
     role ENUM('warga', 'rt', 'rw'),
     created_at TIMESTAMP
   );
   
   CREATE TABLE pengajuan_surat (
     id INT PRIMARY KEY,
     user_id INT,
     jenis_surat VARCHAR(100),
     status ENUM('pending', 'approved', 'rejected'),
     created_at TIMESTAMP
   );
   // ... table lainnya
   ```

---

## 📱 Responsive Design

Website ini responsif dan bisa diakses dari:
- ✅ Desktop (optimal)
- ✅ Tablet
- ✅ Mobile (dengan beberapa penyesuaian)

Untuk improvement mobile, gunakan CSS media queries yang sudah ada di `assets/style.css`.

---

## 🐛 Troubleshooting

### Tidak bisa login
- Cek apakah username & password benar (case-sensitive)
- Cek browser console (F12) untuk error message
- Clear cache browser: Ctrl+Shift+Delete

### Data hilang setelah refresh
- Itu normal (data hanya di localStorage browser session)
- Data akan muncul lagi jika Anda login dengan akun yang sama

### CSS tidak muncul
- Pastikan file `assets/style.css` dan `assets/dashboard.css` ada di folder yang benar
- Cek path relatif di HTML
- Buka browser console untuk melihat error

---

## 📞 Kontak & Support

**Untuk pertanyaan atau masukan:**
- Hubungi Ketua RT: 0812-3456-7890
- Email: rt05@example.com
- Datang langsung ke rumah Ketua RT

---

## 📄 Lisensi

Website ini dibuat untuk keperluan RT/RW Kelurahan Maju Jaya. Silakan modifikasi sesuai kebutuhan.

---

## ✨ Fitur Tambahan yang Bisa Dikembangkan

- [ ] **Chat/Messaging**: Komunikasi real-time antar warga
- [ ] **Payment Gateway**: Pembayaran iuran online
- [ ] **Mobile App**: Aplikasi mobile (React Native, Flutter)
- [ ] **Email Notifications**: Notifikasi via email
- [ ] **WhatsApp Bot**: Integrasi WhatsApp untuk pengumuman
- [ ] **QR Code**: QR untuk pembayaran atau share info
- [ ] **Event Calendar**: Kalender kegiatan
- [ ] **Photo Archive**: Galeri foto lebih lengkap
- [ ] **Voting System**: Pemungutan suara untuk keputusan
- [ ] **Analytics**: Dashboard statistik & laporan

---

## 🎓 Catatan Developer

Website ini dibuat dengan:
- HTML5 vanilla (tanpa framework)
- CSS3 (tanpa Bootstrap/Tailwind)
- JavaScript vanilla (tanpa jQuery/React)

**Keuntungan:**
- ✅ Tidak perlu build process
- ✅ Cepat loading
- ✅ Mudah di-maintain
- ✅ Mudah di-customize
- ✅ Cocok untuk pembelajaran

**Kekurangan:**
- ❌ Belum scalable untuk production besar
- ❌ Perlu backend untuk data real
- ❌ UI/UX bisa lebih polished

---

## 🎯 Visi Website

Website ini dirancang untuk:
1. **Memudahkan warga** dalam hal surat-menyurat dan pembayaran
2. **Mempermudah RT** dalam manajemen administratif
3. **Membantu RW** dalam monitoring dan rekap data
4. **Meningkatkan transparansi** komunikasi antar warga
5. **Menjadi stepping stone** untuk digitalisasi RT/RW

Semoga website ini bermanfaat untuk kemajuan komunitas RT/RW! 🙏

---

**Created with ❤️ for RT 05 RW 05 Kelurahan Maju Jaya**
