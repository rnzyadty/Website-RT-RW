/**
 * DASHBOARD SYSTEM - Notification & Real-time Updates
 * Used by RT/RW dashboard untuk notifikasi & counter updates
 * 
 * Simulates real-time notifications dengan localStorage
 * Siap untuk integrasi WebSocket/Server Sent Events
 */

const DashboardSystem = {
  // ==========================================
  // NOTIFICATION TYPES
  // ==========================================
  NOTIFICATION_TYPES: {
    IURAN_LUNAS: 'iuran_lunas',
    IURAN_OVERDUE: 'iuran_overdue',
    PERMOHONAN_SURAT: 'permohonan_surat',
    ADUAN_MASUK: 'aduan_masuk',
    KEHADIRAN_BARU: 'kehadiran_baru',
    LAPORAN_MASUK: 'laporan_masuk'
  },

  // ==========================================
  // CREATE NOTIFICATION
  // ==========================================
  createNotification(type, data) {
    const notification = {
      id: 'NOTIF-' + Date.now(),
      type: type,
      timestamp: new Date().toISOString(),
      read: false,
      data: data
    };

    let notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    notifications.unshift(notification); // Add to beginning
    localStorage.setItem('notifications', JSON.stringify(notifications));

    // Trigger badge update
    this.updateNotificationBadge();

    return notification;
  },

  // ==========================================
  // GET UNREAD NOTIFICATIONS
  // ==========================================
  getUnreadNotifications() {
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    return notifications.filter(n => !n.read);
  },

  // ==========================================
  // GET NOTIFICATIONS BY TYPE
  // ==========================================
  getNotificationsByType(type) {
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    return notifications.filter(n => n.type === type);
  },

  // ==========================================
  // MARK AS READ
  // ==========================================
  markAsRead(notificationId) {
    let notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    const notification = notifications.find(n => n.id === notificationId);
    
    if (notification) {
      notification.read = true;
      localStorage.setItem('notifications', JSON.stringify(notifications));
      this.updateNotificationBadge();
    }
  },

  // ==========================================
  // UPDATE BADGE COUNT
  // ==========================================
  updateNotificationBadge() {
    const unreadCount = this.getUnreadNotifications().length;
    
    // Update badge element if exists
    const badge = document.getElementById('notificationBadge');
    if (badge) {
      if (unreadCount > 0) {
        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
    }
  },

  // ==========================================
  // IURAN STATISTICS
  // ==========================================
  getIuranStatistics(bulan, tahun) {
    // Get all warga
    const wargaList = AppData.DEMO_USERS.warga;
    const transactions = JSON.parse(localStorage.getItem('transactions') || '[]');

    const total = wargaList.length;
    const lunas = transactions.filter(t => {
      try {
        const user = JSON.parse(t.userSession);
        return t.type === 'iuran_bulanan' && user.role === 'warga';
      } catch (e) {
        return false;
      }
    }).length;

    const belum = total - lunas;
    const percentageLunas = Math.round((lunas / total) * 100);

    return {
      total: total,
      lunas: lunas,
      belum: belum,
      percentage: percentageLunas,
      totalAmount: transactions
        .filter(t => t.type === 'iuran_bulanan')
        .reduce((sum, t) => sum + (t.nominal || 0), 0)
    };
  },

  // ==========================================
  // PERMOHONAN SURAT STATISTICS
  // ==========================================
  getPermohonanStatistics() {
    // Dummy data
    return {
      pending: 3,
      approved: 8,
      rejected: 2,
      total: 13,
      percentageCompleted: 77
    };
  },

  // ==========================================
  // ADUAN STATISTICS
  // ==========================================
  getAduanStatistics() {
    const aduanList = JSON.parse(localStorage.getItem('aduanList') || '[]');
    
    return {
      total: aduanList.length,
      diproses: aduanList.filter(a => a.status === 'DITERIMA' || a.status === 'DIPROSES').length,
      selesai: aduanList.filter(a => a.status === 'SELESAI').length,
      ditolak: aduanList.filter(a => a.status === 'DITOLAK').length
    };
  },

  // ==========================================
  // KEHADIRAN STATISTICS
  // ==========================================
  getKehadiranStatistics(namaRapat) {
    const kehadiranList = JSON.parse(localStorage.getItem('kehadiranList') || '[]');
    const totalWarga = AppData.DEMO_USERS.warga.length;
    
    const registered = kehadiranList.filter(k => 
      k.namaRapat.includes(namaRapat || 'Rapat')
    ).length;

    return {
      registered: registered,
      total: totalWarga,
      belum: totalWarga - registered,
      percentage: Math.round((registered / totalWarga) * 100)
    };
  },

  // ==========================================
  // GET DASHBOARD SUMMARY (UNTUK RT/RW)
  // ==========================================
  getDashboardSummary() {
    const iuran = this.getIuranStatistics();
    const aduan = this.getAduanStatistics();
    const kehadiran = this.getKehadiranStatistics();
    const permohonan = this.getPermohonanStatistics();
    const unreadNotifications = this.getUnreadNotifications();

    return {
      iuran: iuran,
      aduan: aduan,
      kehadiran: kehadiran,
      permohonan: permohonan,
      notificationsUnread: unreadNotifications.length,
      lastUpdated: new Date().toLocaleString('id-ID')
    };
  },

  // ==========================================
  // PAYMENT RECEIVED NOTIFICATION (Called when payment success)
  // ==========================================
  notifyPaymentReceived(wargaName, nominal, bulan) {
    this.createNotification(this.NOTIFICATION_TYPES.IURAN_LUNAS, {
      wargaName: wargaName,
      nominal: nominal,
      bulan: bulan,
      message: `Pembayaran iuran ${bulan} dari ${wargaName} sebesar Rp ${nominal.toLocaleString('id-ID')} diterima`
    });
  },

  // ==========================================
  // NEW COMPLAINT NOTIFICATION
  // ==========================================
  notifyNewComplaint(aduanTitle, wargaName) {
    this.createNotification(this.NOTIFICATION_TYPES.ADUAN_MASUK, {
      title: aduanTitle,
      wargaName: wargaName,
      message: `Aduan baru dari ${wargaName}: ${aduanTitle}`
    });
  },

  // ==========================================
  // NEW ATTENDANCE NOTIFICATION
  // ==========================================
  notifyNewAttendance(wargaName, namaRapat) {
    this.createNotification(this.NOTIFICATION_TYPES.KEHADIRAN_BARU, {
      wargaName: wargaName,
      namaRapat: namaRapat,
      message: `${wargaName} telah mendaftar kehadiran untuk ${namaRapat}`
    });
  },

  // ==========================================
  // RENDER NOTIFICATION LIST
  // ==========================================
  renderNotificationList(container) {
    const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
    const unread = notifications.filter(n => !n.read);
    const read = notifications.filter(n => n.read).slice(0, 5); // Last 5 read

    let html = '';

    if (unread.length === 0 && read.length === 0) {
      html = '<p style="text-align: center; color: #999; padding: 20px;">Tidak ada notifikasi</p>';
    } else {
      // Unread notifications
      if (unread.length > 0) {
        html += '<div style="background: #E3F2FD; padding: 10px; margin-bottom: 10px; border-radius: 3px;"><strong style="color: #1976D2;">Notifikasi Baru</strong></div>';
        
        unread.forEach(n => {
          html += `
            <div style="border-bottom: 1px solid #EEE; padding: 12px 0; background: #FFFBF0;">
              <div style="font-weight: bold; color: var(--warna-utama); font-size: 13px;">${n.data.message || n.type}</div>
              <div style="font-size: 11px; color: #999; margin-top: 5px;">${new Date(n.timestamp).toLocaleString('id-ID')}</div>
            </div>
          `;
        });
      }

      // Read notifications
      if (read.length > 0) {
        html += '<div style="background: #F5F5F5; padding: 10px; margin: 15px 0 10px 0; border-radius: 3px; font-size: 12px;"><strong>Notifikasi Sebelumnya</strong></div>';
        
        read.forEach(n => {
          html += `
            <div style="border-bottom: 1px solid #EEE; padding: 12px 0; opacity: 0.7;">
              <div style="font-size: 13px; color: #666;">${n.data.message || n.type}</div>
              <div style="font-size: 11px; color: #999; margin-top: 5px;">${new Date(n.timestamp).toLocaleString('id-ID')}</div>
            </div>
          `;
        });
      }
    }

    if (container) {
      container.innerHTML = html;
    }

    return html;
  }
};

// Export untuk digunakan
window.DashboardSystem = DashboardSystem;
