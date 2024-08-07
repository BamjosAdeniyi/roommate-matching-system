<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
  <title>View Registered Students</title>
</head>
<body>
  <h2>Students Registered in Hostels</h2>

  <?php
  include '../../../config/db_connect.php';

  // Fetch all hostels
  $hostel_query = "SELECT id, name FROM hostels";
  $hostel_result = mysqli_query($conn, $hostel_query);

  if (mysqli_num_rows($hostel_result) > 0) {
      while ($hostel = mysqli_fetch_assoc($hostel_result)) {
          echo "<h3>" . $hostel['name'] . "</h3>";

          // Fetch students registered in this hostel and determine the predominant trait
          $student_query = "
            SELECT s.id, s.name,
              GREATEST(
                s.agreeableness, 
                s.conscientiousness, 
                s.extraversion, 
                s.neuroticism, 
                s.openness
              ) AS highest_score,
              CASE 
                WHEN s.agreeableness = GREATEST(s.agreeableness, s.conscientiousness, s.extraversion, s.neuroticism, s.openness) THEN 'Agreeableness'
                WHEN s.conscientiousness = GREATEST(s.agreeableness, s.conscientiousness, s.extraversion, s.neuroticism, s.openness) THEN 'Conscientiousness'
                WHEN s.extraversion = GREATEST(s.agreeableness, s.conscientiousness, s.extraversion, s.neuroticism, s.openness) THEN 'Extraversion'
                WHEN s.neuroticism = GREATEST(s.agreeableness, s.conscientiousness, s.extraversion, s.neuroticism, s.openness) THEN 'Neuroticism'
                WHEN s.openness = GREATEST(s.agreeableness, s.conscientiousness, s.extraversion, s.neuroticism, s.openness) THEN 'Openness'
              END AS predominant_trait 
            FROM students s
            WHERE s.hostel_id = " . $hostel['id'];
          
          $student_result = mysqli_query($conn, $student_query);

          if (mysqli_num_rows($student_result) > 0) {
              echo "<table border='1'>";
              echo "<tr><th>ID</th><th>Name</th><th>Predominant Trait</th></tr>";
              while ($student = mysqli_fetch_assoc($student_result)) {
                  echo "<tr><td>" . $student['id'] . "</td><td>" . $student['name'] . "</td><td>" . $student['predominant_trait'] . "</td></tr>";
              }
              echo "</table>";
          } else {
              echo "<p>No students registered in this hostel.</p>";
          }
      }
  } else {
      echo "<p>No hostels available.</p>";
  }

  mysqli_close($conn);
  ?>

  <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
