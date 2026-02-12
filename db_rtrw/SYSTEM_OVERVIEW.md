# RT/RW Information System - Complete Explanation

## WHAT IS THIS SYSTEM?

This system helps manage information and services in a neighborhood (RT/RW).

**RT** = Rukun Tetangga (neighborhood unit, ~40-50 families)  
**RW** = Rukun Warga (district, made up of 5 RTs)

The system lets three types of users do different things:
- **WARGA** (residents) - Request letters, pay dues, submit complaints
- **RT** (neighborhood leader) - Approve letters, manage money, handle complaints
- **RW** (district leader) - Monitor all RTs, approve final documents

---

## PART 1: PAGES BEFORE LOGIN (PUBLIC PAGES)

Anyone can visit these pages **WITHOUT logging in**.

### 1️⃣ index.php - LOGIN PAGE

**Who can see this?** Anyone (no login needed)

**What's on this page:**
- A login box in the center
- Fields for: Username and Password
- Two tabs: "LOGIN" and "DAFTAR" (Register)
- Beautiful gradient background

**What each feature does:**

| Feature | What it does |
|---------|-------------|
| **Username Field** | Type your username here |
| **Password Field** | Type your password here |
| **Login Button** | Click to sign in |
| **Register Tab** | Show form to create new account |
| **Demo Info** | Shows example usernames/passwords for testing |

**What happens when you click LOGIN:**
1. System checks if username and password are correct
2. If correct → Takes you to your dashboard (based on your role)
3. If wrong → Shows error message "Username or password incorrect"
4. System remembers you are logged in

**Default test accounts:**
- `budi_santoso` (warga/resident)
- `rt05` (RT leader)
- `rw05` (RW leader)

---

### 2️⃣ beranda.html - HOME PAGE

**Who can see this?** Anyone (no login needed)

**Purpose:** Show general information about RT 05

**What's on this page:**

| Section | What it shows |
|---------|--------------|
| **Header** | Title "RT 05 KELURAHAN MAJU JAYA" + Login button |
| **Hero Section** | Big title with information about the community |
| **Navigation Menu** | Buttons to visit: Profil, Pengumuman, Galeri, Login |
| **Info Cards** | 3-4 cards showing community statistics |
| **Latest Announcements** | Quick news snippets |
| **Quick Links** | Buttons to other public pages |

**What you can do:**
- Click "LOGIN" button to go to login page
- Click "PROFIL" to see community leaders
- Click "PENGUMUMAN" to read announcements
- Click "GALERI" to see event photos

---

### 3️⃣ profil.html - COMMUNITY PROFILE

**Who can see this?** Anyone (no login needed)

**Purpose:** Show who leads the community and how many people live here

**What's on this page:**

| Section | What it shows |
|---------|--------------|
| **RT Information** | Name: RT 05, RW: 05, District: Maju Jaya |
| **Statistics** | Total families: 45, Total people: 156, Men: 78, Women: 78 |
| **Leadership Structure** | Photos/names of: Ketua RT (leader), Wakil Ketua (vice), Sekretaris (secretary), Bendahara (treasurer) |
| **Contact Info** | Phone numbers of leaders |

**What you can do:**
- Read information about the RT
- See who the leaders are
- Get contact numbers for the leaders
- View the organizational structure

---

### 4️⃣ pengumuman.html - ANNOUNCEMENTS PAGE

**Who can see this?** Anyone (no login needed)

**Purpose:** Read important news and announcements

**What's on this page:**

| Feature | What it does |
|---------|-------------|
| **Filter Buttons** | Filter announcements by: All, Important ⚠️, Meeting 📅, Dues 💰, Activities 🎉 |
| **Announcements List** | Shows latest announcements with: Title, Date, Description |
| **Color Coding** | Different colors for different types: Yellow=Important, Red=Urgent, Blue=Information |

