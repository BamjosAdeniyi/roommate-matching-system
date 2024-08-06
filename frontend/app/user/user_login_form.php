<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <title>Student Login</title>
</head>
<body>
    <h2>Student Login</h2>
    <form action="/roommate-matching-system/backend/auth/user_login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>"><br>
        <div class="error"><?php echo $errors['email'] ?? ''; ?></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>"><br>
        <div class="error"><?php echo $errors['password'] ?? ''; ?></div>

        <div class="error"><?php echo $errors['incorrect'] ?? ''; ?></div><br>

        <input type="submit" name="submit" value="Login">
    </form>
    <a href="../index.html"><button>Home</button></a>
</body>
</html>
