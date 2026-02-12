# Dashboard RT (Sub-District Leader) - Complete Explanation

## 📋 PAGE OVERVIEW

**File:** `pages/dashboard-rt.html`  
**Purpose:** Admin dashboard for RT (Rukun Tetangga) leaders to manage residents, validate letters, track complaints, manage finances, and monitor payments  
**Role:** RT (Sub-district leader/administrator)  
**Access Level:** Logged-in users with role = 'rt'

---

## 🔐 ACCESS & SESSION CONTROL

### Who Can Access This Page?
- **Only:** Users logged in with role = `rt`
- **Cannot access:** Warga (residents), RW, Admin, or non-logged-in users
- **RT Specific:** Each RT leader sees only their RT's data (e.g., RT 05 sees only RT 05 data)

### What Happens on Page Load?
1. **Session Check:**
   - Page loads `dashboard-rt.html`
   - JavaScript function `loadUserSession()` runs automatically
   - Checks `localStorage.getItem('userSession')` for user data

2. **If NOT Logged In:**
   - Session data is empty or missing
   - User redirected to `../index.php` (login page)
   - Cannot view any dashboard content

3. **If Logged In (RT):**
   - Session contains: `{ role: 'rt', name: 'Rahmat', id: '05' }`
   - Page displays RT dashboard content
   - RT name appears in header: "Pengurus RT Rahmat"
   - RT number displayed: "Dashboard Ketua RT 05"

4. **If Logged In (WRONG ROLE):**
   - If role !== 'rt' (e.g., warga or rw)
   - User redirected to login page
   - Wrong role users cannot see RT dashboard

### Session Destruction (Logout)
- User clicks "Logout" button (top right)
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
  - Breadcrumb navigation: "Beranda • Dashboard RT"
  - Title: "Dashboard Ketua RT 05" (dynamic, based on RT number)
  - Subtitle: "Rekap & layanan warga RT"
  - Data attribute: `data-role="rt"` and `data-rt="05"`

- **Right Side:**
  - User name display (from session)
  - Logout button
  - Role badge: "RT" (highlighted)

### Layout Structure
- **Container-based:** Full-width with centered max-width
- **Section Blocks:** Each feature in white card with shadow
- **Responsive:** Works on desktop, tablet, mobile

---

## 📊 MAIN DASHBOARD SECTIONS (In Order)

---

## 1️⃣ HARI INI - TUGAS PRIORITAS (TODAY'S PRIORITY TASKS)

**Section Header:**
- Title: "HARI INI (25 JANUARI 2025) - TUGAS PRIORITAS"
- Date included in header

**Purpose:**
- Quick daily checklist for RT leader
- Track important tasks for the day
- Manage workload priorities

**What's Shown:**
- Priority box with heading: "☑️ Daftar Tugas Harian"
- Interactive checkbox list with 5 tasks

### Task #1: Validate Letter Requests
- **Task:** "Validasi 3 permohonan surat dari warga"
- **Type:** Checkbox (unchecked by default)
- **Action:** RT checks after completing task
- **Related Section:** PERMOHONAN SURAT section below

### Task #2: Input Cash Report
- **Task:** "Input laporan kas RT (perbaikan jalan)"
- **Type:** Checkbox
- **Action:** Record new cash transaction
- **Related Section:** BUKU KAS RT section

### Task #3: Contact Resident
- **Task:** "Hubungi Dina (tanggungan iuran 3 bulan)"
- **Type:** Checkbox
- **Priority:** High (resident has 3 months payment arrears)
- **Related Section:** DATA WARGA section

### Task #4: Verify New Resident
- **Task:** "Verifikasi data warga baru (keluarga Pak Ahmad)"
- **Type:** Checkbox
- **Action:** Add/verify new family data
- **Related Section:** DATA WARGA section

### Task #5: Prepare Meeting
- **Task:** "Siapkan rapat RT besok malam (daftar hadir, topik)"
- **Type:** Checkbox
- **Action:** Prepare materials for tomorrow's meeting

**Functionality:**
- Pure HTML5 checkboxes
- Local storage: Tasks persist in browser
- No server sync (local only)
- User manually checks/unchecks

---

## 2️⃣ PERMOHONAN SURAT - MENUNGGU VALIDASI (PENDING LETTER REQUESTS)