**Example announcements shown:**
1. **"IMPORTANT: Regular RT Meeting"**
   - Date: Friday, January 24, 2025
   - Time: 7:00 PM
   - Location: Ketua RT's house
   - Attendance expected from each family

2. **"Road Repair in Gang Mawar"**
   - Cost: Rp 20,000 per family
   - Deadline: January 30, 2025
   - Work date: Sunday, January 25, 2025

3. **"Lottery Results (Year-End Draw)"**
   - Main prize: Motorcycle
   - 2nd prize: 32" TV
   - 3rd prize: Rice vouchers

**What you can do:**
- Read all announcements
- Filter by category
- See posting date
- Click back to home page

---

### 5️⃣ galeri.html - GALLERY/PHOTO PAGE

**Who can see this?** Anyone (no login needed)

**Purpose:** View photos from community events

**What's on this page:**

| Section | What it shows |
|---------|--------------|
| **Photo Grid** | Photos arranged in a grid (3-4 columns) |
| **Photo Cards** | Each card shows: Title, Date, Description |
| **Hover Effect** | Photo card rises up slightly when you hover over it |

**Example photos:**
- "Year-End Lottery Announcement" - Jan 15, 2025
- "Road Repair Event" - Photos of community working together
- "Monthly Meeting" - Photo from a meeting
- "Community Gathering" - Social event photo

**What you can do:**
- Click a photo to see more details
- Read description and date
- Go back to home page

---

## PART 2: PAGES AFTER LOGIN (PRIVATE/ROLE-BASED)

You **MUST log in** to see these pages. Different roles see different dashboards.

---

## HOW LOGIN WORKS

**Step 1: User Types Username & Password**
- Example: `budi_santoso` + password

**Step 2: System Checks**
- Looks in database: "Is this username real?"
- Checks: "Is password correct?"

**Step 3: System Finds User's ROLE**
- Example: Finds that `budi_santoso` is a **WARGA** (resident)
- System also stores: User's name, ID, family info

**Step 4: Creates Session**
- Stores login info in the server
- Browser remembers you're logged in
- System knows your role

**Step 5: Redirect to Correct Dashboard**
- WARGA → Goes to **dashboard-warga.html**
- RT → Goes to **dashboard-rt.html**
- RW → Goes to **dashboard-rw.html**

**Step 6: Dashboard Loads**
- Each dashboard has different features based on role
- Dashboard checks: "Are you logged in and in the right role?"
- If not → Redirect back to login page

---

## WARGA DASHBOARD - dashboard-warga.html

**Who can see this?** Only residents who logged in as WARGA

**What's the purpose?** Let residents request letters, pay dues, submit complaints

### FEATURES ON WARGA DASHBOARD:

#### 1. **PENGAJUAN SURAT (LETTER REQUESTS)**

**What is this?** System for requesting official letters from the RT

**Types of letters residents can request:**
- ✅ Surat Keterangan Usaha (Business Permission Letter)
- ✅ Surat Domisili (Residence Verification Letter)
- ✅ Surat Izin Usaha (Business License Letter)
- ✅ Other official documents

**How it works:**
1. Resident clicks button to request a letter
2. Selects the type of letter needed
3. System sends request to RT leader
4. RT leader reviews and approves/rejects
5. RW leader does final approval
6. Resident can download the letter

**Status flow shown:**
```
Submitted → RT Checking → RW Checking → Ready to Pick Up → Completed
```

**What you see:**
- List of your previous requests
- Status of each (Pending, Approved, Rejected, Completed)
- Date submitted
- Button to "Download Letter" if ready
- Button to submit new request

---

#### 2. **IURAN BULANAN (MONTHLY DUES)**

**What is this?** System to pay monthly neighborhood fees

**Monthly fee:** Rp 50,000 per family per month

**What the money is used for:**
- Road maintenance
- Street lights
- Community events
- Administrative costs

**Payment methods:**
- 💳 Cash (direct to treasurer)
- 💸 Bank transfer
- 📱 QRIS (mobile payment)

