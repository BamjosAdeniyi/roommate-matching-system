<?php
include '../../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $hostel_id = $_GET['id'];

    // Fetch current hostel details
    $query = "SELECT name, number_of_rooms, students_per_room FROM hostels WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $hostel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $name, $number_of_rooms, $students_per_room);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Handle the form submission for updating hostel details
    $hostel_id = $_POST['id'];
    $name = $_POST['name'];
    $number_of_rooms = $_POST['number_of_rooms'];
    $students_per_room = $_POST['students_per_room'];

    $update_query = "UPDATE hostels SET name = ?, number_of_rooms = ?, students_per_room = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'siii', $name, $number_of_rooms, $students_per_room, $hostel_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: manage_hostels.php?message=Hostel updated successfully");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>Edit Hostel</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Edit Hostel</h2>
    <form action="edit_hostel.php" method="POST">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($hostel_id); ?>">

      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Hostel Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>

      <div class="mb-4">
        <label for="number_of_rooms" class="block text-sm font-medium text-gray-700">Number of Rooms</label>
        <input type="number" id="number_of_rooms" name="number_of_rooms" value="<?php echo htmlspecialchars($number_of_rooms); ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>

      <div class="mb-6">
        <label for="students_per_room" class="block text-sm font-medium text-gray-700">Students per Room</label>
        <input type="number" id="students_per_room" name="students_per_room" value="<?php echo htmlspecialchars($students_per_room); ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>

      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">Update Hostel</button>
    </form>
    
    <a href="manage_hostels.php" class="block mt-4 text-center">
      <button class="w-full bg-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-400 transition duration-300">Back to Manage Hostels</button>
    </a>
  </div>
</body>
</html>
