<?php
session_start();
include '../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('email' => '', 'password' => '', 'incorrect' => '');

$email = $password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = 'An email is required';
    } else {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address';
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Enter a password';
    } else {
        $password = trim($_POST['password']);
    }

    // If no errors, process the login
    if (empty($errors['email']) && empty($errors['password'])) {
        // Sanitize input
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        // Query to check if the user exists
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store user information in session
                $_SESSION['student_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Redirect to the student dashboard or another page
                header("Location: ../../frontend/app/user/user_dashboard.html");
                exit();
            } else {
                $errors['incorrect'] = "Invalid password.";
            }
        } else {
            $errors['incorrect'] = "Incorrect email or password";
        }
    }
}

// Include the frontend form
include '../../frontend/app/user/user_login_form.php';
