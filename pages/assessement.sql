CREATE TABLE assessments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,  -- ← this connects to users.id
  depression_score INT NOT NULL,
  anxiety_score INT NOT NULL,
  stress_score INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
