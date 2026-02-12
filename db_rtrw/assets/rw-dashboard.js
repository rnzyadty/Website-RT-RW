// RW Dashboard Functions
(function() {
  'use strict';

  // Initialize dashboard
  async function initRWDashboard() {
    console.log('Initializing RW Dashboard...');
    
    // Check session first
    await checkSession();
    
    // Load data
    loadSuratRecap();
    loadAduanEscalated();
    loadKeuanganRT();
    loadStatistikWarga();
  }

  // Check session and verify role
  async function checkSession() {
    try {
      const response = await fetch('../php/check_session.php');
      const result = await response.json();
      
      if (!result.logged_in || (result.role !== 'rw' && result.role !== 'admin')) {
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

  // Load surat recap
  async function loadSuratRecap() {
    try {
      const response = await fetch('../php/rw-surat.php?action=recap');
      const result = await response.json();
      
      if (result.success) {
        displaySuratRecap(result.data);
      }
    } catch (e) {
      console.error('Error loading surat recap:', e);
    }
  }

  // Display surat recap
  function displaySuratRecap(data) {
    console.log('Surat Recap for RW:', data);
  }

  // Load aduan escalated
  async function loadAduanEscalated() {
    try {
      const response = await fetch('../php/rw-aduan.php?action=list');
      const result = await response.json();
      
      if (result.success) {
        displayAduanEscalated(result.data);
      }
    } catch (e) {
      console.error('Error loading aduan:', e);
    }
  }

  // Display aduan escalated
  function displayAduanEscalated(data) {
    console.log('Escalated Aduan for RW:', data);
  }

  // Load keuangan RT
  async function loadKeuanganRT() {
    try {
      const response = await fetch('../php/rw-keuangan.php?action=keuangan_rt');
      const result = await response.json();
      
      if (result.success) {
        displayKeuanganRT(result.data);
      }
    } catch (e) {
      console.error('Error loading keuangan:', e);
    }
  }

  // Display keuangan RT
  function displayKeuanganRT(data) {
    console.log('Keuangan per RT:', data);
  }

  // Load statistik warga
  async function loadStatistikWarga() {
    try {
      const response = await fetch('../php/rw-statistik.php?action=warga_stats');
      const result = await response.json();
      
      if (result.success) {
        displayStatistikWarga(result.data);
      }
    } catch (e) {
      console.error('Error loading statistik:', e);
    }
  }

  // Display statistik warga
  function displayStatistikWarga(data) {
    console.log('Statistik Warga per RT:', data);
  }

  // Approve surat
  window.approveSuratRW = async function(id_pengajuan, catatan = '') {
    try {
      const response = await fetch('../php/rw-surat.php?action=approve_surat', {
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
        showNotification('Pengajuan disetujui RW', 'success');
        loadSuratRecap();
      } else {
        showNotification(result.message || 'Error approving', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Reject surat
  window.rejectSuratRW = async function(id_pengajuan, alasan = '') {
    try {
      const response = await fetch('../php/rw-surat.php?action=reject_surat', {
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
        loadSuratRecap();
      } else {
        showNotification(result.message || 'Error rejecting', 'error');
      }
    } catch (e) {
      console.error('Error:', e);
      showNotification('Terjadi kesalahan', 'error');
    }
  };

  // Update aduan RW
  window.updateAduanRW = async function(id_aduan, status, solusi = '') {
    try {
      const response = await fetch('../php/rw-aduan.php?action=update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id_aduan: id_aduan,
          status: status,
          solusi: solusi
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        showNotification('Aduan diupdate', 'success');
        loadAduanEscalated();
      } else {
        showNotification(result.message || 'Error updating', 'error');
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
    document.addEventListener('DOMContentLoaded', initRWDashboard);
  } else {
    initRWDashboard();
  }

  // Expose functions
  window.RWDashboard = {
    initRWDashboard,
    checkSession,
    loadSuratRecap,
    loadAduanEscalated,
    loadKeuanganRT,
    loadStatistikWarga
  };
})();
