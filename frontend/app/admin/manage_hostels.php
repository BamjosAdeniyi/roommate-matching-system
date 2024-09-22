<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
  <title>Manage Hostels</title>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold text-center mb-6">Manage Hostels</h2>

    <?php
    if (isset($_GET['message'])) {
        echo "<p class='text-green-600 font-bold text-center mb-4'>" . htmlspecialchars($_GET['message']) . "</p>";
    }

    include '../../../config/db_connect.php';

    // Add error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $sql = "SELECT id, name, number_of_rooms, students_per_room FROM hostels";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        echo "
        <div class='overflow-x-auto'>
          <table class='min-w-full bg-white border border-gray-300 rounded-md'>
            <thead>
              <tr>
                <th class='px-6 py-3 border-b text-center'>Name</th>
                <th class='px-6 py-3 border-b text-center'>Number of Rooms</th>
                <th class='px-6 py-3 border-b text-center'>Students per Room</th>
                <th class='px-6 py-3 border-b text-center'>Actions</th>
              </tr>
            </thead>
            <tbody class='text-center'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='border-b'>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['name']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['number_of_rooms']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['students_per_room']) . "</td>
                    <td class='px-6 py-4 flex justify-center space-x-2'>
                      <form action='../../../backend/hostel/delete_hostel.php' method='POST' class='inline'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <button type='submit' class='bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition flex items-center'>
                          <i class='fas fa-trash-alt mr-1'></i> Delete
                        </button>
                      </form>
                      <form action='edit_hostel.php' method='GET' class='inline'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <button type='submit' class='bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition flex items-center'>
                          <i class='fas fa-edit mr-1'></i> Edit
                        </button>
                      </form>
                      <form action='match_roommates.php' method='GET' class='inline'>
                        <input type='hidden' name='hostel_id' value='" . $row['id'] . "'>
                        <button type='submit' class='bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition flex items-center'>
                          <i class='fas fa-users mr-1'></i> Match Roommates
                        </button>
                      </form>
                    </td>
                  </tr>";
        }
        echo "</tbody></table></div>";
    } else {
        echo "<p class='text-center text-red-500'>No hostels available.</p>";
    }

    mysqli_close($conn);
    ?>

    <h3 class="text-xl font-semibold mt-8">Add Hostel</h3>
    <form action="../../../backend/hostel/add_hostel.php" method="POST" class="mt-4 space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Hostel Name</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>
      <div>
        <label for="number_of_rooms" class="block text-sm font-medium text-gray-700">Number of Rooms</label>
        <input type="number" id="number_of_rooms" name="number_of_rooms" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>
      <div>
        <label for="students_per_room" class="block text-sm font-medium text-gray-700">Students per Room</label>
        <input type="number" id="students_per_room" name="students_per_room" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
      </div>
      <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Add Hostel</button>
    </form>

    <div class="mt-4">
      <a href="admin_dashboard.php">
        <button class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition">Back to Dashboard</button>
      </a>
    </div>
  </div>
</body>
</html>
