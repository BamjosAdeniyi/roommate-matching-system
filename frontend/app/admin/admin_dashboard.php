<?php
session_start();
// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login_form.php"); // Redirect to login page if not logged in
    exit();
}

include '../../../config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold mb-4 text-center">Welcome to Admin Dashboard</h2>
        <p class="text-lg text-center mb-6">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>. Here you can manage the system.</p>
        <div class="flex flex-col space-y-4">
            <a href="manage_hostels.php">
                <button class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">Manage Hostel</button>
            </a>
            <a href="view_students.php">
                <button class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition duration-300">View Students</button>
            </a>
            <a href="../../../backend/auth/admin_logout.php">
                <button class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition duration-300">Logout</button>
            </a>
        </div>
    </div>
</body>
</html>
