# Dashboard RW (District Coordinator) - Complete Explanation

## 📋 PAGE OVERVIEW

**File:** `pages/dashboard-rw.html`  
**Purpose:** Monitoring dashboard for RW (Rukun Warga) coordinator to oversee all RTs in the district, approve final documents, monitor finances, and manage escalated complaints  
**Role:** RW (District coordinator/supervisor)  
**Access Level:** Logged-in users with role = 'rw' or 'admin'

---

## 🔐 ACCESS & SESSION CONTROL

### Who Can Access This Page?
- **Only:** Users logged in with role = `rw` OR `admin`
- **Cannot access:** Warga (residents), RT, or non-logged-in users
- **RW Scope:** Views aggregated data from ALL RTs in the district (5 RTs in RW 05)

### What Happens on Page Load?
1. **Session Check:**
   - Page loads `dashboard-rw.html`
   - JavaScript function `loadUserSession()` runs automatically
   - Checks `localStorage.getItem('userSession')` for user data

2. **If NOT Logged In:**
   - Session data is empty or missing
   - User redirected to `../index.php` (login page)
   - Cannot view any dashboard content

3. **If Logged In (RW):**
   - Session contains: `{ role: 'rw', name: 'Koordinator RW', id: '05' }`
   - Page displays RW dashboard content
   - Coordinator name appears in header
   - Dashboard title: "Dashboard Koordinator RW"

4. **If Logged In (ADMIN):**
   - Session contains: `{ role: 'admin' }`
   - Can also access this page (admin override)
   - Full RW-level access granted

5. **If Logged In (WRONG ROLE):**
   - If role !== 'rw' AND role !== 'admin' (e.g., warga or rt)
   - User redirected to login page
   - Wrong role users cannot see RW dashboard

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
  - Breadcrumb navigation: "Beranda • Dashboard RW"
  - Title: "Dashboard Koordinator RW"
  - Subtitle: "Monitoring & rekap seluruh RT"
  - Data attribute: `data-role="rw"`

- **Right Side:**
  - RW coordinator name display (from session)
  - Logout button
  - Role badge: "RW" (highlighted)

### Layout Structure
- **Container-based:** Full-width with centered max-width
- **Section Blocks:** Each feature in white card with shadow
- **Responsive:** Works on desktop, tablet, mobile

---

## 📊 MAIN DASHBOARD SECTIONS (In Order)

---

## 1️⃣ RINGKASAN MINGGU INI (THIS WEEK'S SUMMARY)

**Section Header:**
- Title: "RINGKASAN MINGGU INI (19-25 JANUARI 2025)"
- Shows specific date range

**Purpose:**
- Quick overview of RW-wide activity for the week
- High-level metrics across all 5 RTs
- Status check of district operations

**What's Shown:**
- 4 summary cards with key metrics

### Summary Card #1: Letter Requests

**Value:** "12"  
**Label:** "Permohonan Surat" (Letter Requests)  
**Details:** "8 ✓ | 2 ✗ | 2 ⏳"

**Meaning:**
- Total 12 letter requests received this week
- 8 approved (✓)
- 2 rejected (✗)
- 2 still pending (⏳)

**Related Section:** REKAP PERMOHONAN SURAT (Letter recap)

### Summary Card #2: Complaints

**Value:** "5"  
**Label:** "Aduan Warga" (Resident Complaints)  
**Details:** "3 selesai | 2 proses"

**Meaning:**
- Total 5 complaints received
- 3 resolved/completed
- 2 still in progress

**Related Section:** ADUAN MASUK DARI RT (Escalated complaints)

### Summary Card #3: Dues Collection

**Value:** "Rp 4,2 Jt"  
**Label:** "Iuran Terkumpul" (Dues Collected)  
**Details:** "85% dari target" (85% of target)

**Meaning:**
- Total dues collected across all RTs: Rp 4.2 million
- 85% of monthly target achieved
- 15% shortfall to reach goal

**Related Section:** KEUANGAN PER RT (Financial monitoring)

### Summary Card #4: Population

**Value:** "231"  
**Label:** "Total Warga" (Total Residents)  
**Details:** "5 RT aktif" (5 active RTs)

**Meaning:**
- Total 231 families (KK) in RW 05
- All 5 RTs are active
- Households distributed across RTs

### Info Box

