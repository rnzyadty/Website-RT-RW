-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jan 2026 pada 17.01
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rtrw`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE sp_hitung_saldo_kas_rt (IN p_id_rt INT)
BEGIN
    SELECT 
        id_rt,
        SUM(CASE 
            WHEN jenis_transaksi = 'pemasukan' THEN nominal 
            ELSE -nominal 
        END) AS saldo_akhir
    FROM kas_rt
    WHERE id_rt = p_id_rt
    GROUP BY id_rt;
END;


CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_laporan_iuran_bulanan` (IN `p_bulan` INT, IN `p_tahun` INT, IN `p_id_rt` INT)   BEGIN
    SELECT 
        kk.nomor_kk,
        kk.nama_kepala_keluarga,
        kk.alamat,
        iw.nominal,
        iw.status_bayar,
        iw.tanggal_bayar,
        CASE 
            WHEN iw.status_bayar = 'belum' THEN iw.nominal
            ELSE 0
        END AS tunggakan
    FROM kepala_keluarga kk
    LEFT JOIN iuran_warga iw ON kk.id_kk = iw.id_kk 
        AND iw.bulan = p_bulan 
        AND iw.tahun = p_tahun
    WHERE kk.id_rt = p_id_rt
    ORDER BY kk.nama_kepala_keluarga;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aduan`
--

CREATE TABLE `aduan` (
  `id_aduan` int(11) NOT NULL,
  `id_warga` int(11) NOT NULL,
  `judul_aduan` varchar(255) NOT NULL,
  `kategori` enum('infrastruktur','keamanan','kebersihan','sosial','lainnya') NOT NULL,
  `isi_aduan` text NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `status` enum('baru','diproses','selesai','ditolak') DEFAULT 'baru',
  `prioritas` enum('rendah','sedang','tinggi','urgent') DEFAULT 'sedang',
  `tanggal_aduan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_proses` timestamp NULL DEFAULT NULL,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `ditangani_oleh` int(11) DEFAULT NULL,
  `catatan_penanganan` text DEFAULT NULL,
  `solusi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aduan`
--

INSERT INTO `aduan` (`id_aduan`, `id_warga`, `judul_aduan`, `kategori`, `isi_aduan`, `lokasi`, `foto_bukti`, `status`, `prioritas`, `tanggal_aduan`, `tanggal_proses`, `tanggal_selesai`, `ditangani_oleh`, `catatan_penanganan`, `solusi`, `created_at`, `updated_at`) VALUES
(1, 12, 'Banjir di Gang Murai', 'infrastruktur', 'Setiap hujan, gang mawar selalu banjir karena saluran air macet.', 'Gang Murai', NULL, 'baru', 'urgent', '2025-01-24 01:00:00', NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 5, 'Lampu jalan mati', 'infrastruktur', 'Lampu jalan di depan rumah saya sudah mati sejak 3 hari yang lalu.', 'Jl. Melati No. 5', NULL, 'diproses', 'sedang', '2025-01-20 12:00:00', NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 8, 'Sampah menumpuk', 'kebersihan', 'Tempat pembuangan sampah di ujung gang sudah menumpuk dan berbau.', 'Gang Mawar', NULL, 'selesai', 'tinggi', '2025-01-15 03:00:00', NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id_galeri` int(11) NOT NULL,
  `id_kegiatan` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `tipe_file` enum('foto','video') DEFAULT 'foto',
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `uploaded_by` int(11) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'published'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `iuran_warga`
--

