# RT/RW Information System - Detailed Technical Overview

## SYSTEM ARCHITECTURE

### Three-Layer Structure

```
┌─────────────────────────────────────────────┐
│       PRESENTATION LAYER (HTML/CSS)         │
│   What users see on their screen            │
├─────────────────────────────────────────────┤
│    BUSINESS LOGIC LAYER (JavaScript)        │
│   What happens when user clicks/submits     │
├─────────────────────────────────────────────┤
│      DATABASE LAYER (PHP/MySQL)             │
│   Where all data is stored & processed      │
└─────────────────────────────────────────────┘
```

---

## PART 1: BEFORE LOGIN - PUBLIC PAGES (LAYER 1 & 2)

### File Structure for Public Pages

```
index.php              → Login/Register page
pages/
  ├─ beranda.html      → Home page (general info)
  ├─ profil.html       → Community profile & leaders
  ├─ pengumuman.html   → Read announcements
  └─ galeri.html       → View event photos
```

---

### 1. INDEX.PHP - LOGIN PAGE

**Technical Details:**

| Component | Details |
|-----------|---------|
| **Styling** | Modern gradient background, smooth animations |
| **Layout** | Centered login box, 450px wide, responsive |
| **Forms** | Two tabs: LOGIN tab & DAFTAR (Register) tab |
| **Login Fields** | Username, Password |
| **Validation** | Checks if username exists & password matches |
| **Security** | Password stored as SHA2 hash (not plain text) |

**How Login Form Works:**

```
User fills form → Clicks "Masuk" button
    ↓
JavaScript validates input (not empty)
    ↓
Sends username & password to backend
    ↓
Backend (PHP) checks database:
  - Find user with this username
  - Compare password hash
    ↓
If match: ✅ Creates session → Redirects to correct dashboard
If no match: ❌ Shows error → Stay on login page
```

**What Gets Sent to Database:**
- Username: Text field from user
- Password: Hashed with SHA2 (secret algorithm)
- Never sends plain password, only hash

**Test Accounts Available:**
1. Username: `budi_santoso` → Role: WARGA
2. Username: `rt05` → Role: RT Leader
3. Username: `rw05` → Role: RW Leader

---

### 2. BERANDA.HTML - HOME PAGE

**Technical Details:**

| Section | HTML Structure |
|---------|-----------------|
| **Header** | Contains RT/RW title, LOGIN button |
| **Hero** | Gradient background with welcome message |
| **Navigation** | Buttons to: Profil, Pengumuman, Galeri, Login |
| **Info Cards** | CSS Grid: 3-4 columns, responsive design |
| **Footer** | Contact info, copyright |

**What's in the Code:**

```html
Header (top bar)
  ├─ Title "RT 05 KELURAHAN MAJU JAYA"
  ├─ Subtitle "Sistem Informasi Warga"
  └─ LOGIN button (links to index.php)

Hero Section (welcome area)
  ├─ Large title with description
  └─ Navigation menu buttons

Info Cards (statistics boxes)
  ├─ Card 1: Total Families (45)
  ├─ Card 2: Total Residents (156)
  ├─ Card 3: Total Male (78)
  └─ Card 4: Total Female (78)

Latest Announcements Preview
  ├─ Quick snippet of latest news
  └─ "View All" button

Links to Other Pages
  ├─ "Lihat Profil" → profil.html
  ├─ "Lihat Pengumuman" → pengumuman.html
  ├─ "Lihat Galeri" → galeri.html
  └─ "Login" → index.php
```

**No Database Interaction:** All data is hardcoded for display (static page)

---

### 3. PROFIL.HTML - COMMUNITY PROFILE

**Technical Details:**

| Section | Content |
|---------|---------|
| **RT Basic Info** | RT number, RW, District, Sub-district |
| **Statistics Table** | Total KK, Total Jiwa, Males, Females |
| **Leadership Structure** | Grid of 4 people cards |

**Leadership Positions Shown:**

```
┌──────────────────────────────────────────────────────┐
│  Ketua RT (Chairperson)                              │
│  Name: Rahmat                                        │
│  Phone: 0812-3456-7890                               │
│  Period: 2023-2026                                   │
├──────────────────────────────────────────────────────┤
│  Wakil Ketua (Vice Chair) │ Sekretaris (Secretary)   │
│  Budi Santoso              │ Siti Rahayu              │
├──────────────────────────────────────────────────────┤
│  Bendahara (Treasurer)                               │
│  Dina Nurhayati                                      │
└──────────────────────────────────────────────────────┘
```

**HTML Structure for Cards:**

