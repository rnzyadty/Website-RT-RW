// Minimal dashboard header helpers: dropdowns + logout handler
(function () {
	function initUserMenu() {
		const toggle = document.getElementById('userMenuToggle');
		const dropdown = document.getElementById('userDropdown');
		if (!toggle || !dropdown) return;

		toggle.addEventListener('click', (e) => {
			e.stopPropagation();
			const isOpen = dropdown.classList.toggle('open');
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});

		// close when clicking outside
		document.addEventListener('click', (e) => {
			if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
				dropdown.classList.remove('open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});

		// keyboard escape
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				dropdown.classList.remove('open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	function initProfileMenuItem() {
		const profile = document.getElementById('profileMenuItem') || document.getElementById('profileBtn');
		if (!profile) return;
		profile.addEventListener('click', () => {
			window.location.href = 'profil.html';
		});

		// Bind logout inline button if present
		const logoutBtn = document.getElementById('logoutBtn');
		if (logoutBtn) {
			logoutBtn.addEventListener('click', handleLogout);
		}
	}

	async function handleLogout() {
		try {
			// Call PHP logout endpoint
			const response = await fetch('../php/logout.php', {
				method: 'POST'
			});
			const result = await response.json();
		} catch (e) {
			console.log('Logout notice:', e);
		}
		
		// Clear client-side session
		try { localStorage.removeItem('userSession'); } catch (e) {}
		try { localStorage.removeItem('registeredUsers'); } catch (e) {}
		
		// Redirect to homepage
		window.location.href = '../index.php';
	}

	// Expose to global scope for inline onclick handlers
	window.DashboardHeader = window.DashboardHeader || {};
	window.DashboardHeader.initUserMenu = initUserMenu;
	window.DashboardHeader.initProfileMenuItem = initProfileMenuItem;
	window.DashboardHeader.handleLogout = handleLogout;

	// also expose legacy global function used by pages
	window.handleLogout = handleLogout;

	// Ensure any inline onclick="handleLogout()" bindings are normalized
	function normalizeInlineLogout() {
		const nodes = document.querySelectorAll('[onclick]');
		nodes.forEach(n => {
			const v = n.getAttribute('onclick') || '';
			if (v.indexOf('handleLogout') !== -1) {
				n.removeAttribute('onclick');
				n.addEventListener('click', function (e) {
					e.preventDefault();
					handleLogout();
				}, { capture: true });
			}
		});
	}

	document.addEventListener('DOMContentLoaded', normalizeInlineLogout);

	// Auto-init menus and bindings for pages that don't call init functions explicitly
	document.addEventListener('DOMContentLoaded', function () {
		try { initUserMenu(); } catch (e) { /* ignore */ }
		try { initProfileMenuItem(); } catch (e) { /* ignore */ }
	});

	// Ensure logout buttons are interactive and bound even if overlay exists
	function bindLogoutButtons() {
		const buttons = document.querySelectorAll('#logoutBtn');
		if (!buttons || buttons.length === 0) return;
		buttons.forEach(btn => {
			try {
				btn.style.pointerEvents = 'auto';
				btn.style.cursor = 'pointer';
				btn.removeAttribute('disabled');
				btn.addEventListener('click', handleLogout);
			} catch (e) {}
		});
	}

	// Capture-phase listener: if user clicks the visual area of logout button (even if an overlay sits above), trigger logout
	function captureLogoutByCoords(e) {
		const buttons = document.querySelectorAll('#logoutBtn');
		if (!buttons || buttons.length === 0) return;
		for (let i = 0; i < buttons.length; i++) {
			const b = buttons[i];
			const r = b.getBoundingClientRect();
			if (e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom) {
				e.preventDefault();
				e.stopPropagation();
				handleLogout();
				return;
			}
		}
	}

	document.addEventListener('DOMContentLoaded', bindLogoutButtons);
	document.addEventListener('click', captureLogoutByCoords, true);
})();

// Notification positioning & toggle (ensure notifications are fixed under header)
(function () {
	function initNotificationUI() {
		const header = document.querySelector('.app-header') || document.querySelector('header');
		const notifButton = document.getElementById('notifButton');
		const notifDropdown = document.getElementById('notifDropdown') || document.querySelector('.notif-dropdown');
		if (!notifButton || !notifDropdown) return;

		// Ensure dropdown is a direct child of body so it doesn't get clipped by containers or footer
		if (notifDropdown.parentElement !== document.body) {
			document.body.appendChild(notifDropdown);
		}

		// Force fixed positioning and base styles (kept minimal to avoid visual changes)
		notifDropdown.style.position = 'fixed';
		notifDropdown.style.right = '20px';
		notifDropdown.style.zIndex = '12000';
		notifDropdown.style.minWidth = notifDropdown.style.minWidth || '260px';

		function updatePosition() {
			const rect = header ? header.getBoundingClientRect() : { bottom: 0 };
			const top = (rect.bottom || 0) + 8; // place just below header
			notifDropdown.style.top = top + 'px';
		}

		// Initial placement
		updatePosition();

		// Update on resize/scroll
		window.addEventListener('resize', updatePosition);
		window.addEventListener('scroll', updatePosition, true);

		// Toggle behavior
		notifButton.addEventListener('click', (e) => {
			e.stopPropagation();
			const isOpen = notifDropdown.classList.toggle('open');
			notifButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});

		// Close when clicking outside
		document.addEventListener('click', (e) => {
			if (!notifDropdown.contains(e.target) && !notifButton.contains(e.target)) {
				notifDropdown.classList.remove('open');
				notifButton.setAttribute('aria-expanded', 'false');
			}
		});

		// Close with Escape
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				notifDropdown.classList.remove('open');
				notifButton.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// Expose and auto-init
	window.DashboardHeader = window.DashboardHeader || {};
	window.DashboardHeader.initNotificationUI = initNotificationUI;
	document.addEventListener('DOMContentLoaded', initNotificationUI);
})();


