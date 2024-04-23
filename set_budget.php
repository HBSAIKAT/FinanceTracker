<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $amount = floatval($_POST['amount']);
    $user_id = $_SESSION['user_id'];

    // Check if the category already has a budget entry for the user
    $sql = "SELECT * FROM budgets WHERE category='$category' AND user_id='$user_id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        // Update the existing budget entry
        $sql_update = "UPDATE budgets SET amount='$amount' WHERE category='$category' AND user_id='$user_id'";
        if (mysqli_query($conn, $sql_update)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Insert a new budget entry
        $sql_insert = "INSERT INTO budgets (category, amount, user_id) VALUES ('$category', '$amount', '$user_id')";
        if (mysqli_query($conn, $sql_insert)) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
            exit();
        }
    }
    
} else {
    // Redirect if accessed directly without POST request
    header("Location: dashboard.php");
    exit();
}
