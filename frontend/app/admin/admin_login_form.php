<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form action="/roommate-matching-system/backend/auth/admin_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>"><br>
        <div class="error"><?php echo $errors['username'] ?? ''; ?></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>"><br>
        <div class="error"><?php echo $errors['password'] ?? ''; ?></div>

        <div class="error"><?php echo $errors['incorrect'] ?? ''; ?></div><br>

        <button type="submit">Login</button>
    </form>
    <a href="index.html"><button>Home</button></a>
</body>
</html>
