<?php
include 'db_connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO expenses (amount, date, description, category, user_id) VALUES ('$amount', '$date', '$description', '$category', '$user_id')";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php"); 
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}