**Section Header:**
- Title: "PERMOHONAN SURAT - MENUNGGU VALIDASI (3 PENDING)"
- Shows count of pending requests

**Data Source:**
- Backend: `php/rt-pengajuan-surat.php?action=list`
- Loaded via: `loadPengajuanSurat()` JavaScript function
- Data displayed: `displayPengajuanSurat(data)`

**Purpose:**
- RT validates letter requests from residents
- Decide whether to approve or reject
- Forward approved letters to RW

---

### Example Request #1: Surat Keterangan Usaha (APPROVE OPTION)

**Information Displayed:**
- **Number:** "1. Budi Santoso - Surat Keterangan Usaha"
- **Submission Date:** "📝 Diajukan: 15 Januari 2025"
- **Status:** "🔄 Status: Tunggu Validasi"
- **Purpose:** "Tujuan: Izin mendirikan warung di rumah"
- **Address:** "Alamat: Jl. Mawar No. 12 RT 05"
- **KK Number:** "No. KK: 3271234567"

**Card Styling:**
- Class: `permohonan-item pending`
- Border-left: Colored accent line
- Background: Light shade
- Clear typography

**Available Action Buttons (3 choices):**

1. **✓ Setujui** (Approve) - Green button
   - onclick: `handlePersetujuan('Budi Santoso', 'Surat Keterangan Usaha')`
   - What happens:
     - Confirmation dialog: "Setujui permohonan Budi Santoso untuk Surat Keterangan Usaha?"
     - Additional info: "Surat akan dikirim ke RW untuk validasi final sebelum diterbitkan."
     - If confirmed:
       - Success modal: "Permohonan dari Budi Santoso telah disetujui."
       - Message: "Surat akan diteruskan ke RW untuk validasi final dalam 1-2 hari kerja."
       - Request removed from pending list (in UI)
       - Sent to RW dashboard

2. **✗ Tolak** (Reject) - Red button
   - onclick: `handlePenolakan('Budi Santoso')`
   - What happens:
     - Modal opens asking for rejection reason
     - Textarea: "Masukkan alasan penolakan..."
     - RT types reason (required field)
     - Clicks "❌ Tolak dengan Alasan"
     - Success message: "Permohonan dari Budi Santoso telah ditolak."
     - Message: "Warga akan mendapat notifikasi penolakan."
     - Reason sent to resident for reference

3. **? Lebih Info** (More Info) - Gray button
   - onclick: `handleMoreInfo('Budi Santoso')`
   - What happens:
     - Modal shows contact options
     - "Hubungi Budi Santoso untuk informasi lebih lanjut:"
     - Options shown:
       - "✓ WhatsApp: Akan dihubungi sesuai nomor terdaftar"
       - "✓ Datang Langsung: Ke rumah yang terdaftar di sistem"
       - "✓ Telepon: Sesuai nomor kontak warga"

**User Scenario (RT):**
1. RT logs in and sees 3 pending requests
2. Reviews first request details
3. Decides: Approve (person is legitimate)
4. Clicks "✓ Setujui"
5. Confirms in dialog
6. System forwards to RW
7. RT notified request sent to RW

---

### Example Request #2: Surat Domisili (PENDING)

**Information Displayed:**
- **Number:** "2. Siti Nurhaliza - Surat Domisili"
- **Submission Date:** "📝 Diajukan: 18 Januari 2025"
- **Status:** "🔄 Status: Tunggu Validasi"
- **Purpose:** "Tujuan: Surat keterangan tempat tinggal untuk KTP"
- **Address:** "Alamat: Jl. Melati No. 5 RT 05"
- **KK Number:** "No. KK: 3271234568"

**Same 3 Action Buttons Available**

---

### Example Request #3: Surat Izin Usaha (PENDING)

**Information Displayed:**
- **Number:** "3. Ahmad Gunawan - Surat Izin Usaha"
- **Submission Date:** "📝 Diajukan: 20 Januari 2025"
- **Status:** "🔄 Status: Tunggu Validasi"
- **Purpose:** "Tujuan: Pembukaan toko elektronik"
- **Address:** "Alamat: Jl. Raya No. 45 RT 05 / RW 05"
- **KK Number:** "No. KK: 3271234569"

**Same 3 Action Buttons Available**

---