**Content:**
- "📊 Status Umum: Operasional lancar. Ada 1 aduan infrastruktur menunggu follow-up RW."
- Overall status: Operations running smoothly
- Note: 1 infrastructure complaint pending RW follow-up

---

## 2️⃣ ADUAN MASUK DARI RT (INCOMING COMPLAINTS FROM RTs)

**Section Header:**
- Title: "ADUAN MASUK DARI RT"

**Subtitle:**
- "Aduan yang diteruskan pengurus RT ke RW akan tampil di sini untuk ditindaklanjuti."

**Data Source:**
- LocalStorage: `aduanRWList` array
- Populated when RT escalates complaint (via "📢 Lapor ke RW" button)
- Function: `renderAduanFromRT()`

**Purpose:**
- RW sees all complaints escalated from RTs
- RW decides on action (handle, assign, reject)
- Track escalated issues at district level

---

### Example Complaint (Dynamic from localStorage)

**Information Displayed:**
- **Title:** "Banjir di Gang Murai" (or other complaint)
- **Description:** "Setiap hujan, gang mawar selalu banjir karena saluran air macet."
- **From:** "Dina Nurhayati (RW05/001)"
- **Original Submission:** "Masuk: 24 Januari 2025"
- **Forwarded By:** "Diteruskan oleh: Pengurus RT 05"
- **Status Badge:** "⏳ Menunggu Respon RW" (orange, pending RW response)

**Card Styling:**
- Class: `aduan-item`
- Border-left: Colored accent
- Shows escalation chain

**Available Action Buttons (2 choices):**

1. **🔧 Tindaklanjuti** (Handle/Follow-up)
   - onclick: `showToast('Aduan ditandai sedang ditindaklanjuti RW', 'success')`
   - What happens:
     - Toast notification: "Aduan ditandai sedang ditindaklanjuti RW"
     - RW marks as handling
     - Status changes locally

2. **📩 Minta Detail ke RT** (Request Details from RT)
   - onclick: `showToast('Permintaan detail dikirim ke RT', 'info')`
   - What happens:
     - Toast: "Permintaan detail dikirim ke RT"
     - RT notified to provide more info
     - Request tracked for follow-up

**User Scenario (RW):**
1. RW sees complaint forwarded from RT 05
2. Reviews issue (flooding problem)
3. Decides action needed
4. Clicks "🔧 Tindaklanjuti"
5. RW handles at district level (coordinate with kelurahan, etc.)

---

### No Complaints State

**Display:**
- "Belum ada aduan yang diteruskan ke RW." (No complaints forwarded yet)
- Shows when `aduanRWList` is empty

---

## 3️⃣ REKAP PERMOHONAN SURAT (LETTER REQUEST SUMMARY)

**Section Header:**
- Title: "REKAP PERMOHONAN SURAT - BULAN JANUARI (12 TOTAL)"

**Subtitle:**
- "Status semua permohonan surat dari kelima RT di wilayah RW 05:"

**Purpose:**
- RW sees all letter requests across all RTs
- Final approval authority
- Monitor processing status per RT
- Identify bottlenecks

**Data Shown:**
- Summary table: One row per RT
- Columns: RT | Total | Disetujui (Approved) | Ditolak (Rejected) | Proses (Processing)

---

### Summary Table Header

**Columns:**
1. **RT** - RT identifier (RT 01, 02, 03, etc.)
2. **Total** - Total requests from that RT
3. **Disetujui** - Approved count (✓)
4. **Ditolak** - Rejected count (✗)
5. **Proses** - Still processing (⏳)

---

### Example Row #1: RT 01

**Data Displayed:**
- **RT:** "RT 01"
- **Total:** 4 requests
- **Progress Bar:** Visual representation (████)
- **Approved:** "3 ✓" (green text)
- **Rejected:** "0" (gray)
- **Processing:** "1 ⏳" (orange)

**Status:** All requests accounted for (3+0+1=4)

---

### Example Row #2: RT 02

**Data Displayed:**
- **RT:** "RT 02"
- **Total:** 3 requests
- **Progress Bar:** Visual representation (███)
- **Approved:** "3 ✓"
- **Rejected:** "0"
- **Processing:** "0 ⏳"

**Status:** All approved, no pending

---

### Example Row #3: RT 03

