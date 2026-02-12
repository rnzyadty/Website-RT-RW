# Website RT/RW Sistem Informasi Warga

## SITEMAP

```
website-rtrw/
├── index.html (Login)
├── pages/
│   ├── beranda.html (Halaman publik)
│   ├── profil.html (Struktur pengurus)
│   ├── pengumuman.html (Daftar pengumuman)
│   ├── galeri.html (Galeri kegiatan)
│   ├── dashboard-warga.html
│   ├── dashboard-rt.html
│   └── dashboard-rw.html
├── assets/
│   ├── style.css
│   ├── dashboard.css
│   └── script.js
└── data/
    └── sample-data.json
```

---

## WIREFRAME BERBASIS TEKS

### 1. HALAMAN LOGIN (index.html)
```
┌─────────────────────────────────────────┐
│                                         │
│    RT 05 KELURAHAN MAJU JAYA           │
│    Login Sistem Warga                  │
│                                         │
│  ┌─────────────────────────────────┐  │
│  │ Email/Username                  │  │
│  │ [________________________]       │  │
│  │                                 │  │
│  │ Password                        │  │
│  │ [________________________]       │  │
│  │                                 │  │
│  │ Login Sebagai:                  │  │
│  │ ○ Warga    ○ RT    ○ RW        │  │
│  │                                 │  │
│  │        [LOGIN]                  │  │
│  └─────────────────────────────────┘  │
│                                         │
│  Lupa password? | Hubungi pengurus      │
│                                         │
└─────────────────────────────────────────┘
```

### 2. DASHBOARD WARGA
```
┌────────────────────────────────────────────────────────┐
│ WARGA: Budi Santoso (ID: RW05/001)  [Logout]           │
├────────────────────────────────────────────────────────┤
│                                                         │
│ ═══ PENGAJUAN SURAT SAYA ═══════════════════════════   │
│                                                         │
│ [Surat Keterangan Usaha] - Diajukan 2025-01-15        │
│   Status: ⏳ PROSES (tunggu RT)                         │
│   Riwayat: Dikirim ke RT → Tunggu validasi RW         │
│                                                         │
│ [Surat Domisili] - Diajukan 2025-01-10                │
│   Status: ✓ SELESAI (ambil di kantor RT)              │
│                                                         │
│ [Surat Izin Usaha] - Diajukan 2024-12-20              │
│   Status: ✓ SELESAI (sudah diambil)                   │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ IURAN BULANAN ═══════════════════════════════════  │
│                                                         │
│ Bulan Ini (Januari 2025): Rp 50.000                   │
│ Status: Belum bayar - [ Bayar ] [ Rincian ]           │
│                                                         │
│ Riwayat:                                               │
│ Desember 2024 - Rp 50.000 ✓ LUNAS                    │
│ November 2024 - Rp 50.000 ✓ LUNAS                    │
│ Oktober 2024  - Rp 50.000 ✓ LUNAS                    │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ PENGUMUMAN TERBARU ═════════════════════════════   │
│                                                         │
│ 📢 Rapat Rutin RT 05 - Jum'at 24 Januari pukul 19:00 │
│                                                         │
│ 📢 Perbaikan Jalan Gang Mawar - Minggu 25 Januari     │
│    Iuran tambahan Rp 20.000 untuk renovasi             │
│                                                         │
│ 📢 Undian Akhir Tahun RT/RW - 15 Januari              │
│    Hadiah utama sepeda motor                           │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ KIRIM ADUAN / ASPIRASI ═════════════════════════   │
│                                                         │
│ Judul: [____________________________]                  │
│ Kategori: [Infrastruktur▼]                            │
│ Deskripsi: [_________________________              │
│            |_______________________]                  │
│                        [ KIRIM ]                      │
│                                                         │
└────────────────────────────────────────────────────────┘
```