### Info Box
- **Text:** "📌 Catatan Proses: Permohonan yang sudah disetujui RT akan dikirim ke RW untuk validasi final sebelum diterbitkan."
- **Message:** Explains the workflow (RT → RW → published)

---

## 3️⃣ PEMBAYARAN IURAN MASUK (INCOMING PAYMENTS)

**Section Header:**
- Title: "PEMBAYARAN IURAN MASUK"

**Subtitle:**
- "Ringkasan pembayaran terbaru yang dilakukan warga."

**Data Source:**
- LocalStorage: `paymentNotifications` array
- Data populated when warga completes payment
- Function: `renderPaymentNotifications()`

**What's Shown:**
- List of 5 most recent payments
- Each payment shows:
  - Resident name
  - Month paid for
  - Payment timestamp
  - Amount (green, bold)
  - Payment method (TUNAI/TRANSFER/QRIS)

**Dynamic Display:**
- If no payments: "Belum ada pembayaran tercatat."
- If payments exist: Show list in reverse chronological order (newest first)

**Example Payments Shown:**
1. Budi Santoso - Januari 2025 | Rp 50.000 | QRIS
2. Siti Nurhaliza - Januari 2025 | Rp 50.000 | TRANSFER
3. Ahmad Gunawan - Januari 2025 | Rp 50.000 | TUNAI

**Purpose:**
- RT sees payment flow in real-time
- Verify residents are paying
- Monitor collection status

---

## 4️⃣ ADUAN / KELUHAN WARGA (RESIDENT COMPLAINTS)

**Section Header:**
- Title: "ADUAN / KELUHAN WARGA (5 TOTAL)"
- Shows total count of complaints

**Data Source:**
- LocalStorage: `aduanList` array
- Submitted by warga via dashboard-warga.html form
- Function: `renderDynamicAduan()`

**Purpose:**
- RT sees all resident complaints/suggestions
- Decide on action (process, escalate, contact resident)
- Track complaint status

---

### Example Complaint #1: BANJIR (URGENT - NEEDS FOLLOW-UP)

**Information Displayed:**
- **Icon:** 🆕 (new)
- **Title:** "Banjir di Gang Murai"
- **Description:** "Setiap hujan, gang mawar selalu banjir karena saluran air macet."
- **From:** "Dina Nurhayati"
- **Date:** "24 Januari 2025"
- **Status Badge:** "⚠️ PERLU FOLLOW UP" (orange/warning color)

**Available Action Buttons (3 choices):**

1. **🔧 Tandai Ditindak** (Mark as Handled)
   - onclick: `handleAduanAction('Banjir', 'Perbaiki')`
   - What happens:
     - Toast message: "Aduan 'Banjir di Gang Murai' telah ditandai sebagai sedang ditangani."
     - Status changes locally
     - Tracks that RT is working on it

2. **📢 Lapor ke RW** (Report to RW)
   - onclick: `handleAduanAction('Banjir', 'Lapor RW')`
   - What happens:
     - Complaint escalated to RW level
     - Added to `aduanRWList` in localStorage
     - RW dashboard updated with this complaint
     - Toast: "Aduan 'Banjir di Gang Murai' telah dilaporkan ke RW."
     - Shows RW will see in their dashboard

3. **📞 Hubungi Warga** (Contact Resident)
   - onclick: `handleAduanAction('Banjir', 'Hubungi')`
   - What happens:
     - Toast: "Warga pelapor akan dihubungi mengenai aduan 'Banjir di Gang Murai'."
     - RT uses resident's contact info to call/WhatsApp

---

### Example Complaint #2: Jalan Berlubang (IN PROGRESS)

**Information Displayed:**
- **Title:** "Jalan Berlubang Depan Toko Haji Joni"
- **Description:** "Sudah berlubang besar, berbahaya bagi motor dan mobil."
- **From:** "Pak Bambang"
- **Date:** "20 Januari 2025"
- **Status Badge:** "✓ DITINDAK LANJUTI" (green/success)

**Status Update Box (Green):**
- "Tindakan: Sudah dikonfirmasi. Akan dilaporkan ke RW untuk renovasi jalan."

**Meaning:**
- RT already acknowledged this
- Work is being coordinated
- Will be escalated to RW if needed

---

### Example Complaint #3: Lampu Jalan Mati (RESOLVED)