**What you see:**
- Current month's dues: Rp 50,000 (Belum Bayar = Not Paid)
- List of previous months paid/unpaid
- Amount and payment date
- Buttons: "Bayar Sekarang" (Pay Now), "QRIS", "See Details"

**What happens when you click "Bayar Sekarang":**
1. Opens payment form
2. You choose payment method
3. You enter amount
4. System records the payment
5. Shows confirmation message
6. Payment status changes to "LUNAS" (Paid)

**Summary shown:**
- Total owed this month
- Total paid this year

---

#### 3. **ADUAN / ASPIRASI (COMPLAINTS & SUGGESTIONS)**

**What is this?** System to report problems or give suggestions to the RT

**Types of complaints:**
- 🛣️ Infrastructure (broken roads, water, electricity)
- 🚨 Security & safety issues
- 🧹 Cleanliness & environment
- ❤️ Social & welfare
- 💰 Money/finance questions
- 📝 Other

**What you see:**
- Form with fields: Title, Category, Description
- Text area to explain the problem
- Checkbox to agree to the terms
- "Submit" button

**What happens when you submit:**
1. Your complaint goes to RT leader
2. RT leader reads and responds
3. Shows message: "Your complaint was received. We will follow up within 1 week"
4. You get notification when RT leader responds

**Example complaints in system:**
- "Jalan berlubang di depan toko" (Road hole in front of shop)
- "Lampu jalan mati" (Street light broken)
- "Saluran air tersumbat" (Drain blocked)

---

#### 4. **PENGUMUMAN TERBARU (LATEST ANNOUNCEMENTS)**

**What is this?** Show important news from RT/RW

**What you see:**
- 3-4 latest announcements
- Color-coded by importance
- Yellow = Warning, Red = Urgent, Blue = Info
- Announcement title, date, description

**Example announcements:**
- "PENTING: Rapat Rutin RT 05" (Important: RT Meeting)
  - Date & time
  - Location
  - What will be discussed
  - Button: "Tandai Akan Hadir" (Mark Attendance)

- "Iuran Tambahan: Perbaikan Jalan" (Extra dues: Road Repair)
  - Cost: Rp 20,000 per family
  - Deadline: January 30
  - Work date: January 25
  - Button: "Bayar Iuran Tambahan" (Pay Extra Dues)

- "Undian Akhir Tahun" (Year-End Lottery)
  - Prize: Motorcycle, TV, Rice vouchers
  - Your status: "Tidak menang" (Not won)
  - Button: "Lihat Daftar Pemenang" (See Winners)

**What you can do:**
- Read announcements
- Click buttons to respond or take action
- Click "Lihat Semua Pengumuman" to see all announcements

---

### WARGA DASHBOARD SUMMARY

| Feature | What it does | What you can do |
|---------|-------------|-----------------|
| **Letter Requests** | Request official documents | Submit request, track status, download |
| **Monthly Dues** | Pay neighborhood fees | See amount, choose payment method, pay |
| **Complaints Form** | Report problems | Submit complaint, choose category |
| **Announcements** | See latest news | Read, respond, see details |

---

## RT DASHBOARD - dashboard-rt.html

**Who can see this?** Only the RT leader (neighborhood leader) who logged in

**What's the purpose?** Manage all requests from residents, handle money, manage problems

### FEATURES ON RT DASHBOARD:

#### 1. **TODAY'S PRIORITIES CHECKLIST**

**What is this?** Daily to-do list for RT leader

**Example tasks:**
- ☐ Validate 3 letter requests from residents
- ☐ Input cash book entries (road repair payments)
- ☐ Call Dina (3 months behind on dues)
- ☐ Verify new resident family data
- ☐ Prepare tomorrow's RT meeting (attendance list, topics)

**What you can do:**
- Check boxes as you complete tasks
- Helps keep track of daily work

---

#### 2. **PERMOHONAN SURAT PENDING (PENDING LETTER REQUESTS)**

