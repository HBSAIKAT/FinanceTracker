<?php
include 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$new_email = $_POST['new_email'];
$user_id = $_SESSION['user_id'];

$update_sql = "UPDATE users SET email='$new_email' WHERE id='$user_id'";
if (mysqli_query($conn, $update_sql)) {
    echo "Email updated successfully.";
} else {
    echo "Error updating email: " . mysqli_error($conn);
}

mysqli_close($conn);