```html
Card container (CSS Grid)
  ├─ Ketua RT card
  │   ├─ Emoji icon 👨‍⚖️
  │   ├─ Position title
  │   ├─ Name
  │   ├─ Phone number
  │   └─ Term period
  │
  ├─ Wakil Ketua card
  │   └─ Same structure
  │
  ├─ Sekretaris card
  │   └─ Same structure
  │
  └─ Bendahara card
      └─ Same structure
```

**Data Source:** Hardcoded in HTML (static, not from database)

---

### 4. PENGUMUMAN.HTML - ANNOUNCEMENTS

**Technical Details:**

| Feature | Details |
|---------|---------|
| **Filter Buttons** | 5 buttons: All, Important, Meeting, Dues, Activities |
| **Announcement Cards** | Each card shows: Title, Date, Description |
| **Color Coding** | Yellow (⚠️), Red (🔴), Blue (ℹ️) |
| **Layout** | Vertical list of announcement boxes |

**Announcement Card Structure:**

```html
Card (colored box)
  ├─ Header (colored bar on left)
  │   └─ Title "PENTING: Rapat Rutin RT 05"
  │
  ├─ Content
  │   ├─ Date & Time
  │   ├─ Location
  │   ├─ Description
  │   └─ Bullet points of details
  │
  └─ Footer
      └─ Posted date: "23 Januari 2025"
```

**Filter Button Function:**
- Buttons are HTML elements (CSS hides/shows items)
- JavaScript filters announcements by category
- No database query (all announcements in HTML)

**Announcement Types & Colors:**

| Type | Color | Icon | Used For |
|------|-------|------|----------|
| **Penting** (Important) | Yellow | ⚠️ | Urgent announcements |
| **Rapat** (Meeting) | Orange | 📅 | Meeting schedules |
| **Iuran** (Dues) | Red | 💰 | Payment deadlines |
| **Kegiatan** (Activity) | Green | 🎉 | Community events |

---

### 5. GALERI.HTML - PHOTO GALLERY

**Technical Details:**

| Component | Details |
|-----------|---------|
| **Grid Layout** | CSS Grid: 3-4 columns, responsive |
| **Photo Cards** | Image placeholder, title, date, description |
| **Hover Effect** | Card rises up (translateY) when mouse hovers |
| **Images** | Placeholder emoji (📸) since no actual images |

**Gallery Card Structure:**

```html
Gallery Item (card)
  ├─ Image area (200px height)
  │   └─ Placeholder emoji 📸
  │
  └─ Content area
      ├─ Title: "Pengumuman Undian Akhir Tahun"
      ├─ Date: "15 Januari 2025"
      └─ Description: "Acara pengumuman pemenang..."
```

**Gallery Items Shown:**
1. Year-End Lottery Announcement - Jan 15, 2025
2. Road Repair Event - Jan 20, 2025
3. Monthly Meeting - Jan 10, 2025
4. Community Gathering - Jan 05, 2025

**Data Source:** Hardcoded HTML (no database)

---

## PART 2: AFTER LOGIN - PROTECTED PAGES

These pages require:
1. User logged in (have session)
2. User has correct role (WARGA/RT/RW)
3. Session verification on page load

---

## SESSION MANAGEMENT

### How Sessions Work

```
LOGIN PROCESS:
┌─────────────────────────────────────────────────┐
│ 1. User submits form on index.php               │
│    (username: "budi_santoso", password: "xxx")  │
│                                                 │
│ 2. Form sends to backend (PHP)                  │
│    Compares password hash in database           │
│                                                 │
│ 3. If match: Backend creates SESSION object     │
│    session['logged_in'] = true                  │
│    session['username'] = 'budi_santoso'         │
│    session['role'] = 'warga'                    │
│    session['user_id'] = 1                       │
│                                                 │
│ 4. Backend redirects to dashboard               │
│    location: 'pages/dashboard-warga.html'       │
│                                                 │
│ 5. Dashboard JavaScript calls check_session.php │
│    Backend returns: {logged_in: true, role: 'warga'} │
│                                                 │
│ 6. JavaScript confirms user is in right place   │
│    If not → redirect to login                   │
└─────────────────────────────────────────────────┘
```

### Session Security

| Aspect | How it works |
|--------|-------------|
| **Server-side** | Session data stored on server, not in browser |
| **ID Verification** | Each page checks if user logged in + correct role |
| **Cross-role blocking** | WARGA cannot open RT dashboard (redirect to login) |
| **Logout** | Session destroyed, user must login again |

---

## WARGA DASHBOARD - TECHNICAL STRUCTURE

### File: pages/dashboard-warga.html

**Header Section:**
```html
Header
├─ Breadcrumb: Beranda • Dashboard Warga
├─ Title: "Portal Warga"
├─ Subtitle: "Layanan surat, aduan, iuran"
└─ Logout button
```

**Main Content Sections:**

#### 1. PENGAJUAN SURAT (Letter Request Section)

