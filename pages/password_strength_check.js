(() => {
const passwordInput = document.getElementById("password");
const powerBar = document.getElementById("power-point");
const strengthText = document.getElementById("strength-text");
const form = document.querySelector(".auth-form");

// Visual styles per strength score (0:the worst, 4:the best)
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

  if (password === "") {
    powerBar.style.width = "1%";
    powerBar.style.backgroundColor = "#ccc";
    strengthText.textContent = "";
    return; // Don't evaluate if it's empty
  }

  if (password.length >= 8) {
    strengthChecks.forEach((regex) => {
      if (regex.test(password)) score++;
    });
  }

  currentScore = score;

  // Update strength bar and label and use default value if missing
  powerBar.style.width = barWidths[score] || "1%";
  powerBar.style.backgroundColor = barColors[score] || "#D73F40";

  strengthText.textContent = strengthLabels[score] || "";
  strengthText.style.color = barColors[score] || "#D73F40";
});
})();
