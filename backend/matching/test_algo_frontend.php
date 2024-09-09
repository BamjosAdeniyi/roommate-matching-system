<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Roommate Matching Algorithm</title>
</head>
<body>
    <h1>Roommate Matching Algorithm Test</h1>
    <?php 
    // Fetch all hostels from the database
    include '../../config/db_connect.php';
    ?>

    <!-- Form to select a hostel -->
    <form action="test_algo_backend.php" method="GET">
        <label for="hostel_id">Select Hostel:</label>
        <select name="hostel_id" id="hostel_id">
            <?php
            
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
        </select>
        <br><br>
        <button type="submit">Run Algorithm</button>
    </form>

    <!-- Result will be displayed below after algorithm runs -->
    <div id="result">
        <?php
        if (isset($_GET['hostel_id'])) {
            include 'test_algo_backend.php';
        }
        ?>
    </div>
</body>
</html>
