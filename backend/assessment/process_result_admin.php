<?php
session_start();
include '../../../config/db_connect.php'; // Database connection

/// Check if the user is an admin or a student
if (isset($_SESSION['admin_id'])) {
    // If admin is logged in, use the student ID passed in the URL
    $student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
} else {
    // Redirect to admin login if no valid session is found
    header("Location: /roommate-matching-system/frontend/app/admin/admin_login_form.php"); // Adjust this to your user login page
    exit();
}


// Validate the student ID
if ($student_id == 0) {
    die("Invalid student ID.");
}

// Trait name mappings
$traitNames = [
  'A' => 'Agreeableness',
  'C' => 'Conscientiousness',
  'E' => 'Extraversion',
  'N' => 'Neuroticism',
  'O' => 'Openness',
];

// Query to fetch personality trait scores for the specific student ID (use $student_id, not session)
$traitQuery = "SELECT * FROM personality_traits WHERE student_id = ?";
$traitStmt = mysqli_prepare($conn, $traitQuery);
mysqli_stmt_bind_param($traitStmt, "i", $student_id); // Use the correct student ID based on the session or URL
mysqli_stmt_execute($traitStmt);
$traitResult = mysqli_stmt_get_result($traitStmt);
$traitScores = mysqli_fetch_assoc($traitResult);

// Query to fetch personality facet scores for the specific student ID
$facetQuery = "SELECT domain, facet, score FROM personality_facets WHERE student_id = ? ORDER BY domain ASC";
$facetStmt = mysqli_prepare($conn, $facetQuery);
mysqli_stmt_bind_param($facetStmt, "i", $student_id); // Ensure you're using $student_id here
mysqli_stmt_execute($facetStmt);
$facetResult = mysqli_stmt_get_result($facetStmt);

$facetScores = [];
while ($row = mysqli_fetch_assoc($facetResult)) {
    $domain = $row['domain'];
    $facet = $row['facet'];
    $score = $row['score'];
    
    if (!isset($facetScores[$domain])) {
        $facetScores[$domain] = [];
    }
    $facetScores[$domain][$facet] = $score;
}

// Close prepared statements and database connection
mysqli_stmt_close($traitStmt);
mysqli_stmt_close($facetStmt);
mysqli_close($conn);
