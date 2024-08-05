<?php
include '../../config/db_connect.php';

session_start();
$student_id = $_SESSION['student_id'];
$hostel_id = $_POST['hostel_id'];

$sql = "UPDATE students SET hostel_id = $hostel_id WHERE id = $student_id";

if (mysqli_query($conn, $sql)) {
    echo "Hostel selected successfully!";
    // Redirect to assessment page
    header("Location: ../../frontend/html-php/assessment.html");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