**HTML Structure:**
```html
Section: PENGAJUAN SURAT SAYA
├─ Tracking Item 1: Surat Keterangan Usaha
│   ├─ Title & submitted date
│   ├─ Status badge: "⏳ PROSES"
│   ├─ Status flow: "Sent to RT → Waiting RW → Ready"
│   └─ Buttons: (No action buttons until approved)
│
├─ Tracking Item 2: Surat Domisili
│   ├─ Status badge: "✓ SELESAI"
│   ├─ Status flow: "✓ Sent → ✓ Validated → ✓ Ready"
│   └─ Button: "📥 Download Surat"
│
└─ Tracking Item 3: Surat Izin Usaha
    ├─ Status badge: "✓ SUDAH DIAMBIL"
    └─ Completed (no download available, already taken)
```

**Status Progression:**
```
pending
  ↓ (RT approves)
disetujui_rt
  ↓ (RW approves)
disetujui_rw
  ↓ (Ready to pick up)
selesai
  ↓ (Resident takes it)
sudah_diambil
```

**JavaScript Function (Triggered on Page Load):**
```
On page load:
  → Check session (call check_session.php)
  → Verify role is 'warga'
  → Load pengajuan surat list (call warga-pengajuan-surat.php?action=list)
  → Display in HTML
```

---

#### 2. IURAN BULANAN (Monthly Dues Section)

**HTML Structure:**
```html
Section: IURAN BULANAN
├─ Current Month (Belum Bayar - Not paid)
│   ├─ Month: "Januari 2025"
│   ├─ Amount: "Rp 50.000"
│   ├─ Status: "Belum Bayar" (red)
│   └─ Buttons:
│       ├─ "💳 Bayar Sekarang" (Pay Now)
│       ├─ "📱 QRIS" (Mobile payment)
│       └─ "📋 Lihat Rincian" (See details)
│
├─ Previous Months (Riwayat Pembayaran)
│   ├─ December 2024: ✓ LUNAS (Paid)
│   │   └─ Rp 50.000 (Bayar tgl 28 Des)
│   │
│   ├─ November 2024: ✓ LUNAS
│   │   └─ Rp 50.000 (Bayar tgl 15 Nov)
│   │
│   └─ October 2024: ✓ LUNAS
│       └─ Rp 50.000 (Bayar tgl 10 Okt)
│
└─ Summary Box
    ├─ Total owed: Rp 50.000
    └─ Total paid this year: Rp 150.000
```

**Payment Flow (What Happens When Click "Bayar Sekarang"):**

```
Click button
  ↓
Opens payment modal dialog
  ├─ Shows: Month, Amount (Rp 50.000)
  ├─ Choose payment method:
  │   ├─ Tunai (Cash) - Bayar ke bendahara
  │   ├─ Transfer Bank - Get bank account info
  │   └─ QRIS - Show QR code
  │
  └─ User selects method
    ↓
Form submitted (JavaScript)
  ↓
Sends to backend: warga-iuran.php?action=bayar
  ├─ Data sent: {month: 'Januari 2025', amount: 50000, method: 'tunai'}
  │
  └─ Backend:
      ├─ Finds user's family (KK)
      ├─ Updates iuran_warga record
      │   ├─ Set status_bayar = 'lunas'
      │   ├─ Set tanggal_bayar = today
      │   └─ Set metode_bayar = 'tunai'
      │
      └─ Returns: {success: true, message: "Payment recorded"}
        ↓
JavaScript shows success message
  ↓
Refreshes the iuran list
  ↓
Status changes from "Belum Bayar" to "LUNAS" ✓
```

---

#### 3. PENGUMUMAN TERBARU (Latest Announcements)

**HTML Structure:**
```html
Section: PENGUMUMAN & INFORMASI TERBARU
├─ Announcement 1: RAPAT RUTIN RT 05
│   ├─ Color: Yellow (⚠️ warning)
│   ├─ Details:
│   │   ├─ Hari: Jum'at, 24 Januari 2025
│   │   ├─ Jam: 19:00 (malam)
│   │   ├─ Tempat: Rumah Ketua RT, Gang Mawar No. 5
│   │   ├─ Topik: Perbaikan jalan & rapat bulanan
│   │   └─ Kehadiran: Min 1 perwakilan per KK
│   │
│   └─ Button: "Tandai Akan Hadir" (Mark attendance)
│
├─ Announcement 2: IURAN TAMBAHAN PERBAIKAN JALAN
│   ├─ Color: Red (🔴 urgent)
│   ├─ Details:
│   │   ├─ Nominal: Rp 20.000 per KK
│   │   ├─ Target: Perbaikan jalan gang mawar
│   │   ├─ Deadline: 30 Januari 2025
│   │   └─ Work date: 25 Januari, 07:00
│   │
│   └─ Button: "📝 Bayar Iuran Tambahan"
│
├─ Announcement 3: UNDIAN AKHIR TAHUN
│   ├─ Color: Blue (ℹ️ info)
│   ├─ Details:
│   │   ├─ Hadiah Utama: Sepeda Motor
│   │   ├─ Hadiah 2: TV 32 Inch (2 pemenang)
│   │   └─ Hadiah 3: Voucher Beras (10 pemenang)
│   │
│   ├─ Status Badge: "Tidak menang undian ini"
│   └─ Button: "👑 Lihat Daftar Pemenang"
│
└─ Button: "📢 Lihat Semua Pengumuman" → Links to pengumuman.html
```

