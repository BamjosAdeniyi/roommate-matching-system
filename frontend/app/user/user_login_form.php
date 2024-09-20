<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Student Login</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Student Login</h2>
        <form action="/roommate-matching-system/backend/auth/user_login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['email'] ?? ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo htmlspecialchars($password ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['password'] ?? ''; ?></div>
            </div>

            <div class="text-red-500 text-sm mt-2 mb-4"><?php echo $errors['incorrect'] ?? ''; ?></div>

            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">
                Login
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="../../frontend/app/index.html" class="text-blue-500 hover:underline">Home</a>
        </div>
    </div>
</body>
</html>
