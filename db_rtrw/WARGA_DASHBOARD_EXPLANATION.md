# Dashboard Warga - Complete Explanation

## 📋 PAGE OVERVIEW

**File:** `pages/dashboard-warga.html`  
**Purpose:** Home page for residents (warga) to manage letters, pay dues, submit complaints, and view announcements  
**Role:** WARGA (Resident)  
**Access Level:** Logged-in users with role = 'warga'

---

## 🔐 ACCESS & SESSION CONTROL

### Who Can Access This Page?
- **Only:** Users logged in with role = `warga`
- **Cannot access:** RT, RW, Admin, or non-logged-in users

### What Happens on Page Load?
1. **Session Check:**
   - Page loads `dashboard-warga.html`
   - JavaScript function `loadUserSession()` runs automatically
   - Checks `localStorage.getItem('userSession')` for user data

2. **If NOT Logged In:**
   - Session data is empty or missing
   - User redirected to `../index.php` (login page)
   - Cannot view any dashboard content

3. **If Logged In (WARGA):**
   - Session contains: `{ role: 'warga', name: 'Budi Santoso', id: 'RW05/001' }`
   - Page displays dashboard content
   - User name appears in header area
   - User ID appears as "ID: RW05/001"

4. **If Logged In (WRONG ROLE):**
   - If role !== 'warga' (e.g., RT or RW)
   - User redirected to login page
   - Wrong role users cannot see warga dashboard

### Session Destruction (Logout)
- User clicks "Logout" button
- Confirmation dialog appears: "Apakah Anda yakin ingin logout?"
- If confirmed:
  - `localStorage.removeItem('userSession')` executes
  - Session data deleted
  - Redirected to `../index.php` (login page)
  - All dashboard data inaccessible

---

## 🎨 PAGE HEADER & STRUCTURE

### Header Section
- **Left Side:**
  - Breadcrumb navigation: "Beranda • Dashboard Warga"
  - Title: "Portal Warga" (displayed as `pageTitle`)
  - Subtitle: "Layanan surat, aduan, iuran" (displayed as `pageSubtitle`)

- **Right Side:**
  - User name display (from session)
  - Logout button
  - Role indicator: `data-role="warga"`

### Layout Structure
- **Container-based:** Full-width with centered max-width
- **Section Blocks:** Each feature in white card with shadow
- **Responsive:** Works on desktop, tablet, mobile

---

## 📊 MAIN DASHBOARD SECTIONS (In Order)

---

## 1️⃣ PAYMENT SUCCESS BANNER

**What is it?**
- Alert box that appears ONLY after successful payment
- Hidden by default (`style="display:none"`)

**When Does It Show?**
- User completes payment successfully
- JavaScript sets `display: block`
- Shows message: "✅ Pembayaran Berhasil" (Payment Successful)

**Content:**
- Success icon: ✅
- Title: "Pembayaran Berhasil"
- Details: Payment amount, date, receipt info (via `#payment-success-text`)

**Auto-Hide:**
- Stays visible for 5 seconds (typical)
- Then auto-hides or user can close

---

## 2️⃣ PENGAJUAN SURAT SAYA (MY LETTER REQUESTS)

**Section Header:**
- Title: "PENGAJUAN SURAT SAYA"
- Styled with gradient purple text
- Divider line below

**Data Source:**
- Backend: `php/warga-pengajuan-surat.php?action=list`
- Loaded via: `loadPengajuanSurat()` JavaScript function
- Data displayed: `displayPengajuanSurat(data)`

**What's Shown:**
- List of 3+ letter requests (sample/demo data)
- Each request as card/box with details

### Example Letter Request #1: Surat Domisili (COMPLETED)

**Information Displayed:**
- Letter type: 📄 Surat Domisili
- Submission date: Diajukan: 10 Januari 2025
- Current status: ✓ SELESAI (Completed)
- Status badge color: Green (success)

**Status Flow Shown:**
- Visual flow: "✓ Dikirim ke RT → ✓ Validasi RW → ✓ Ambil di Kantor RT"
- Shows all 3 stages completed (✓)

**Available Actions:**
- Button: "📥 Download Surat" - User can download completed letter
- onclick: `handleDownloadSurat('Surat Domisili')`

**User Scenario:**
1. Resident submitted domisili letter
2. RT approved it
3. RW gave final approval
4. Letter is ready to collect
5. Resident can download copy via button

