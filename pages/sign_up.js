  (() => {
  const form = document.querySelector('.auth-form');
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('confirm');

  form.addEventListener('submit', function (event) {
    const errors = [];

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const confirm = confirmInput.value;

    // Name validation
    if (name === '') {
      errors.push('Name is required.');
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      errors.push('Email is not valid.');
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
      errors.push('Password must contain at least one special character (!@#$%^&*).');
    }

    // Confirm password
    if (password !== confirm) {
      errors.push('Passwords do not match.');
    }

    // Show errors or allow submit
    if (errors.length > 0) {
      event.preventDefault();
      alert(errors.join('\n'));
    }
  });
})();
