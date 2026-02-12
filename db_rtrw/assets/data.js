/**
 * DEMO DATA & CONSTANTS
 * Pusat data untuk semua halaman
 * Mudah untuk di-update atau integrasikan dengan backend
 */

// =============================================
// DEMO USERS
// =============================================

const DEMO_USERS = {
  warga: [
    { 
      username: 'budi', 
      password: '12345', 
      name: 'Budi Santoso', 
      id: 'RW05/001',
      rt: '05',
      role: 'warga'
    },
    { 
      username: 'siti', 
      password: '12345', 
      name: 'Siti Nurhaliza', 
      id: 'RW05/002',
      rt: '05',
      role: 'warga'
    },
    { 
      username: 'ahmad', 
      password: '12345', 
      name: 'Ahmad Gunawan', 
      id: 'RW05/003',
      rt: '05',
      role: 'warga'
    }
  ],
  rt: [
    { 
      username: 'rahmat', 
      password: '12345', 
      name: 'Rahmat', 
      position: 'Ketua RT',
      rt: '05',
      role: 'rt'
    }
  ],
  rw: [
    { 
      username: 'suryanto', 
      password: '12345', 
      name: 'Suryanto', 
      position: 'Ketua RW',
      role: 'rw'
    }
  ]
};

// =============================================
// PENGAJUAN SURAT (Demo Data untuk Dashboard Warga)
// =============================================

const DEMO_PENGAJUAN_SURAT = [
  {
    id: 'SRT001',
    nama: 'Surat Keterangan Usaha',
    diajukan: '15 Januari 2025',
    status: 'PROSES',
    riwayat: 'Dikirim ke RT → 🔄 Tunggu Validasi RW → Selesai',
    tujuan: 'Izin mendirikan warung di rumah'
  },
  {
    id: 'SRT002',
    nama: 'Surat Domisili',
    diajukan: '10 Januari 2025',
    status: 'SELESAI',
    riwayat: '✓ Dikirim ke RT → ✓ Validasi RW → ✓ Ambil di Kantor RT',
    tujuan: 'Surat keterangan tempat tinggal untuk KTP',
    diselesaikan: '12 Januari 2025'
  },
  {
    id: 'SRT003',
    nama: 'Surat Izin Usaha',
    diajukan: '20 Desember 2024',
    status: 'SUDAH_DIAMBIL',
    riwayat: '✓ Dikirim ke RT → ✓ Validasi RW → ✓ Ambil di Kantor RT',
    tujuan: 'Pembukaan toko elektronik',
    diselesaikan: '2 Januari 2025'
  }
];

// =============================================
// IURAN WARGA (Demo Data untuk Dashboard Warga)
// =============================================

const DEMO_IURAN = [
  {
    bulan: 'Januari 2025',
    nominal: 50000,
    status: 'BELUM_BAYAR',
    jatuhTempo: '31 Januari 2025'
  },
  {
    bulan: 'Desember 2024',
    nominal: 50000,
    status: 'LUNAS',
    bayarTanggal: '28 Desember 2024'
  },
  {
    bulan: 'November 2024',
    nominal: 50000,
    status: 'LUNAS',
    bayarTanggal: '15 November 2024'
  },
  {
    bulan: 'Oktober 2024',
    nominal: 50000,
    status: 'LUNAS',
    bayarTanggal: '10 Oktober 2024'
  }
];

// =============================================
// PENGUMUMAN (Demo Data untuk Dashboard Warga & Halaman Publik)
// =============================================