CREATE TABLE `iuran_warga` (
  `id_iuran` int(11) NOT NULL,
  `id_kk` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `jenis_iuran` varchar(100) DEFAULT 'Iuran Rutin',
  `status_bayar` enum('belum','lunas') DEFAULT 'belum',
  `tanggal_bayar` timestamp NULL DEFAULT NULL,
  `metode_bayar` enum('tunai','transfer','lainnya') DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `iuran_warga`
--

INSERT INTO `iuran_warga` (`id_iuran`, `id_kk`, `bulan`, `tahun`, `nominal`, `jenis_iuran`, `status_bayar`, `tanggal_bayar`, `metode_bayar`, `bukti_bayar`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-10-10 03:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 2, 10, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-10-10 04:00:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 3, 10, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-10-12 02:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 4, 10, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-10-15 07:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 5, 10, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-10-08 01:30:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(6, 1, 11, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-11-15 03:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(7, 2, 11, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-11-10 04:00:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(8, 3, 11, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-11-12 02:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(9, 4, 11, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-11-20 07:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(10, 5, 11, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-11-08 01:30:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(11, 1, 12, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-12-28 03:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(12, 2, 12, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-12-15 04:00:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(13, 3, 12, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-12-12 02:00:00', 'tunai', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(14, 4, 12, 2024, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(15, 5, 12, 2024, 50000.00, 'Iuran Rutin', 'lunas', '2024-12-08 01:30:00', 'transfer', NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(16, 1, 1, 2025, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(17, 2, 1, 2025, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(18, 3, 1, 2025, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(19, 4, 1, 2025, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(20, 5, 1, 2025, 50000.00, 'Iuran Rutin', 'belum', NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_surat`
--

CREATE TABLE `jenis_surat` (
  `id_jenis_surat` int(11) NOT NULL,
  `nama_surat` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `persyaratan` text DEFAULT NULL,
  `template_surat` text DEFAULT NULL,
  `biaya_admin` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_surat`
--

INSERT INTO `jenis_surat` (`id_jenis_surat`, `nama_surat`, `keterangan`, `persyaratan`, `template_surat`, `biaya_admin`, `created_at`) VALUES
(1, 'Surat Keterangan Domisili', 'Surat keterangan tempat tinggal untuk keperluan administrasi', NULL, NULL, 5000.00, '2026-01-07 14:02:23'),
(2, 'Surat Keterangan Usaha', 'Surat keterangan untuk izin usaha di lingkungan RT/RW', NULL, NULL, 10000.00, '2026-01-07 14:02:23'),
(3, 'Surat Izin Usaha', 'Surat izin usaha untuk pembukaan usaha baru', NULL, NULL, 15000.00, '2026-01-07 14:02:23'),
(4, 'Surat Keterangan Tidak Mampu', 'Surat keterangan ekonomi tidak mampu', NULL, NULL, 0.00, '2026-01-07 14:02:23'),
(5, 'Surat Keterangan Kelakuan Baik', 'Surat keterangan kelakuan baik untuk keperluan kerja/pendidikan', NULL, NULL, 5000.00, '2026-01-07 14:02:23'),
(6, 'Surat Pengantar Nikah', 'Surat pengantar untuk keperluan pernikahan', NULL, NULL, 10000.00, '2026-01-07 14:02:23'),
(7, 'Surat Keterangan Pindah', 'Surat keterangan pindah domisili', NULL, NULL, 5000.00, '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas_rt`
--

CREATE TABLE `kas_rt` (
  `id_kas` int(11) NOT NULL,
  `id_rt` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_transaksi` enum('pemasukan','pengeluaran') NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_transaksi` varchar(255) DEFAULT NULL,
  `id_user_input` int(11) DEFAULT NULL,
  `saldo_akhir` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kas_rt`
--

INSERT INTO `kas_rt` (`id_kas`, `id_rt`, `id_kategori`, `tanggal`, `jenis_transaksi`, `nominal`, `keterangan`, `bukti_transaksi`, `id_user_input`, `saldo_akhir`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2024-10-10', 'pemasukan', 250000.00, 'Iuran rutin Oktober 2024', NULL, 10, 250000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 5, 4, '2024-10-15', 'pengeluaran', 100000.00, 'Perbaikan lampu jalan', NULL, 10, 150000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 5, 1, '2024-11-10', 'pemasukan', 250000.00, 'Iuran rutin November 2024', NULL, 10, 400000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 5, 5, '2024-11-20', 'pengeluaran', 50000.00, 'Kegiatan 17 Agustus', NULL, 10, 350000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 5, 1, '2024-12-10', 'pemasukan', 200000.00, 'Iuran rutin Desember 2024 (sebagian)', NULL, 10, 550000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(6, 5, 4, '2024-12-28', 'pengeluaran', 500000.00, 'Perbaikan jalan gang mawar', NULL, 10, 50000.00, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas_rw`
--

CREATE TABLE `kas_rw` (
  `id_kas` int(11) NOT NULL,
  `id_rw` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_transaksi` enum('pemasukan','pengeluaran') NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_transaksi` varchar(255) DEFAULT NULL,
  `id_user_input` int(11) DEFAULT NULL,
  `saldo_akhir` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_keuangan`
--

CREATE TABLE `kategori_keuangan` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tipe` enum('pemasukan','pengeluaran') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_keuangan`
--

INSERT INTO `kategori_keuangan` (`id_kategori`, `nama_kategori`, `tipe`, `keterangan`, `created_at`) VALUES
(1, 'Iuran Rutin Warga', 'pemasukan', 'Iuran bulanan dari warga', '2026-01-07 14:02:23'),
(2, 'Iuran Khusus/Tambahan', 'pemasukan', 'Iuran untuk keperluan khusus', '2026-01-07 14:02:23'),
(3, 'Donasi/Sumbangan', 'pemasukan', 'Donasi dari warga atau pihak luar', '2026-01-07 14:02:23'),
(4, 'Perbaikan Infrastruktur', 'pengeluaran', 'Biaya perbaikan jalan, saluran, dll', '2026-01-07 14:02:23'),
(5, 'Kegiatan Sosial', 'pengeluaran', 'Biaya kegiatan sosial dan kemasyarakatan', '2026-01-07 14:02:23'),
(6, 'Administrasi', 'pengeluaran', 'Biaya operasional dan administrasi', '2026-01-07 14:02:23'),
(7, 'Keamanan', 'pengeluaran', 'Biaya ronda dan keamanan lingkungan', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id_kegiatan` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_kegiatan` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `penyelenggara` enum('rt','rw','gabungan') DEFAULT 'rt',
  `id_rt` int(11) DEFAULT NULL,
  `id_rw` int(11) DEFAULT NULL,
  `kategori` enum('sosial','keagamaan','olahraga','pendidikan','kesehatan','lainnya') DEFAULT 'sosial',
  `peserta_target` varchar(100) DEFAULT NULL,
  `status` enum('rencana','berlangsung','selesai','dibatalkan') DEFAULT 'rencana',
  `anggaran` decimal(12,2) DEFAULT NULL,
  `realisasi_biaya` decimal(12,2) DEFAULT NULL,
  `jumlah_peserta` int(11) DEFAULT NULL,
  `foto_kegiatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `nama_kegiatan`, `deskripsi`, `tanggal_kegiatan`, `waktu_mulai`, `waktu_selesai`, `lokasi`, `penyelenggara`, `id_rt`, `id_rw`, `kategori`, `peserta_target`, `status`, `anggaran`, `realisasi_biaya`, `jumlah_peserta`, `foto_kegiatan`, `created_at`, `updated_at`) VALUES
(1, 'Gotong Royong Bersih Lingkungan', 'Kegiatan gotong royong membersihkan lingkungan RT 05', '2025-02-02', '07:00:00', NULL, 'Seluruh area RT 05', 'rt', 5, NULL, 'sosial', 'Seluruh warga RT 05', 'rencana', 200000.00, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 'Pengajian Rutin', 'Pengajian rutin bulanan RT/RW', '2025-02-10', '19:30:00', NULL, 'Masjid Al-Ikhlas', 'rw', 1, NULL, 'keagamaan', 'Ibu-ibu warga RW 05', 'rencana', 500000.00, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 'Senam Sehat Bersama', 'Senam pagi untuk kesehatan warga', '2025-01-28', '06:00:00', NULL, 'Lapangan RT 05', 'rt', 5, NULL, 'kesehatan', 'Seluruh warga RT 05', 'rencana', 100000.00, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kehadiran_rapat`
--

CREATE TABLE `kehadiran_rapat` (
  `id_kehadiran` int(11) NOT NULL,
  `id_rapat` int(11) NOT NULL,
  `id_kk` int(11) NOT NULL,
  `nama_perwakilan` varchar(100) DEFAULT NULL,
  `status_kehadiran` enum('hadir','tidak_hadir','izin') DEFAULT 'tidak_hadir',
  `waktu_datang` timestamp NULL DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelurahan`
--

CREATE TABLE `kelurahan` (
  `id_kelurahan` int(11) NOT NULL,
  `nama_kelurahan` varchar(100) NOT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelurahan`
--

INSERT INTO `kelurahan` (`id_kelurahan`, `nama_kelurahan`, `kecamatan`, `kota`, `kode_pos`, `created_at`) VALUES
(1, 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', '12345', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kepala_keluarga`
--

CREATE TABLE `kepala_keluarga` (
  `id_kk` int(11) NOT NULL,
  `id_rt` int(11) DEFAULT NULL,
  `nomor_kk` varchar(20) NOT NULL,
  `nama_kepala_keluarga` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `rt_rw` varchar(20) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_kepemilikan` enum('Milik Sendiri','Kontrak','Sewa','Menumpang') DEFAULT 'Milik Sendiri',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kepala_keluarga`
--

INSERT INTO `kepala_keluarga` (`id_kk`, `id_rt`, `nomor_kk`, `nama_kepala_keluarga`, `alamat`, `rt_rw`, `kelurahan`, `kecamatan`, `kota`, `provinsi`, `kode_pos`, `telepon`, `email`, `status_kepemilikan`, `created_at`, `updated_at`) VALUES
(1, 5, '3271234567890001', 'Budi Santoso', 'Jl. Mawar No. 12', '05/05', 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', 'Provinsi Maju', '12345', '081234567001', NULL, 'Milik Sendiri', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 5, '3271234567890002', 'Siti Nurhaliza', 'Jl. Melati No. 5', '05/05', 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', 'Provinsi Maju', '12345', '081234567002', NULL, 'Milik Sendiri', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 5, '3271234567890003', 'Ahmad Gunawan', 'Jl. Raya No. 45', '05/05', 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', 'Provinsi Maju', '12345', '081234567003', NULL, 'Milik Sendiri', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 5, '3271234567890004', 'Dina Nurhayati', 'Gang Murai No. 8', '05/05', 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', 'Provinsi Maju', '12345', '081234567004', NULL, 'Kontrak', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 5, '3271234567890005', 'Eko Prasetyo', 'Jl. Dahlia No. 20', '05/05', 'Maju Jaya', 'Kecamatan Sejahtera', 'Kota Harapan', 'Provinsi Maju', '12345', '081234567005', NULL, 'Milik Sendiri', '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `tabel_terkait` varchar(100) DEFAULT NULL,
  `id_record` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id_pengajuan` int(11) NOT NULL,
  `id_warga` int(11) NOT NULL,
  `id_jenis_surat` int(11) NOT NULL,
  `nomor_surat` varchar(50) DEFAULT NULL,
  `tujuan_surat` text DEFAULT NULL,
  `keterangan_tambahan` text DEFAULT NULL,
  `status_pengajuan` enum('pending','disetujui_rt','ditolak_rt','disetujui_rw','ditolak_rw','selesai','diambil') DEFAULT 'pending',
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_validasi_rt` timestamp NULL DEFAULT NULL,
  `tanggal_validasi_rw` timestamp NULL DEFAULT NULL,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `tanggal_diambil` timestamp NULL DEFAULT NULL,
  `divalidasi_oleh_rt` int(11) DEFAULT NULL,
  `divalidasi_oleh_rw` int(11) DEFAULT NULL,
  `catatan_rt` text DEFAULT NULL,
  `catatan_rw` text DEFAULT NULL,
  `alasan_penolakan` text DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id_pengajuan`, `id_warga`, `id_jenis_surat`, `nomor_surat`, `tujuan_surat`, `keterangan_tambahan`, `status_pengajuan`, `tanggal_pengajuan`, `tanggal_validasi_rt`, `tanggal_validasi_rw`, `tanggal_selesai`, `tanggal_diambil`, `divalidasi_oleh_rt`, `divalidasi_oleh_rw`, `catatan_rt`, `catatan_rw`, `alasan_penolakan`, `file_surat`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'SKU/RT05/001/2025', 'Izin mendirikan warung di rumah', NULL, 'pending', '2025-01-15 03:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 5, 1, 'SKD/RT05/002/2025', 'Surat keterangan tempat tinggal untuk KTP', NULL, 'disetujui_rt', '2025-01-18 07:30:00', '2025-01-19 02:00:00', NULL, NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 8, 3, 'SIU/RT05/003/2025', 'Pembukaan toko elektronik', NULL, 'selesai', '2024-12-20 04:00:00', '2024-12-21 03:00:00', NULL, NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi_pengumuman` text NOT NULL,
  `kategori` enum('penting','info','peringatan','acara','lainnya') DEFAULT 'info',
  `prioritas` enum('rendah','normal','tinggi','urgent') DEFAULT 'normal',
  `target_audience` enum('semua','rt_tertentu','rw','pengurus') DEFAULT 'semua',
  `id_rt` int(11) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi_pengumuman`, `kategori`, `prioritas`, `target_audience`, `id_rt`, `tanggal_mulai`, `tanggal_berakhir`, `gambar`, `dibuat_oleh`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Rapat Rutin RT 05', 'Diadakan rapat rutin RT 05 pada:\n\nHari: Jum\'at, 24 Januari 2025\nJam: 19:00 (malam)\nTempat: Rumah Ketua RT, Gang Mawar No. 5\nTopik: Rencana perbaikan jalan dan rapat rutin bulanan\n\nDimohon minimal satu perwakilan per KK untuk hadir.', 'penting', 'tinggi', 'rt_tertentu', NULL, '2025-01-15', '2025-01-24', NULL, 10, 'published', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 'Iuran Tambahan Perbaikan Jalan', 'Kepada seluruh warga RT 05,\n\nDiinformasikan bahwa akan diadakan perbaikan jalan gang mawar yang sudah berlubang-lubang.\n\nNominal: Rp 20.000 per KK\nBatas Pembayaran: 30 Januari 2025\nJadwal Pekerjaan: Minggu, 25 Januari 2025 (Pukul 07:00)\n\nMohon partisipasinya. Terima kasih.', 'penting', 'urgent', 'rt_tertentu', NULL, '2025-01-10', '2025-01-30', NULL, 10, 'published', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 'Undian Akhir Tahun RT/RW', 'Undian akhir tahun telah dilaksanakan pada 15 Januari 2025.\n\nPemenang:\n- Hadiah Utama (Sepeda Motor): Pak Ahmad (RT 03)\n- TV 32 Inch: Ibu Siti (RT 01) dan Pak Budi (RT 05)\n- Voucher Beras: 10 pemenang dari berbagai RT\n\nSelamat kepada para pemenang!', 'acara', 'normal', 'semua', NULL, '2025-01-15', '2025-01-31', NULL, 11, 'published', '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rapat`
--

CREATE TABLE `rapat` (
  `id_rapat` int(11) NOT NULL,
  `judul_rapat` varchar(255) NOT NULL,
  `tanggal_rapat` datetime NOT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `jenis_rapat` enum('rt','rw','gabungan') NOT NULL,
  `id_rt` int(11) DEFAULT NULL,
  `id_rw` int(11) DEFAULT NULL,
  `notulen` text DEFAULT NULL,
  `hasil_rapat` text DEFAULT NULL,
  `status` enum('dijadwalkan','berlangsung','selesai','dibatalkan') DEFAULT 'dijadwalkan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rt`
--

CREATE TABLE `rt` (
  `id_rt` int(11) NOT NULL,
  `id_rw` int(11) DEFAULT NULL,
  `nomor_rt` varchar(10) NOT NULL,
  `ketua_rt` varchar(100) DEFAULT NULL,
  `sekretaris_rt` varchar(100) DEFAULT NULL,
  `bendahara_rt` varchar(100) DEFAULT NULL,
  `alamat_kantor` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rt`
--

INSERT INTO `rt` (`id_rt`, `id_rw`, `nomor_rt`, `ketua_rt`, `sekretaris_rt`, `bendahara_rt`, `alamat_kantor`, `telepon`, `created_at`) VALUES
(1, 1, '01', 'Bapak Hendra', 'Ibu Siti', 'Bapak Budi', 'Jl. Mawar Gang 1', '081234567801', '2026-01-07 14:02:23'),
(2, 1, '02', 'Bapak Joko', 'Ibu Ani', 'Bapak Rudi', 'Jl. Melati Gang 2', '081234567802', '2026-01-07 14:02:23'),
(3, 1, '03', 'Bapak Santoso', 'Ibu Rina', 'Bapak Dedi', 'Jl. Dahlia Gang 3', '081234567803', '2026-01-07 14:02:23'),
(4, 1, '04', 'Bapak Wirawan', 'Ibu Dewi', 'Bapak Eko', 'Jl. Anggrek Gang 4', '081234567804', '2026-01-07 14:02:23'),
(5, 1, '05', 'Bapak Gunawan', 'Ibu Lina', 'Bapak Fajar', 'Jl. Kenanga Gang 5', '081234567805', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rw`
--

CREATE TABLE `rw` (
  `id_rw` int(11) NOT NULL,
  `id_kelurahan` int(11) DEFAULT NULL,
  `nomor_rw` varchar(10) NOT NULL,
  `ketua_rw` varchar(100) DEFAULT NULL,
  `sekretaris_rw` varchar(100) DEFAULT NULL,
  `bendahara_rw` varchar(100) DEFAULT NULL,
  `alamat_kantor` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rw`
--

INSERT INTO `rw` (`id_rw`, `id_kelurahan`, `nomor_rw`, `ketua_rw`, `sekretaris_rw`, `bendahara_rw`, `alamat_kantor`, `telepon`, `created_at`) VALUES
(1, 1, '05', 'Bapak Suherman', 'Ibu Ratna', 'Bapak Ahmad', 'Jl. Raya Utama No. 100', '081234567890', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas_rt`
--

CREATE TABLE `tugas_rt` (
  `id_tugas` int(11) NOT NULL,
  `id_rt` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul_tugas` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `prioritas` enum('rendah','normal','tinggi','urgent') DEFAULT 'normal',
  `deadline` date DEFAULT NULL,
  `status` enum('belum','progress','selesai') DEFAULT 'belum',
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas_rt`
--

INSERT INTO `tugas_rt` (`id_tugas`, `id_rt`, `id_user`, `judul_tugas`, `deskripsi`, `prioritas`, `deadline`, `status`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 5, 10, 'Validasi 3 permohonan surat dari warga', 'Segera validasi permohonan dari Budi, Siti, dan Ahmad', 'tinggi', '2025-01-25', 'belum', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 5, 10, 'Input laporan kas RT (perbaikan jalan)', 'Input pengeluaran kas untuk perbaikan jalan gang mawar', 'normal', '2025-01-25', 'belum', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 5, 10, 'Hubungi Dina (tanggungan iuran 3 bulan)', 'Mengingatkan Ibu Dina untuk melunasi iuran yang tertunggak', 'tinggi', '2025-01-26', 'belum', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 5, 10, 'Verifikasi data warga baru (keluarga Pak Ahmad)', 'Cek kelengkapan dokumen keluarga baru', 'normal', '2025-01-27', 'belum', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 5, 10, 'Siapkan rapat RT besok malam (daftar hadir, topik)', 'Persiapan rapat rutin bulanan', 'tinggi', '2025-01-23', 'progress', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('warga','rt','rw','admin') NOT NULL,
  `id_warga` int(11) DEFAULT NULL,
  `id_rt` int(11) DEFAULT NULL,
  `id_rw` int(11) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `id_warga`, `id_rt`, `id_rw`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'budi_santoso', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'warga', 1, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 'siti_nurhaliza', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'warga', 5, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 'ahmad_gunawan', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'warga', 8, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 'dina_nurhayati', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'warga', 12, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 'eko_prasetyo', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'warga', 15, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(6, 'rt01', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rt', NULL, 1, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(7, 'rt02', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rt', NULL, 2, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(8, 'rt03', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rt', NULL, 3, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(9, 'rt04', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rt', NULL, 4, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(10, 'rt05', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rt', NULL, 5, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(11, 'rw05', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'rw', NULL, NULL, 1, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(12, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin', NULL, NULL, NULL, 'aktif', NULL, '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_rekap_iuran_per_rt`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_rekap_iuran_per_rt` (
`id_rt` int(11)
,`nomor_rt` varchar(10)
,`bulan` int(11)
,`tahun` int(11)
,`total_tagihan` bigint(21)
,`total_lunas` decimal(22,0)
,`total_belum_bayar` decimal(22,0)
,`total_nominal` decimal(32,2)
,`total_terbayar` decimal(32,2)
,`total_tunggakan` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_rekap_keuangan_rt`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_rekap_keuangan_rt` (
`id_rt` int(11)
,`nomor_rt` varchar(10)
,`bulan` int(2)
,`tahun` int(4)
,`total_pemasukan` decimal(34,2)
,`total_pengeluaran` decimal(34,2)
,`saldo` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_statistik_warga_per_rt`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_statistik_warga_per_rt` (
`id_rt` int(11)
,`nomor_rt` varchar(10)
,`ketua_rt` varchar(100)
,`jumlah_kk` bigint(21)
,`jumlah_warga` bigint(21)
,`jumlah_laki` decimal(22,0)
,`jumlah_perempuan` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_status_surat_per_rt`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_status_surat_per_rt` (
`id_rt` int(11)
,`nomor_rt` varchar(10)
,`total_pengajuan` bigint(21)
,`pending` decimal(22,0)
,`disetujui` decimal(22,0)
,`ditolak` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `warga`
--

CREATE TABLE `warga` (
  `id_warga` int(11) NOT NULL,
  `id_kk` int(11) DEFAULT NULL,
  `nik` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') NOT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `status_perkawinan` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT 'Belum Kawin',
  `status_dalam_keluarga` enum('Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orangtua','Lainnya') NOT NULL,
  `kewarganegaraan` varchar(20) DEFAULT 'WNI',
  `nomor_paspor` varchar(50) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_hidup` enum('Hidup','Meninggal') DEFAULT 'Hidup',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `warga`
--

INSERT INTO `warga` (`id_warga`, `id_kk`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `status_dalam_keluarga`, `kewarganegaraan`, `nomor_paspor`, `nomor_telepon`, `email`, `status_hidup`, `created_at`, `updated_at`) VALUES
(1, 1, '3271010101850001', 'Budi Santoso', 'Jakarta', '1985-01-01', 'Laki-laki', 'Islam', 'S1', 'Wiraswasta', 'Kawin', 'Kepala Keluarga', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(2, 1, '3271010102880002', 'Ani Wulandari', 'Bandung', '1988-02-15', 'Perempuan', 'Islam', 'S1', 'Guru', 'Kawin', 'Istri', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(3, 1, '3271010103100003', 'Farhan Budi Santoso', 'Kota Harapan', '2010-05-20', 'Laki-laki', 'Islam', 'SD', 'Pelajar', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(4, 1, '3271010104150004', 'Zahra Budi Santoso', 'Kota Harapan', '2015-08-10', 'Perempuan', 'Islam', 'TK', 'Belum Sekolah', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(5, 2, '3271020201900005', 'Siti Nurhaliza', 'Surabaya', '1990-03-10', 'Perempuan', 'Islam', 'S1', 'Pegawai Swasta', 'Kawin', 'Kepala Keluarga', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(6, 2, '3271020202880006', 'Andi Wijaya', 'Medan', '1988-07-25', 'Laki-laki', 'Islam', 'S2', 'Dosen', 'Kawin', 'Istri', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(7, 2, '3271020203120007', 'Alya Siti Wijaya', 'Kota Harapan', '2012-11-05', 'Perempuan', 'Islam', 'SMP', 'Pelajar', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(8, 3, '3271030301830008', 'Ahmad Gunawan', 'Yogyakarta', '1983-04-12', 'Laki-laki', 'Islam', 'S1', 'Pengusaha', 'Kawin', 'Kepala Keluarga', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(9, 3, '3271030302850009', 'Ratna Sari', 'Semarang', '1985-09-08', 'Perempuan', 'Islam', 'D3', 'Ibu Rumah Tangga', 'Kawin', 'Istri', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(10, 3, '3271030303080010', 'Rizky Ahmad Gunawan', 'Kota Harapan', '2008-01-15', 'Laki-laki', 'Islam', 'SMP', 'Pelajar', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(11, 3, '3271030304140011', 'Salma Ahmad Gunawan', 'Kota Harapan', '2014-06-20', 'Perempuan', 'Islam', 'SD', 'Pelajar', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(12, 4, '3271040401920012', 'Dina Nurhayati', 'Malang', '1992-12-05', 'Perempuan', 'Islam', 'S1', 'Perawat', 'Kawin', 'Kepala Keluarga', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(13, 4, '3271040402900013', 'Hadi Setiawan', 'Bogor', '1990-03-20', 'Laki-laki', 'Islam', 'S1', 'Karyawan BUMN', 'Kawin', 'Istri', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(14, 4, '3271040403160014', 'Naufal Hadi Setiawan', 'Kota Harapan', '2016-07-10', 'Laki-laki', 'Islam', 'TK', 'Belum Sekolah', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(15, 5, '3271050501870015', 'Eko Prasetyo', 'Solo', '1987-08-30', 'Laki-laki', 'Islam', 'S2', 'PNS', 'Kawin', 'Kepala Keluarga', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(16, 5, '3271050502890016', 'Fitri Handayani', 'Bekasi', '1989-11-12', 'Perempuan', 'Islam', 'S1', 'Guru', 'Kawin', 'Istri', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23'),
(17, 5, '3271050503110017', 'Aditya Eko Prasetyo', 'Kota Harapan', '2011-04-25', 'Laki-laki', 'Islam', 'SMP', 'Pelajar', 'Belum Kawin', 'Anak', 'WNI', NULL, NULL, NULL, 'Hidup', '2026-01-07 14:02:23', '2026-01-07 14:02:23');

-- --------------------------------------------------------

--
-- Struktur untuk view `view_rekap_iuran_per_rt`
--
DROP TABLE IF EXISTS `view_rekap_iuran_per_rt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rekap_iuran_per_rt`  AS SELECT `rt`.`id_rt` AS `id_rt`, `rt`.`nomor_rt` AS `nomor_rt`, `iw`.`bulan` AS `bulan`, `iw`.`tahun` AS `tahun`, count(`iw`.`id_iuran`) AS `total_tagihan`, sum(case when `iw`.`status_bayar` = 'lunas' then 1 else 0 end) AS `total_lunas`, sum(case when `iw`.`status_bayar` = 'belum' then 1 else 0 end) AS `total_belum_bayar`, sum(`iw`.`nominal`) AS `total_nominal`, sum(case when `iw`.`status_bayar` = 'lunas' then `iw`.`nominal` else 0 end) AS `total_terbayar`, sum(case when `iw`.`status_bayar` = 'belum' then `iw`.`nominal` else 0 end) AS `total_tunggakan` FROM ((`rt` left join `kepala_keluarga` `kk` on(`rt`.`id_rt` = `kk`.`id_rt`)) left join `iuran_warga` `iw` on(`kk`.`id_kk` = `iw`.`id_kk`)) GROUP BY `rt`.`id_rt`, `rt`.`nomor_rt`, `iw`.`bulan`, `iw`.`tahun` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_rekap_keuangan_rt`
--
DROP TABLE IF EXISTS `view_rekap_keuangan_rt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rekap_keuangan_rt`  AS SELECT `rt`.`id_rt` AS `id_rt`, `rt`.`nomor_rt` AS `nomor_rt`, month(`k`.`tanggal`) AS `bulan`, year(`k`.`tanggal`) AS `tahun`, sum(case when `k`.`jenis_transaksi` = 'pemasukan' then `k`.`nominal` else 0 end) AS `total_pemasukan`, sum(case when `k`.`jenis_transaksi` = 'pengeluaran' then `k`.`nominal` else 0 end) AS `total_pengeluaran`, sum(case when `k`.`jenis_transaksi` = 'pemasukan' then `k`.`nominal` else 0 end) - sum(case when `k`.`jenis_transaksi` = 'pengeluaran' then `k`.`nominal` else 0 end) AS `saldo` FROM (`rt` left join `kas_rt` `k` on(`rt`.`id_rt` = `k`.`id_rt`)) GROUP BY `rt`.`id_rt`, `rt`.`nomor_rt`, month(`k`.`tanggal`), year(`k`.`tanggal`) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_statistik_warga_per_rt`
--
DROP TABLE IF EXISTS `view_statistik_warga_per_rt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_statistik_warga_per_rt`  AS SELECT `rt`.`id_rt` AS `id_rt`, `rt`.`nomor_rt` AS `nomor_rt`, `rt`.`ketua_rt` AS `ketua_rt`, count(distinct `kk`.`id_kk`) AS `jumlah_kk`, count(`w`.`id_warga`) AS `jumlah_warga`, sum(case when `w`.`jenis_kelamin` = 'Laki-laki' then 1 else 0 end) AS `jumlah_laki`, sum(case when `w`.`jenis_kelamin` = 'Perempuan' then 1 else 0 end) AS `jumlah_perempuan` FROM ((`rt` left join `kepala_keluarga` `kk` on(`rt`.`id_rt` = `kk`.`id_rt`)) left join `warga` `w` on(`kk`.`id_kk` = `w`.`id_kk` and `w`.`status_hidup` = 'Hidup')) GROUP BY `rt`.`id_rt`, `rt`.`nomor_rt`, `rt`.`ketua_rt` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_status_surat_per_rt`
--
DROP TABLE IF EXISTS `view_status_surat_per_rt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_status_surat_per_rt`  AS SELECT `rt`.`id_rt` AS `id_rt`, `rt`.`nomor_rt` AS `nomor_rt`, count(`ps`.`id_pengajuan`) AS `total_pengajuan`, sum(case when `ps`.`status_pengajuan` = 'pending' then 1 else 0 end) AS `pending`, sum(case when `ps`.`status_pengajuan` in ('disetujui_rt','disetujui_rw','selesai') then 1 else 0 end) AS `disetujui`, sum(case when `ps`.`status_pengajuan` in ('ditolak_rt','ditolak_rw') then 1 else 0 end) AS `ditolak` FROM (((`rt` left join `kepala_keluarga` `kk` on(`rt`.`id_rt` = `kk`.`id_rt`)) left join `warga` `w` on(`kk`.`id_kk` = `w`.`id_kk`)) left join `pengajuan_surat` `ps` on(`w`.`id_warga` = `ps`.`id_warga`)) GROUP BY `rt`.`id_rt`, `rt`.`nomor_rt` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aduan`
--
ALTER TABLE `aduan`
  ADD PRIMARY KEY (`id_aduan`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `ditangani_oleh` (`ditangani_oleh`),
  ADD KEY `idx_aduan_status` (`status`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_kegiatan` (`id_kegiatan`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indeks untuk tabel `iuran_warga`
--
ALTER TABLE `iuran_warga`
  ADD PRIMARY KEY (`id_iuran`),
  ADD UNIQUE KEY `unique_iuran` (`id_kk`,`bulan`,`tahun`,`jenis_iuran`),
  ADD KEY `idx_iuran_periode` (`bulan`,`tahun`),
  ADD KEY `idx_iuran_status` (`status_bayar`);

--
-- Indeks untuk tabel `jenis_surat`
--
ALTER TABLE `jenis_surat`
  ADD PRIMARY KEY (`id_jenis_surat`);

--
-- Indeks untuk tabel `kas_rt`
--
ALTER TABLE `kas_rt`
  ADD PRIMARY KEY (`id_kas`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_user_input` (`id_user_input`);

--
-- Indeks untuk tabel `kas_rw`
--
ALTER TABLE `kas_rw`
  ADD PRIMARY KEY (`id_kas`),
  ADD KEY `id_rw` (`id_rw`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_user_input` (`id_user_input`);

--
-- Indeks untuk tabel `kategori_keuangan`
--
ALTER TABLE `kategori_keuangan`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `id_rw` (`id_rw`);

--
-- Indeks untuk tabel `kehadiran_rapat`
--
ALTER TABLE `kehadiran_rapat`
  ADD PRIMARY KEY (`id_kehadiran`),
  ADD KEY `id_rapat` (`id_rapat`),
  ADD KEY `id_kk` (`id_kk`);

--
-- Indeks untuk tabel `kelurahan`
--
ALTER TABLE `kelurahan`
  ADD PRIMARY KEY (`id_kelurahan`);

--
-- Indeks untuk tabel `kepala_keluarga`
--
ALTER TABLE `kepala_keluarga`
  ADD PRIMARY KEY (`id_kk`),
  ADD UNIQUE KEY `nomor_kk` (`nomor_kk`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `idx_kk_nomor` (`nomor_kk`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD UNIQUE KEY `nomor_surat` (`nomor_surat`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `id_jenis_surat` (`id_jenis_surat`),
  ADD KEY `divalidasi_oleh_rt` (`divalidasi_oleh_rt`),
  ADD KEY `divalidasi_oleh_rw` (`divalidasi_oleh_rw`),
  ADD KEY `idx_pengajuan_status` (`status_pengajuan`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `dibuat_oleh` (`dibuat_oleh`),
  ADD KEY `idx_pengumuman_tanggal` (`tanggal_mulai`,`tanggal_berakhir`);

--
-- Indeks untuk tabel `rapat`
--
ALTER TABLE `rapat`
  ADD PRIMARY KEY (`id_rapat`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `id_rw` (`id_rw`);

--
-- Indeks untuk tabel `rt`
--
ALTER TABLE `rt`
  ADD PRIMARY KEY (`id_rt`),
  ADD KEY `id_rw` (`id_rw`);

--
-- Indeks untuk tabel `rw`
--
ALTER TABLE `rw`
  ADD PRIMARY KEY (`id_rw`),
  ADD KEY `id_kelurahan` (`id_kelurahan`);

--
-- Indeks untuk tabel `tugas_rt`
--
ALTER TABLE `tugas_rt`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_warga` (`id_warga`),
  ADD KEY `id_rt` (`id_rt`),
  ADD KEY `id_rw` (`id_rw`),
  ADD KEY `idx_users_username` (`username`);

--
-- Indeks untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id_warga`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `id_kk` (`id_kk`),
  ADD KEY `idx_warga_nik` (`nik`),
  ADD KEY `idx_warga_nama` (`nama_lengkap`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aduan`
--
ALTER TABLE `aduan`
  MODIFY `id_aduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `iuran_warga`
--
ALTER TABLE `iuran_warga`
  MODIFY `id_iuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `jenis_surat`
--
ALTER TABLE `jenis_surat`
  MODIFY `id_jenis_surat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kas_rt`
--
ALTER TABLE `kas_rt`
  MODIFY `id_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kas_rw`
--
ALTER TABLE `kas_rw`
  MODIFY `id_kas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_keuangan`
--
ALTER TABLE `kategori_keuangan`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kehadiran_rapat`
--
ALTER TABLE `kehadiran_rapat`
  MODIFY `id_kehadiran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kelurahan`
--
ALTER TABLE `kelurahan`
  MODIFY `id_kelurahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kepala_keluarga`
--
ALTER TABLE `kepala_keluarga`
  MODIFY `id_kk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rapat`
--
ALTER TABLE `rapat`
  MODIFY `id_rapat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rt`
--
ALTER TABLE `rt`
  MODIFY `id_rt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `rw`
--
ALTER TABLE `rw`
  MODIFY `id_rw` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tugas_rt`
--
ALTER TABLE `tugas_rt`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `warga`
--
ALTER TABLE `warga`
  MODIFY `id_warga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aduan`
--
ALTER TABLE `aduan`
  ADD CONSTRAINT `aduan_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id_warga`) ON DELETE CASCADE,
  ADD CONSTRAINT `aduan_ibfk_2` FOREIGN KEY (`ditangani_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD CONSTRAINT `galeri_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE SET NULL,
  ADD CONSTRAINT `galeri_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `iuran_warga`
--
ALTER TABLE `iuran_warga`
  ADD CONSTRAINT `iuran_warga_ibfk_1` FOREIGN KEY (`id_kk`) REFERENCES `kepala_keluarga` (`id_kk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kas_rt`
--
ALTER TABLE `kas_rt`
  ADD CONSTRAINT `kas_rt_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE CASCADE,
  ADD CONSTRAINT `kas_rt_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_keuangan` (`id_kategori`) ON DELETE CASCADE,
  ADD CONSTRAINT `kas_rt_ibfk_3` FOREIGN KEY (`id_user_input`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kas_rw`
--
ALTER TABLE `kas_rw`
  ADD CONSTRAINT `kas_rw_ibfk_1` FOREIGN KEY (`id_rw`) REFERENCES `rw` (`id_rw`) ON DELETE CASCADE,
  ADD CONSTRAINT `kas_rw_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_keuangan` (`id_kategori`) ON DELETE CASCADE,
  ADD CONSTRAINT `kas_rw_ibfk_3` FOREIGN KEY (`id_user_input`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE SET NULL,
  ADD CONSTRAINT `kegiatan_ibfk_2` FOREIGN KEY (`id_rw`) REFERENCES `rw` (`id_rw`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kehadiran_rapat`
--
ALTER TABLE `kehadiran_rapat`
  ADD CONSTRAINT `kehadiran_rapat_ibfk_1` FOREIGN KEY (`id_rapat`) REFERENCES `rapat` (`id_rapat`) ON DELETE CASCADE,
  ADD CONSTRAINT `kehadiran_rapat_ibfk_2` FOREIGN KEY (`id_kk`) REFERENCES `kepala_keluarga` (`id_kk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kepala_keluarga`
--
ALTER TABLE `kepala_keluarga`
  ADD CONSTRAINT `kepala_keluarga_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id_warga`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_surat_ibfk_2` FOREIGN KEY (`id_jenis_surat`) REFERENCES `jenis_surat` (`id_jenis_surat`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_surat_ibfk_3` FOREIGN KEY (`divalidasi_oleh_rt`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengajuan_surat_ibfk_4` FOREIGN KEY (`divalidasi_oleh_rw`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengumuman_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rapat`
--
ALTER TABLE `rapat`
  ADD CONSTRAINT `rapat_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE SET NULL,
  ADD CONSTRAINT `rapat_ibfk_2` FOREIGN KEY (`id_rw`) REFERENCES `rw` (`id_rw`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rt`
--
ALTER TABLE `rt`
  ADD CONSTRAINT `rt_ibfk_1` FOREIGN KEY (`id_rw`) REFERENCES `rw` (`id_rw`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rw`
--
ALTER TABLE `rw`
  ADD CONSTRAINT `rw_ibfk_1` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahan` (`id_kelurahan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tugas_rt`
--
ALTER TABLE `tugas_rt`
  ADD CONSTRAINT `tugas_rt_ibfk_1` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE CASCADE,
  ADD CONSTRAINT `tugas_rt_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id_warga`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_rt`) REFERENCES `rt` (`id_rt`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`id_rw`) REFERENCES `rw` (`id_rw`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD CONSTRAINT `warga_ibfk_1` FOREIGN KEY (`id_kk`) REFERENCES `kepala_keluarga` (`id_kk`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