**Information Displayed:**
- **Title:** "Lampu Jalan Mati (4 Titik)"
- **Description:** "Lampu di depan rumah Pak Suryanto dan tiga tempat lain sudah padam."
- **From:** "Siti Rahayu"
- **Date:** "18 Januari 2025"
- **Status Badge:** "✓ SELESAI" (green/completed)

**Resolution Box (Green):**
- "Tindakan: Sudah perbaiki (15 Jan). Teknisi datang dan mengganti lampu."

**Meaning:**
- Complaint already resolved
- Details of resolution documented
- Closed/archived status

---

### View More Button
- **Button:** "➕ Lihat Semua Aduan"
- **Onclick:** Scrolls/redirects to full complaint list
- Shows all complaints, not just first 3

---

## 5️⃣ BUKU KAS RT (CASH BOOK)

**Section Header:**
- Title: "BUKU KAS RT - JANUARI 2025"
- Shows current month

**Purpose:**
- Track all RT financial transactions
- Record income (iuran/dues from residents)
- Record expenses (road repairs, activities, admin)
- Calculate running balance

**Data Source:**
- LocalStorage: `kasList` array
- Function: `renderKasDynamic()` and `formatCurrency()`

---

### Opening Balance
- **Label:** "Saldo Awal (1 Januari):"
- **Amount:** "Rp 2.500.000"
- **Color:** Blue (info)
- **Meaning:** RT started January with this amount

---

### Input Buttons
- **Button 1:** "➕ Input Pemasukan Baru" (Add Income)
  - Onclick: `showKasForm('pemasukan')`
  - Opens modal to add income transaction
  
- **Button 2:** "➖ Input Pengeluaran Baru" (Add Expense)
  - Onclick: `showKasForm('pengeluaran')`
  - Opens modal to add expense transaction

### Cash Entry Modal (Pemasukan/Pengeluaran)
When user clicks input buttons, modal appears:

**For Pengeluaran (Expense), available categories:**
- Perbaikan Jalan (Road repairs)
- Kegiatan Sosial (Social activities)
- Administrasi (Admin fees)
- Keamanan (Security)
- Lain-lain (Other)

**Form Fields:**
1. **Nominal (Rupiah):** Number input (e.g., 500000)
2. **Deskripsi:** Dropdown to select category

**Action:** "💾 Simpan" (Save)
- Validates both fields filled
- Records transaction with:
  - Nominal amount
  - Category
  - Current date/time
  - RT name who recorded it
- Adds to kasList array
- Toast confirmation: "Kas Pemasukan Rp X,XXX berhasil dicatat"

---

### Income Section: PEMASUKAN (Income)

**Transaction #1: Iuran Rutin**
- **Date:** 20 Januari
- **Description:** "Iuran Rutin"
- **Amount:** "+ Rp 850.000" (green, positive)
- **Details:** "(17 KK × Rp 50.000)"

**Transaction #2: Iuran Tambahan**
- **Date:** 22 Januari
- **Description:** "Iuran Tambahan (Perbaikan Jalan)"
- **Amount:** "+ Rp 400.000" (green)
- **Details:** "(20 KK × Rp 20.000)"

**Subtotal:**
- **Label:** "Total Pemasukan:"
- **Amount:** "+ Rp 1.250.000" (bold, green)

---

### Expense Section: PENGELUARAN (Expenses)

**Transaction #1: Prize Fund**
- **Date:** 15 Januari
- **Description:** "Hadiah Undian Akhir Tahun"
- **Amount:** "- Rp 500.000" (red, negative)

**Transaction #2: Materials**
- **Date:** 22 Januari
- **Description:** "Beli Kabel & Material Perbaikan Jalan"
- **Amount:** "- Rp 150.000" (red)

**Transaction #3: Admin Fee**
- **Date:** 23 Januari
- **Description:** "Biaya Pembantu Ketua RT (Honor)"
- **Amount:** "- Rp 50.000" (red)

**Subtotal:**
- **Label:** "Total Pengeluaran:"
- **Amount:** "- Rp 700.000" (bold, red)

---

### Final Balance
- **Background:** Light green (good status)
- **Label:** "SALDO AKHIR (25 Januari):" (bold, green)
- **Amount:** "Rp 3.050.000" (large, bold, green)
- **Calculation:** 2,500,000 + 1,250,000 - 700,000 = 3,050,000