const DEMO_PENGUMUMAN = [
  {
    id: 'PGM001',
    judul: 'RAPAT RUTIN RT 05 - JANGAN SAMPAI KETINGGALAN!',
    kategori: 'PENTING',
    hari: 'Jum\'at, 24 Januari 2025',
    jam: '19:00 (malam)',
    lokasi: 'Rumah Ketua RT, Gang Mawar No. 5',
    agenda: ['Laporan kas RT bulan Desember', 'Rencana perbaikan jalan gang Mawar', 'Rapat umum tahunan'],
    catatan: 'Kehadiran minimal 1 perwakilan per kepala keluarga'
  },
  {
    id: 'PGM002',
    judul: 'PENTING: Iuran Tambahan Perbaikan Jalan',
    kategori: 'PERINGATAN',
    nominal: 20000,
    target: 'Perbaikan jalan gang mawar',
    batasBayar: '30 Januari 2025',
    jadwalPekerjaan: 'Minggu, 25 Januari 2025 (Pukul 07:00)'
  },
  {
    id: 'PGM003',
    judul: 'UNDIAN AKHIR TAHUN RT/RW',
    kategori: 'INFO',
    tanggal: '15 Januari 2025',
    hadiaUtama: 'Sepeda Motor',
    hadiah2: 'TV 32 Inch (2 pemenang)',
    hadiah3: 'Voucher Beras (10 pemenang)'
  }
];

// =============================================
// PERMOHONAN SURAT (Demo Data untuk Dashboard RT)
// =============================================

const DEMO_PERMOHONAN = [
  {
    id: 'PM001',
    nomor: '1',
    nama: 'Budi Santoso',
    jenisSurat: 'Surat Keterangan Usaha',
    diajukan: '15 Januari 2025',
    status: 'PENDING',
    tujuan: 'Izin mendirikan warung di rumah',
    alamat: 'Jl. Mawar No. 12 RT 05',
    noKK: '3271234567'
  },
  {
    id: 'PM002',
    nomor: '2',
    nama: 'Siti Nurhaliza',
    jenisSurat: 'Surat Domisili',
    diajukan: '18 Januari 2025',
    status: 'PENDING',
    tujuan: 'Surat keterangan tempat tinggal untuk KTP',
    alamat: 'Jl. Melati No. 5 RT 05',
    noKK: '3271234568'
  },
  {
    id: 'PM003',
    nomor: '3',
    nama: 'Ahmad Gunawan',
    jenisSurat: 'Surat Izin Usaha',
    diajukan: '20 Januari 2025',
    status: 'PENDING',
    tujuan: 'Pembukaan toko elektronik',
    alamat: 'Jl. Raya No. 45 RT 05 / RW 05',
    noKK: '3271234569'
  }
];

// =============================================
// ADUAN WARGA (Demo Data untuk Dashboard RT)
// =============================================

const DEMO_ADUAN = [
  {
    id: 'ADU001',
    nomor: '1',
    judul: 'Banjir di Gang Murai',
    deskripsi: 'Setiap hujan, gang mawar selalu banjir karena saluran air macet.',
    nama: 'Dina Nurhayati',
    tanggal: '24 Januari 2025',
    status: 'PERLU_FOLLOW_UP',
    prioritas: 'URGENT'
  },
  {
    id: 'ADU002',
    nomor: '2',
    judul: 'Sistem PDAM Putus Seminggu',
    deskripsi: 'Air bersih tidak ada selama 1 minggu terakhir.',
    nama: 'Ibu Sulastri',
    tanggal: '22 Januari 2025',
    status: 'IN_PROGRESS',
    prioritas: 'HIGH'
  },
  {
    id: 'ADU003',
    nomor: '3',
    judul: 'Lampu Jalan Padam',
    deskripsi: 'Lampu di depan rumah Pak Suryanto dan tiga tempat lain sudah padam.',
    nama: 'Pak Suryanto',
    tanggal: '20 Januari 2025',
    status: 'RESOLVED',
    prioritas: 'MEDIUM'
  },
  {
    id: 'ADU004',
    nomor: '4',
    judul: 'Saran: Kegiatan Olahraga Rutin',
    deskripsi: 'Rencanakan kegiatan olahraga bersama untuk mempererat silaturahmi warga.',
    nama: 'Bambang',
    tanggal: '18 Januari 2025',
    status: 'NOTED',
    prioritas: 'LOW'
  },
  {
    id: 'ADU005',
    nomor: '5',
    judul: 'Keributan Malam',
    deskripsi: 'Ada kegiatan karaoke di rumah tetangga sampai jam 1 pagi setiap hari Sabtu-Minggu.',
    nama: 'Ibu Dina',
    tanggal: '17 Januari 2025',
    status: 'RESOLVED',
    prioritas: 'HIGH'
  }
];

