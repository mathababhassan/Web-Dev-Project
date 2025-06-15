<?php
include "connect.php";
include "authentication.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['responses'])) {
  $user_id = $_SESSION['user_id'];
  $responses = $_POST['responses'];

  if (count($responses) != 21) {
    echo "You must answer all 21 questions.";
    exit();
  }

  // Define question indexes (1-based)
  $depression_qs = [3, 5, 10, 13, 16, 17, 21];
  $anxiety_qs = [2, 4, 7, 9, 15, 19, 20];
  $stress_qs = [1, 6, 8, 11, 12, 14, 18];

  // Calculate scores
  $depression = 0;
  $anxiety = 0;
  $stress = 0;

  foreach ($depression_qs as $q) {
    $depression += (int)$responses[$q - 1];
  }
  foreach ($anxiety_qs as $q) {
    $anxiety += (int)$responses[$q - 1];
  }
  foreach ($stress_qs as $q) {
    $stress += (int)$responses[$q - 1];
  }

  // Multiply by 2 as per DASS-21 scoring
  $depression *= 2;
  $anxiety *= 2;
  $stress *= 2;

  // Insert into database
  $sql = "INSERT INTO assessments (user_id, depression_score, anxiety_score, stress_score) 
          VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iiii", $user_id, $depression, $anxiety, $stress);

  if ($stmt->execute()) {
    header("Location: results.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

} else {
  echo "Invalid form submission.";
}
?>