---

### Example Letter Request #2: Surat Keterangan Usaha (PENDING)

**Information Displayed:**
- Letter type: 📄 Surat Keterangan Usaha
- Purpose: Izin mendirikan warung
- Submission date: Diajukan: 15 Januari 2025
- Current status: ⏳ PROSES (Tunggu RT) - Processing, Waiting for RT

**Status Badge:**
- Color: Orange/amber (warning)
- Text: "⏳ PROSES (Tunggu RT)"
- Meaning: Letter waiting for RT validation

**Status Flow Shown:**
- Visual: "Dikirim ke RT → 🔄 Tunggu Validasi RW → Selesai"
- First stage done (✓ Dikirim ke RT)
- Second stage in progress (🔄 Tunggu Validasi RW)
- Third stage not started (Selesai)

**Available Actions:**
- NO buttons (request still processing)
- User must wait for RT to approve

**User Scenario:**
1. Resident submitted letter request
2. Letter sent to RT for validation
3. RT hasn't approved yet
4. Resident sees status and waits

---

### Example Letter Request #3: Surat Izin Usaha (ALREADY COLLECTED)

**Information Displayed:**
- Letter type: 📄 Surat Izin Usaha
- Submission date: Diajukan: 20 Desember 2024
- Completion date: Diselesaikan: 2 Januari 2025
- Current status: ✓ SUDAH DIAMBIL (Already Collected)

**Status Badge:**
- Color: Green (success)
- Text: "✓ SUDAH DIAMBIL"
- Meaning: Resident already picked up the letter

**Available Actions:**
- NO buttons (letter already collected)
- Can't download (already in hand)

**User Scenario:**
1. Resident submitted letter
2. Got approved
3. Picked up from RT office
4. Letter now archived in history

---

### Info Box in This Section
- **Text:** "📌 Catatan: Pengajuan surat baru dapat dilakukan kapan saja. Hubungi pengurus RT jika ada pertanyaan."
- **Message:** Users can submit letters anytime; contact RT if questions

---

## 3️⃣ IURAN BULANAN (MONTHLY DUES)

**Section Header:**
- Title: "IURAN BULANAN"
- Gradient purple styled

**Data Source:**
- Backend: `php/warga-iuran.php?action=list` and `?action=history`
- Loaded via: `loadIuran()` function
- Data displayed: `displayIuran(data)`

---

### Current Month Outstanding Dues

**Information Displayed:**
- Month: **Januari 2025**
- Amount: **Rp 50.000**
- Payment status: **Belum Bayar** (Not Paid)
- Status color: Orange/amber (warning) - overdue

**Card Styling:**
- Left border: 4px orange (warning color)
- Background: Light orange tint
- Font weight: Bold for amount

**Available Action Buttons:**
1. **💳 Bayar Sekarang** (Pay Now)
   - Opens payment form
   - onclick: `handleBayarIuran('Januari 2025', 50000)`
   - Resident can pay immediately

2. **📱 QRIS**
   - Opens QRIS payment scanner modal
   - onclick: `showQrisModal('Januari 2025', 50000)`
   - Alternative payment method (QR code)

3. **📋 Lihat Rincian** (View Details)
   - Shows payment breakdown details
   - onclick: `handleLihatRincian('iuran')`

**What Happens When User Clicks "Bayar Sekarang"?**
1. Payment form/modal opens
2. System processes payment
3. If successful:
   - Success banner appears at top
   - Status changes to "✓ LUNAS"
   - Receipt generated

---

### Payment History Section

**Heading:** "Riwayat Pembayaran (Payment History)"

**Shown:** 3+ months of past payments

---

### Example Payment #1: Desember 2024 (PAID)

**Information Displayed:**
- Month: **Desember 2024**
- Amount: **Rp 50.000**
- Status: **✓ LUNAS** (Paid)
- Payment date: **Bayar tgl 28 Desember** (Paid on Dec 28)

**Card Styling:**
- Left border: 4px green (success color)
- Background: Light green tint
- Text color: Green

**Available Actions:**
- NO buttons
- This payment complete
- Can view receipt (optional feature)

---

### Example Payment #2: November 2024 (PAID)