**What is this?** List of all resident letter requests waiting for RT approval

**What you see for each request:**
- Resident's name
- Letter type requested
- Purpose/reason
- Resident's address & family number
- Submission date
- Current status

**Example pending requests:**
1. **Budi Santoso** - Business Permission Letter
   - Purpose: Open a warung (small shop) at home
   - Address: Jl. Mawar No. 12 RT 05
   - Submitted: January 15, 2025

2. **Siti Nurhaliza** - Residence Verification Letter
   - Purpose: For ID card application
   - Address: Jl. Melati No. 5 RT 05
   - Submitted: January 18, 2025

3. **Ahmad Gunawan** - Business License Letter
   - Purpose: Open electronics store
   - Address: Jl. Raya No. 45 RT 05 / RW 05
   - Submitted: January 20, 2025

**What RT leader can do:**
- Click **"✓ Setujui"** (Approve) → Send to RW for final approval
- Click **"✗ Tolak"** (Reject) → Reject with reason
- Click **"? Lebih Info"** (More Info) → Get resident's full details
- Add notes/comments

**After RT approves:** Resident gets notified, request goes to RW leader

---

#### 3. **PEMBAYARAN IURAN MASUK (INCOMING PAYMENTS)**

**What is this?** Shows which residents paid their dues

**What you see:**
- Resident's name
- Amount paid: Rp 50,000
- Payment date
- Payment method (Cash/Transfer/QRIS)
- Notification if payment received

**What RT leader can do:**
- Confirm payment received ✓
- Record payment in system
- Track who hasn't paid

---

#### 4. **ADUAN / KELUHAN WARGA (RESIDENT COMPLAINTS)**

**What is this?** List of all complaints/problems reported by residents

**What you see for each complaint:**
- Complaint title
- Who reported it
- Date reported
- Current status (New, Being handled, Completed)
- Description of problem

**Example complaints in RT dashboard:**
1. **🆕 Banjir di Gang Murai** (Flooding in Gang Murai)
   - From: Dina Nurhayati
   - Description: "Every rain, gang mawar floods because drain is blocked"
   - Status: ⚠️ Needs follow-up
   - Buttons: "Mark as being handled", "Report to RW", "Call resident"

2. **Jalan Berlubang** (Road hole)
   - From: Pak Bambang
   - Status: ✓ Being handled
   - Note: "Confirmed. Will report to RW for road renovation"

3. **Lampu Jalan Mati** (Street light broken)
   - From: Siti Rahayu
   - Status: ✓ Completed
   - Note: "Fixed on Jan 15. Technician replaced the light"

**What RT leader can do:**
- Mark complaint status: New → Being handled → Completed
- Add notes about what action was taken
- Click "Lapor ke RW" (Report to RW) to escalate serious problems
- Call resident for more info

---

#### 5. **BUKU KAS RT (CASH BOOK - MONEY TRACKING)**

**What is this?** Record of all money coming in and going out

**What you see:**
- Opening balance (money from previous month)
- All income entries (dues paid, donations, etc.)
- All expense entries (prizes, supplies, payments, etc.)
- Closing balance (money left at end of month)

**Example income entries:**
- Jan 20: Regular Monthly Dues → +Rp 850,000 (17 families × Rp 50,000)
- Jan 22: Extra Dues (Road Repair) → +Rp 400,000 (20 families × Rp 20,000)

**Example expense entries:**
- Jan 15: Year-end Lottery Prizes → -Rp 500,000
- Jan 22: Buy cables & materials for road repair → -Rp 150,000
- Jan 23: Pay RT leader assistant → -Rp 50,000

**Summary shown:**
- Total Income for month
- Total Expenses for month
- Final Balance: Rp 3,050,000

**What RT leader can do:**
- Click "➕ Input Pemasukan" (Add Income) → Add new income entry
- Click "➖ Input Pengeluaran" (Add Expense) → Add new expense entry
- See all transactions in list
- Calculate balance automatically

