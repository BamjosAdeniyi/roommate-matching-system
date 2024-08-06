<?php
session_start();
include '../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'signup' => '');

$name = $email = $password = $confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate name
    if (empty($_POST['name'])) {
        $errors['name'] = 'A name is required';
    } else {
        $name = trim($_POST['name']);
    }

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

    // Validate confirm password
    if (empty($_POST['confirm_password'])) {
        $errors['confirm_password'] = 'Confirm your password';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
    }

    // If no errors, process the signup
    if (empty($errors['name']) && empty($errors['email']) && empty($errors['password']) && empty($errors['confirm_password'])) {
        // Sanitize input
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        // Check if the email already exists
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $errors['signup'] = "Email already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql = "INSERT INTO students (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
                // Redirect to the login page after successful registration
                header("Location: ../../frontend/app/user/user_login_form.php");
                exit();
            } else {
                $errors['signup'] = "Signup failed: " . mysqli_error($conn);
            }
        }
    }
}

// Include the frontend form
include '../../frontend/app/user/user_signup_form.php';

