<?php
session_start();
include '../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('surname' => '', 'first_name' => '', 'other_name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'signup' => '');

$first_name = $surname = $other_name = $email = $password = $confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate surname
    if (empty($_POST['surname'])) {
        $errors['surname'] = 'A Surname is required';
    } else {
        $surname = trim($_POST['surname']);
    }

    // Validate first name
    if (empty($_POST['first_name'])) {
        $errors['first_name'] = 'A first name is required';
    } else {
        $first_name = trim($_POST['first_name']);
    }
    
    // Validate first name
    if (empty($_POST['other_name'])) {
        $errors['other_name'] = 'A middle name is required';
    } else {
        $other_name = trim($_POST['other_name']);
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
    if (empty($errors['first_name']) && empty($errors['surname']) && empty($errors['other_name']) && empty($errors['email']) && empty($errors['password']) && empty($errors['confirm_password'])) {
        // Sanitize input
        $first_name = mysqli_real_escape_string($conn, $first_name);
        $surname = mysqli_real_escape_string($conn, $surname);
        $other_name = mysqli_real_escape_string($conn, $other_name);
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
            $sql = "INSERT INTO students (first_name, surname, other_name, email, password) VALUES ('$first_name', '$surname', '$other_name', '$email', '$hashed_password')";
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

