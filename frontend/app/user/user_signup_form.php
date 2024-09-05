<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <title>Student Registration</title>
</head>
<body>
    <h2>Student Registration</h2>
    <form action="/roommate-matching-system/backend/auth/user_signup.php" method="POST">
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['surname']) ? $errors['surname'] : ''; ?></div><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['first_name']) ? $errors['first_name'] : ''; ?></div><br>

        <label for="other_name">Middle Name:</label>
        <input type="text" id="other_name" name="other_name" value="<?php echo isset($_POST['other_name']) ? htmlspecialchars($_POST['other_name']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['other_name']) ? $errors['other_name'] : ''; ?></div><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></div><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo isset($_POST['confirm_password']) ? htmlspecialchars($_POST['confirm_password']) : ''; ?>"><br>
        <div class="error"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></div><br>

        <div class="error"><?php echo isset($errors['signup']) ? $errors['signup'] : ''; ?></div><br>

        <button type="submit">Signup</button>
    </form>
    <p>Login if you already have an account</p>
    <a href="user_login_form.php"><button>Login</button></a>
    <a href="../index.html"><button>Home</button></a>
</body>
</html>
