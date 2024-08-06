<?php
session_start();
include '../../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('name'=>'', 'email'=>'', 'password'=>'', 'confirm_password'=>'', 'signup'=>'');

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
                header("Location: login.php");
                exit();
            } else {
                $errors['signup'] = "Signup failed: " . mysqli_error($conn);
            }
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
    <title>Student Registration</title>
</head>
<body>
    <h2>Student Registration</h2>
    <form action="signup.php" method="POST">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>
        <div class="error"><?php echo $errors['name']; ?></div><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
        <div class="error"><?php echo $errors['email']; ?></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>"><br>
        <div class="error"><?php echo $errors['password']; ?></div><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>"><br>
        <div class="error"><?php echo $errors['confirm_password']; ?></div><br>

        <div class="error"><?php echo $errors['signup']; ?></div><br>

        <button type="submit">Signup</button>
    </form>
    <p>Login if you already have an account</p>
    <a href="login.php"><button>Login</button></a>
    <a href="../index.html"><button>Home</button></a>
</body>
</html>