---

#### 6. **DATA WARGA RT 05 (RESIDENT DATA)**

**What is this?** Statistics about people in the neighborhood

**What you see:**
- Total families: 45 KK
- Total residents: 156 people
- Total heads of family: 45
- Families paying dues: 42

**What RT leader can do:**
- Click "👥 Lihat Daftar Warga Lengkap" (View full resident list)
- Click "📋 Cetak Data (PDF)" (Print as PDF)
- Click "➕ Tambah Warga Baru" (Add new resident)

---

### RT DASHBOARD SUMMARY

| Feature | What it does | What RT leader can do |
|---------|-------------|----------------------|
| **Today's Checklist** | Daily task list | Check off completed tasks |
| **Pending Letters** | See letter requests waiting | Approve or reject requests |
| **Incoming Payments** | Track dues paid | Confirm & record payments |
| **Complaints** | See resident problems | Add notes, escalate to RW |
| **Cash Book** | Track money in/out | Add income/expense entries |
| **Resident Data** | View statistics | See full list, print, add new |

---

## RW DASHBOARD - dashboard-rw.html

**Who can see this?** Only the RW leader (district leader) who logged in

**What's the purpose?** Monitor all 5 RTs in the district, handle big decisions, monitor money

### FEATURES ON RW DASHBOARD:

#### 1. **RINGKASAN MINGGU INI (THIS WEEK'S SUMMARY)**

**What is this?** Quick overview of everything happening this week

**What you see (4 cards with numbers):**

| Card | Shows |
|------|-------|
| **12 Permohonan Surat** (Letter Requests) | 8 approved ✓, 2 rejected ✗, 2 pending ⏳ |
| **5 Aduan Warga** (Complaints) | 3 completed, 2 being handled |
| **Rp 4,2 Juta** (Dues Collected) | 85% of monthly target reached |
| **231 Warga** (Total Residents) | In 5 active RTs |

**Status:** "Operations running smoothly. 1 infrastructure complaint waiting for follow-up"

---

#### 2. **ADUAN MASUK DARI RT (COMPLAINTS ESCALATED FROM RTs)**

**What is this?** List of serious problems that RTs sent to RW for help

**Example escalated complaints:**
- "Banjir di Gang Murai" (Flooding) - From RT 05
- "Jalan berlubang besar" (Large road holes) - From RT 03
- "Lampu jalan tidak cukup" (Not enough street lights) - From RT 04

**What you see:**
- Which RT sent it
- Complaint title
- Description
- Current status
- When it was reported

**What RW leader can do:**
- Read details
- Mark status: New → Being handled → Completed
- Add notes about solution
- Assign budget to fix problem

---

#### 3. **REKAP PERMOHONAN SURAT (LETTER REQUEST SUMMARY)**

**What is this?** Summary of ALL letter requests from all 5 RTs

**What you see (in a table):**

| RT | Total | Approved ✓ | Rejected ✗ | Pending ⏳ |
|----|-------|-----------|-----------|----------|
| **RT 01** | 4 | 3 ✓ | 0 | 1 ⏳ |
| **RT 02** | 3 | 3 ✓ | 0 | 0 |
| **RT 03** | 3 | 2 ✓ | 0 | 1 ⏳ |
| **RT 04** | 2 | 0 | 0 | 2 ⏳ |
| **RT 05** | 1 | 0 | 0 | 1 ⏳ |

**What you see:**
- Which RT has more requests
- Which are approved/rejected/pending
- Progress bar showing completion

**What RW leader can do:**
- Click on each RT's requests to see details
- Approve final letters
- Reject if needed
- Add RW signature/stamp

---

#### 4. **MONITORING KEUANGAN PER RT (FINANCIAL MONITORING BY RT)**

**What is this?** Track money situation in each RT

**What you see (for each RT):**
- RT number
- Total income this month
- Total expenses this month
- Current balance/saldo

