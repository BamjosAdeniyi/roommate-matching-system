<?php
// Start the session
session_start();
include '../../../config/db_connect.php';

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['student_id'])) {
    // If the user is not logged in, redirect them to the login page with a message
    echo "<p>User not logged in. Please <a href='/roommate-matching-system/frontend/app/user/user_login_form.php'>log in</a> to access the dashboard.</p>";
    exit();
}

// Get the student's ID from the session
$student_id = $_SESSION['student_id'];

// Query to get the first name from the students table
$sql = "SELECT first_name FROM students WHERE id = $student_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $student_first_name = $row['first_name'];
} else {
    // If no result, set a default name
    $student_first_name = 'User';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome to the Student Dashboard</h2>
    <p>Hello, <?php echo htmlspecialchars($student_first_name); ?>! You have successfully logged in!</p>
    <h5>Let's get you matched-up with a compatible roommate</h5>
    <p>Start by selecting your preferred hostel, if you have not chosen before.</p>
    <a href="hostel.php"><button>Select Hostel</button></a>
    <a href="check_assessment.php"><button>Personality Assessment</button></a>
    <a href="check_results.php"><button>Assessment Result</button></a>
    <a href="../../../backend/auth/user_logout.php"><button>Logout</button></a>
</body>
</html>