**Data Source:**
- Hardcoded in HTML (static announcements)
- Could be from database in future (warga-pengumuman.php)

---

#### 4. ADUAN / ASPIRASI FORM (Complaints Form)

**HTML Structure:**
```html
Section: KIRIM ADUAN / ASPIRASI / LAPORAN
├─ Description: "Sampaikan masalah, saran, atau aspirasi..."
│
├─ Form Fields:
│   ├─ 1. Judul Aduan / Aspirasi
│   │   └─ Text input: "Contoh: Jalan berlubang, Lampu jalan mati"
│   │
│   ├─ 2. Kategori
│   │   └─ Dropdown menu:
│   │       ├─ 🛣️ Infrastruktur (Roads, water, electricity)
│   │       ├─ 🚨 Keamanan & Ketentraman (Security)
│   │       ├─ 🧹 Kebersihan & Lingkungan (Cleanliness)
│   │       ├─ ❤️ Sosial & Kesejahteraan (Social)
│   │       ├─ 💰 Keuangan & Kas RT (Finance)
│   │       └─ 📝 Lainnya (Other)
│   │
│   ├─ 3. Deskripsi Detail
│   │   └─ Textarea: "Jelaskan masalahnya secara detail..."
│   │       (Lokasi, kapan terjadi, dampak, dll)
│   │
│   └─ 4. Checkbox: "Saya setuju data ini diproses..."
│
├─ Buttons:
│   ├─ "✉️ Kirim Aduan" (Submit)
│   └─ "Bersihkan Form" (Reset)
│
└─ Info Box: "Aduan Anda akan ditindaklanjuti dalam 1 minggu..."
```

**Form Submission Flow:**

```
User fills form:
  ├─ Title: "Jalan berlubang di depan toko"
  ├─ Category: "Infrastruktur"
  ├─ Description: "Sudah berlubang besar, berbahaya bagi motor"
  └─ Checks consent box

Clicks "Kirim Aduan" button
  ↓
JavaScript validates (all fields filled)
  ↓
Sends to backend: warga-aduan.php?action=submit
  ├─ Data: {
  │   id_warga: (from session),
  │   judul: "Jalan berlubang di depan toko",
  │   kategori: "infrastruktur",
  │   deskripsi: "Sudah berlubang besar...",
  │   lokasi: (optional),
  │   prioritas: "sedang"
  │ }
  │
  └─ Backend:
      ├─ Inserts into aduan table
      ├─ Set status = 'baru' (new)
      ├─ Set prioritas = 'sedang' (medium)
      ├─ Set tanggal_aduan = today
      └─ Returns: {success: true, message: "Aduan diterima"}

JavaScript shows success notification:
  "✅ Aduan Anda berhasil dikirim. RT akan menindaklanjuti dalam 1 minggu"
  ↓
Form clears
```

---

## RT DASHBOARD - TECHNICAL STRUCTURE

### File: pages/dashboard-rt.html

#### 1. TODAY'S PRIORITY CHECKLIST

**HTML Structure:**
```html
Section: HARI INI (25 JANUARI 2025) - TUGAS PRIORITAS
├─ Title: "☑️ Daftar Tugas Harian"
│
└─ Checkbox List:
    ├─ ☐ Validasi 3 permohonan surat dari warga
    ├─ ☐ Input laporan kas RT (perbaikan jalan)
    ├─ ☐ Hubungi Dina (tanggungan iuran 3 bulan)
    ├─ ☐ Verifikasi data warga baru (keluarga Pak Ahmad)
    └─ ☐ Siapkan rapat RT besok malam (daftar hadir, topik)
```

**Functionality:**
- HTML checkboxes only (no database backend)
- User can click to check/uncheck
- Helps RT leader track daily tasks
- No data saving (resets on page refresh)

---

#### 2. PERMOHONAN SURAT PENDING (Letter Requests)