**Math Check:**
- Saldo Awal: Rp 2.500.000
- + Pemasukan: Rp 1.250.000
- - Pengeluaran: Rp 700.000
- = Saldo Akhir: Rp 3.050.000 ✓

---

### Info Box
- **Text:** "💡 Catatan: Pastikan setiap transaksi kas dicatat dengan rapi. Laporan kas akan dikirim ke RW setiap akhir bulan."
- **Message:** Reminds RT to keep accurate records for RW reporting

---

## 6️⃣ DATA WARGA RT 05 (RESIDENT DATA)

**Section Header:**
- Title: "DATA WARGA RT 05"

**Purpose:**
- View statistics of all residents in RT
- Monitor family data
- Manage resident information

### Summary Cards (Statistics)

**Card 1: Total Families**
- **Value:** "45"
- **Label:** "Total KK" (Kepala Keluarga)
- **Meaning:** 45 households in RT 05

**Card 2: Total Population**
- **Value:** "156"
- **Label:** "Total Jiwa" (Total persons)
- **Meaning:** 156 individuals total

**Card 3: Heads of Household**
- **Value:** "45"
- **Label:** "Kepala Keluarga"
- **Meaning:** 45 family heads registered

**Card 4: Dues-Paying Families**
- **Value:** "42"
- **Label:** "KK Pembayar Iuran"
- **Meaning:** 42 out of 45 families are paying dues
- **Gap:** 3 families with arrears

---

### Action Buttons

**Button 1: View Full Resident List**
- **Text:** "👥 Lihat Daftar Warga Lengkap"
- **Onclick:** Opens full resident list (can filter, sort)

**Button 2: Print Data (PDF)**
- **Text:** "📋 Cetak Data (PDF)"
- **Onclick:** Generates and downloads PDF report of all residents

**Button 3: Add New Resident**
- **Text:** "➕ Tambah Warga Baru"
- **Onclick:** Opens form to register new resident/family

---

## 7️⃣ FOOTER

**Content:**
- Copyright: "&copy; 2025 RT 05 Kelurahan Maju Jaya | Dashboard Pengurus RT"
- Contact to RW: "Hubungi RW: WhatsApp 0812-9876-5432"
- Display: Centered text

---

## 🔄 DATA LOADING FLOW (On Page Load)

```
1. User opens dashboard-rt.html
2. Browser loads HTML, CSS, JavaScript files
3. loadUserSession() runs:
   - Checks localStorage for session
   - If not found → redirect to login
   - If found + role = 'rt' → continue
   - If found + role != 'rt' → redirect to login
   
4. configureHeaderByRole() updates header:
   - Sets RT number (e.g., 05)
   - Sets RT name (e.g., Rahmat)
   - Updates title: "Dashboard Ketua RT 05"
   
5. Parallel data loads:
   - renderDynamicAduan() → Load complaints from localStorage
   - renderPaymentNotifications() → Load recent payments
   - renderKasDynamic() → Load cash book transactions
   - initUserMenu() → Setup user menu interactions
   - initProfileMenuItem() → Setup profile link
   
6. Dashboard ready for user interaction
```

---

## 📱 USER INTERACTIONS & ACTIONS

### Possible RT User Actions

| Action | Button/Element | Function Called | Result |
|--------|---|---|---|
| Approve Letter Request | "✓ Setujui" | `handlePersetujuan()` | Confirmation, request forwarded to RW |
| Reject Letter Request | "✗ Tolak" | `handlePenolakan()` | Modal for reason, warga notified |
| Get More Info on Request | "? Lebih Info" | `handleMoreInfo()` | Contact options shown |
| Mark Complaint as Handled | "🔧 Tandai Ditindak" | `handleAduanAction()` | Status updated to "handled" |
| Escalate Complaint to RW | "📢 Lapor ke RW" | `handleAduanAction()` | Complaint sent to RW dashboard |
| Contact Resident on Complaint | "📞 Hubungi Warga" | `handleAduanAction()` | Shows contact methods |
| Add Cash Income | "➕ Input Pemasukan Baru" | `showKasForm('pemasukan')` | Modal opens, transaction recorded |
| Add Cash Expense | "➖ Input Pengeluaran Baru" | `showKasForm('pengeluaran')` | Modal opens, transaction recorded |
| View Full Resident List | "👥 Lihat Daftar..." | Page navigation | Redirects to residents page |
| Print Resident Data | "📋 Cetak Data (PDF)" | PDF generation | Downloads PDF report |
| Add New Resident | "➕ Tambah Warga Baru" | Opens form | Registers new family |
| Logout | "Logout" button | `handleLogout()` | Confirmation, session destroyed, redirect to login |

