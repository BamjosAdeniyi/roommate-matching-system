<?php
session_start();
include '../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the user exists
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password (assuming passwords are stored hashed)
        if (password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the student dashboard or another page
            header("Location: ../../frontend/app/user/dashboard.html");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    mysqli_close($conn);
}
?>
