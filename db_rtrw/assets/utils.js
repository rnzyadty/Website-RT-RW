/**
 * UTILITY FUNCTIONS - Modal, Toast, Form Handling
 * Reusable components untuk website RT/RW
 */

// =============================================
// MODAL - Untuk dialog/alerts yang lebih professional
// =============================================

function showModal(options) {
  const {
    title = 'Informasi',
    message = '',
    type = 'info', // 'info', 'success', 'error', 'warning'
    buttons = [{ text: 'OK', action: () => closeModal() }]
  } = options;

  // Remove existing modal jika ada
  const existingModal = document.getElementById('custom-modal');
  if (existingModal) existingModal.remove();

  // Create modal HTML
  const modal = document.createElement('div');
  modal.id = 'custom-modal';
  modal.className = `modal-overlay modal-${type}`;
  
  const modalContent = document.createElement('div');
  modalContent.className = 'modal-content';
  
  const icon = getIconByType(type);
  
  modalContent.innerHTML = `
    <div class="modal-header">
      <span class="modal-icon">${icon}</span>
      <h3>${title}</h3>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div class="modal-body">
      ${message}
    </div>
    <div class="modal-footer">
      ${buttons.map(btn => `
        <button class="btn btn-modal" onclick="handleModalButton('${btn.text}')">${btn.text}</button>
      `).join('')}
    </div>
  `;
  
  modal.appendChild(modalContent);
  document.body.appendChild(modal);

  // Lock background scroll while modal open
  document.body.classList.add('modal-open');
  
  // Store button actions
  window.modalButtonActions = {};
  buttons.forEach((btn, idx) => {
    window.modalButtonActions[btn.text] = btn.action || (() => closeModal());
  });
  
  // Auto focus pada modal
  modal.querySelector('.modal-close').focus();
}

function closeModal() {
  const modal = document.getElementById('custom-modal');
  if (modal) {
    modal.style.animation = 'slideDown 0.3s ease-out reverse';
    setTimeout(() => modal.remove(), 300);
  }
  document.body.classList.remove('modal-open');
}

function handleModalButton(buttonText) {
  if (window.modalButtonActions && window.modalButtonActions[buttonText]) {
    window.modalButtonActions[buttonText]();
  }
  closeModal();
}

function getIconByType(type) {
  const icons = {
    'info': '📋',
    'success': '✅',
    'error': '❌',
    'warning': '⚠️'
  };
  return icons[type] || '📋';
}

// =============================================
// TOAST - Untuk notifikasi yang ephemeral
// =============================================

function showToast(message, type = 'info', duration = 3000) {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  
  const container = document.getElementById('toast-container');
  if (!container) {
    const newContainer = document.createElement('div');
    newContainer.id = 'toast-container';
    document.body.appendChild(newContainer);
    newContainer.appendChild(toast);
  } else {
    container.appendChild(toast);
  }
  
  // Auto remove
  setTimeout(() => {
    toast.style.animation = 'slideDown 0.3s ease-out reverse';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// Centered toast - uses HTML content and centered placement
function showCenteredToast(messageHtml, type = 'info', duration = 3000) {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type} toast-center`;
  // allow HTML (trusted content from app)
  toast.innerHTML = messageHtml;

  let container = document.getElementById('toast-center');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-center';
    document.body.appendChild(container);
  }
  container.appendChild(toast);

  // Auto remove
  setTimeout(() => {
    toast.style.animation = 'slideDown 0.3s ease-out reverse';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// =============================================
// FORM VALIDATION - Better feedback
// =============================================

function validateForm(formId, fields) {
  const form = document.getElementById(formId);
  if (!form) return false;
  
  const errors = [];
  const formData = {};
  
  fields.forEach(field => {
    const input = form.querySelector(`[name="${field.name}"]`);
    if (!input) return;
    
    const value = input.value.trim();
    
    // Check required
    if (field.required && !value) {
      errors.push(`${field.label} harus diisi`);
    }
    
    // Check min length
    if (field.minLength && value.length < field.minLength) {
      errors.push(`${field.label} minimal ${field.minLength} karakter`);
    }
    
    // Check email
    if (field.type === 'email' && value && !isValidEmail(value)) {
      errors.push(`${field.label} tidak valid`);
    }
    
    // Check phone
    if (field.type === 'phone' && value && !isValidPhone(value)) {
      errors.push(`${field.label} harus nomor telepon yang valid`);
    }
    
    formData[field.name] = value;
  });
  
  if (errors.length > 0) {
    showModal({
      title: 'Validasi Form',
      message: `<ul style="text-align: left; margin: 10px 0;">
        ${errors.map(err => `<li>${err}</li>`).join('')}
      </ul>`,
      type: 'warning',
      buttons: [{ text: 'OK' }]
    });
    return false;
  }
  
  return formData;
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPhone(phone) {
  return /^(\+62|0)[0-9]{9,12}$/.test(phone.replace(/\D/g, ''));
}

// =============================================
// LOADING STATE - Untuk async operations
// =============================================

function setLoadingState(elementId, isLoading = true) {
  const element = document.getElementById(elementId);
  if (!element) return;
  
  if (isLoading) {
    element.disabled = true;
    element.style.opacity = '0.6';
    element.style.pointerEvents = 'none';
    element.innerHTML = '⏳ Loading...';
  } else {
    element.disabled = false;
    element.style.opacity = '1';
    element.style.pointerEvents = 'auto';
    // Restore original text
    element.innerHTML = element.getAttribute('data-original-text') || element.innerHTML;
  }
}

// =============================================
// EMPTY STATE - Standardized empty messages
// =============================================

function createEmptyState(title, description, actionText = null, actionCallback = null) {
  const html = `
    <div class="empty-state">
      <div class="empty-state-icon">📭</div>
      <h3>${title}</h3>
      <p>${description}</p>
      ${actionText ? `<button class="btn btn-primary" onclick="handleEmptyAction()">${actionText}</button>` : ''}
    </div>
  `;
  
  if (actionCallback) {
    window.handleEmptyAction = actionCallback;
  }
  
  return html;
}

// =============================================
// CONFIRM DIALOG - Better than window.confirm()
// =============================================

function showConfirm(options) {
  const {
    title = 'Konfirmasi',
    message = 'Lanjutkan?',
    confirmText = 'Ya',
    cancelText = 'Batal',
    onConfirm = () => {},
    onCancel = () => {}
  } = options;
  
  showModal({
    title: title,
    message: message,
    type: 'warning',
    buttons: [
      { 
        text: confirmText, 
        action: () => {
          onConfirm();
          closeModal();
        }
      },
      { 
        text: cancelText, 
        action: () => {
          onCancel();
          closeModal();
        }
      }
    ]
  });
}

// =============================================
// EXPORT untuk penggunaan di semua halaman
// =============================================

window.UIUtils = {
  modal: showModal,
  closeModal: closeModal,
  toast: showToast,
  centerToast: showCenteredToast,
  validateForm: validateForm,
  setLoading: setLoadingState,
  emptyState: createEmptyState,
  confirm: showConfirm
};
