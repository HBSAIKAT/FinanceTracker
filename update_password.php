<?php
include 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT password FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$current_hashed_password = $row['password'];

if (password_verify($current_password, $current_hashed_password)) {
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_sql = "UPDATE users SET password='$new_hashed_password' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
} else {
    echo "Incorrect current password.";
}

mysqli_close($conn);

