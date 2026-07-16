// ── Inzira Fashion App Utilities ──

function toggleMobile() {
  document.getElementById('mobileMenu')?.classList.toggle('open');
}

function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

// Form validation helper
function validateForm(formId, rules) {
  let valid = true;
  const form = document.getElementById(formId);
  if (!form) return false;

  Object.entries(rules).forEach(([field, rule]) => {
    const el  = form.querySelector(`[name="${field}"]`);
    const err = form.querySelector(`[data-err="${field}"]`);
    if (!el) return;
    el.classList.remove('error');
    if (err) err.textContent = '';

    if (rule.required && !el.value.trim()) {
      el.classList.add('error');
      if (err) err.textContent = rule.message || 'This field is required';
      valid = false;
    } else if (rule.email && el.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value)) {
      el.classList.add('error');
      if (err) err.textContent = 'Please enter a valid email';
      valid = false;
    } else if (rule.minLength && el.value.length < rule.minLength) {
      el.classList.add('error');
      if (err) err.textContent = `Minimum ${rule.minLength} characters`;
      valid = false;
    } else if (rule.phone && el.value && !/^(\+?25[0-9]|07)[0-9]{8,}$/.test(el.value.replace(/\s/g,''))) {
      el.classList.add('error');
      if (err) err.textContent = 'Enter a valid phone number';
      valid = false;
    }
  });
  return valid;
}
