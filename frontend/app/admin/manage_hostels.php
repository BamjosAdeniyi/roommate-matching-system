<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/style.css">
  <title>Manage Hostels</title>
</head>
<body>
  <h2>Manage Hostels</h2>

  <?php
  if (isset($_GET['message'])) {
      echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
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
      echo "<table border='1'>";
      echo "<tr><th>Name</th><th>Number of Rooms</th><th>Students per Room</th><th>Action</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . $row['number_of_rooms'] . "</td>";
          echo "<td>" . $row['students_per_room'] . "</td>";
          echo "<td><form action='../../../backend/hostel/delete_hostel.php' method='POST'><input type='hidden' name='id' value='" . $row['id'] . "'><button type='submit'>Delete</button></form></td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      echo "No hostels available.";
  }

  mysqli_close($conn);
  ?>

  <h3>Add Hostel</h3>
  <form action="../../../backend/hostel/add_hostel.php" method="POST">
    <label for="name">Hostel Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>
    <label for="number_of_rooms">Number of Rooms:</label><br>
    <input type="number" id="number_of_rooms" name="number_of_rooms" required><br><br>
    <label for="students_per_room">Students per Room:</label><br>
    <input type="number" id="students_per_room" name="students_per_room" required><br><br>
    <button type="submit">Add Hostel</button>
  </form>
  <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
</body>
</html>