**HTML Structure:**
```html
Section: PERMOHONAN SURAT - MENUNGGU VALIDASI (3 PENDING)

Permohonan Item 1:
├─ Header: "1. Budi Santoso - Surat Keterangan Usaha"
├─ Meta: "📝 Diajukan: 15 Januari 2025 | 🔄 Status: Tunggu Validasi"
├─ Details:
│   ├─ Tujuan: Izin mendirikan warung di rumah
│   ├─ Alamat: Jl. Mawar No. 12 RT 05
│   └─ No. KK: 3271234567
│
└─ Action Buttons:
    ├─ "✓ Setujui" (Approve) - Green button
    ├─ "✗ Tolak" (Reject) - Red button
    └─ "? Lebih Info" (More info) - Blue button

[Same structure for Permohonan 2 & 3]
```

**What Happens When RT Clicks "Setujui" (Approve):**

```
Click "✓ Setujui" button
  ↓
Opens approval modal/form
  ├─ Shows: Resident name, Letter type, Details
  ├─ Optional field: Catatan (notes)
  └─ Button: "Konfirmasi Persetujuan"

User clicks "Konfirmasi Persetujuan"
  ↓
Sends to backend: rt-pengajuan-surat.php?action=approve
  ├─ Data: {
  │   id_pengajuan: 1,
  │   catatan: "Sudah diverifikasi oleh RT"
  │ }
  │
  └─ Backend:
      ├─ Finds pengajuan_surat record
      ├─ Updates:
      │   ├─ status_pengajuan = 'disetujui_rt'
      │   ├─ divalidasi_oleh_rt = (RT user ID)
      │   ├─ tanggal_validasi_rt = today
      │   └─ catatan_rt = (notes if any)
      │
      └─ Returns: {success: true, message: "Pengajuan disetujui"}

JavaScript shows success message
  ↓
Request moves to RW queue (next stage)
  ↓
Item disappears from RT's pending list
```

**What Happens When RT Clicks "✗ Tolak" (Reject):**

```
Click "✗ Tolak" button
  ↓
Opens rejection form
  ├─ Required field: Alasan Penolakan (Reason for rejection)
  └─ Button: "Konfirmasi Penolakan"

User enters reason (e.g., "Data tidak sesuai") & submits
  ↓
Sends to backend: rt-pengajuan-surat.php?action=reject
  ├─ Data: {
  │   id_pengajuan: 1,
  │   alasan_penolakan: "Data tidak sesuai"
  │ }
  │
  └─ Backend:
      ├─ Updates pengajuan_surat:
      │   ├─ status_pengajuan = 'ditolak_rt'
      │   ├─ alasan_penolakan = "Data tidak sesuai"
      │   └─ divalidasi_oleh_rt = (RT user ID)
      │
      └─ Returns: {success: true}

Notification sent to resident
  ↓
Item disappears from RT's list (request closed)
```

---

#### 3. PEMBAYARAN IURAN MASUK (Incoming Payments)

**HTML Structure:**
```html
Section: PEMBAYARAN IURAN MASUK

Info: "Ringkasan pembayaran terbaru yang dilakukan warga"

Dynamic Container (id="payment-notify")
  └─ Filled by JavaScript with recent payments
      ├─ When Rp 50,000 paid → Shows: "✓ Budi Santoso bayar Rp 50.000"
      ├─ When Rp 50,000 paid → Shows: "✓ Siti Nurhaliza bayar Rp 50.000"
      └─ When Rp 20,000 paid → Shows: "✓ Ahmad Gunawan bayar Rp 20.000"
```

**Functionality:**
- Loads when page opens: rt-dashboard.js calls loadPayments()
- Fetches recent payment records from database
- Updates list in real-time
- Shows notification toast/popup

---

#### 4. ADUAN / KELUHAN WARGA (Complaints)

**HTML Structure:**
```html
Section: ADUAN / KELUHAN WARGA (5 TOTAL)

Aduan Item 1 (Urgent - Red Background):
├─ Title: "🆕 Banjir di Gang Murai"
├─ Description: "Setiap hujan, gang mawar selalu banjir..."
├─ Meta:
│   ├─ Dari: Dina Nurhayati
│   └─ Tgl: 24 Januari 2025
├─ Status Badge: "⚠️ PERLU FOLLOW UP" (red)
│
└─ Action Buttons:
    ├─ "🔧 Tandai Ditindak" (Mark as being handled)
    ├─ "📢 Lapor ke RW" (Escalate to RW)
    └─ "📞 Hubungi Warga" (Call resident)

Aduan Item 2 (In Progress):
├─ Title: "Jalan Berlubang Depan Toko Haji Joni"
├─ Status Badge: "✓ DITINDAK LANJUTI" (green)
│
└─ Note: "Sudah dikonfirmasi. Akan dilaporkan ke RW untuk renovasi"

Aduan Item 3 (Completed):
├─ Title: "Lampu Jalan Mati (4 Titik)"
├─ Status Badge: "✓ SELESAI" (green)
│
└─ Note: "Sudah perbaiki (15 Jan). Teknisi datang dan mengganti lampu"
```

