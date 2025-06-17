  (() => {
  const form = document.querySelector('.auth-form');
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('confirm');
  const errorBox = document.getElementById('js-error-box'); 

  form.addEventListener('submit', function (event) {
    const errors = [];

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const confirm = confirmInput.value;

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      errors.push('Invalid email format.');
    }

    // Password validation
    if (password.length < 8) {
      errors.push('Password must be at least 8 characters.');
    }
    if (!/[a-z]/.test(password)) {
      errors.push('Password must contain at least one lowercase letter.');
    }
    if (!/[A-Z]/.test(password)) {
      errors.push('Password must contain at least one uppercase letter.');
    }
    if (!/[0-9]/.test(password)) {
      errors.push('Password must contain at least one number.');
    }
    if (!/[!@#$%^&*]/.test(password)) {
      errors.push('Password must contain at least one special character (!@#$%^&*)');
    }

    if (errors.length > 0) {
  event.preventDefault();
  errorBox.innerHTML = errors.join('<br>');
  errorBox.style.display = "block";
} else {
  errorBox.style.display = "none";
}

  });
})();