### 3. DASHBOARD RT
```
┌────────────────────────────────────────────────────────┐
│ RT 05 - PENGURUS: Rahmat (Ketua RT)  [Logout]         │
├────────────────────────────────────────────────────────┤
│                                                         │
│ ═══ HARI INI (25 Januari 2025) ═════════════════════   │
│ Tugas Prioritas:                                       │
│ [ ] Validasi 3 permohonan surat                       │
│ [ ] Input kas RT perbaikan jalan                      │
│ [ ] Hubungi Dina (tanggungan iuran 3 bulan)           │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ PERMOHONAN SURAT (3 Pending) ═══════════════════   │
│                                                         │
│ 1. [Budi Santoso] Surat Keterangan Usaha              │
│    Diajukan: 2025-01-15 | Status: ⏳ Tunggu validasi  │
│    [ Setujui ] [ Tolak ] [ Lebih Info ]               │
│                                                         │
│ 2. [Siti Nurhaliza] Surat Domisili                    │
│    Diajukan: 2025-01-18 | Status: ⏳ Tunggu validasi  │
│    [ Setujui ] [ Tolak ] [ Lebih Info ]               │
│                                                         │
│ 3. [Ahmad Gunawan] Surat Izin Usaha                   │
│    Diajukan: 2025-01-20 | Status: ⏳ Tunggu validasi  │
│    [ Setujui ] [ Tolak ] [ Lebih Info ]               │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ ADUAN / KELUHAN WARGA ═════════════════════════    │
│                                                         │
│ [NEW] Banjir di Gang Murai - Ditolak 2025-01-24      │
│       "Ada saluran air yang macet"                    │
│       Status: ⚠️ PERLU FOLLOW UP                      │
│       Tindakan: ☐ Perbaiki ☐ Tunggu RW                │
│                                                         │
│ Jalan berlubang depan toko Haji Joni - 2025-01-20    │
│   Status: ✓ DITINDAK LANJUTI                          │
│   Tindakan: Lapor RW untuk renovasi                   │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ BUKU KAS RT ════════════════════════════════════   │
│                                                         │
│ Bulan: [Januari 2025]                                 │
│ Saldo Awal: Rp 2.500.000                              │
│                                                         │
│ [Input Pemasukan Baru]   [Input Pengeluaran Baru]    │
│                                                         │
│ Pemasukan:                                             │
│ 20 Januari - Iuran Rutin - Rp 850.000 (17 KK)        │
│ 22 Januari - Iuran Tambahan - Rp 400.000 (20 KK)     │
│                                                         │
│ Pengeluaran:                                           │
│ 15 Januari - Hadiah Undian - Rp 500.000              │
│ 22 Januari - Beli Kabel Jalan - Rp 150.000           │
│                                                         │
│ Saldo Akhir: Rp 3.100.000                             │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ DATA WARGA RT 05 ════════════════════════════════  │
│                                                         │
│ Total KK: 45 | Jiwa: 156 | Kepala Keluarga: 45      │
│                                                         │
│ [ Lihat Daftar Warga ] [ Cetak Data ]                │
│                                                         │
└────────────────────────────────────────────────────────┘
```

### 4. DASHBOARD RW
```
┌────────────────────────────────────────────────────────┐
│ RW 05 - KOORDINATOR: Suryanto (Ketua RW)  [Logout]    │
├────────────────────────────────────────────────────────┤
│                                                         │
│ ═══ RINGKASAN MINGGU INI ════════════════════════════  │
│ Permohonan Surat: 12 (8 disetujui, 2 ditolak, 2 proses)
│ Aduan Warga: 5 masuk (3 selesai, 2 proses)            │
│ Iuran Terkumpul: Rp 4.2 Juta (85% pembayaran)         │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ REKAP PERMOHONAN SURAT (12 TOTAL) ══════════════  │
│                                                         │
│ RT 01 │ ■■■■ 4 permohonan  │ 3 selesai ✓  │ 1 proses │
│ RT 02 │ ■■■ 3 permohonan   │ 3 selesai ✓  │ 0 proses │
│ RT 03 │ ■■■ 3 permohonan   │ 2 selesai ✓  │ 1 proses │
│ RT 04 │ ■■ 2 permohonan    │ 0 selesai    │ 2 proses │
│ RT 05 │ ■■ 0 permohonan    │ 0 selesai    │ 0 proses │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ KEUANGAN PER RT (BULAN JANUARI) ═════════════════  │
│                                                         │
│ RT 01  Pemasukan: Rp 1.050.000  Pengeluaran: Rp 500.000
│        Saldo: Rp 2.100.000 | Status: ✓ NORMAL        │
│                                                         │
│ RT 02  Pemasukan: Rp 950.000    Pengeluaran: Rp 600.000
│        Saldo: Rp 1.800.000 | Status: ⚠️ PERLU CEK    │
│                                                         │
│ RT 03  Pemasukan: Rp 850.000    Pengeluaran: Rp 700.000
│        Saldo: Rp 1.200.000 | Status: ✓ NORMAL        │
│                                                         │
│ RT 04  Pemasukan: Rp 1.200.000  Pengeluaran: Rp 400.000
│        Saldo: Rp 3.500.000 | Status: ✓ NORMAL        │
│                                                         │
│ RT 05  Pemasukan: Rp 1.100.000  Pengeluaran: Rp 800.000
│        Saldo: Rp 3.100.000 | Status: ✓ NORMAL        │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ STATISTIK WARGA ════════════════════════════════   │
│                                                         │
│ Total RW: 231 Kepala Keluarga                          │
│ RT 01: 45 KK  │ RT 02: 44 KK  │ RT 03: 48 KK         │
│ RT 04: 46 KK  │ RT 05: 45 KK  │ (per data terbaru)   │
│                                                         │
│ ─────────────────────────────────────────────         │
│
│ ═══ VALIDASI LAPORAN RT ════════════════════════════   │
│                                                         │
│ [Laporan RT 01] Bulan Desember - Status: ✓ DISETUJUI │
│ [Laporan RT 02] Bulan Desember - Status: ⏳ PENDING   │
│ [Laporan RT 03] Bulan Desember - Status: ✓ DISETUJUI │
│ [Laporan RT 04] Bulan Desember - Status: ⏳ PENDING   │
│ [Laporan RT 05] Bulan Desember - Status: ✓ DISETUJUI │
│                                                         │
│ [ Validasi ] [ Kembalikan ] [ Cetak ]                 │
│                                                         │
└────────────────────────────────────────────────────────┘
```