**Data Displayed:**
- **RT:** "RT 03"
- **Total:** 3 requests
- **Progress Bar:** Visual representation (███)
- **Approved:** "2 ✓"
- **Rejected:** "0"
- **Processing:** "1 ⏳"

---

### Example Row #4: RT 04

**Data Displayed:**
- **RT:** "RT 04"
- **Total:** 2 requests
- **Progress Bar:** Visual representation (██)
- **Approved:** "0 ✓"
- **Rejected:** "0"
- **Processing:** "2 ⏳"

**Status:** ALL pending (no approvals yet) - FLAG: Slow processing

---

### Example Row #5: RT 05

**Data Displayed:**
- **RT:** "RT 05"
- **Total:** [N] requests
- **Approved:** "[N] ✓"
- **Rejected:** "[N]"
- **Processing:** "[N] ⏳"

---

### Info Box

**Content:**
- "📝 Catatan: RT 04 perlu diingatkan untuk mempercepat proses validasi permohonan."
- Note: RT 04 is slow, needs reminder to speed up approval process

---

## 4️⃣ KEUANGAN PER RT (FINANCIAL STATUS PER RT)

**Section Header:**
- Title: "KEUANGAN PER RT - BULAN JANUARI 2025"

**Subtitle:**
- "Ringkasan transaksi kas setiap RT untuk monitoring kesehatan keuangan wilayah RW:"

**Data Source:**
- Backend: `php/rw-keuangan.php?action=keuangan_rt`
- Shows financial health of each RT

**Purpose:**
- RW monitors cash position of all RTs
- Identify RTs with financial problems
- Ensure transparency across district
- Detect anomalies/mismanagement

---

### Example RT #1: RT 01 (Healthy)

**Information Displayed:**
- **RT Leader:** "RT 01 (Ketua: Pak Hendra)"
- **Income:** "Rp 1.050.000"
- **Expenses:** "Rp 500.000"
- **Balance:** "Rp 2.100.000"
- **Status Badge:** "✓ NORMAL" (green)

**Meaning:**
- Positive cash flow: 1.050.000 - 500.000 = 550.000
- Healthy balance: Rp 2.1 million
- No financial red flags

---

### Example RT #2: RT 02 (Warning)

**Information Displayed:**
- **RT Leader:** "RT 02 (Ketua: Ibu Sulastri)"
- **Income:** "Rp 950.000"
- **Expenses:** "Rp 600.000"
- **Balance:** "Rp 1.800.000"
- **Status Badge:** "⚠️ PERLU CEK" (orange/warning)

**Meaning:**
- Lower income than RT 01
- Higher expenses relative to income
- Balance declining trend
- Needs RW to check/audit

**Action:** RW should contact RT 02 to review finances

---

### Example RT #3: RT 03 (Healthy)

**Information Displayed:**
- **RT Leader:** "RT 03 (Ketua: Pak Bambang)"
- **Income:** "Rp 850.000"
- **Expenses:** "Rp 700.000"
- **Balance:** "Rp 1.200.000"
- **Status Badge:** "✓ NORMAL" (green)

**Meaning:**
- Stable financials
- Expenses controlled
- Positive position

---

### Example RT #4: RT 04 (Healthy)

**Information Displayed:**
- **RT Leader:** "RT 04 (Ketua: Pak Rinto)"
- **Income:** "Rp 1.200.000"
- **Expenses:** "Rp 400.000"
- **Balance:** "Rp 3.500.000"
- **Status Badge:** "✓ NORMAL" (green)

**Meaning:**
- Strong income collection (highest)
- Low expenses (well-managed)
- Highest balance in district
- Financial model to follow

---

### Example RT #5: RT 05 (Healthy)

**Information Displayed:**
- **RT Leader:** "RT 05 (Ketua: Pak Rahmat)"
- **Income:** "Rp 1.100.000"
- **Expenses:** "Rp 800.000"
- **Balance:** "Rp 3.100.000"
- **Status Badge:** "✓ NORMAL" (green)

---

### Summary Box (RW Totals)

**Content:**
- **Total Income:** "Rp 5.150.000" (sum of all RTs)
- **Total Expenses:** "Rp 3.000.000"
- **Combined Balance:** "Rp 11.700.000" (RW-wide cash position)

**Meaning:**
- District-wide financial health snapshot
- All RTs combined position
- Strong positive cash flow
- Healthy district finances

