<?php
// Start the session
session_start();
include '../../../config/db_connect.php';

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['student_id'])) {
    // Redirect to user login if no valid session is found
    header("Location: /roommate-matching-system/frontend/app/user/user_login_form.php"); // Adjust this to your user login page
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Student Dashboard</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white p-12 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Student Dashboard</h2>
        <p class="text-lg text-gray-700 text-center mb-4">Hello, <?php echo htmlspecialchars($student_first_name); ?>! You have successfully logged in!</p>
        <h5 class="text-xl text-gray-800 font-semibold text-center mb-6">Let's get you matched-up with a compatible roommate</h5>
        <p class="text-sm text-gray-600 text-center mb-6">Start by selecting your preferred hostel, if you have not chosen before.</p>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <a href="hostel.php" class="w-full">
                <button class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Select Hostel</button>
            </a>
            <a href="check_assessment.php" class="w-full">
                <button class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-300">Personality Assessment</button>
            </a>
            <a href="check_results.php" class="w-full">
                <button class="w-full bg-yellow-500 text-white py-2 px-4 rounded-md hover:bg-yellow-600 transition duration-300">Assessment Result</button>
            </a>
            <a href="../../../backend/auth/user_logout.php" class="w-full">
                <button class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition duration-300">Logout</button>
            </a>
        </div>
    </div>
</body>
</html>
