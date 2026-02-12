# RT/RW INFORMATION SYSTEM - IMPLEMENTATION GUIDE

## SYSTEM OVERVIEW

This is a complete, functional RT/RW Information System with role-based access for:
- **WARGA** (Residents): Submit letters, complaints, pay dues
- **RT** (Sub-district admin): Validate letters, manage complaints, maintain cash book
- **RW** (District admin): Monitor all RT operations, approve final requests

## TECHNOLOGY STACK

- **Frontend**: HTML5, CSS3, Vanilla JavaScript (no frameworks)
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Authentication**: PHP Sessions + SHA2 password hashing
- **API**: RESTful JSON endpoints

## DATABASE SETUP

### Prerequisites
- MySQL/MariaDB server running
- XAMPP or similar local development environment

### Import Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create new database: `db_rtrw`
3. Import SQL file: `database/db_rtrw.sql`

### Default Test Users (all passwords: `password`)

**WARGA (Residents)**
- Username: `budi_santoso` | Password: SHA2(password, 256)
- Username: `siti_nurhaliza` | Password: SHA2(password, 256)
- Username: `ahmad_gunawan` | Password: SHA2(password, 256)
- Username: `dina_nurhayati` | Password: SHA2(password, 256)
- Username: `eko_prasetyo` | Password: SHA2(password, 256)

**RT (Sub-district admins)**
- Username: `rt05` | Password: SHA2(password, 256)
- Username: `rt01` through `rt04` | Password: SHA2(password, 256)

**RW (District admin)**
- Username: `rw05` | Password: SHA2(password, 256)

**Admin**
- Username: `admin` | Password: SHA2(password, 256)

## PROJECT STRUCTURE

```
db_rtrw/
├── index.php                           # Login page
├── pages/
│   ├── dashboard-warga.html            # Warga dashboard
│   ├── dashboard-rt.html               # RT dashboard
│   ├── dashboard-rw.html               # RW dashboard
│   ├── beranda.html                    # Public home
│   ├── pengumuman.html                 # Announcements
│   ├── galeri.html                     # Gallery
│   └── profil.html                     # Profile
├── php/
│   ├── login.php                       # Login endpoint
│   ├── logout.php                      # Logout endpoint
│   ├── check_session.php               # Session verification
│   ├── db_connect.php                  # Database connection
│   ├── api-router.php                  # API routing & auth
│   │
│   ├── warga-pengajuan-surat.php       # Letter requests (WARGA)
│   ├── warga-aduan.php                 # Complaints (WARGA)
│   ├── warga-iuran.php                 # Dues management (WARGA)
│   ├── warga-payment.php               # Payment receipts (WARGA)
│   │
│   ├── rt-pengajuan-surat.php          # Validate letters (RT)
│   ├── rt-aduan.php                    # Manage complaints (RT)
│   ├── rt-kas.php                      # Cash book (RT)
│   ├── rt-data-warga.php               # Warga statistics (RT)
│   │
│   ├── rw-surat.php                    # Letter recap (RW)
│   ├── rw-aduan.php                    # Escalated complaints (RW)
│   ├── rw-keuangan.php                 # Financial monitoring (RW)
│   ├── rw-statistik.php                # Population stats (RW)
│   │
│   └── public-content.php              # Public announcements & gallery
│
├── assets/
│   ├── style.css                       # Global styles
│   ├── dashboard.css                   # Dashboard styles
│   ├── utils.js                        # Utility functions
│   ├── data.js                         # Shared data
│   ├── payment-system.js               # Payment logic
│   ├── dashboard-header.js             # Header & logout
│   ├── warga-dashboard.js              # Warga dashboard logic
│   ├── rt-dashboard.js                 # RT dashboard logic
│   ├── rw-dashboard.js                 # RW dashboard logic
│   └── images/                         # Assets
│
└── database/
    ├── db_rtrw.sql                     # Full database schema + sample data
    ├── config.php                      # Database config example
    └── README_DATABASE.md              # DB documentation
```

