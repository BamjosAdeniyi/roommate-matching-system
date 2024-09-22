<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Check Assessment</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <?php
    // Start session only if it's not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include necessary files and database connection
    include '../../../config/db_connect.php'; // Ensure this initializes the connection
    // include '../../../backend/assessment/process_result.php';

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
            echo "<div class='bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-10'>";
            echo "<p class='text-lg text-gray-700'>You have not taken the personality test. Please take the assessment first.</p>";
            echo "<a href='/roommate-matching-system/frontend/app/user/assessment.php'><button class='mt-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300'>Take Assessment</button></a>";
            echo "<a href='/roommate-matching-system/frontend/app/user/user_dashboard.php'><button class='mt-4 bg-gray-300 text-black py-2 px-4 rounded-md hover:bg-gray-400 ml-3 transition duration-300'>Go to Dashboard</button></a>";
            echo "</div>";
        }
    } else {
        // Handle the error if the statement was not prepared
        echo "<div class='bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-10'>";
        echo "<p class='text-lg text-gray-700'>There was an error processing your request. Please try again later.</p>";
        echo "</div>";
    }
    
    // Close the database connection at the end of the script
    if ($conn) {
        mysqli_close($conn);
    }
    ?>
</body>
</html>
