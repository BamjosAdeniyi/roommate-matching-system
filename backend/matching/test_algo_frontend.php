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

    <!-- Form to select a hostel and scenario -->
    <form action="test_algo_backend.php" method="GET">
        <label for="hostel_id">Select Hostel:</label>
        <select name="hostel_id" id="hostel_id">
            <?php
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
        <br><br>

        <label for="scenario_id">Select Scenario:</label>
        <select name="scenario_id" id="scenario_id">
            <option value="1">Scenario 1: 10-15 students</option>
            <option value="2">Scenario 2: 20-30 students</option>
            <option value="3">Scenario 3: 40-60 students</option>
            <option value="4">Scenario 4: 60-120 students</option>
        </select>
        <br><br>

        <button type="submit">Run Algorithm</button>
    </form>

    <!-- Result will be displayed below after algorithm runs -->
    <div id="result">
        <?php
        if (isset($_GET['hostel_id']) && isset($_GET['scenario_id'])) {
            include 'test_algo_backend.php';
        }
        ?>
    </div>
</body>
</html>