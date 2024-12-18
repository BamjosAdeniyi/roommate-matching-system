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
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Student Login</h2>
        <form action="/roommate-matching-system/backend/auth/user_login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['email'] ?? ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($password ?? ''); ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo $errors['password'] ?? ''; ?></div>
            </div>

            <div class="text-red-500 text-sm mt-2 mb-4"><?php echo $errors['incorrect'] ?? ''; ?></div>

            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">
                Login
            </button>
        </form>

        <p class="text-sm text-gray-600 mt-4 text-center">Don't have an account? 
            <a href="user_signup.php" class="text-blue-500 hover:underline">Sign up</a>
        </p>
        <div class="mt-4 text-center">
            <a href="../index.html" class="text-blue-500 hover:underline">Home</a>
        </div>
    </div>
</body>
</html>
