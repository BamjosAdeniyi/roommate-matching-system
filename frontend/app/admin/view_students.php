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
            SELECT s.id, s.first_name, s.surname, s.other_name
              GREATEST(
                pt.agreeableness, 
                pt.conscientiousness, 
                pt.extraversion, 
                pt.neuroticism, 
                pt.openness
              ) AS highest_score,
              CASE 
                WHEN pt.agreeableness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Agreeableness'
                WHEN pt.conscientiousness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Conscientiousness'
                WHEN pt.extraversion = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Extraversion'
                WHEN pt.neuroticism = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Neuroticism'
                WHEN pt.openness = GREATEST(pt.agreeableness, pt.conscientiousness, pt.extraversion, pt.neuroticism, pt.openness) THEN 'Openness'
              END AS predominant_trait 
            FROM students s
            JOIN personality_traits pt ON s.id = pt.student_id
            WHERE s.hostel_id = " . $hostel['id'];
          
          $student_result = mysqli_query($conn, $student_query);

          if (mysqli_num_rows($student_result) > 0) {
              echo "<table border='1'>";
              echo "<tr><th>ID</th><th>Name</th><th>Predominant Trait</th><th>Action</th></tr>";
              while ($student = mysqli_fetch_assoc($student_result)) {
                  echo "<tr>";
                  echo "<td>" . $student['id'] . "</td>";
                  echo "<td>" . $student['name'] . "</td>";
                  echo "<td>" . $student['predominant_trait'] . "</td>";
                  echo "<td><a href='view_student_profile.php?id=" . $student['id'] . "'><button>View Profile</button></a></td>";
                  echo "</tr>";
              }
              echo "</table>";
          } else {
              echo "<p>No students registered in this hostel.</p>";
          }
      }
  } else {
      echo "<p>No Registered Student yet.</p>";
  }

  mysqli_close($conn);
  ?>

  <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
