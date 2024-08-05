<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/style.css">
  <title>Hostel Selection</title>
</head>
<body>
  <h2>Select Your Preferred Hostel</h2>
  <form action="select_hostel.php" method="POST">
    <label for="hostels">Choose a hostel:</label>
    <select id="hostels" name="hostel_id" required>
    <?php
      // Add error reporting
      error_reporting(E_ALL);
      ini_set('display_errors', 1);

      include '../../config/db_connect.php';

      // Check connection
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      } else {
          echo "Connected successfully.<br>";
      }

      $sql = "SELECT id, name, number_of_rooms, students_per_room FROM hostels";
      echo "Query executed successfully.<br>";
      $result = mysqli_query($conn, $sql);

      // Check query execution
      if (!$result) {
          die("Query failed: " . mysqli_error($conn));
      } else {
          echo "Query executed successfully.<br>";
      }

      // Check number of rows
      if (mysqli_num_rows($result) > 0) {
          echo "Number of rows: " . mysqli_num_rows($result) . "<br>";
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value='" . $row['id'] . "'>" . $row['name'] . " (Rooms: " . $row['number_of_rooms'] . ", Students per room: " . $row['students_per_room'] . ")</option>";
          }
      } else {
          echo "<option value=''>No hostels available</option>";
      }

      mysqli_close($conn);
    ?>

    </select><br><br>
    <button type="submit">Submit</button>
  </form>
  <a href="index.html"><button>Home</button></a>
</body>
</html>