// =============================================
// STRUKTUR RT/RW (Demo Data untuk Halaman Profil)
// =============================================

const STRUKTUR_ORGANISASI = {
  rt: [
    { jabatan: 'Ketua RT', nama: 'Rahmat', kontak: '0812-3456-7890' },
    { jabatan: 'Wakil Ketua', nama: 'Hendra', kontak: '0851-1111-1111' },
    { jabatan: 'Sekretaris', nama: 'Bambang', kontak: '0899-1234-5678' },
    { jabatan: 'Bendahara', nama: 'Siti Rahayu', kontak: '0851-2345-6789' },
    { jabatan: 'Ketua Keamanan', nama: 'Joni', kontak: '0899-2222-2222' },
    { jabatan: 'Ketua Kebersihan', nama: 'Ibu Sulastri', kontak: '0851-3333-3333' },
    { jabatan: 'Ketua Sosial', nama: 'Ibu Dina', kontak: '0899-4444-4444' }
  ],
  rw: [
    { jabatan: 'Ketua RW', nama: 'Suryanto', kontak: '0854-5555-5555' },
    { jabatan: 'Wakil Ketua RW', nama: 'Hendra', kontak: '0851-6666-6666' }
  ]
};

// =============================================
// STATISTIK RT (Demo Data untuk Dashboard)
// =============================================

const STATISTIK_RT = {
  totalKK: 45,
  totalJiwa: 156,
  ratanRutin: '4x per tahun',
  wilayah: 'Jalan Mawar, Jalan Melati, dan sekitarnya',
  nomorRT: '05',
  kelurahan: 'Maju Jaya',
  kecamatan: 'Semarang Timur'
};

// =============================================
// KATEGORI FORM
// =============================================

const KATEGORI_ADUAN = [
  { value: 'infrastruktur', label: '🛣️ Infrastruktur (Jalan, Air, Listrik)' },
  { value: 'keamanan', label: '🚨 Keamanan & Ketertiban' },
  { value: 'sosial', label: '🤝 Sosial & Acara' },
  { value: 'administrasi', label: '📋 Administrasi' },
  { value: 'lainnya', label: '💬 Lainnya' }
];

// =============================================
// BADGE STYLES & COLORS
// =============================================

const STATUS_COLORS = {
  'PROSES': { bg: '#FFF8E1', color: '#856404', icon: '⏳' },
  'PENDING': { bg: '#FFF8E1', color: '#856404', icon: '⏳' },
  'SELESAI': { bg: '#F1F8F4', color: '#155724', icon: '✓' },
  'APPROVED': { bg: '#F1F8F4', color: '#155724', icon: '✓' },
  'SUDAH_DIAMBIL': { bg: '#F1F8F4', color: '#155724', icon: '✓' },
  'BELUM_BAYAR': { bg: '#FFF8E1', color: '#856404', icon: '⏳' },
  'LUNAS': { bg: '#F1F8F4', color: '#155724', icon: '✓' },
  'PERLU_FOLLOW_UP': { bg: '#FFEBEE', color: '#C62828', icon: '⚠️' },
  'IN_PROGRESS': { bg: '#E3F2FD', color: '#1565C0', icon: '🔄' },
  'RESOLVED': { bg: '#F1F8F4', color: '#155724', icon: '✓' },
  'NOTED': { bg: '#E3F2FD', color: '#1565C0', icon: '📝' }
};

// =============================================
// Export untuk global access
// =============================================

window.AppData = {
  users: DEMO_USERS,
  pengajuanSurat: DEMO_PENGAJUAN_SURAT,
  iuran: DEMO_IURAN,
  pengumuman: DEMO_PENGUMUMAN,
  permohonan: DEMO_PERMOHONAN,
  aduan: DEMO_ADUAN,
  organisasi: STRUKTUR_ORGANISASI,
  statistik: STATISTIK_RT,
  kategoriAduan: KATEGORI_ADUAN,
  statusColors: STATUS_COLORS
};