---

## 🎨 STATUS BADGES & COLOR CODING

### Status Badges Used

| Badge | Color | Meaning | When Shown |
|-------|-------|---------|-----------|
| 🔄 Tunggu Validasi | Orange | Awaiting RT validation | Pending letter requests |
| ✓ Setujui | Green | Approved | After approval action |
| ✗ Tolak | Red | Rejected | After rejection action |
| ⚠️ PERLU FOLLOW UP | Orange | Needs attention | New/urgent complaints |
| ✓ DITINDAK LANJUTI | Green | Being handled | Active complaints |
| ✓ SELESAI | Green | Completed/closed | Resolved complaints |
| ⏳ Aduan Masuk | Orange | New incoming | Newly submitted complaints |

---

## 🔒 SECURITY & ACCESS RULES

### Can Access This Page?
- ✓ Users with role = 'rt'
- ✓ Users with valid active session
- ✓ Users logged in via index.php as RT

### Cannot Access This Page?
- ✗ Non-logged-in users
- ✗ Users with role = 'warga'
- ✗ Users with role = 'rw'
- ✗ Users with role = 'admin'
- ✗ Users with expired/invalid session

### Session Validation Method
- Client-side: `localStorage.getItem('userSession')`
- Contains: User ID, name, role, RT number
- Checked on page load
- Re-checked for each action

### What Data Can RT See?
- ✓ All letter requests from their residents
- ✓ All complaints from their residents
- ✓ Their RT's financial (cash book)
- ✓ Their RT's resident statistics
- ✓ Incoming payments from their residents
- Cannot see: Other RTs' data, RW operations, warga accounts in detail

---

## ⚡ COMMON RT USER SCENARIOS

### Scenario 1: First-Time Login (RT)
1. RT logs in at index.php with credentials
2. Session created, stored in localStorage
3. Page redirects to dashboard-rt.html
4. Session validated (role = 'rt')
5. Dashboard loads with RT's data
6. RT sees:
   - 3 pending letter requests
   - 5 resident complaints
   - Recent payments
   - Cash book balance

### Scenario 2: Approve Letter Request
1. RT sees pending letter from Budi Santoso (Surat Keterangan Usaha)
2. Reviews details (purpose: start warung business)
3. Clicks "✓ Setujui"
4. Confirmation dialog: "Setujui permohonan Budi Santoso untuk Surat Keterangan Usaha?"
5. RT confirms
6. Success: "Permohonan dari Budi Santoso telah disetujui."
7. Message: "Surat akan diteruskan ke RW untuk validasi final dalam 1-2 hari kerja."
8. Request removed from pending list
9. Forwarded to RW dashboard

### Scenario 3: Reject Letter Request
1. RT sees letter request from someone ineligible
2. Clicks "✗ Tolak"
3. Modal opens: "Alasan penolakan permohonan dari [nama]:"
4. Textarea for reason
5. RT types reason: "Data tidak lengkap, KK number tidak terdaftar"
6. Clicks "❌ Tolak dengan Alasan"
7. Success: "Permohonan dari [nama] telah ditolak."
8. Message: "Warga akan mendapat notifikasi penolakan."
9. Reason sent to resident

### Scenario 4: Escalate Complaint to RW
1. RT sees complaint: "Banjir di Gang Murai"
2. Priority: Urgent infrastructure issue
3. RT decides this needs RW level action
4. Clicks "📢 Lapor ke RW"
5. Toast: "Aduan 'Banjir di Gang Murai' telah dilaporkan ke RW."
6. Complaint added to RW's complaint list
7. RW will see it in their dashboard

### Scenario 5: Record Cash Transaction
1. RT received Iuran (dues) payments today
2. Amount: Rp 850,000 from 17 families
3. Clicks "➕ Input Pemasukan Baru"
4. Modal opens: "Input Kas - PEMASUKAN"
5. Fills form:
   - Nominal: 850000
   - Kategori: "Iuran Warga"