**Information Displayed:**
- Month: **November 2024**
- Amount: **Rp 50.000**
- Status: **✓ LUNAS**
- Payment date: **Bayar tgl 15 November**

**Card Styling:**
- Same as December (green, paid)

---

### Example Payment #3: Oktober 2024 (PAID)

**Information Displayed:**
- Month: **Oktober 2024**
- Amount: **Rp 50.000**
- Status: **✓ LUNAS**
- Payment date: **Bayar tgl 10 Oktober**

---

### Summary Box

**Content:**
- **💰 Ringkasan (Summary):**
  - Total Terhutang: Rp 50.000 (Current outstanding)
  - Total Terbayar Tahun Ini: Rp 150.000 (3 months paid)

**Meaning:**
- Resident owes 1 month
- Paid 3 months already this year

---

## 4️⃣ PENGUMUMAN & INFORMASI TERBARU (ANNOUNCEMENTS & LATEST INFO)

**Section Header:**
- Title: "PENGUMUMAN & INFORMASI TERBARU"

**Data Source:**
- Backend: `php/public-content.php?action=pengumuman`
- Loaded via: `loadPengumuman()` function

---

### Announcement Box #1: RAPAT RUTIN RT 05 (MEETING)

**Box Styling:**
- Border-left: 4px orange (warning/important)
- Background: Light yellow (#FFF8E1)

**Information Displayed:**
- **Icon:** 📢 (announcement)
- **Title:** "PENTING: Rapat Rutin RT 05"
- **Day:** Jum'at (Friday)
- **Date:** 24 Januari 2025
- **Time:** 19:00 (7 PM)
- **Location:** Rumah Ketua RT, Gang Mawar No. 5
- **Topic:** Rencana perbaikan jalan dan rapat rutin bulanan
- **Attendance:** Dimohon minimal satu perwakilan per KK (One representative per household)

**Available Action Button:**
- **Tandai Akan Hadir** (Mark Attendance)
- onclick: `handleMarkKehadiran('Rapat Rutin RT 05', '24-01-2025')`
- Resident can RSVP attendance

**User Scenario:**
1. Resident sees meeting announcement
2. Clicks "Tandai Akan Hadir"
3. System records attendance intention
4. RT can count expected attendees

---

### Announcement Box #2: IURAN TAMBAHAN (ADDITIONAL DUES)

**Box Styling:**
- Border-left: 4px red (danger/urgent)
- Background: Light red (#FFEBEE)

**Information Displayed:**
- **Icon:** ⚠️ (warning)
- **Title:** "PENGUMUMAN: Iuran Tambahan Perbaikan Jalan"
- **Amount:** Rp 20.000 per KK
- **Purpose:** Perbaikan jalan gang mawar (yang sudah berlubang-lubang)
- **Payment Deadline:** 30 Januari 2025
- **Work Schedule:** Minggu, 25 Januari 2025 (Pukul 07:00) (Sunday, 7 AM)

**Available Action Button:**
- **📝 Bayar Iuran Tambahan** (Pay Additional Dues)
- onclick: `handleBayarIuranTambahan('Perbaikan Jalan', 20000)`

**User Scenario:**
1. Resident sees additional fee announcement
2. Understands it's for road repair
3. Clicks button to pay
4. Payment processed

---

### Announcement Box #3: UNDIAN AKHIR TAHUN (YEAR-END LOTTERY)

**Box Styling:**
- Border-left: 4px blue (info)
- Background: Light blue (#E3F2FD)

**Information Displayed:**
- **Icon:** 🎁 (gift)
- **Title:** "UNDIAN AKHIR TAHUN RT/RW"
- **Lottery Date:** 15 Januari 2025
- **Grand Prize:** Sepeda Motor (Motorcycle)
- **Prize 2:** TV 32 Inch (2 winners)
- **Prize 3:** Voucher Beras (10 winners)
- **User Status:** "Tidak menang undian ini" (You didn't win this lottery)

**User Status Display:**
- Shows badge: "Tidak menang undian ini"
- Color: Red/danger (didn't win)
- Alternative badge (if won): Green success badge with prize info

**Available Action Button:**
- **👑 Lihat Daftar Pemenang** (View Winners List)
- onclick: `handleViewWinner()`
- Shows list of all winners

**User Scenario:**
1. Resident sees lottery results
2. Sees their status (won/didn't win)
3. Can view full winners list

---

### View All Announcements Button

- **Button Text:** "📢 Lihat Semua Pengumuman"
- **Action:** Links to `pengumuman.html` (full announcements page)
- Onclick: `window.location.href='pengumuman.html'`

---

## 5️⃣ KIRIM ADUAN / ASPIRASI / LAPORAN (SUBMIT COMPLAINT/SUGGESTION/REPORT)

**Section Header:**
- Title: "KIRIM ADUAN / ASPIRASI / LAPORAN"

**Purpose:**
- Allow residents to submit complaints, suggestions, or reports
- Direct to RT leadership

**Introduction Text:**
- "Sampaikan masalah, saran, atau aspirasi Anda kepada pengurus RT/RW. Form ini akan diteruskan langsung ke ketua RT."

---

### Form Fields

#### Field 1: Judul Aduan / Aspirasi (Title)

**Input Type:** Text input
**Placeholder:** "Contoh: Jalan berlubang, Lampu jalan mati, Saran kegiatan"
**Required:** Yes (required attribute)
**ID:** `aduan-title`

**What User Enters:**
- Title of complaint or suggestion
- Examples: "Road with potholes", "Broken street light", "Activity suggestion"

---

#### Field 2: Kategori (Category)

**Input Type:** Dropdown/Select
**ID:** `aduan-category`
**Required:** Yes

**Available Options:**
1. **"-- Pilih Kategori --"** (Select category - default)
2. **🛣️ Infrastruktur** (Infrastructure: Roads, Water, Electricity)
3. **🚨 Keamanan & Ketentraman** (Security & Peace)
4. **🧹 Kebersihan & Lingkungan** (Cleanliness & Environment)
5. **❤️ Sosial & Kesejahteraan** (Social & Welfare)
6. **💰 Keuangan & Kas RT** (Finance & Cash)
7. **📝 Lainnya** (Other)

**User Selection:**
- Resident picks category that fits their complaint/suggestion

---

#### Field 3: Deskripsi Detail (Detailed Description)

**Input Type:** Textarea
**ID:** `aduan-desc`
**Required:** Yes
**Placeholder:** "Jelaskan masalahnya secara detail (Lokasi, kapan terjadi, dampak, dll)"

**What User Enters:**
- Detailed explanation of issue
- Location information
- When it happened
- Impact/consequences
- Any other relevant details

---

#### Field 4: Data Processing Consent

**Input Type:** Checkbox
**ID:** `aduan-consent`
**Required:** Yes
**Label:** "Saya setuju data ini diproses oleh pengurus RT/RW"

**Meaning:**
- User agrees their data will be processed by RT/RW
- Legal consent for data handling

---

### Form Buttons

#### Submit Button

**Button Text:** "✉️ Kirim Aduan" (Send Complaint)
**Button Type:** Submit
**Class:** `btn btn-primary`
**Onclick:** Form submission triggers `handleSubmitAduan(event)`

**What Happens:**
1. Form validation (all required fields checked)
2. If valid:
   - Data submitted to backend
   - Confirmation message shown
   - Form may clear
3. If invalid:
   - Error message for missing fields
   - Form not submitted

---

#### Reset Button

**Button Text:** "Bersihkan Form" (Clear Form)
**Button Type:** Reset
**Class:** `btn`
**Action:** Clears all form inputs

**What Happens:**
- All fields reset to empty
- Checkbox unchecked
- User can start over

---

### Info Box After Form

**Content:**
- **Text:** "📝 Catatan: Aduan Anda akan ditindaklanjuti dalam waktu maksimal 1 minggu. Pengurus RT akan menghubungi Anda jika diperlukan informasi tambahan."
- **Message:** Complaints handled within 1 week; RT will contact if more info needed

---

## 6️⃣ FOOTER

**Content:**
- Copyright: "&copy; 2025 RT 05 Kelurahan Maju Jaya | Sistem Informasi Warga"
- Contact: "Hubungi: Pengurus RT (WhatsApp: 0812-3456-7890)"
- Display: Centered text

---

## 🔄 DATA LOADING FLOW (On Page Load)

```
1. User opens dashboard-warga.html
2. Browser loads HTML, CSS, JavaScript files
3. JavaScript executes (all script tags)
4. loadUserSession() function runs:
   - Checks localStorage for session
   - If not found → redirect to login
   - If found + role = 'warga' → continue
   - If found + role != 'warga' → redirect to login
5. Once validated, parallel data loads:
   - loadPengajuanSurat() → GET /php/warga-pengajuan-surat.php?action=list
   - loadIuran() → GET /php/warga-iuran.php?action=list
   - loadPengumuman() → GET /php/public-content.php?action=pengumuman
   - loadAduan() → GET /php/warga-aduan.php?action=list (if applicable)
6. Response data displayed in respective sections
7. Dashboard ready for user interaction
```

---

## 📱 USER INTERACTIONS & ACTIONS

### Possible User Actions

| Action | Button/Element | Function Called | Result |
|--------|---|---|---|
| Pay Monthly Dues | "💳 Bayar Sekarang" | `handleBayarIuran()` | Payment form opens |
| Pay via QRIS | "📱 QRIS" | `showQrisModal()` | QR code scanner modal opens |
| View Dues Details | "📋 Lihat Rincian" | `handleLihatRincian()` | Details popup |
| Download Completed Letter | "📥 Download Surat" | `handleDownloadSurat()` | Letter PDF downloads |
| Mark Meeting Attendance | "Tandai Akan Hadir" | `handleMarkKehadiran()` | Attendance recorded |
| Pay Additional Dues | "📝 Bayar Iuran Tambahan" | `handleBayarIuranTambahan()` | Payment form opens |
| View Lottery Winners | "👑 Lihat Daftar Pemenang" | `handleViewWinner()` | Winners list displays |
| Submit Complaint | "✉️ Kirim Aduan" (form submit) | `handleSubmitAduan()` | Complaint sent, confirmation shown |
| Clear Form | "Bersihkan Form" | HTML reset | Form cleared |
| View All Announcements | "📢 Lihat Semua Pengumuman" | Page navigation | Redirects to pengumuman.html |
| Logout | "Logout" button | `handleLogout()` | Confirmation dialog, session destroyed, redirect to login |

---

## 🎨 STATUS BADGES & COLOR CODING

### Status Badges Used

| Badge | Color | Meaning | When Shown |
|-------|-------|---------|-----------|
| ✓ SELESAI | Green | Letter/task completed | Completed letter requests |
| ⏳ PROSES (Tunggu RT) | Orange | Processing, waiting for RT | Pending letter requests |
| ✓ SUDAH DIAMBIL | Green | Already collected | Collected letters |
| ✓ LUNAS | Green | Payment complete | Paid dues |
| Belum Bayar | Orange | Not paid | Unpaid dues |
| Tidak menang | Red | Didn't win lottery | No win status |

---

## 🔒 SECURITY & ACCESS RULES

### Can Access This Page?
- ✓ Users with role = 'warga'
- ✓ Users with valid active session
- ✓ Users logged in via index.php

### Cannot Access This Page?
- ✗ Non-logged-in users
- ✗ Users with role = 'rt'
- ✗ Users with role = 'rw'
- ✗ Users with role = 'admin'
- ✗ Users with expired/invalid session

### Session Validation Method
- Client-side: `localStorage.getItem('userSession')`
- Contains: User ID, name, role, permissions
- Checked on page load
- Re-checked before each API call (typically)

### What Data Can Warga See?
- Their own letter requests only
- Their own payment history
- Their own complaints/submissions
- Public announcements (visible to all)
- Community-wide lottery results
- Cannot see: Other warga's data, RT/RW operations, financial details, administrative info

---

## ⚡ COMMON USER SCENARIOS

### Scenario 1: First-Time Login
1. User logs in at index.php with credentials
2. Session created, stored in localStorage
3. Page redirects to dashboard-warga.html
4. Session validated
5. Dashboard loads with user's data
6. User sees their letter requests, dues, announcements

### Scenario 2: Pay Monthly Dues
1. User sees "Iuran Bulanan" section
2. Current month shows "Belum Bayar" (Rp 50,000)
3. User clicks "💳 Bayar Sekarang"
4. Payment form opens
5. User enters amount/confirms
6. Payment processes
7. If successful:
   - Success banner appears at top
   - Status changes to "✓ LUNAS"
   - Current month removed from "Belum Bayar" list
   - Moves to "Riwayat Pembayaran" with green status

### Scenario 3: Submit Complaint
1. User scrolls to "KIRIM ADUAN" section
2. Fills form:
   - Judul: "Jalan berlubang depan rumah saya"
   - Kategori: "🛣️ Infrastruktur"
   - Deskripsi: "Sudah berlubang sebulan, berbahaya untuk motor dan mobil"
   - Consent: Checks checkbox
3. Clicks "✉️ Kirim Aduan"
4. Form validates (all fields present)
5. Data sent to backend: `warga-aduan.php?action=submit`
6. If successful:
   - Confirmation message: "Aduan diterima. Kami akan menghubungi Anda dalam 1 minggu."
   - Form clears
   - User sees receipt/ticket number (optional)

### Scenario 4: Download Approved Letter
1. User scrolls to "PENGAJUAN SURAT SAYA"
2. Sees completed letter: "Surat Domisili" with status "✓ SELESAI"
3. Clicks "📥 Download Surat"
4. Browser downloads letter as PDF
5. Letter contains:
   - Full name
   - Address
   - RT/RW info
   - Signature of RT/RW leaders
   - Official stamp

### Scenario 5: Logout
1. User finishes using dashboard
2. Clicks "Logout" button (top right)
3. Confirmation dialog: "Apakah Anda yakin ingin logout?"
4. User clicks "Ya, Logout"
5. JavaScript executes: `localStorage.removeItem('userSession')`
6. Session destroyed
7. Page redirects to `../index.php` (login page)
8. User must login again to access dashboard

---

## 📊 DATA DISPLAY LOGIC

### How Letter Requests Display:
- **Source:** Backend returns array of requests
- **Filtering:** Only requests where `id_warga = current_user_id`
- **Sorting:** Newest first (by submission date)
- **Status determination:**
  - If `status_pengajuan = 'pending'` → Show orange badge, waiting message
  - If `status_pengajuan = 'selesai'` → Show green badge, download button
  - If `status_pengajuan = 'diambil'` → Show green badge, "SUDAH DIAMBIL"

### How Dues Display:
- **Current Month:**
  - If `status_bayar = 'belum'` → Show as pending (orange), display buttons
  - If `status_bayar = 'lunas'` → Move to history section (green)
- **History:**
  - Show last 3-6 months
  - Sorted: Newest first
  - Display only `status_bayar = 'lunas'` items

### How Announcements Display:
- **Fetch:** All public announcements from `pengumuman` table
- **Priority Order:**
  1. Urgent/Important (⚠️ flags)
  2. Date-sorted (newest first)
  3. Maximum 5 shown on dashboard (rest on pengumuman.html)

---

## 🎯 CORE FEATURES (REQUIRED)
- ✓ Session authentication (required for access)
- ✓ Letter request tracking
- ✓ Monthly dues display & payment
- ✓ Announcement viewing
- ✓ Complaint form submission
- ✓ Logout functionality

## ⭕ OPTIONAL FEATURES (ENHANCEMENT)
- ○ Payment receipt download
- ○ Notification bell/counter
- ○ Dark mode toggle
- ○ Language selector
- ○ Profile/account settings
- ○ Complaint status tracking (real-time)
- ○ Push notifications
- ○ Mobile app integration

---

## 🚨 NOTES & KNOWN BEHAVIORS

### Important Notes:
1. **LocalStorage Based:** Session stored in browser localStorage
   - If user clears browser data → session lost
   - If user logs in on another device → separate session
   - Not ideal for production (should use server-side sessions)

2. **Data Persistence:**
   - Sample/demo data shown initially
   - Real data loads from backend API calls
   - Mix of hardcoded and dynamic content

3. **No Real-time Updates:**
   - Dashboard doesn't auto-refresh
   - User must refresh page to see new data
   - Better implementation: Polling every 30 seconds or WebSocket

4. **Limited Error Handling:**
   - If API fails → console errors only
   - No user-facing error messages for API failures
   - Should show error alerts/toasts to user

5. **Payment Simulator:**
   - Payment buttons likely trigger simulation/demo mode
   - Not connected to real payment gateway initially
   - Can integrate with Midtrans, GCash, etc.

---

## 📞 CONTACT & SUPPORT

**RT Contact Info (shown in footer):**
- WhatsApp: 0812-3456-7890
- For complaints taking > 1 week
- For letter request status updates
- For payment issues

---

**End of Warga Dashboard Explanation**