## AUTHENTICATION FLOW

### Login Process
1. User enters credentials on `index.php`
2. Form POSTs to `php/login.php`
3. Backend validates against `users` table (SHA2 password check)
4. Sets PHP `$_SESSION` with user data
5. Returns JSON with redirect URL based on role
6. Client redirects to appropriate dashboard

### Session Management
- **Server-side**: PHP SESSION stores user credentials & role
- **Client-side**: localStorage stores minimal session info
- **Session check**: Every page calls `check_session.php` on load
- **Role verification**: API endpoints validate user role before responding

### Logout Process
1. User clicks logout button
2. Frontend calls `php/logout.php` 
3. Backend destroys session
4. Client clears localStorage
5. Redirect to `index.php`

## WARGA (RESIDENT) FEATURES

### 1. Pengajuan Surat (Letter Requests)

**API Endpoint**: `php/warga-pengajuan-surat.php`

Actions:
- `?action=list` - GET all user's letter requests
- `?action=submit` - POST new letter request
- `?action=types` - GET available letter types

Database Flow:
1. User submits form with jenis_surat & tujuan
2. INSERT into `pengajuan_surat` (status = 'pending')
3. RT sees in dashboard, validates
4. RW approves final request
5. User can download when status = 'selesai'

**Status Workflow**:
```
pending → disetujui_rt → disetujui_rw → selesai → diambil
```

### 2. Aduan Warga (Complaints)

**API Endpoint**: `php/warga-aduan.php`

Actions:
- `?action=list` - GET all complaints from user
- `?action=submit` - POST new complaint

Database:
- User submits: category, title, description, location
- RT can update status: baru → diproses → selesai
- RT can escalate (prioritas = 'urgent') to RW

### 3. Iuran Bulanan (Monthly Dues)

**API Endpoint**: `php/warga-iuran.php`

Actions:
- `?action=list` - GET iuran records for user's KK
- `?action=bayar` - POST payment record

Features:
- Fetch from `iuran_warga` table (linked via kepala_keluarga)
- User can mark as 'lunas' with method (tunai/transfer/qris)
- Updates `tanggal_bayar` and `metode_bayar`

### 4. Payment System

**API Endpoint**: `php/warga-payment.php`

Actions:
- `?action=generate_receipt` - GET receipt HTML for printing
- `?action=payment_history` - GET all paid dues

Receipt includes:
- KK number and family head name
- Month & year of payment
- Amount paid
- Payment method & date
- Printable HTML format

## RT (SUB-DISTRICT ADMIN) FEATURES

### 1. Validasi Pengajuan Surat (Letter Validation)

**API Endpoint**: `php/rt-pengajuan-surat.php`

Actions:
- `?action=list` - GET pending letters for RT's area
- `?action=approve` - POST approval
- `?action=reject` - POST rejection with reason

Flow:
1. Fetch all warga's requests from kecamatan/RT area
2. RT reviews & can approve/reject
3. Approved letters go to RW for final validation
4. Rejected letters notify warga with reason

### 2. Manajemen Aduan (Complaint Management)

**API Endpoint**: `php/rt-aduan.php`

Actions:
- `?action=list` - GET complaints for RT's warga
- `?action=update_status` - POST status update
- `?action=escalate` - POST escalate to RW

Status Flow:
- baru → diproses → selesai
- Can escalate as 'urgent' to RW if needs district-level help

### 3. Buku Kas RT (Cash Book)

**API Endpoint**: `php/rt-kas.php`

Actions:
- `?action=list` - GET transactions
- `?action=summary` - GET financial summary
- `?action=categories` - GET expense categories
- `?action=add` - POST new transaction

Tracks:
- Income (pemasukan): dues collection, donations
- Expenses (pengeluaran): repairs, events, admin
- Running balance (saldo_akhir)

### 4. Data Warga (Resident Data)

**API Endpoint**: `php/rt-data-warga.php`

Actions:
- `?action=summary` - GET KK & warga count stats
- `?action=kepala_keluarga` - GET list of all households
- `?action=warga_detail` - GET details of specific family