**Example data:**
- RT 01: Income Rp 2.5M, Expenses Rp 800K, Balance Rp 4.2M
- RT 02: Income Rp 2.0M, Expenses Rp 600K, Balance Rp 3.8M
- RT 03: Income Rp 1.8M, Expenses Rp 700K, Balance Rp 2.9M
- RT 04: Income Rp 1.5M, Expenses Rp 500K, Balance Rp 2.1M
- RT 05: Income Rp 1.25M, Expenses Rp 700K, Balance Rp 3.05M

**What RW leader can do:**
- See which RT has financial problems
- Check if money is being used properly
- Request reports from RT leaders
- Help if RT needs money for emergency

---

#### 5. **STATISTIK WARGA (RESIDENT STATISTICS)**

**What is this?** Population data for the whole RW district

**What you see:**
- Total families: 220 KK (all 5 RTs combined)
- Total residents: 870 people
- Breakdown by gender: Males 435, Females 435
- Per RT breakdown (same stats for each RT separately)

**Table showing per RT:**

| RT | Families | People | Males | Females |
|----|----------|--------|-------|---------|
| **RT 01** | 48 | 178 | 89 | 89 |
| **RT 02** | 42 | 165 | 82 | 83 |
| **RT 03** | 45 | 172 | 86 | 86 |
| **RT 04** | 40 | 159 | 80 | 79 |
| **RT 05** | 45 | 156 | 78 | 78 |

**What RW leader can do:**
- Monitor population changes
- Plan activities based on population
- See which RT has most people
- Print reports

---

### RW DASHBOARD SUMMARY

| Feature | What it does | What RW leader can do |
|---------|-------------|----------------------|
| **Weekly Summary** | Quick overview of all RTs | See key numbers at a glance |
| **Escalated Complaints** | Problems sent up from RTs | Handle serious issues, add notes |
| **Letter Recap** | All letter requests in RW | Approve final letters, reject if needed |
| **Financial Monitoring** | Track money in each RT | Check if RTs managing money well |
| **Population Stats** | Resident count per RT | Monitor population, plan activities |

---

## LOGOUT - WHAT HAPPENS

**When user clicks "Logout" button:**

1. System destroys login session
2. Clears user info from server
3. Browser goes back to login page (index.php)
4. User must log in again to access dashboards
5. All previous data is forgotten (for security)

---

# COMPLETE FEATURE COMPARISON TABLE

## PAGES BY ROLE

| Page | Public? | WARGA | RT | RW | What it does |
|------|---------|-------|----|----|-------------|
| **index.php** | ✅ | ✅ | ✅ | ✅ | Login page |
| **beranda.html** | ✅ | ✅ | ✅ | ✅ | Home page with info |
| **profil.html** | ✅ | ✅ | ✅ | ✅ | Community leaders & stats |
| **pengumuman.html** | ✅ | ✅ | ✅ | ✅ | Read announcements |
| **galeri.html** | ✅ | ✅ | ✅ | ✅ | View event photos |
| **dashboard-warga.html** | ❌ | ✅ | ❌ | ❌ | Request letters, pay dues, submit complaints |
| **dashboard-rt.html** | ❌ | ❌ | ✅ | ❌ | Approve letters, manage money, handle complaints |
| **dashboard-rw.html** | ❌ | ❌ | ❌ | ✅ | Monitor all RTs, final approvals, statistics |

---

## FEATURES BY ROLE

### WARGA (Resident) Can:
- ✅ Request official letters (Surat Domisili, Surat Usaha, etc.)
- ✅ Track letter status (Pending → Approved → Ready to pickup)
- ✅ Pay monthly dues (Rp 50,000/month)
- ✅ Submit complaints/suggestions
- ✅ View announcements from RT/RW
- ✅ Mark attendance to meetings
- ✅ Read community profile & statistics
- ✅ View community photos

