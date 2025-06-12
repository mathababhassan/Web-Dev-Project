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
  <style>
    body { font-family: Arial; background-color: #f4f8fc; padding: 20px; }
    .container { background: white; max-width: 700px; margin: auto; padding: 20px; border-radius: 10px; }
    h2 { text-align: center; }
    .result-box { background: #e0f0ff; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .interpretation { background: #f0fdf4; border-left: 5px solid #4caf50; padding: 15px; border-radius: 5px; margin-top: 20px; }
    .btns { text-align: center; margin-top: 20px; }
    .btns a {
      display: inline-block;
      background: #4CAF50;
      color: white;
      padding: 10px 15px;
      margin: 0 10px;
      border-radius: 5px;
      text-decoration: none;
    }
    .note { font-size: 13px; text-align: center; margin-top: 30px; color: gray; }
  </style>
</head>
<body>

<div class="container">
  <h2>Your DASS-21 Results</h2>

  <div class="result-box">
    <strong>Depression:</strong> <?php echo $dep; ?> (<?php echo $dep_sev; ?>)
  </div>
  <div class="result-box">
    <strong>Anxiety:</strong> <?php echo $anx; ?> (<?php echo $anx_sev; ?>)
  </div>
  <div class="result-box">
    <strong>Stress:</strong> <?php echo $str; ?> (<?php echo $str_sev; ?>)
  </div>

  <div class="interpretation">
    <h3>What this means</h3>
    <p><strong>Depression:</strong> <?php echo getMessage('depression', $dep_sev); ?></p>
    <p><strong>Anxiety:</strong> <?php echo getMessage('anxiety', $anx_sev); ?></p>
    <p><strong>Stress:</strong> <?php echo getMessage('stress', $str_sev); ?></p>
  </div>

  <div class="btns">
    <a href="assessment.html">Retake Quiz</a>
    <a href="recommendations.html">View Tips</a>
  </div>

  <div class="note">
    This summary is for your awareness only. If needed, seek help from a clinic or counselor.
  </div>
</div>

</body>
</html>

<?php
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
      'Extremely Severe' => "You’re not alone. Strongly consider contacting a health provider soon."
    ],
    'stress' => [
      'Normal' => "You're coping well emotionally. Keep checking in with yourself.",
      'Mild' => "Mild stress is manageable. Self-care can make a big difference.",
      'Moderate' => "Rest and recharge. If it worsens, speak to a support person.",
      'Severe' => "High stress isn’t yours to carry alone—seek a counselor or clinic.",
      'Extremely Severe' => "Very high stress needs attention—help is available and you're not alone."
    ]
  ];
  return $tips[$type][$level];
}
?>
