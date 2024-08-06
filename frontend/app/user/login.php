<?php
session_start();
include '../../../config/db_connect.php';

// Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize errors array
$errors = array('email'=>'', 'password'=>'', 'incorrect'=>'');

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
                header("Location: dashboard.html");
                exit();
            } else {
                $errors['incorrect'] = "Invalid password.";
            }
        } else {
            $errors['incorrect'] = "Incorrect email or password";
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
    <title>Student Login</title>
</head>
<body>
    <h2>Student Login</h2>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
        <div class="error"><?php echo $errors['email']; ?></div><br>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password);?>"><br> <!-- Change this back to password for production -->
        <div class="error"><?php echo $errors['password']; ?></div>

        <div class="error"><?php echo $errors['incorrect']; ?></div><br>

        <input type="submit" name="submit" value="Login">
    </form>
    <a href="../index.html"><button>Home</button></a>
</body>
</html>