Shows:
- Total families (KK) & people (jiwa)
- Gender breakdown
- Family details & addresses

## RW (DISTRICT ADMIN) FEATURES

### 1. Rekap Pengajuan Surat (Letter Summary)

**API Endpoint**: `php/rw-surat.php`

Actions:
- `?action=recap` - GET summary by RT
- `?action=surat_pending` - GET pending approvals
- `?action=approve_surat` - POST approval
- `?action=reject_surat` - POST rejection

Shows:
- Total requests per RT
- Approved/rejected/pending counts
- Can approve/reject final requests

### 2. Aduan Masuk (Escalated Complaints)

**API Endpoint**: `php/rw-aduan.php`

Actions:
- `?action=list` - GET escalated (urgent) complaints
- `?action=update` - POST resolution with solution

RW-level tasks:
- Review complaints escalated from RTs
- Approve solutions
- Track resolution status

### 3. Monitoring Keuangan (Financial Monitoring)

**API Endpoint**: `php/rw-keuangan.php`

Actions:
- `?action=keuangan_rt` - GET financial summary per RT
- `?action=iuran_recap` - GET dues collection status

Shows:
- Income vs expense per RT
- Running balance per RT
- Current month dues collection rate

### 4. Statistik Warga (Population Statistics)

**API Endpoint**: `php/rw-statistik.php`

Actions:
- `?action=warga_stats` - GET stats by RT
- `?action=summary` - GET RW-wide summary

Reports:
- Total families, people per RT
- Gender breakdown
- Total population in RW

## API ENDPOINTS - DETAILED

### Public Endpoints (No Auth Required)

```
GET  /php/public-content.php?action=pengumuman
GET  /php/public-content.php?action=galeri
```

### Authentication Endpoints

```
POST /php/login.php
     Body: { "username": "...", "password": "..." }
     Returns: { "success": true, "data": { ... } }

POST /php/logout.php
     Returns: { "success": true }

GET  /php/check_session.php
     Returns: { "logged_in": true/false, "role": "...", "nama": "..." }
```

### Warga Endpoints (role: warga)

```
GET  /php/warga-pengajuan-surat.php?action=list
POST /php/warga-pengajuan-surat.php?action=submit
GET  /php/warga-pengajuan-surat.php?action=types

GET  /php/warga-aduan.php?action=list
POST /php/warga-aduan.php?action=submit

GET  /php/warga-iuran.php?action=list
POST /php/warga-iuran.php?action=bayar

GET  /php/warga-payment.php?action=generate_receipt
GET  /php/warga-payment.php?action=payment_history
```

### RT Endpoints (role: rt)

```
GET  /php/rt-pengajuan-surat.php?action=list
POST /php/rt-pengajuan-surat.php?action=approve
POST /php/rt-pengajuan-surat.php?action=reject

GET  /php/rt-aduan.php?action=list
POST /php/rt-aduan.php?action=update_status
POST /php/rt-aduan.php?action=escalate

GET  /php/rt-kas.php?action=list
GET  /php/rt-kas.php?action=summary
GET  /php/rt-kas.php?action=categories
POST /php/rt-kas.php?action=add

GET  /php/rt-data-warga.php?action=summary
GET  /php/rt-data-warga.php?action=kepala_keluarga
GET  /php/rt-data-warga.php?action=warga_detail
```

### RW Endpoints (role: rw, admin)

```
GET  /php/rw-surat.php?action=recap
GET  /php/rw-surat.php?action=surat_pending
POST /php/rw-surat.php?action=approve_surat
POST /php/rw-surat.php?action=reject_surat

GET  /php/rw-aduan.php?action=list
POST /php/rw-aduan.php?action=update

GET  /php/rw-keuangan.php?action=keuangan_rt
GET  /php/rw-keuangan.php?action=iuran_recap

GET  /php/rw-statistik.php?action=warga_stats
GET  /php/rw-statistik.php?action=summary
```

## TESTING THE SYSTEM

