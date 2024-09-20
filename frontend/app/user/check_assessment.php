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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Check Assessment</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg text-center">
        <?php if ($count > 0): ?>
            <p class="text-lg font-semibold mb-4">You have already taken the personality test.</p>
            <p class="mb-6">Are you sure you want to retake it?</p>
            <div class="flex justify-center space-x-4">
                <a href="/roommate-matching-system/frontend/app/user/assessment.php">
                    <button class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Yes, Retake Test</button>
                </a>
                <a href="/roommate-matching-system/frontend/app/user/user_dashboard.php">
                    <button class="bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300">No, Go Back to Dashboard</button>
                </a>
            </div>
        <?php else: ?>
            <?php
                // Redirect to assessment page if the assessment has not been taken
                header("Location: /roommate-matching-system/frontend/app/user/assessment.php");
                exit();
            ?>
        <?php endif; ?>
    </div>
</body>
</html>
