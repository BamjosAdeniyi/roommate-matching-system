<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit();
}

include '../../../config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome to Admin Dashboard</h2>
    <p>Hello, <?php echo $_SESSION['admin']; ?>. Here you can manage the system.</p>
    <a href="manage_hostels.php"><button>Manage Hostel</button></a>
    <a href="view_students.php"><button>View Students</button></a>
    <a href="../../../backend/auth/admin_logout.php"><button>Logout</button></a>
</body>
</html>
