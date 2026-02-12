// RT Dashboard Functions
(function() {
  'use strict';

  // Initialize dashboard
  async function initRTDashboard() {
    console.log('Initializing RT Dashboard...');
    
    // Check session first
    await checkSession();
    
    // Load data
    loadPengajuanSurat();
    loadAduan();
    loadKas();
    loadDataWarga();
  }

  // Check session and verify role
  async function checkSession() {
    try {
      const response = await fetch('../php/check_session.php');
      const result = await response.json();
      
      if (!result.logged_in || result.role !== 'rt') {
        window.location.href = '../index.php';
      }
      
      // Set user info in header
      if (result.nama) {
        const userNameEl = document.getElementById('userNameDisplay');
        if (userNameEl) userNameEl.textContent = result.nama;
      }
      
      window.currentSession = result;
    } catch (e) {
      console.error('Session check failed:', e);
      window.location.href = '../index.php';
    }
  }

  // Load pengajuan surat
  async function loadPengajuanSurat() {
    try {
      const response = await fetch('../php/rt-pengajuan-surat.php?action=list');
      const result = await response.json();
      
      if (result.success) {
        displayPengajuanSurat(result.data);
      }
    } catch (e) {
      console.error('Error loading surat:', e);
    }
  }

  // Display pengajuan surat
  function displayPengajuanSurat(data) {
    console.log('Pengajuan Surat for RT:', data);
  }

  // Load aduan
  async function loadAduan() {
    try {
      const response = await fetch('../php/rt-aduan.php?action=list');
      const result = await response.json();
      
      if (result.success) {
        displayAduan(result.data);
      }
    } catch (e) {
      console.error('Error loading aduan:', e);
    }
  }

  // Display aduan
  function displayAduan(data) {
    console.log('Aduan for RT:', data);
  }

  // Load kas RT
  async function loadKas() {
    try {
      const response = await fetch('../php/rt-kas.php?action=summary');
      const result = await response.json();
      
      if (result.success) {
        displayKas(result.data);
      }
    } catch (e) {
      console.error('Error loading kas:', e);
    }
  }

  // Display kas
  function displayKas(data) {
    console.log('Kas RT:', data);
  }

  // Load data warga
  async function loadDataWarga() {
    try {
      const response = await fetch('../php/rt-data-warga.php?action=summary');
      const result = await response.json();
      
      if (result.success) {
        displayDataWarga(result.data);
      }
    } catch (e) {
      console.error('Error loading data warga:', e);
    }
  }

  // Display data warga
  function displayDataWarga(data) {
    console.log('Data Warga RT:', data);
  }

  // Approve surat
  window.approveSurat = async function(id_pengajuan, catatan = '') {
    try {
      const response = await fetch('../php/rt-pengajuan-surat.php?action=approve', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_pengajuan: id_pengajuan,
          catatan: catatan
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Pengajuan disetujui', 'success');
        loadPengajuanSurat();
      } else {
        showNotification(result.message || 'Error approving', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Reject surat
  window.rejectSurat = async function(id_pengajuan, alasan = '') {
    try {
      const response = await fetch('../php/rt-pengajuan-surat.php?action=reject', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_pengajuan: id_pengajuan,
          alasan: alasan
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Pengajuan ditolak', 'success');
        loadPengajuanSurat();
      } else {
        showNotification(result.message || 'Error rejecting', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Update aduan status
  window.updateAduanStatus = async function(id_aduan, status, catatan = '') {
    try {
      const response = await fetch('../php/rt-aduan.php?action=update_status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_aduan: id_aduan,
          status: status,
          catatan: catatan
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Status aduan diupdate', 'success');
        loadAduan();
      } else {
        showNotification(result.message || 'Error updating', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Add kas transaction
  window.addKasTransaction = async function(tanggal, jenis, kategori, nominal, keterangan) {
    try {
      const response = await fetch('../php/rt-kas.php?action=add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          tanggal: tanggal,
          jenis_transaksi: jenis,
          id_kategori: kategori,
          nominal: nominal,
          keterangan: keterangan
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Transaksi kas berhasil ditambahkan', 'success');
        loadKas();
      } else {
        showNotification(result.message || 'Error adding transaction', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Notification helper
  function showNotification(message, type = 'info') {
    const banner = document.getElementById('notify-banner') || document.querySelector('.info-box');
    if (banner) {
      banner.style.display = 'block';
      banner.className = 'info-box ' + (type === 'success' ? 'success' : type === 'error' ? 'error' : '');
      banner.textContent = message;
    } else {
      alert(message);
    }
  }

  // Initialize on page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRTDashboard);
  } else {
    initRTDashboard();
  }

  // Expose functions
  window.RTDashboard = {
    initRTDashboard,
    checkSession,
    loadPengajuanSurat,
    loadAduan,
    loadKas,
    loadDataWarga
  };
})();