**What Happens When RT Clicks "🔧 Tandai Ditindak":**

```
Click button
  ↓
Sends to backend: rt-aduan.php?action=update_status
  ├─ Data: {
  │   id_aduan: (complaint ID),
  │   status: 'diproses'
  │ }
  │
  └─ Backend:
      ├─ Updates aduan record
      │   ├─ status = 'diproses'
      │   ├─ ditangani_oleh = (RT user ID)
      │   ├─ tanggal_proses = today
      │   └─ catatan = ""
      │
      └─ Returns: {success: true}

Item status changes to "✓ DITINDAK LANJUTI"
```

**What Happens When RT Clicks "📢 Lapor ke RW" (Escalate):**

```
Click button
  ↓
Opens escalation form
  ├─ Shows complaint details
  ├─ RT can add notes: "Perlu bantuan RW untuk..."
  └─ Button: "Kirim ke RW"

RT submits
  ↓
Sends to backend: rt-aduan.php?action=escalate
  ├─ Data: {
  │   id_aduan: (complaint ID),
  │   prioritas: 'urgent'
  │ }
  │
  └─ Backend:
      ├─ Updates aduan:
      │   ├─ prioritas = 'urgent'
      │   └─ ditangani_oleh = (RW user ID)
      │
      └─ Marks for RW dashboard visibility

Item now appears in RW Dashboard → "ADUAN MASUK DARI RT"
```

---

#### 5. BUKU KAS RT (Cash Book)

**HTML Structure:**
```html
Section: BUKU KAS RT - JANUARI 2025

Summary Cards:
├─ "Saldo Awal (1 Januari): Rp 2.500.000" (blue)
└─ [Input buttons area]

Buttons:
├─ "➕ Input Pemasukan Baru" (Add income)
└─ "➖ Input Pengeluaran Baru" (Add expense)

Dynamic Area (id="kas-dynamic")
  └─ Filled by JavaScript with recent transactions

PEMASUKAN (Income):
├─ 20 Januari - Iuran Rutin: +Rp 850.000 (17 KK × 50.000)
├─ 22 Januari - Iuran Tambahan: +Rp 400.000 (20 KK × 20.000)
└─ Total Pemasukan: +Rp 1.250.000 ✓

PENGELUARAN (Expenses):
├─ 15 Januari - Hadiah Undian: -Rp 500.000
├─ 22 Januari - Beli Material: -Rp 150.000
├─ 23 Januari - Honor Bantuan: -Rp 50.000
└─ Total Pengeluaran: -Rp 700.000

SALDO AKHIR (Closing Balance):
└─ (25 Januari): Rp 3.050.000 ✅ (green, bold)
```

**What Happens When RT Clicks "➕ Input Pemasukan Baru":**

```
Click button
  ↓
Opens form modal
  ├─ Fields:
  │   ├─ Tanggal (Date): Date picker
  │   ├─ Jenis Transaksi (Type): Dropdown
  │   │   ├─ Iuran Rutin
  │   │   ├─ Iuran Tambahan
  │   │   ├─ Donasi
  │   │   └─ Lainnya
  │   ├─ Nominal (Amount): Number field
  │   └─ Keterangan (Notes): Text field
  │
  └─ Button: "Simpan Transaksi"

RT fills & submits
  ↓
Sends to backend: rt-kas.php?action=add
  ├─ Data: {
  │   id_rt: (RT ID),
  │   tanggal: "25-01-2025",
  │   jenis_transaksi: "pemasukan",
  │   id_kategori: (category ID),
  │   nominal: 50000,
  │   keterangan: "Bayar iuran Pak Budi"
  │ }
  │
  └─ Backend:
      ├─ Inserts into kas_rt table
      ├─ Calculates new balance
      └─ Returns: {success: true}

New transaction appears in list
  ↓
Saldo Akhir updates automatically
```

**Calculation Logic:**
```
Saldo Awal = Rp 2.500.000
+ All Pemasukan (income) = +Rp 1.250.000
- All Pengeluaran (expenses) = -Rp 700.000
= Saldo Akhir = Rp 3.050.000
```

---

#### 6. DATA WARGA RT 05 (Population Statistics)

**HTML Structure:**
```html
Section: DATA WARGA RT 05

Summary Cards (4 cards):
├─ "45" - Total KK (Families)
├─ "156" - Total Jiwa (Residents)
├─ "45" - Kepala Keluarga (Heads of family)
└─ "42" - KK Pembayar Iuran (Paying families)

Action Buttons:
├─ "👥 Lihat Daftar Warga Lengkap" (View full list)
├─ "📋 Cetak Data (PDF)" (Print as PDF)
└─ "➕ Tambah Warga Baru" (Add new resident)
```