---

## 5️⃣ STATISTIK WARGA (RESIDENT STATISTICS)

**Section Header:**
- Title: "STATISTIK WARGA RW 05 (DATA TERBARU: 25 JANUARI 2025)"

**Purpose:**
- Demographic data for all RW
- Population breakdown by RT
- Track changes over time

---

### Summary Cards

**Card 1: Total Families (RW)**

**Value:** "231"  
**Label:** "Total KK RW"  
**Meaning:** Total 231 households in entire RW 05

---

### Individual RT Cards (Population Distribution)

**Card 2: RT 01**

**Value:** "45"  
**Label:** "RT 01"

---

**Card 3: RT 02**

**Value:** "44"  
**Label:** "RT 02"

---

**Card 4: RT 03**

**Value:** "48"  
**Label:** "RT 03"

---

**Additional Cards (implied):**
- RT 04: [value]
- RT 05: [value]

**Total Check:** 45 + 44 + 48 + [RT04] + [RT05] = 231

---

## 🔄 DATA LOADING FLOW (On Page Load)

```
1. User opens dashboard-rw.html
2. Browser loads HTML, CSS, JavaScript files
3. loadUserSession() runs:
   - Checks localStorage for session
   - If not found → redirect to login
   - If found + (role = 'rw' OR role = 'admin') → continue
   - If found + wrong role → redirect to login
   
4. configureHeaderByRole() updates header:
   - Sets page title: "Dashboard Koordinator RW"
   - Sets subtitle: "Monitoring & rekap seluruh RT"
   - Sets CTA label: "Rekap RW" with 📊 icon
   
5. Parallel data loads:
   - renderAduanFromRT() → Load escalated complaints from localStorage
   - updateNotificationBadge() → Show count of pending complaints
   - initUserMenu() → Setup user menu interactions
   - initProfileMenuItem() → Setup profile link
   
6. Dashboard ready for user interaction
```

---

## 📱 USER INTERACTIONS & ACTIONS

### Possible RW User Actions

| Action | Button/Element | Function Called | Result |
|--------|---|---|---|
| Handle Escalated Complaint | "🔧 Tindaklanjuti" | Toast notification | Status marked as RW handling |
| Request Details from RT | "📩 Minta Detail ke RT" | Toast notification | RT notified for more info |
| View Full Letter List | Summary table | Navigation | Shows detailed letter list |
| Approve RT Report | "✓ Setujui" (implied) | `handleValidasiLaporan()` | Report archived, confirmed |
| Reject RT Report | "❌ Kembalikan" (implied) | `handleValidasiLaporan()` | Report returned with reason |
| Send Reminder to RT | "📞 Kirim Reminder" (implied) | `handleValidasiLaporan()` | RT reminded to submit |
| Logout | "Logout" button | `handleLogout()` | Session destroyed, redirect to login |

---

## 🎨 STATUS BADGES & COLOR CODING

### Status Badges Used

| Badge | Color | Meaning | When Shown |
|-------|-------|---------|-----------|
| ✓ NORMAL | Green | Financial status healthy | RT with good finances |
| ⚠️ PERLU CEK | Orange | Needs checking/audit | RT with declining finances |
| ⏳ Menunggu Respon RW | Orange | Awaiting RW action | Escalated complaints |
| ✓ SELESAI | Green | Complaint resolved | (in complaint histories) |

---

## 🔒 SECURITY & ACCESS RULES

### Can Access This Page?
- ✓ Users with role = 'rw'
- ✓ Users with role = 'admin'
- ✓ Users with valid active session
- ✓ Users logged in via index.php as RW/Admin

### Cannot Access This Page?
- ✗ Non-logged-in users
- ✗ Users with role = 'warga'
- ✗ Users with role = 'rt'
- ✗ Users with expired/invalid session

### Session Validation Method
- Client-side: `localStorage.getItem('userSession')`
- Contains: User ID, name, role (rw or admin)
- Checked on page load
- Re-checked for each action

### What Data Can RW See?
- ✓ Letter requests from ALL RTs (aggregate)
- ✓ Complaints escalated from ALL RTs
- ✓ Financial summaries for ALL RTs
- ✓ Population statistics for entire RW
- ✓ Weekly/monthly reports from RTs
- Cannot see: Individual warga accounts, private RT operations, detailed complaints from lower levels

