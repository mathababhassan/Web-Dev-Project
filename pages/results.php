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
      'Normal' => "Your responses show no significant signs of depression. This is a healthy place to be, and it's important to continue caring for your emotional well-being. Keep checking in with yourself and leaning into the things that make you feel connected and alive.",
      'Mild' => "Your results indicate mild signs of low mood. You may feel a bit flat or disconnected at times, and that's okay. Simple routines like journaling, sunlight, and rest can help. If these feelings linger, don't hesitate to visit a nearby clinic for support.",
      'Moderate' => "You may be experiencing moderate symptoms of depression, such as lack of motivation, sadness, or emotional fatigue. Please be kind to yourself and take things one day at a time. If it starts affecting your daily life, speaking to a health professional can really help.",
      'Severe' => "Your results suggest you're dealing with severe emotional strain. It's not weakness, it's a signal that you've been carrying too much alone. Please consider reaching out to a nearby clinic or healthcare provider. You deserve to feel better and be supported.",
      'Extremely Severe' => "Your responses reflect very high emotional distress. Depression at this level can be heavy, but you are not alone and you are not hopeless. We strongly urge you to speak with a professional at the nearest clinic. Help is available and healing is possible."
    ],
    'anxiety' => [
      'Normal' => "Your responses suggest that you're currently experiencing low levels of anxiety. This is a good sign that you're coping well with daily stressors. Keep practicing the habits that support your calm, and remember to check in with yourself regularly.",
      'Mild' => "Your results show mild signs of anxiety. Occasional worry or unease is normal, especially during stressful times. Grounding exercises and mindful breathing can help. If the feelings persist or grow stronger, consider speaking with someone at a local clinic.",
      'Moderate' => "You may be feeling moderately anxious, which can affect your focus, rest, or physical well-being. Take time to slow down and care for yourself. If anxiety begins to interfere with your daily life, don't hesitate to reach out to a nearby clinic for support.",
      'Severe' => "Your responses indicate a high level of anxiety. You might be feeling overwhelmed, tense, or constantly on edge. These feelings are valid and they're not permanent. Please consider talking to a professional at a nearby clinic to find the support you need.",
      'Extremely Severe' => "Your results show very high levels of anxiety. This can feel exhausting and isolating, but you're not alone. We strongly encourage you to reach out to a healthcare provider or local clinic as soon as possible. Help is available, and healing is within reach."
    ],
    'stress' => [
      'Normal' => "Based on your responses, you're currently within the normal range. This means you're coping well emotionally. Keep taking care of your well-being and check in with yourself regularly. If you ever need support, your local clinic can guide you further.",
      'Mild' => "Your results indicate a mild level of emotional stress. This is common and manageable—simple self-care practices may help ease the weight. If you're unsure or want to talk to someone, don't hesitate to visit a nearby clinic for support.",
      'Moderate' => "You may be experiencing moderate emotional distress. This can affect your mood, focus, and energy levels. Take time to rest and recharge. If the feelings continue or grow heavier, visiting a clinic or talking to a health professional is a good next step.",
      'Severe' => "Your results suggest that you're under severe emotional stress. You're not alone, and you don't need to carry this by yourself. We strongly encourage you to seek professional help by reaching out to a nearby clinic or mental health provider.",
      'Extremely Severe' => "Your responses indicate very high emotional distress. Please take this seriously and prioritize your mental health. Support is available — you are encouraged to speak to a professional at the nearest clinic as soon as you can. You deserve help and healing."
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
