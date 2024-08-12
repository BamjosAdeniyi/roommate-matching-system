<?php
session_start();
include '../../../config/db_connect.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    die("User not logged in.");
}

$studentId = $_SESSION['student_id'];

// Query to fetch personality trait scores
$traitQuery = "SELECT * FROM personality_traits WHERE student_id = ?";
$traitStmt = mysqli_prepare($conn, $traitQuery);
mysqli_stmt_bind_param($traitStmt, "i", $studentId);
mysqli_stmt_execute($traitStmt);
$traitResult = mysqli_stmt_get_result($traitStmt);
$traitScores = mysqli_fetch_assoc($traitResult);

// Query to fetch personality facet scores
$facetQuery = "SELECT domain, facet, score FROM personality_facets WHERE student_id = ?";
$facetStmt = mysqli_prepare($conn, $facetQuery);
mysqli_stmt_bind_param($facetStmt, "i", $studentId);
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

// Include the frontend rendering file
// include '../../frontend/app/user/results.php';