**What Happens When RT Clicks "👥 Lihat Daftar Warga Lengkap":**

```
Click button
  ↓
Opens modal or new page
  ├─ Shows table of all residents in this RT
  ├─ Columns: No., Nama, Alamat, No. KK, Status
  ├─ Can search/filter by name
  └─ Can click each row to see details

Example table data:
  1 | Budi Santoso | Jl. Mawar No. 12 | 3271234567 | Aktif ✓
  2 | Siti Nurhaliza | Jl. Melati No. 5 | 3271234568 | Aktif ✓
  3 | Ahmad Gunawan | Jl. Raya No. 45 | 3271234569 | Aktif ✓
```

---

## RW DASHBOARD - TECHNICAL STRUCTURE

### File: pages/dashboard-rw.html

#### 1. RINGKASAN MINGGU INI (This Week's Summary)

**HTML Structure:**
```html
Section: RINGKASAN MINGGU INI (19-25 JANUARI 2025)

4 Summary Cards (grid layout):

Card 1: Permohonan Surat
├─ Big Number: "12"
├─ Label: "Permohonan Surat"
└─ Details: "8 ✓ | 2 ✗ | 2 ⏳"

Card 2: Aduan Warga
├─ Big Number: "5"
├─ Label: "Aduan Warga"
└─ Details: "3 selesai | 2 proses"

Card 3: Iuran Terkumpul
├─ Big Number: "Rp 4,2 Jt"
├─ Label: "Iuran Terkumpul"
└─ Details: "85% dari target"

Card 4: Total Warga
├─ Big Number: "231"
├─ Label: "Total Warga"
└─ Details: "5 RT aktif"

Status Box (yellow):
└─ "📊 Status Umum: Operasional lancar. Ada 1 aduan infrastruktur
   menunggu follow-up RW."
```

**Data Calculation:**
```
Permohonan Surat:
  = SUM(approved) + SUM(rejected) + SUM(pending)
  = 8 + 2 + 2 = 12 total

Aduan Warga:
  = SUM(completed) + SUM(in_progress)
  = 3 + 2 = 5 total

Iuran Terkumpul:
  = SUM(payment_status = 'lunas')
  = Rp 4,200,000

Percentage:
  = (4,200,000 / 5,000,000) × 100
  = 84% ≈ 85%
```

---

#### 2. ADUAN MASUK DARI RT (Escalated Complaints)

**HTML Structure:**
```html
Section: ADUAN MASUK DARI RT

Intro: "Aduan yang diteruskan pengurus RT ke RW akan tampil di sini
        untuk ditindaklanjuti"

Dynamic Container (id="aduan-rw-container")
  └─ Each escalated complaint shown as:
      ├─ RT Number: "Dari: RT 05"
      ├─ Complaint Title: "Banjir di Gang Murai"
      ├─ Description: "Setiap hujan, gang mawar selalu banjir..."
      ├─ Priority: "🔴 URGENT"
      └─ Action Buttons:
          ├─ "📋 Lihat Detail"
          └─ "✓ Tandai Selesai"
```

**What Happens When RW Clicks "✓ Tandai Selesai":**

```
Click button
  ↓
Opens form
  ├─ Field: Solusi (Solution)
  │   └─ Text area: "Describe what RW did to solve"
  └─ Button: "Konfirmasi Selesai"

RW fills & submits
  ↓
Sends to backend: rw-aduan.php?action=update
  ├─ Data: {
  │   id_aduan: (complaint ID),
  │   status: 'selesai',
  │   solusi: "Sudah koordinasi dengan dinas..."
  │ }
  │
  └─ Backend:
      ├─ Updates aduan record
      │   ├─ status = 'selesai'
      │   ├─ solusi = (RW's solution)
      │   ├─ tanggal_selesai = today
      │   └─ ditangani_oleh = (RW user ID)
      │
      └─ Returns: {success: true}

Notification sent to RT who escalated it
  ↓
Item disappears from RW's list
```

---

#### 3. REKAP PERMOHONAN SURAT (Letter Request Summary)

**HTML Structure:**
```html
Section: REKAP PERMOHONAN SURAT - BULAN JANUARI (12 TOTAL)

Table with 5 columns:
┌───────┬───────┬──────────┬────────┬────────┐
│  RT   │ Total │ Disetujui│ Ditolak│ Proses │
├───────┼───────┼──────────┼────────┼────────┤
│ RT 01 │  4    │  3 ✓     │  0     │  1 ⏳  │
│ RT 02 │  3    │  3 ✓     │  0     │  0     │
│ RT 03 │  3    │  2 ✓     │  0     │  1 ⏳  │
│ RT 04 │  2    │  0       │  0     │  2 ⏳  │
│ RT 05 │  1    │  0       │  0     │  1 ⏳  │
├───────┼───────┼──────────┼────────┼────────┤
│ TOTAL │ 12    │  8 ✓     │  0 ✗   │  2 ⏳  │
└───────┴───────┴──────────┴────────┴────────┘

Progress Bar for each RT:
  RT 01: ■■■■ (4 items)
  RT 02: ■■■ (3 items)
  RT 03: ■■■ (3 items)
  RT 04: ■■ (2 items)
  RT 05: ■ (1 item)
```

