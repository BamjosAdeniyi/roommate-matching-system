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
  <link rel="stylesheet" href="/roommate-matching-system/frontend/styles/style.css">
  <title>Edit Hostel</title>
</head>
<body>
  <h2>Edit Hostel</h2>
  <form action="edit_hostel.php" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($hostel_id); ?>">
    <label for="name">Hostel Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
    <label for="number_of_rooms">Number of Rooms:</label><br>
    <input type="number" id="number_of_rooms" name="number_of_rooms" value="<?php echo htmlspecialchars($number_of_rooms); ?>" required><br><br>
    <label for="students_per_room">Students per Room:</label><br>
    <input type="number" id="students_per_room" name="students_per_room" value="<?php echo htmlspecialchars($students_per_room); ?>" required><br><br>
    <button type="submit">Update Hostel</button>
  </form>
  <a href="manage_hostels.php"><button>Back to Manage Hostels</button></a>
</body>
</html>
