<?php
session_start();
include '../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('username' => '', 'password' => '', 'incorrect' => '');

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
                header("Location: ../../frontend/app/admin/admin_dashboard.php");
                exit();
            } else {
                $errors['incorrect'] = "Invalid password.";
            }
        } else {
            $errors['incorrect'] = "Incorrect username or password";
        }
    }
}

// Include the frontend form
include '../../frontend/app/admin/admin_login_form.php';