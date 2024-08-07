<?php
include 'questions.php';
include '../../config/db_connect.php';
include '../auth/session_manager.php';

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugging output for session
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Check if the user is logged in and get the user ID
if (isset($_SESSION['student_id'])) {
    $userId = $_SESSION['student_id']; // Change to student_id
} else {
    die("User not logged in.");
}

// Initialize an array to store the scores for each trait
$traitScores = [
    'A' => 0, // Agreeableness
    'C' => 0, // Conscientiousness
    'E' => 0, // Extraversion
    'N' => 0, // Neuroticism
    'O' => 0, // Openness
];

// Collect the responses from the POST request
$responses = $_POST['responses'];

// Calculate facet scores and accumulate them to trait scores
foreach ($responses as $questionId => $score) {
    foreach ($questions as $question) {
        if ($question['id'] == $questionId) {
            $domain = $question['domain'];
            $traitScores[$domain] += $score;
            break;
        }
    }
}

// Database connection is already included via db_connect.php
// Assuming the $conn variable is available from db_connect.php

// Prepare and execute the SQL query to update the student's trait scores
$query = "UPDATE students SET 
          agreeableness = ?, 
          conscientiousness = ?, 
          extraversion = ?, 
          neuroticism = ?, 
          openness = ? 
          WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iiiiii", $traitScores['A'], $traitScores['C'], $traitScores['E'], $traitScores['N'], $traitScores['O'], $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}

// Close the database connection if necessary (depends on db_connect.php implementation)
// mysqli_close($conn);

// Output the results for debugging (optional)
echo "<pre>";
print_r($traitScores);
echo "</pre>";
?>
<html>
  <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html">
    <button type="button" id="exitBtn">Exit Questionnaire</button>
  </a>
</html>