<?php

include "connect.php"; 
include "authentication.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM assessments WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "No assessment found.";
  exit();
}

$row = $result->fetch_assoc();

// Severity function
function getSeverity($score, $type) {
  $scale = [
    'depression' => [9, 13, 20, 27],
    'anxiety' => [7, 9, 14, 19],
    'stress' => [14, 18, 25, 33]
  ];
  list($mild, $mod, $sev, $extreme) = $scale[$type];

  if ($score <= $mild) return "Normal";
  elseif ($score <= $mod) return "Mild";
  elseif ($score <= $sev) return "Moderate";
  elseif ($score <= $extreme) return "Severe";
  else return "Extremely Severe";
}

//getMessage function
function getMessage($type, $level) {
  $tips = [
    'depression' => [
      'Normal' => "You're in a healthy state. Stay connected and care for your well-being.",
      'Mild' => "Low mood is manageable. Try journaling, fresh air, or routine care.",
      'Moderate' => "Consider rest and small goals. Seek support if it affects daily life.",
      'Severe' => "You're carrying a lot. Talking to a health professional can help.",
      'Extremely Severe' => "You may be in serious distress. Reach out for help—you deserve support."
    ],
    'anxiety' => [
      'Normal' => "You're handling stress well. Continue good mental habits.",
      'Mild' => "Mild anxiety is common. Try breathing or grounding exercises.",
      'Moderate' => "Slow down. If it persists, consider professional support.",
      'Severe' => "Overwhelming anxiety is valid—speak to a trusted person or clinic.",
      'Extremely Severe' => "You're not alone. Strongly consider contacting a health provider soon."
    ],
    'stress' => [
      'Normal' => "You're coping well emotionally. Keep checking in with yourself.",
      'Mild' => "Mild stress is manageable. Self-care can make a big difference.",
      'Moderate' => "Rest and recharge. If it worsens, speak to a support person.",
      'Severe' => "High stress isn't yours to carry alone—seek a counselor or clinic.",
      'Extremely Severe' => "Very high stress needs attention—help is available and you're not alone."
    ]
  ];
  return $tips[$type][$level];
}

// Store values
$dep = $row['depression_score'];
$anx = $row['anxiety_score'];
$str = $row['stress_score'];

$dep_sev = getSeverity($dep, 'depression');
$anx_sev = getSeverity($anx, 'anxiety');
$str_sev = getSeverity($str, 'stress');
?>

<!DOCTYPE html>
<html>
<head>
  <title>DASS-21 Results</title>
  <link rel="stylesheet" href="../assets/css/reset.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/results.css">

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

  <!-- RESUTS SUMMARY -->
    <div class="container">
        <h1 class="page-title">Results</h1>
        
        <div class="results-grid">
            <!-- Stress Results -->
            <div class="result-card">
                <div class="result-header">
                    <h2>Stress</h2>
                </div>
                <div class="result-content">
                    <div class="score-section">
                        <div class="score-display">
                            <span class="score-number"><?php echo $str; ?></span>
                            <span class="score-label">Score</span>
                        </div>
                        <div class="severity-bar">
                            <div class="severity-indicator severity-<?php echo strtolower(str_replace(' ', '-', $str_sev)); ?>">
                                <span class="severity-text"><?php echo $str_sev; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <p><?php echo getMessage('stress', $str_sev); ?></p>
                    </div>
                    <div class="severity-legend">
                        <span class="legend-item normal">Normal</span>
                        <span class="legend-item mild">Mild</span>
                        <span class="legend-item moderate">Moderate</span>
                        <span class="legend-item severe">Severe</span>
                        <span class="legend-item extremely-severe">Extremely Severe</span>
                    </div>
                </div>
            </div>

            <!-- Anxiety Results -->
            <div class="result-card">
                <div class="result-header">
                    <h2>Anxiety</h2>
                </div>
                <div class="result-content">
                    <div class="score-section">
                        <div class="score-display">
                            <span class="score-number"><?php echo $anx; ?></span>
                            <span class="score-label">Score</span>
                        </div>
                        <div class="severity-bar">
                            <div class="severity-indicator severity-<?php echo strtolower(str_replace(' ', '-', $anx_sev)); ?>">
                                <span class="severity-text"><?php echo $anx_sev; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <p><?php echo getMessage('anxiety', $anx_sev); ?></p>
                    </div>
                    <div class="severity-legend">
                        <span class="legend-item normal">Normal</span>
                        <span class="legend-item mild">Mild</span>
                        <span class="legend-item moderate">Moderate</span>
                        <span class="legend-item severe">Severe</span>
                        <span class="legend-item extremely-severe">Extremely Severe</span>
                    </div>
                </div>
            </div>

            <!-- Depression Results -->
            <div class="result-card">
                <div class="result-header">
                    <h2>Depression</h2>
                </div>
                <div class="result-content">
                    <div class="score-section">
                        <div class="score-display">
                            <span class="score-number"><?php echo $dep; ?></span>
                            <span class="score-label">Score</span>
                        </div>
                        <div class="severity-bar">
                            <div class="severity-indicator severity-<?php echo strtolower(str_replace(' ', '-', $dep_sev)); ?>">
                                <span class="severity-text"><?php echo $dep_sev; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <p><?php echo getMessage('depression', $dep_sev); ?></p>
                    </div>
                    <div class="severity-legend">
                        <span class="legend-item normal">Normal</span>
                        <span class="legend-item mild">Mild</span>
                        <span class="legend-item moderate">Moderate</span>
                        <span class="legend-item severe">Severe</span>
                        <span class="legend-item extremely-severe">Extremely Severe</span>
                    </div>
                </div>
            </div>
        </div>
  <div class="btns">
    <a href="recommendations.html">View Recommendations</a>
  </div>
</div>

 <!-- Footer -->
  <footer class="site-footer">
    <p>© Mindly · All rights reserved</p>
    <a href="#">Privacy Policy</a>
  </footer>
<script src="logout.js"></script>

</body>
</html>
