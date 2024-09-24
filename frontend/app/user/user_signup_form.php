<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Student Registration</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg m-10">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Student Registration</h2>
        <form action="/roommate-matching-system/backend/auth/user_signup.php" method="POST">
            <div class="mb-4">
                <label for="surname" class="block font-semibold text-gray-700">Surname</label>
                <input type="text" id="surname" name="surname" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['surname']) ? $errors['surname'] : ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="first_name" class="block font-semibold text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['first_name']) ? $errors['first_name'] : ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="other_name" class="block font-semibold text-gray-700">Middle Name</label>
                <input type="text" id="other_name" name="other_name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['other_name']) ? htmlspecialchars($_POST['other_name']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['other_name']) ? $errors['other_name'] : ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block font-semibold text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?php echo isset($_POST['confirm_password']) ? htmlspecialchars($_POST['confirm_password']) : ''; ?>">
                <div class="text-red-500 text-sm mt-1"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></div>
            </div>

            <div class="text-red-500 text-sm mt-2 mb-4"><?php echo isset($errors['signup']) ? $errors['signup'] : ''; ?></div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Sign Up</button>
        </form>

        <p class="text-sm text-gray-600 mt-4 text-center">Already have an account? 
            <a href="user_login_form.php" class="text-blue-500 hover:underline">Login</a>
        </p>
        <div class="mt-4 text-center">
            <a href="../index.html" class="text-blue-500 hover:underline">Home</a>
        </div>
    </div>
</body>
</html>
