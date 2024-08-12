<?php
include 'questions.php';
include '../../config/db_connect.php';
include '../auth/session_manager.php';
include 'facet_mappings.php'; // Include the facet mapping file

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and get the user ID
if (isset($_SESSION['student_id'])) {
    $studentId = $_SESSION['student_id'];
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

// Initialize an array to store facet scores
$facetScores = [];

// Collect the responses from the POST request
$responses = $_POST['responses'];

// Calculate facet scores and accumulate them to trait scores
foreach ($responses as $questionId => $score) {
    foreach ($questions as $question) {
        if ($question['id'] == $questionId) {
            $domain = $question['domain'];
            $traitScores[$domain] += $score; // Accumulate trait scores

            // Calculate facet number
            $facetNumber = $question['facet'];

            // Map the facet number to the actual name using the facetMappings
            if (!isset($facetScores[$domain][$facetNumber])) {
                $facetScores[$domain][$facetNumber] = 0; // Initialize facet score if not set
            }
            $facetScores[$domain][$facetNumber] += $score; // Accumulate score for each facet

            break;
        }
    }
}

// Database connection is already included via db_connect.php
// Assuming the $conn variable is available from db_connect.php

// Prepare and execute the SQL query to insert the student's trait scores
$traitQuery = "INSERT INTO personality_traits (student_id, agreeableness, conscientiousness, extraversion, neuroticism, openness) VALUES (?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE agreeableness = VALUES(agreeableness), conscientiousness = VALUES(conscientiousness), extraversion = VALUES(extraversion), neuroticism = VALUES(neuroticism), openness = VALUES(openness)";

$traitStmt = mysqli_prepare($conn, $traitQuery);
if ($traitStmt) {
    mysqli_stmt_bind_param($traitStmt, "iiiiii", $studentId, $traitScores['A'], $traitScores['C'], $traitScores['E'], $traitScores['N'], $traitScores['O']);
    mysqli_stmt_execute($traitStmt);
    mysqli_stmt_close($traitStmt);
} else {
    echo "Error preparing statement for traits: " . mysqli_error($conn);
}

// Now, insert the facet scores into the personality_facets table
foreach ($facetScores as $domain => $facets) {
    foreach ($facets as $facetNumber => $score) {
        $facetName = $facetMappings[$domain][$facetNumber]; // Get the actual facet name

        // Prepare and execute the SQL query to insert the facet scores with ON DUPLICATE KEY UPDATE
        $facetQuery = "INSERT INTO personality_facets (student_id, domain, facet, score) 
                       VALUES (?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE score = VALUES(score)";
        $facetStmt = mysqli_prepare($conn, $facetQuery);
        if ($facetStmt) {
            mysqli_stmt_bind_param($facetStmt, "issi", $studentId, $domain, $facetName, $score);
            mysqli_stmt_execute($facetStmt);
            mysqli_stmt_close($facetStmt);
        } else {
            echo "Error preparing statement for facets: " . mysqli_error($conn);
        }
    }
}

// Close the database connection if necessary (depends on db_connect.php implementation)
// mysqli_close($conn);

// Output the results for debugging (optional)
// echo "<pre>";
// print_r($traitScores);
// print_r($facetScores);
// echo "</pre>";
header('location: /roommate-matching-system/frontend/app/user/results.php');
// echo "Assessment Submitted Successfully";
?>
<html>
  <a href="/roommate-matching-system/frontend/app/user/user_dashboard.html">
    <button type="button" id="exitBtn">Exit Questionnaire</button>
  </a>
  <a href="/roommate-matching-system/frontend/app/user/results.php">
    <button type="button" id="exitBtn">Assessment Result</button>
  </a>
</html>