---

## ⚡ COMMON RW USER SCENARIOS

### Scenario 1: First-Time Login (RW)
1. RW coordinator logs in at index.php
2. Session created, stored in localStorage
3. Page redirects to dashboard-rw.html
4. Session validated (role = 'rw' or 'admin')
5. Dashboard loads with RW-wide data
6. RW sees:
   - Weekly summary (4 cards)
   - Complaints forwarded from RTs
   - Letter status across all RTs
   - Financial health per RT
   - Population statistics

### Scenario 2: Review Weekly Performance
1. RW opens dashboard
2. Sees "RINGKASAN MINGGU INI" cards:
   - 12 letter requests (8 approved, 2 rejected, 2 pending)
   - 5 complaints (3 resolved, 2 in progress)
   - Rp 4.2M dues collected (85% target)
   - 231 total residents, 5 RTs active
3. Assesses: Overall good week, slight shortfall on dues
4. Decides: Follow up with low-collecting RTs

### Scenario 3: Handle Escalated Complaint
1. RW sees complaint: "Banjir di Gang Murai"
2. Forwarded by RT 05
3. Status: "⏳ Menunggu Respon RW"
4. Reviews complaint details
5. Decides: This needs city/kelurahan level coordination
6. Clicks "🔧 Tindaklanjuti"
7. Toast: "Aduan ditandai sedang ditindaklanjuti RW"
8. RW contacts kelurahan (city government) to address
9. Later: Updates progress, resolves at city level

### Scenario 4: Monitor Financial Health
1. RW checks "KEUANGAN PER RT"
2. Sees all 5 RTs with financial data
3. Notices: RT 02 has "⚠️ PERLU CEK" (warning)
4. Income lower than other RTs
5. Expenses high relative to income
6. Balance declining
7. RW action: Contact Ibu Sulastri (RT 02 leader)
   - Ask: Why expenses high?
   - Ask: Why income low (payment collection)?
   - Offer: RW support for cost control
   - Schedule: Financial review meeting

### Scenario 5: Review Letter Processing
1. RW checks "REKAP PERMOHONAN SURAT"
2. Table shows:
   - RT 01: 3 approved, 0 rejected, 1 pending = 4 total (Good)
   - RT 02: 3 approved, 0 rejected, 0 pending = 3 total (Good)
   - RT 03: 2 approved, 0 rejected, 1 pending = 3 total (Good)
   - RT 04: 0 approved, 0 rejected, 2 pending = 2 total (RED FLAG)
   - RT 05: [data]
3. Notes: "RT 04 perlu diingatkan untuk mempercepat proses validasi"
4. Info box recommends: Remind RT 04
5. RW action: Send reminder to Pak Rinto (RT 04)
   - Message: "Please expedite letter approvals, 2 pending"
   - Offer: Help if issues
   - Deadline: Complete by end of week

### Scenario 6: Request More Details on Complaint
1. RW sees infrastructure complaint from RT 03
2. Needs more details before escalating to city
3. Clicks "📩 Minta Detail ke RT"
4. Toast: "Permintaan detail dikirim ke RT"
5. RT 03 leader (Pak Bambang) receives request
6. RT provides: Photos, location mapping, cost estimates
7. RW can then take informed action

### Scenario 7: Logout
1. RW finishes district oversight work
2. Clicks "Logout" button (top right)
3. Confirmation dialog: "Apakah Anda yakin ingin logout?"
4. RW clicks "Ya, Logout"
5. JavaScript executes: `localStorage.removeItem('userSession')`
6. Session destroyed
7. Page redirects to `../index.php` (login page)
8. RW must login again next time

---

## 📊 DATA DISPLAY LOGIC

### How Summary Cards Display:
- **Source:** Aggregate from all RTs
- **Filtering:** Sum across all 5 RTs
- **Calculation:**
  - Total letters: Sum of approved + rejected + processing
  - Total complaints: Sum across all RTs
  - Dues collected: Sum from all RT payment records
  - Population: Sum of all families

### How Letter Summary Table Displays:
- **Source:** Recap from each RT
- **Organization:** One row per RT (5 rows)
- **Calculation:**
  - Total = Approved + Rejected + Processing
  - Progress bar: Visual representation of completion