6. Clicks "💾 Simpan"
7. Toast: "Kas Pemasukan Rp 850.000 berhasil dicatat"
8. Transaction appears in cash book
9. Saldo Akhir updates automatically

### Scenario 6: Respond to Complaint
1. RT sees new complaint: "Jalan berlubang depan toko"
2. RT coordinates with residents to fix
3. Clicks "🔧 Tandai Ditindak"
4. Status changes to "DITINDAK LANJUTI"
5. Toast: "Aduan 'Jalan berlubang...' telah ditandai sebagai sedang ditangani."
6. Later, work completed
7. RT documents: "Sudah perbaiki (15 Jan). Teknisi datang dan mengganti lampu."
8. Status changes to "SELESAI"

### Scenario 7: Logout
1. RT finishes work for the day
2. Clicks "Logout" button (top right)
3. Confirmation dialog: "Apakah Anda yakin ingin logout?"
4. RT clicks "Ya, Logout"
5. JavaScript executes: `localStorage.removeItem('userSession')`
6. Session destroyed
7. Page redirects to `../index.php` (login page)
8. RT must login again tomorrow

---

## 📊 DATA DISPLAY LOGIC

### How Letter Requests Display:
- **Source:** Backend returns array of requests
- **Filtering:** Only requests where `id_rt = current_rt_id`
- **Sorting:** Newest first (by submission date)
- **Status determination:**
  - If `status = 'pending'` → Orange badge, show approve/reject buttons
  - If `status = 'approved'` → Green badge, show to RW
  - If `status = 'rejected'` → Red badge, hide

### How Complaints Display:
- **Source:** Local storage `aduanList` or backend
- **Sorting:** Newest first
- **Status categories:**
  - New/Incoming: Orange "⏳ Aduan Masuk"
  - Being handled: Green "✓ DITINDAK LANJUTI"
  - Resolved: Green "✓ SELESAI"

### How Cash Transactions Display:
- **Income:** Green text, "+" sign
- **Expense:** Red text, "-" sign
- **Balance:** Green box, largest font
- **Calculation:** Sum of all (income - expense) since opening balance

---

## 🎯 CORE FEATURES (REQUIRED)
- ✓ Session authentication (required for access)
- ✓ Approve/reject letter requests
- ✓ View resident complaints
- ✓ Escalate complaints to RW
- ✓ Record cash transactions (income/expense)
- ✓ View resident statistics
- ✓ Track incoming payments
- ✓ Daily task checklist
- ✓ Logout functionality

## ⭕ OPTIONAL FEATURES (ENHANCEMENT)
- ○ Edit/archive complaints
- ○ Generate financial reports (monthly, yearly)
- ○ Bulk resident import (CSV)
- ○ SMS/WhatsApp notifications
- ○ Digital signature on letters
- ○ Real-time payment sync with bank
- ○ Audit trail of all actions
- ○ Mobile app version

---

## 🚨 NOTES & KNOWN BEHAVIORS

### Important Notes:
1. **LocalStorage Based:**
   - Session stored in browser localStorage
   - Complaints, payments, cash stored locally
   - If user clears browser data → all data lost
   - Not ideal for production (should use database)

2. **Data Persistence:**
   - Cash book transactions persist until browser cleared
   - Complaint list updates only when warga submits new
   - No real-time sync with backend (simulated)

3. **No Real-time Notifications:**
   - Dashboard doesn't auto-refresh
   - RT must refresh page to see new complaints
   - Better: Auto-poll every 30 seconds or WebSocket

4. **Limited Error Handling:**
   - If form submission fails → console error only
   - No user-facing error messages
   - Should show error alerts/toasts

5. **Calculation Accuracy:**
   - Cash balance calculated from array
   - No validation against source documents
   - Should have audit/verification mechanism

6. **Report Generation:**
   - PDF export not fully implemented (button exists)
   - Should generate proper reports with:
     - Resident list with contact info
     - Payment records
     - Complaint tracking
     - Financial summary

---

## 📞 CONTACT & ESCALATION

**When RT Needs Help:**
- Contact RW (higher level)
- WhatsApp: 0812-9876-5432 (shown in footer)
- For complicated issues
- For financial approval

**When RT Needs to Contact Resident:**
- Via WhatsApp (personal numbers in system)
- Direct home visit
- Phone call (landline if available)

---

**End of RT Dashboard Explanation**