**What RW Can Do:**
- Click on RT's row to see list of requests
- See which requests are pending
- Final approval/rejection power
- Add RW signature/stamp

---

#### 4. MONITORING KEUANGAN PER RT (Financial Monitoring)

**HTML Structure:**
```html
Section: MONITORING KEUANGAN RT - JANUARI 2025

Status: "Kondisi keuangan seluruh RT dalam wilayah RW 05"

Table:
┌─────┬──────────┬──────────┬────────┐
│ RT  │ Pemasukan│Pengeluaran│ Saldo  │
├─────┼──────────┼──────────┼────────┤
│ 01  │Rp 2,5 M  │ Rp 800K  │Rp 4,2M │
│ 02  │Rp 2,0 M  │ Rp 600K  │Rp 3,8M │
│ 03  │Rp 1,8 M  │ Rp 700K  │Rp 2,9M │
│ 04  │Rp 1,5 M  │ Rp 500K  │Rp 2,1M │
│ 05  │Rp 1,25 M │ Rp 700K  │Rp 3,05M│
└─────┴──────────┴──────────┴────────┘

Notes shown:
├─ "RT 01 saldo sehat (Rp 4,2M)"
├─ "RT 04 perlu perhatian (saldo terendah Rp 2,1M)"
└─ "Total kas RW 05: Rp 16,15 Juta"
```

**What RW Can Do:**
- Monitor saldo per RT
- Identify RTs with low balance
- Help RT with financial problems
- Request detailed kas report from RT

---

#### 5. STATISTIK WARGA (Population Statistics)

**HTML Structure:**
```html
Section: STATISTIK WARGA RW 05

Summary for Whole RW (5 RTs combined):
├─ Total KK: 220 families
├─ Total Warga: 870 people
├─ Laki-laki: 435
└─ Perempuan: 435

Breakdown Table per RT:
┌─────┬────────┬───────┬──────┬──────────┐
│ RT  │ Keluarga│ Jiwa │ Laki │ Perempuan│
├─────┼────────┼───────┼──────┼──────────┤
│ 01  │  48    │  178  │ 89   │   89     │
│ 02  │  42    │  165  │ 82   │   83     │
│ 03  │  45    │  172  │ 86   │   86     │
│ 04  │  40    │  159  │ 80   │   79     │
│ 05  │  45    │  156  │ 78   │   78     │
├─────┼────────┼───────┼──────┼──────────┤
│TOTAL│ 220    │  870  │435   │  435     │
└─────┴────────┴───────┴──────┴──────────┘

Chart/Graph (optional visual)
  └─ Bar chart showing population per RT
```

**What RW Can Do:**
- See population trends
- Plan activities based on population
- Identify which RT has most/least residents
- Print demographic reports

---

## LOGOUT PROCESS

**What Happens When User Clicks "Logout" Button:**

```
User clicks "Logout" button in header
  ↓
JavaScript function triggered: handleLogout()
  ↓
Async function calls: fetch('../php/logout.php')
  ↓
Backend (logout.php):
  ├─ Calls: session_destroy()
  ├─ Clears all session data from server
  └─ Returns: {success: true}

JavaScript receives response
  ↓
JavaScript clears localStorage
  ├─ Removes: userSession
  ├─ Removes: any cached user data
  └─ Clears: temporary variables

Browser redirects to: index.php (login page)
  ↓
User sees login form again
  ↓
Must login with username/password to access dashboards
```

**Security:**
- Session destroyed on server (can't be accessed again)
- Browser side cleared (localStorage emptied)
- No way to access dashboard without fresh login
- Old session ID no longer valid

---

## SUMMARY TABLE

| Component | File | Purpose |
|-----------|------|---------|
| **Login Page** | index.php | Authenticate user, create session |
| **Home Page** | pages/beranda.html | Public homepage |
| **Profile Page** | pages/profil.html | Show community leaders |
| **Announcements** | pages/pengumuman.html | Read news |
| **Gallery** | pages/galeri.html | View photos |
| **WARGA Dashboard** | pages/dashboard-warga.html | Request letters, pay dues, submit complaints |
| **RT Dashboard** | pages/dashboard-rt.html | Approve letters, manage money, handle complaints |
| **RW Dashboard** | pages/dashboard-rw.html | Monitor all RTs, final approvals |

---

**END OF TECHNICAL OVERVIEW**

