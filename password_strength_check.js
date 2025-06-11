const passwordInput = document.getElementById("password");
const powerBar = document.getElementById("power-point");
const strengthText = document.getElementById("strength-text");
const form = document.querySelector("form");

// Visual styles per strength score (0 to 4)
const barWidths = ["1%", "25%", "50%", "75%", "100%"];
const barColors = ["#D73F40", "#DC6551", "#F2B84F", "#BDE952", "#3ba62f"];
const strengthLabels = ["Too Weak", "Weak", "Moderate", "Good", "Strong"];

// Regex checks: digit, lowercase, uppercase, symbol
const strengthChecks = [
  /[0-9]/,          // Digits
  /[a-z]/,          // Lowercase letters
  /[A-Z]/,          // Uppercase letters
  /[^0-9a-zA-Z]/    // Symbols
];

let currentScore = 0; // stores password strength score

// Evaluate password on input
passwordInput.addEventListener("input", () => {
  const password = passwordInput.value;
  let score = 0;

  if (password.length >= 6) {
    strengthChecks.forEach((regex) => {
      if (regex.test(password)) score++;
    });
  }

  currentScore = score;

  // Update strength bar and label
  powerBar.style.width = barWidths[score] || "1%";
  powerBar.style.backgroundColor = barColors[score] || "#D73F40";

  strengthText.textContent = strengthLabels[score] || "";
  strengthText.style.color = barColors[score] || "#D73F40";
});

// Prevent form submission if password is weak
form.addEventListener("submit", (e) => {
  if (currentScore < 3) {
    e.preventDefault();
    alert("Your password is too weak. Please include a mix of uppercase, lowercase, numbers, and symbols.");
    passwordInput.focus();
  }
});
