<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>Hostel Selection</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Select Your Preferred Hostel</h2>
    <form action="../../../backend/hostel/select_hostel.php" method="POST">
      <label for="hostels" class="block text-sm font-medium text-gray-700 mb-2">Choose a hostel:</label>
      <select id="hostels" name="hostel_id" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4">
      
      <?php
        // Add error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        include '../../../config/db_connect.php';

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT id, name, number_of_rooms, students_per_room FROM hostels";
        $result = mysqli_query($conn, $sql);

        // Check query execution
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Check number of rows
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . " (Rooms: " . $row['number_of_rooms'] . ", Students per room: " . $row['students_per_room'] . ")</option>";
            }
        } else {
            echo "<option value=''>No hostels available</option>";
        }

        mysqli_close($conn);
      ?>

      </select>

      <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Submit</button>
    </form>
    
    <div class="mt-4 text-center">
      <a href="user_dashboard.php" class="text-blue-500 hover:underline">Home</a>
    </div>
  </div>
</body>
</html>
