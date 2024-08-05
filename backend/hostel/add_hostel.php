<?php
include '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $number_of_rooms = $_POST['number_of_rooms'];
    $students_per_room = $_POST['students_per_room'];

    $sql = "INSERT INTO hostels (name, number_of_rooms, students_per_room) VALUES ('$name', '$number_of_rooms', '$students_per_room')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../frontend/app/admin/manage_hostels.php?message=Hostel added successfully");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
