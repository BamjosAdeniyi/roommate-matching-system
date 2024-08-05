<?php
include '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM hostels WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../frontend/app/admin/manage_hostels.php?message=Hostel deleted successfully");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
