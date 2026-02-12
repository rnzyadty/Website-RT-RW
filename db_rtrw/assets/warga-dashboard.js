// Warga Dashboard Functions
(function() {
  'use strict';

  // Initialize dashboard
  async function initWargaDashboard() {
    console.log('Initializing Warga Dashboard...');
    
    // Check session first
    await checkSession();
    
    // Load data
    loadPengajuanSurat();
    loadIuran();
    loadPengumuman();
    loadAduan();
  }

  // Check session and verify role
  async function checkSession() {
    try {
      const response = await fetch('../php/check_session.php');
      const result = await response.json();
      
      if (!result.logged_in || result.role !== 'warga') {
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
      const response = await fetch('../php/warga-pengajuan-surat.php?action=list');
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
    const container = document.getElementById('suratContainer') || document.querySelector('[data-section="surat"]');
    if (!container) return;
    
    // Update counter if exists
    const counter = container.querySelector('[data-count]');
    if (counter) {
      const pending = data.filter(s => s.status_pengajuan === 'pending').length;
      counter.textContent = pending;
    }
    
    // Log for debugging - in production this would update UI
    console.log('Pengajuan Surat loaded:', data);
  }

  // Load iuran
  async function loadIuran() {
    try {
      const response = await fetch('../php/warga-iuran.php?action=list');
      const result = response.json();
      
      if (result.success) {
        displayIuran(result.data);
      }
    } catch (e) {
      console.error('Error loading iuran:', e);
    }
  }

  // Display iuran
  function displayIuran(data) {
    const container = document.getElementById('iuranContainer') || document.querySelector('[data-section="iuran"]');
    if (!container) return;
    
    console.log('Iuran loaded:', data);
  }

  // Load pengumuman
  async function loadPengumuman() {
    try {
      const response = await fetch('../php/public-content.php?action=pengumuman');
      const result = await response.json();
      
      if (result.success) {
        displayPengumuman(result.data);
      }
    } catch (e) {
      console.error('Error loading pengumuman:', e);
    }
  }

  // Display pengumuman
  function displayPengumuman(data) {
    const container = document.getElementById('pengumumanContainer') || document.querySelector('[data-section="pengumuman"]');
    if (!container) return;
    
    console.log('Pengumuman loaded:', data.slice(0, 3));
  }

  // Load aduan
  async function loadAduan() {
    try {
      const response = await fetch('../php/warga-aduan.php?action=list');
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
    const container = document.getElementById('aduanContainer') || document.querySelector('[data-section="aduan"]');
    if (!container) return;
    
    console.log('Aduan loaded:', data);
  }

  // Submit pengajuan surat
  window.submitPengajuanSurat = async function(jenis_surat, tujuan) {
    try {
      const response = await fetch('../php/warga-pengajuan-surat.php?action=submit', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_jenis_surat: jenis_surat,
          tujuan_surat: tujuan
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Pengajuan surat berhasil disubmit', 'success');
        loadPengajuanSurat();
      } else {
        showNotification(result.message || 'Error submitting surat', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Submit aduan
  window.submitAduan = async function(judul, isi, kategori, lokasi) {
    try {
      const response = await fetch('../php/warga-aduan.php?action=submit', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          judul_aduan: judul,
          isi_aduan: isi,
          kategori: kategori,
          lokasi: lokasi
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Aduan berhasil disubmit', 'success');
        loadAduan();
      } else {
        showNotification(result.message || 'Error submitting aduan', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Bayar iuran
  window.bayarIuran = async function(id_iuran, metode) {
    try {
      const response = await fetch('../php/warga-iuran.php?action=bayar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_iuran: id_iuran,
          metode_bayar: metode
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Pembayaran berhasil dicatat', 'success');
        loadIuran();
      } else {
        showNotification(result.message || 'Error recording payment', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Notification helper
  function showNotification(message, type = 'info') {
    const banner = document.getElementById('payment-success-banner');
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
    document.addEventListener('DOMContentLoaded', initWargaDashboard);
  } else {
    initWargaDashboard();
  }

  // Expose functions
  window.WargaDashboard = {
    initWargaDashboard,
    checkSession,
    loadPengajuanSurat,
    loadIuran,
    loadPengumuman,
    loadAduan
  };
})();