---

## PENJELASAN PERBEDAAN SETIAP DASHBOARD

### Dashboard Warga
- **Fokus**: Urusan pribadi dan kebutuhan warga
- **Ciri**: Sederhana, minimal action, user-centric
- **Konten Utama**: Status pengajuan pribadi, iuran, pengumuman, aduan
- **Tone**: Bahasa manusia, seperti membaca catatan RT di kertas

### Dashboard RT
- **Fokus**: Operasional harian RT, tugas ketua RT
- **Ciri**: Banyak list, status tracking, fokus "apa yang harus dikerjakan hari ini"
- **Konten Utama**: Daftar pengajuan surat (untuk divalidasi), aduan warga, buku kas, data warga
- **Tone**: Seperti mencatat di buku kas atau daftar todo pengurusRT

### Dashboard RW
- **Fokus**: Monitoring dan rekap seluruh RW, validation layer
- **Ciri**: Ringkasan, perbandingan antar RT, grafik sederhana
- **Konten Utama**: Rekap pengajuan, keuangan RT, statistik warga, validasi laporan
- **Tone**: Seperti laporan bulanan atau arsip pengurus RW

---

## GAYA DESAIN

- **Warna**: Krem (#F5F3EF), Hijau Tua (#2D4A3C), Abu-abu (#6B6B6B), Putih (#FFFFFF)
- **Font**: System fonts (Segoe UI, Roboto, Arial) - sederhana dan readable
- **Spacing**: Generous, tidak crowded
- **Typography**: Heading besar, body text cukup besar (16px+) untuk dibaca di pintu
- **Icons**: Minimal, gunakan emoji atau simbol sederhana
- **Layout**: Tidak simetris berlebihan, seperti papan pengumuman nyata

---

## TEKNIS

- **Frontend**: HTML5, CSS3, JavaScript Vanilla
- **Storage**: LocalStorage untuk demo (bisa diganti API nanti)
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)
- **Responsive**: Mobile-first, tapi fokus desktop untuk kecanggihan admin
- **Ready for Backend**: Struktur data JSON siap dikoneksi ke API

---

## KONVENSI PENAMAAN

```
/pages/
├── dashboard-{role}.html        # Dashboard per role
├── beranda.html                 # Halaman umum
├── profil.html
├── pengumuman.html
└── galeri.html

/assets/
├── style.css                    # Styling umum
├── dashboard.css                # Styling dashboard
└── script.js                    # Login logic & routing

/data/
└── sample-data.json             # Mock data untuk demo
```

---

## FITUR CORE

1. **Login System**: Validasi simple, 3 role berbeda
2. **Dashboard Dynamic**: Redirect ke dashboard sesuai role
3. **Session Management**: Simpan user info di localStorage
4. **Data Mock**: JSON data untuk demo tanpa backend
5. **Responsive Design**: Bisa diakses dari mobile (admin), tapi optimal di desktop

---

## CATATAN IMPLEMENTASI

- Setiap dashboard adalah file HTML terpisah
- CSS di-share (style.css) tapi ada overrides di dashboard.css
- JavaScript handle login logic dan session
- Data disimpan di localStorage dulu (mudah di-upgrade ke backend)
- Tidak ada external CDN - semua vanilla code
