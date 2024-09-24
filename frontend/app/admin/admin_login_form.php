<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Login</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Login</h2>
        <form action="/roommate-matching-system/backend/auth/admin_login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username:</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['username'] ?? ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password:</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($password ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['password'] ?? ''; ?></div>
            </div>

            <div class="text-red-500 text-sm mb-4"><?php echo $errors['incorrect'] ?? ''; ?></div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">Login</button>
        </form>
        <!-- <div class="mt-4 text-center">
            <a href="/roommate-matching-system/frontend/app/admin/" class="text-blue-500 hover:underline">Home</a>
        </div> -->
    </div>
</body>
</html>