### RT (Neighborhood Leader) Can:
- ✅ All that WARGA can do (read announcements, view profile, etc.)
- ✅ **Approve or reject** letter requests from residents
- ✅ View all complaints submitted by residents
- ✅ Add notes/actions to complaints
- ✅ **Escalate serious complaints** to RW
- ✅ Track payment status (who paid, who didn't)
- ✅ Record cash in/out (income & expenses)
- ✅ View daily task checklist
- ✅ View resident statistics (45 families, 156 people)

### RW (District Leader) Can:
- ✅ All that WARGA & RT can do
- ✅ **Monitor all 5 RTs** in the district
- ✅ See **summary of all letter requests** (from all RTs)
- ✅ **Final approval** of important letters
- ✅ Handle **escalated complaints** from RTs
- ✅ **Monitor financial situation** in each RT
- ✅ View **population statistics** for whole district
- ✅ See weekly/monthly summary dashboard
- ✅ Generate reports

---

## DATA FLOW EXAMPLES

### EXAMPLE 1: Resident Requests a Letter

```
1. WARGA logs in → Opens dashboard-warga.html
2. Clicks "Request Letter" → Selects "Surat Domisili"
3. Submits request → Request stored in database
4. RT leader sees it in "Pending Letter Requests"
5. RT reviews & clicks "Approve" → Request moves to RW
6. RW leader sees it in "Letter Recap"
7. RW clicks "Approve Final" → Letter is ready
8. WARGA gets notification → Can download letter
```

---

### EXAMPLE 2: Resident Pays Monthly Dues

```
1. WARGA sees: "January 2025 - Rp 50,000 - Belum Bayar" (Not paid)
2. Clicks "Bayar Sekarang" (Pay Now)
3. Chooses payment method: Cash / Bank Transfer / QRIS
4. Submits payment
5. RT leader sees payment notification
6. RT confirms payment received
7. WARGA's status changes to "LUNAS" (Paid) ✓
8. System adds to cash book: "+Rp 50,000"
```

---

### EXAMPLE 3: Resident Submits Complaint

```
1. WARGA fills form: Title, Category, Description
2. Submits form
3. RT leader sees complaint in "Aduan/Keluhan" list
4. RT reads & adds note: "Will check tomorrow"
5. If serious, RT clicks "Lapor ke RW" (Report to RW)
6. RW sees it in "Escalated Complaints"
7. RW adds solution note & marks "Completed"
8. WARGA & RT both get notification about resolution
```

---

# SUMMARY - SIMPLE VERSION

## WHAT THIS SYSTEM DOES

| User Type | Main Job | Main Features |
|-----------|----------|---------------|
| **WARGA** (Resident) | Use services from RT | Request letters, pay dues, report problems |
| **RT** (Leader) | Manage the neighborhood | Approve requests, manage money, handle complaints |
| **RW** (District Boss) | Supervise all RTs | Monitor all RTs, make final decisions, check finances |

## THE 3 BIG FUNCTIONS

1. **SURAT (Letters)** - Request & approval system
   - Residents request → RT approves → RW approves → Resident gets letter

2. **IURAN (Dues)** - Payment system
   - Residents pay → RT records → Tracked in cash book

3. **ADUAN (Complaints)** - Complaint system
   - Residents report → RT handles → Escalates to RW if serious

---

# KEY POINTS TO REMEMBER

✅ **Public pages** (beranda, profil, pengumuman, galeri) - Anyone can read  
✅ **Dashboard pages** - Only logged-in users can access (role-based)  
✅ **Each role sees different features** - Resident sees payment form, RT sees approval buttons  
✅ **Login determines your role** - System knows if you're WARGA, RT, or RW  
✅ **Logout destroys session** - You must login again to access dashboards  
✅ **Data flows up the chain** - Warga → RT → RW → Final decision

---

**END OF SYSTEM OVERVIEW**

This is a complete neighborhood management system that helps residents, leaders, and district coordinators work together efficiently!

