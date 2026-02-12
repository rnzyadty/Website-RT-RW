<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Warga RT/RW</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="assets/utils.js"></script>
  <script src="assets/data.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      overflow: hidden;
    }

    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      position: relative;
      overflow: hidden;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .login-container::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -100px;
      right: -100px;
      animation: float 6s ease-in-out infinite;
    }

    .login-container::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 50%;
      bottom: -50px;
      left: -50px;
      animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(30px); }
    }

    .login-box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 50px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      position: relative;
      z-index: 10;
      animation: slideUp 0.6s ease-out;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-header {
      text-align: center;
      margin-bottom: 35px;
    }

    .login-header .logo {
      font-size: 48px;
      margin-bottom: 15px;
      animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .login-header h1 {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 28px;
      margin: 0 0 8px 0;
      font-weight: 700;
    }

    .login-header p {
      color: #666;
      font-size: 14px;
      margin: 0;
    }

    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
      border-bottom: 2px solid #f0f0f0;
    }

    .tab-btn {
      flex: 1;
      padding: 12px;
      background: none;
      border: none;
      font-size: 15px;
      font-weight: 600;
      color: #999;
      cursor: pointer;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
    }

    .tab-btn.active {
      color: #667eea;
      border-bottom-color: #667eea;
    }

    .tab-btn:hover {
      color: #667eea;
    }

    .tab-content {
      display: none;
      animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #333;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 14px;
      font-family: inherit;
      transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      background: #f8f9ff;
    }

    .form-group input::placeholder {
      color: #aaa;
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 15px;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .login-footer {
      text-align: center;
      margin-top: 25px;
      font-size: 13px;
      color: #666;
    }

    .login-footer a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .login-footer a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .demo-info {
      background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(56, 142, 60, 0.1) 100%);
      border: 2px solid #4CAF50;
      border-radius: 12px;
      padding: 18px;
      margin-top: 25px;
      font-size: 12px;
      color: #1B5E20;
    }

    .demo-info strong {
      display: block;
      margin-bottom: 10px;
      color: #155724;
      font-size: 13px;
    }

    .demo-info p {
      margin: 6px 0;
      font-family: 'Courier New', monospace;
    }

    .error-message {
      background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(211, 47, 47, 0.1) 100%);
      border: 2px solid #F44336;
      color: #C62828;
      padding: 14px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: none;
      font-size: 14px;
      animation: shake 0.3s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    .success-message {
      background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(56, 142, 60, 0.1) 100%);
      border: 2px solid #4CAF50;
      color: #155724;
      padding: 14px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: none;
      font-size: 14px;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .switch-tab {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: #666;
    }

    .switch-tab a {
      color: #667eea;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .switch-tab a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .login-box {
        padding: 30px 25px;
      }

      .login-header h1 {
        font-size: 24px;
      }

      .role-options {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <div class="login-header">
        <div class="logo">🏘️</div>
        <h1>Sistem RT/RW</h1>
        <p>Platform Manajemen Komunitas Modern</p>
      </div>

      <div class="error-message" id="errorMessage"></div>
      <div class="success-message" id="successMessage"></div>

      <div class="tabs">
        <button type="button" class="tab-btn active" data-tab="login">Login</button>
        <button type="button" class="tab-btn" data-tab="register">Daftar</button>
      </div>

      <!-- Login Tab -->
      <div id="login" class="tab-content active">
        <form id="loginForm">
          <div class="form-group">
            <label>Username</label>
            <input type="text" id="username" placeholder="Masukkan username Anda" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" placeholder="Masukkan password Anda" required>
          </div>

          <button type="submit" class="btn-login">Login Sekarang</button>
        </form>

        <div class="switch-tab">
          Belum punya akun? <a onclick="switchTab('register')">Daftar di sini</a>
        </div>
      </div>

      <!-- Register Tab -->
      <div id="register" class="tab-content">
        <form id="registerForm">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" id="regName" placeholder="Masukkan nama lengkap Anda" required>
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" id="regUsername" placeholder="Pilih username unik" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" id="regPassword" placeholder="Minimal 6 karakter" required minlength="6">
          </div>

          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" id="regPasswordConfirm" placeholder="Ulangi password Anda" required>
          </div>

          <div class="form-group">
            <label>Tipe Pengguna</label>
            <select id="regRole" required>
              <option value="">-- Pilih Tipe --</option>
              <option value="warga">Warga</option>
              <option value="rt">RT</option>
              <option value="rw">RW</option>
            </select>
          </div>

          <button type="submit" class="btn-login">Daftar Akun</button>
        </form>

        <div class="switch-tab">
          Sudah punya akun? <a onclick="switchTab('login')">Login di sini</a>
        </div>
      </div>

      <div class="login-footer">
        <p>&copy; 2024 Sistem RT/RW. Semua Hak Dilindungi.</p>
      </div>
    </div>
  </div>

  <script>
    // Storage users - untuk demo dan registered users
    let allUsers = {
      warga: [
        { username: 'warga1', password: '1234', name: 'Budi Santoso', id: 'RW05/001', rt: '05' },
        { username: 'warga2', password: '1234', name: 'Siti Nurhaliza', id: 'RW05/002', rt: '05' },
        { username: 'warga3', password: '1234', name: 'Ahmad Gunawan', id: 'RW05/003', rt: '05' }
      ],
      rt: [
        { username: 'rt1', password: '1234', name: 'Rahmat', position: 'Ketua RT', rt: '05' },
        { username: 'rt2', password: '1234', name: 'Siti Rahayu', position: 'Bendahara RT', rt: '05' }
      ],
      rw: [
        { username: 'rw1', password: '1234', name: 'Suryanto', position: 'Ketua RW' },
        { username: 'rw2', password: '1234', name: 'Hendra', position: 'Koordinator RW' }
      ]
    };

    // Load registered users from localStorage
    function loadRegisteredUsers() {
      const registered = localStorage.getItem('registeredUsers');
      if (registered) {
        const parsed = JSON.parse(registered);
        Object.keys(parsed).forEach(role => {
          allUsers[role] = [...allUsers[role], ...parsed[role]];
        });
      }
    }

    loadRegisteredUsers();

    // Tab switching
    function switchTab(tab) {
      // Hide all tabs
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

      // Show selected tab
      document.getElementById(tab).classList.add('active');
      document.querySelector(`[data-tab="${tab}"]`).classList.add('active');

      // Clear error messages
      document.getElementById('errorMessage').style.display = 'none';
      document.getElementById('successMessage').style.display = 'none';
    }

    // Show message
    function showMessage(type, message) {
      const elem = type === 'error' ? document.getElementById('errorMessage') : document.getElementById('successMessage');
      elem.textContent = message;
      elem.style.display = 'block';
    }

    // Login handler dengan PHP Backend
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!username || !password) {
        showModal({
          title: 'Validasi Login',
          message: 'Username dan password harus diisi.',
          type: 'warning',
          buttons: [{ text: 'OK' }]
        });
        return;
      }

      try {
        // Set loading state
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = '⏳ Memproses...';

        // Kirim request ke PHP backend
        const response = await fetch('php/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            username: username,
            password: password
          })
        });

        const result = await response.json();

        if (result.success) {
          // Save session immediately
          localStorage.setItem('userSession', JSON.stringify({
            username: result.data.username,
            nama: result.data.nama,
            role: result.data.role
          }));

          // Show centered toast (brief) and redirect
          if (window.UIUtils && typeof window.UIUtils.centerToast === 'function') {
            window.UIUtils.centerToast(`Selamat datang, <strong>${result.data.nama}</strong>!`, 'success', 900);
          } else if (typeof showCenteredToast === 'function') {
            showCenteredToast(`Selamat datang, <strong>${result.data.nama}</strong>!`, 'success', 900);
          } else {
            // fallback to normal toast
            showToast(`Selamat datang, ${result.data.nama}!`, 'success', 900);
          }

          setTimeout(() => {
            window.location.href = result.data.redirect;
          }, 1100);
        } else {
          // Error modal
          showModal({
            title: 'Login Gagal',
            message: result.message || 'Username atau password salah.',
            type: 'error',
            buttons: [{ text: 'Coba Lagi' }]
          });
          btn.disabled = false;
          btn.textContent = originalText;
        }
      } catch (error) {
        console.error('Error:', error);
        showModal({
          title: 'Terjadi Kesalahan',
          message: 'Gagal terhubung ke server. Silakan coba lagi.',
          type: 'error',
          buttons: [{ text: 'OK' }]
        });
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = false;
        btn.textContent = 'Login Sekarang';
      }
    });

    // Register handler
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const name = document.getElementById('regName').value.trim();
      const username = document.getElementById('regUsername').value.trim();
      const password = document.getElementById('regPassword').value;
      const passwordConfirm = document.getElementById('regPasswordConfirm').value;
      const role = document.getElementById('regRole').value;

      // Validation
      if (!name || !username || !password || !passwordConfirm || !role) {
        showModal({
          title: 'Validasi Form',
          message: 'Semua field harus diisi!',
          type: 'warning',
          buttons: [{ text: 'OK' }]
        });
        return;
      }

      if (password !== passwordConfirm) {
        showModal({
          title: 'Password Tidak Cocok',
          message: 'Password dan konfirmasi password tidak sesuai. Silakan cek kembali.',
          type: 'warning',
          buttons: [{ text: 'OK' }]
        });
        return;
      }

      if (password.length < 6) {
        showModal({
          title: 'Password Terlalu Pendek',
          message: 'Password harus minimal 6 karakter untuk keamanan.',
          type: 'warning',
          buttons: [{ text: 'OK' }]
        });
        return;
      }

      // Check username exists
      let usernameExists = false;
      Object.values(allUsers).forEach(roleUsers => {
        if (roleUsers.find(u => u.username === username)) {
          usernameExists = true;
        }
      });

      if (usernameExists) {
        showModal({
          title: 'Username Sudah Terdaftar',
          message: `Username <strong>${username}</strong> sudah digunakan. Silakan gunakan username lain.`,
          type: 'error',
          buttons: [{ text: 'OK' }]
        });
        return;
      }

      // Create user
      const newUser = {
        username: username,
        password: password,
        name: name,
        id: Date.now().toString(),
        rt: '05'
      };

      // Add to users
      if (!allUsers[role]) allUsers[role] = [];
      allUsers[role].push(newUser);

      // Save to localStorage
      let registered = localStorage.getItem('registeredUsers');
      registered = registered ? JSON.parse(registered) : {};
      if (!registered[role]) registered[role] = [];
      registered[role].push(newUser);
      localStorage.setItem('registeredUsers', JSON.stringify(registered));

      // Success message
      showModal({
        title: 'Registrasi Berhasil',
        message: `Selamat! Akun <strong>${username}</strong> telah didaftarkan.<br><br>Silakan login dengan akun baru Anda.`,
        type: 'success',
        buttons: [{ 
          text: 'OK',
          action: () => {
            switchTab('login');
            document.getElementById('username').value = username;
            document.getElementById('username').focus();
          }
        }]
      });

      // Reset form
      document.getElementById('registerForm').reset();
    });

    // Tab button handlers
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        switchTab(this.dataset.tab);
      });
    });

    // Check if already logged in
    window.addEventListener('load', function() {
      const session = localStorage.getItem('userSession');
      if (session) {
        try {
          const user = JSON.parse(session);
          // Optional: Redirect immediately or show welcome
          setTimeout(() => {
            if (user.role === 'warga') {
              window.location.href = 'pages/dashboard-warga.html';
            } else if (user.role === 'rt') {
              window.location.href = 'pages/dashboard-rt.html';
            } else if (user.role === 'rw') {
              window.location.href = 'pages/dashboard-rw.html';
            }
          }, 300);
        } catch (e) {
          console.error('Error parsing session:', e);
        }
      }
    });
  </script>
</body>
</html>