### Test Scenario 1: Complete Warga Workflow

1. **Login as Warga**
   - Go to http://localhost/db_rtrw/index.php
   - Login: `budi_santoso` / `password`
   - Should redirect to dashboard-warga.html

2. **Submit Letter Request**
   - Click "Ajukan Surat"
   - Select jenis surat
   - Enter purpose/tujuan
   - Submit → Shows in pengajuan surat list

3. **View Payment Status**
   - Check "Iuran Bulanan" section
   - See unpaid dues for current month
   - Click "Bayar Sekarang" → Records payment

4. **Submit Complaint**
   - Click "Buat Aduan"
   - Enter title, description, category
   - Submit → Aduan shows in dashboard

5. **Logout**
   - Click "Logout" button
   - Should redirect to index.php

### Test Scenario 2: RT Letter Validation

1. **Login as RT**
   - Login: `rt05` / `password`
   - Should go to dashboard-rt.html

2. **View Pending Letters**
   - Check "Permohonan Surat Pending"
   - See warga's requests

3. **Approve Letter**
   - Click "Setujui" on a request
   - Add comment (optional)
   - Submit → Status changes, goes to RW

4. **Manage Complaints**
   - View aduan from warga
   - Can update status or escalate to RW

5. **Record Cash Transaction**
   - Click "Input Kas RT"
   - Enter income/expense with category
   - Submit → Updates balance

### Test Scenario 3: RW Monitoring

1. **Login as RW**
   - Login: `rw05` / `password`
   - Should go to dashboard-rw.html

2. **Review Recap**
   - See summary of letters by RT
   - See financial status per RT
   - See population statistics

3. **Approve Final Letters**
   - Check pending approvals
   - Can approve/reject from RW level

4. **Monitor Escalated Complaints**
   - See urgent complaints from RTs
   - Can provide solutions & mark complete

## SECURITY FEATURES

1. **Password Hashing**: SHA2(256) standard
2. **Session Management**: PHP SERVER-SIDE sessions only
3. **Role-Based Access**: Every endpoint checks user role
4. **CSRF Protection**: Form submissions validated
5. **SQL Injection Prevention**: Prepared statements used throughout
6. **XSS Prevention**: JSON escaping, no inline scripts

## TROUBLESHOOTING

### "Unauthorized" Error
- Check if logged in: go to check_session.php
- Verify session is set on server: check $_SESSION
- Clear browser cookies/localStorage if persists

### Database Connection Fails
- Verify MySQL running: `mysql -u root -p`
- Check `php/db_connect.php` credentials
- Ensure `db_rtrw` database created

### 404 Errors on Pages
- Check file paths are relative (../ from pages folder)
- Verify all .php files exist in php/ folder
- Check URL routing

### Payment Not Recording
- Verify id_iuran is correct
- Check iuran_warga table has records
- Verify id_kk linkage in warga table

## DEPLOYMENT NOTES

1. **Production Database**
   - Don't use default test users
   - Change all passwords via admin panel
   - Enable database backups
   - Restrict database user permissions

2. **HTTPS**
   - Always use HTTPS in production
   - Set session.cookie_secure = on
   - Update all localhost URLs

3. **File Permissions**
   - Set php/ folder to 755
   - Create upload folder for files if needed
   - Restrict write access appropriately

4. **Configuration**
   - Update DB_HOST if not localhost
   - Update session timeout in php.ini
   - Disable error display (set display_errors = off)

## FUTURE ENHANCEMENTS

- Email notifications for status changes
- SMS alerts for urgent complaints
- File upload for letter attachments
- Digital signature for approvals
- Archive & historical reports
- Mobile app integration
- Payment gateway integration (QRIS, Transfer)

## SUPPORT & DOCUMENTATION

- Database schema: See `database/db_rtrw.sql`
- SQL procedures: `sp_hitung_saldo_kas_rt`, `sp_laporan_iuran_bulanan`
- Database views: `view_rekap_iuran_per_rt`, `view_statistik_warga_per_rt`

