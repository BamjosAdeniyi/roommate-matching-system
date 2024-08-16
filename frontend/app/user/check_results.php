<?php
// Include necessary files and database connection
include '../../../config/db_connect.php'; // Ensure this initializes the connection without closing it
include '../../../backend/assessment/process_result.php';

// Check if the session is not already started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: /roommate-matching-system/frontend/app/user/user_login_form.php");
    exit();
}

// Get user ID from session
$student_id = $_SESSION['student_id'];

// Check if the user has taken the assessment
$query = "SELECT COUNT(*) AS count FROM personality_traits WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        // Redirect to result page if the assessment has been taken
        header("Location: /roommate-matching-system/frontend/app/user/results.php");
        exit();
    } else {
        // Display message if the assessment has not been taken
        echo "<p>You have not taken the personality test. Please take the assessment first.</p>";
        echo "<a href='/roommate-matching-system/frontend/app/user/assessment.php'><button>Take Assessment</button></a>";
    }
} else {
    // Handle the error if the statement was not prepared
    echo "<p>There was an error processing your request. Please try again later.</p>";
}

// Close the database connection at the end of the script
if ($conn) {
    mysqli_close($conn);
}
?>
