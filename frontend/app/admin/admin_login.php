<?php
session_start();
include '../../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('username'=>'', 'password'=>'', 'incorrect'=>'');

$username = $password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    if (empty($_POST['username'])) {
        $errors['username'] = 'A username is required';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Enter a password';
    } else {
        $password = trim($_POST['password']);
    }

    // If no errors, process the login
    if (empty($errors['username']) && empty($errors['password'])) {
        // Sanitize input
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // Query to check if the admin exists
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);

            // Verify password (comparing plain text passwords)
            if (strcmp($password, $admin['password']) == 0) {
                // Store admin information in session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];

                // Redirect to the admin dashboard or another page
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $errors['incorrect'] = "Invalid password.";
            }
        } else {
            $errors['incorrect'] = "Incorrect username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/style.css">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
        <div class="error"><?php echo $errors['username']; ?></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>"><br>
        <div class="error"><?php echo $errors['password']; ?></div>

        <div class="error"><?php echo $errors['incorrect']; ?></div><br>

        <button type="submit">Login</button>
    </form>
    <a href="index.html"><button>Home</button></a>
</body>
</html>
