  const form = document.querySelector('.auth-form');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');

  form.addEventListener('submit', function (event) {
    const errors = [];

    const email = emailInput.value.trim();
    const password = passwordInput.value;

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
      errors.push('Email is required.');
    } else if (!emailPattern.test(email)) {
      errors.push('Please enter a valid email.');
    }

    // Password needs to be non-empty
    if (password === '') {
      errors.push('Password is required.');
    }

    if (errors.length > 0) {
      event.preventDefault();
      alert(errors.join('\n'));
    }
  });
