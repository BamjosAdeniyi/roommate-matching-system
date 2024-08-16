<?php
// Include necessary files and database connection
include '../../../config/db_connect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: /roommate-matching-system/frontend/app/user/user_login_form.php");
    exit();
}

// Get user ID
$user_id = $_SESSION['student_id'];

// Check if the user has taken the assessment
$query = "SELECT COUNT(*) AS count FROM personality_traits WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($count > 0) {
    // Display message if the assessment has already been taken
    echo "<p>You have already taken the personality test. Are you sure you want to retake it?</p>";
    echo "<a href='/roommate-matching-system/frontend/app/user/assessment.php'><button>Yes, Retake Test</button></a>";
    echo "<a href='/roommate-matching-system/frontend/app/user/user_dashboard.html'><button>No, Go Back to Dashboard</button></a>";
} else {
    // Redirect to assessment page if the assessment has not been taken
    header("Location: /roommate-matching-system/frontend/app/user/assessment.php");
    exit();
}

mysqli_close($conn);
?>
