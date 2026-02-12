/**
 * PAYMENT SIMULATOR SYSTEM
 * Production-ready demo pembayaran iuran & transaksi RT/RW
 * 
 * IMPORTANT: "🧪 Simulasi Pembayaran (bukan transaksi asli)"
 * Semua data disimpan di localStorage, siap integrasi dengan backend
 */

const PaymentSystem = {
  // ==========================================
  // PAYMENT METHODS (DUMMY DATA)
  // ==========================================
  PAYMENT_METHODS: {
    qris: {
      id: 'qris',
      name: '💳 QRIS',
      icon: '📱',
      description: 'Scan QR Code atau gunakan E-Wallet (GoPay, OVO, DANA, dll)'
    },
    transfer: {
      id: 'transfer',
      name: '🏦 Transfer Bank',
      icon: '🏦',
      description: 'Transfer langsung ke rekening RT'
    },
    cash: {
      id: 'cash',
      name: '💵 Tunai',
      icon: '💰',
      description: 'Bayar tunai langsung ke petugas RT'
    }
  },

  // ==========================================
  // STATUS PEMBAYARAN
  // ==========================================
  PAYMENT_STATUS: {
    PENDING: 'pending',
    PROCESSING: 'processing',
    SUCCESS: 'success',
    FAILED: 'failed',
    CANCELLED: 'cancelled'
  },

  // ==========================================
  // TIPE TRANSAKSI
  // ==========================================
  TRANSACTION_TYPE: {
    IURAN_BULANAN: 'iuran_bulanan',
    IURAN_TAMBAHAN: 'iuran_tambahan',
    INFAQ: 'infaq',
    LAINNYA: 'lainnya'
  },

  // ==========================================
  // INIT PAYMENT SIMULATOR
  // ==========================================
  initPaymentSession(paymentData) {
    const session = {
      id: 'PAY-' + Date.now(),
      timestamp: new Date().toISOString(),
      status: this.PAYMENT_STATUS.PENDING,
      ...paymentData
    };

    // Save ke localStorage
    let sessions = JSON.parse(localStorage.getItem('paymentSessions') || '[]');
    sessions.push(session);
    localStorage.setItem('paymentSessions', JSON.stringify(sessions));

    return session;
  },

  // ==========================================
  // PROCESS PAYMENT SIMULATION
  // ==========================================
  processPayment(paymentSessionId, paymentMethod) {
    return new Promise((resolve) => {
      // Simulate processing delay (realistic UX)
      setTimeout(() => {
        const sessions = JSON.parse(localStorage.getItem('paymentSessions') || '[]');
        const session = sessions.find(s => s.id === paymentSessionId);

        if (!session) {
          resolve({ success: false, error: 'Session tidak ditemukan' });
          return;
        }

        // 95% berhasil, 5% gagal (untuk realistic demo)
        const isSuccess = Math.random() > 0.05;

        if (isSuccess) {
          session.status = this.PAYMENT_STATUS.SUCCESS;
          session.paymentMethod = paymentMethod;
          session.completedAt = new Date().toISOString();
          session.receiptNumber = 'REC-' + Date.now();

          // Save transaction ke transaksi list
          this.saveTransaction({
            paymentSessionId,
            ...session,
            status: 'success'
          });

          // Update iuran status ke LUNAS
          this.markIuranAsLunas(session);
        } else {
          session.status = this.PAYMENT_STATUS.FAILED;
          session.failureReason = 'Koneksi timeout (simulasi demo)';
        }

        sessions.splice(sessions.findIndex(s => s.id === paymentSessionId), 1, session);
        localStorage.setItem('paymentSessions', JSON.stringify(sessions));

        resolve({
          success: isSuccess,
          session,
          message: isSuccess
            ? 'Pembayaran berhasil diproses'
            : 'Pembayaran gagal. Silakan coba lagi.'
        });
      }, 2000 + Math.random() * 2000); // 2-4 detik
    });
  },

  // ==========================================
  // SAVE TRANSACTION RECORD
  // ==========================================
  saveTransaction(transaction) {
    let transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
    transactions.push({
      id: transaction.paymentSessionId,
      nominal: transaction.nominal,
      type: transaction.type,
      description: transaction.description,
      paymentMethod: transaction.paymentMethod,
      userSession: transaction.userSession || localStorage.getItem('userSession'),
      timestamp: transaction.completedAt,
      status: 'success',
      receiptNumber: transaction.receiptNumber
    });
    localStorage.setItem('transactions', JSON.stringify(transactions));

    try {
      const user = (() => {
        try {
          return JSON.parse(transaction.userSession || localStorage.getItem('userSession') || '{}');
        } catch (e) {
          return {};
        }
      })();

      const kasEntry = {
        id: 'KAS-' + Date.now(),
        type: 'pemasukan',
        nominal: transaction.nominal || 0,
        description: transaction.description || 'Pembayaran warga',
        source: transaction.type || 'transaksi',
        paymentMethod: transaction.paymentMethod,
        date: transaction.completedAt || new Date().toISOString(),
        warga: user.name || 'Warga',
        reference: transaction.paymentSessionId || transaction.id
      };

      let kasList = JSON.parse(localStorage.getItem('kasList') || '[]');
      kasList.push(kasEntry);
      localStorage.setItem('kasList', JSON.stringify(kasList));
    } catch (e) {
      console.warn('Gagal menyimpan kas otomatis', e);
    }
  },

  // ==========================================
  // MARK IURAN AS PAID
  // ==========================================
  markIuranAsLunas(paymentSession) {
    if (paymentSession.type !== this.TRANSACTION_TYPE.IURAN_BULANAN) {
      return; // Hanya untuk iuran bulanan
    }

    let iuranList = JSON.parse(localStorage.getItem('iuranList') || '[]');
    const iuran = iuranList.find(i =>
      i.bulan === paymentSession.bulan &&
      i.tahun === paymentSession.tahun &&
      i.warga === paymentSession.warga
    );

    if (iuran) {
      iuran.status = 'LUNAS';
      iuran.paidDate = new Date().toLocaleString('id-ID');
      iuran.paidAmount = iuran.nominal;
      iuranList.splice(iuranList.findIndex(i => i === iuran), 1, iuran);
      localStorage.setItem('iuranList', JSON.stringify(iuranList));
    }
  },

  // ==========================================
  // GET PAYMENT SESSION
  // ==========================================
  getPaymentSession(sessionId) {
    const sessions = JSON.parse(localStorage.getItem('paymentSessions') || '[]');
    return sessions.find(s => s.id === sessionId);
  },

  // ==========================================
  // GET ALL TRANSACTIONS
  // ==========================================
  getAllTransactions() {
    return JSON.parse(localStorage.getItem('transactions') || '[]');
  },

  // ==========================================
  // GET WARGA TRANSACTION HISTORY
  // ==========================================
  getWargaTransactions(wargaName) {
    const transactions = this.getAllTransactions();
    return transactions.filter(t => {
      try {
        const userSession = JSON.parse(t.userSession);
        return userSession.name === wargaName;
      } catch (e) {
        return false;
      }
    });
  },

  // ==========================================
  // GET STATISTICS (FOR DASHBOARD RT/RW)
  // ==========================================
  getPaymentStatistics() {
    const transactions = this.getAllTransactions();
    return {
      totalTransactions: transactions.length,
      totalAmount: transactions.reduce((sum, t) => sum + (t.nominal || 0), 0),
      byType: {
        iuranBulanan: transactions.filter(t => t.type === this.TRANSACTION_TYPE.IURAN_BULANAN).length,
        iuranTambahan: transactions.filter(t => t.type === this.TRANSACTION_TYPE.IURAN_TAMBAHAN).length,
        infaq: transactions.filter(t => t.type === this.TRANSACTION_TYPE.INFAQ).length
      },
      byMethod: {
        qris: transactions.filter(t => t.paymentMethod === 'qris').length,
        transfer: transactions.filter(t => t.paymentMethod === 'transfer').length,
        cash: transactions.filter(t => t.paymentMethod === 'cash').length
      }
    };
  },

  // ==========================================
  // GENERATE RECEIPT (PDF READY)
  // ==========================================
  generateReceipt(paymentSessionId) {
    const session = this.getPaymentSession(paymentSessionId);
    if (!session) return null;

    return {
      receiptNumber: session.receiptNumber,
      transactionId: session.id,
      amount: session.nominal,
      type: session.type,
      description: session.description,
      paymentMethod: session.paymentMethod,
      timestamp: session.completedAt,
      status: session.status,
      // Bisa di-generate jadi PDF nanti
      html: `
        <div style="text-align: center; font-family: Arial; padding: 20px; border: 1px solid #ccc;">
          <h2>🧪 BUKTI SIMULASI PEMBAYARAN</h2>
          <p style="color: red; font-weight: bold;">Bukan transaksi asli - Untuk demo saja</p>
          <hr>
          <p><strong>No. Referensi:</strong> ${session.receiptNumber}</p>
          <p><strong>Tanggal:</strong> ${new Date(session.completedAt).toLocaleString('id-ID')}</p>
          <p><strong>Nominal:</strong> Rp ${session.nominal.toLocaleString('id-ID')}</p>
          <p><strong>Deskripsi:</strong> ${session.description}</p>
          <p><strong>Metode:</strong> ${this.PAYMENT_METHODS[session.paymentMethod]?.name || session.paymentMethod}</p>
          <p><strong>Status:</strong> ${session.status === 'success' ? '✓ BERHASIL' : '✗ GAGAL'}</p>
          <hr>
          <p style="font-size: 12px; color: #999;">Cetak bukti ini sebagai arsip</p>
        </div>
      `
    };
  }
};

// Export untuk digunakan di seluruh aplikasi
window.PaymentSystem = PaymentSystem;
