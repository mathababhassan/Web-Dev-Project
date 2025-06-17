<?php
session_start();
include "connect.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_POST && isset($_POST['consent_agreed'])) {
    $user_id = $_SESSION['user_id'];
    
    // Update user's consent status in database
    $sql = "UPDATE users SET consent_given = 1, consent_date = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // Redirect to assessment page
        header("Location: assessment.php");
        exit();
    } else {
        $error_message = "Error updating consent. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DASS-21 Assessment | Mindly</title>
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/assessment.css">
  <link rel="stylesheet" href="../assets/css/consent.css">
</head>
<body>

 <!-- Navbar -->
  <header class="navbar">
    <div class="logo-area">
      <img src="../assets/img/clr-primary-dark-logo.webp" alt="Mindly Logo" class="logo" />
      <span class="site-name">Mindly</span>
    </div>
    <nav class="nav-links">
      <a href="dashboard.php">Dashboard</a>
      <a href="profile.html">Profile</a>
      <a href="logout.html">Logout</a>
    </nav>
  </header>

<!-- Main Content -->
  <main class="consent-page">
    <!-- Consent Box -->
    <section class="consent-section">
      <h1>Before We Begin</h1>
      
      <?php if (isset($error_message)): ?>
        <div class="error-message">
          <?php echo $error_message; ?>
        </div>
      <?php endif; ?>

      <div class="consent-card">
        <div class="consent-notice">
          <p>To protect your privacy and ensure compliance with the PDPA, we kindly ask for your consent. Your responses will be stored securely and used only to help you understand your mental well-being. Please confirm your agreement before starting the assessment.</p>
        </div>

        <form method="POST" action="assessment.html" class="consent-form">
          <div class="consent-checkbox">
            <input type="checkbox" id="consent_agreed" name="consent_agreed" required>
            <label for="consent_agreed">
              I have read and agree to the <a href="#" class="pdpa-link">PDPA consent notice</a>.
            </label>
          </div>

          <div class="consent-button">
            <button type="submit" class="begin-assessment-btn" id="beginBtn" disabled>
              Begin Assessment
            </button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>

  <script>
    // Enable/disable button based on checkbox
    const checkbox = document.getElementById('consent_agreed');
    const button = document.getElementById('beginBtn');
    
    checkbox.addEventListener('change', function() {
      button.disabled = !this.checked;
    });
  </script>

</body>
</html>