### How Financial Data Displays:
- **Source:** Cash book summaries from each RT
- **Color coding:**
  - Green (✓ NORMAL): Positive cash flow, healthy balance
  - Orange (⚠️ PERHA CEK): Revenue declining, expenses increasing, lower balance
- **RW Summary:** Total = Sum of all RT transactions

---

## 🎯 CORE FEATURES (REQUIRED)
- ✓ Session authentication (required for access)
- ✓ View weekly performance summary
- ✓ See escalated complaints from RTs
- ✓ Review letter request status per RT
- ✓ Monitor financial health per RT
- ✓ View population statistics
- ✓ Handle escalated issues
- ✓ Request details from RTs
- ✓ Logout functionality

## ⭕ OPTIONAL FEATURES (ENHANCEMENT)
- ○ Approve/reject RT monthly reports
- ○ Send formal reminders to RTs
- ○ Generate monthly compliance reports
- ○ Financial audit trail
- ○ Resident complaint tracking (detailed)
- ○ Performance scoring per RT
- ○ Escalation to city/kelurahan
- ○ SMS/WhatsApp notifications to RTs
- ○ Export reports to PDF/Excel

---

## 🚨 NOTES & KNOWN BEHAVIORS

### Important Notes:
1. **LocalStorage Based:**
   - Session stored in browser localStorage
   - Complaint data loaded from RT escalations
   - Financial data from RT reports
   - Not ideal for production (should use database)

2. **Aggregate Data:**
   - RW sees summary/aggregate data only
   - Not detailed individual transaction view
   - Balance is aggregate across RTs, not drilled-down

3. **No Real-time Updates:**
   - Dashboard doesn't auto-refresh
   - RW must refresh to see latest complaints
   - Better: Auto-poll every 30 seconds or WebSocket

4. **Limited Reporting:**
   - Summary cards only
   - No historical trends
   - No year-over-year comparison
   - Should add: Charts, graphs, trend analysis

5. **Notification System:**
   - Complaint count shown in badge
   - No pop-up alerts
   - Should add: Real-time notifications, sound alerts

6. **Report Management:**
   - Function `handleValidasiLaporan()` exists
   - Can approve/reject RT monthly reports
   - Can send reminders to RTs
   - Implementation: Via modals, not fully integrated

---

## 📞 CONTACT & ESCALATION

**When RW Needs Help:**
- Contact Kelurahan (city government)
- Phone: 0274-1234567 (shown in footer)
- For city-level issues
- For budget/policy decisions

**When RW Contacts RTs:**
- Via WhatsApp (numbers on file)
- Phone calls for urgent matters
- Formal meetings for reviews
- Email for documentation

**Escalation Chain:**
- Warga → RT (complaints, requests)
- RT → RW (escalated issues, reports)
- RW → Kelurahan (city coordination)

---

## 🔄 REPORT HANDLING (Advanced Features)

### Approve RT Monthly Report
**Function:** `handleValidasiLaporan(rt, 'setuju')`
1. RW sees RT's monthly report (implied)
2. Clicks "✓ Setujui" (if shown)
3. Confirmation: "Setujui laporan bulanan dari [RT]?"
4. If confirmed:
   - Report marked APPROVED
   - Archived in laporanList
   - RT notified
   - Next month cycle begins

### Reject RT Monthly Report
**Function:** `handleValidasiLaporan(rt, 'tolak')`
1. RW reviews report
2. Finds issues/discrepancies
3. Clicks "❌ Kembalikan"
4. Modal opens: "Alasan mengembalikan laporan [RT]:"
5. Textarea for reason (required)
6. RW types: "Ada pembayaran yang tidak terverifikasi, mohon koreksi"
7. Clicks "❌ Kembalikan"
8. Report marked REJECTED
9. RT receives reason and must fix
10. Resubmitted for approval

### Send Reminder to RT
**Function:** `handleValidasiLaporan(rt, 'reminder')`
1. RW notices RT hasn't submitted monthly report
2. Clicks "📞 Kirim Reminder"
3. Modal: "Pengingat akan dikirim ke [RT]"
4. Clicks "📞 Kirim Reminder"
5. Toast: "Reminder telah dikirim ke [RT]"
6. RT receives notification to submit report
7. RT submits within reminder window

---

**End of RW Dashboard Explanation**
