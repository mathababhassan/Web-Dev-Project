<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

// Connect to database
include "connect.php";

// Get latest assessment for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM assessments WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

// Default values
$stress = "No data";
$anxiety = "No data";
$depression = "No data";

// If result found, fetch scores
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $stress = $row['stress_score'];
  $anxiety = $row['anxiety_score'];
  $depression = $row['depression_score'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | Mindly</title>
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo-area">
      <img src="../assets/img/clr-primary-dark-logo.webp" alt="Mindly Logo" class="logo" />
      <span class="site-name">Mindly</span>
    </div>
    <nav class="nav-links">
      <a href="dashboard.html">Dashboard</a>
      <a href="profile.html">Profile</a>
      <a href="logout.html">Logout</a>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="dashboard-page">

    <!-- Welcome Section -->
    <section class="welcome-section">
      <h1>Welcome, Student</h1>

      <div class="assessment-card">
        <div class="assessment-text">
          <strong>It's important to check in with yourself every now and then</strong>
          <p>
            This short DASS-21 assessment helps you pause, reflect, and understand where you stand emotionally
            in terms of depression, anxiety, and stress. It's just a few minutes, for your peace of mind.
          </p>
          <a href="assessment.html" class="btn btn-secondary">Take Assessment</a>
        </div>
      </div>
    </section>

    <!-- Results Summary -->
    <section class="results-section">
      <h2>Latest Assessment Results</h2>

      <div class="results-grid">
        <div class="result-card stress">
          <p><strong>Stress</strong></p>
          <span><?php echo $stress; ?></span>
        </div>
        <div class="result-card anxiety">
          <p><strong>Anxiety</strong></p>
          <span><?php echo $anxiety; ?></span>
        </div>
        <div class="result-card depression">
          <p><strong>Depression</strong></p>
          <span><?php echo $depression; ?></span>
        </div>
      </div>

      <a href="assessment.html" class="btn btn-light">Retake Assessment</a>
    </section>

    <!-- Recommendations Preview -->
    <section class="recommendation-section">
      <h2>Just For You</h2>

      <div class="recommendation-card">
        <div class="recommendation-left">
          <h3>Little steps<br />make a big difference.</h3>
        </div>
        <div class="recommendation-right">
          <p>
            Carefully curated personalized routines selected just for you to help you evolve in your mental wellbeing journey
          </p>
          <a href="recommendations.html" class="btn btn-primary">View Recommendations</a>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>
<script src="logout.js"></script>
</body>
</html>
