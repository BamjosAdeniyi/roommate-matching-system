<?php
session_start();
// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

include '../../../config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome to Admin Dashboard</h2>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>. Here you can manage the system.</p>
    <a href="manage_hostels.php"><button>Manage Hostel</button></a>
    <a href="view_students.php"><button>View Students</button></a>
    <a href="../../../backend/auth/admin_logout.php"><button>Logout</button></a>
</body>
</html>
