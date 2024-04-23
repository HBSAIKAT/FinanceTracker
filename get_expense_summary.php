<?php
include 'db_connect.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$current_month = date('m');
$current_year = date('Y');

$user_id = $_SESSION['user_id'];
$sql = "SELECT category, SUM(amount) AS total_amount FROM expenses WHERE user_id='$user_id' AND MONTH(date)='$current_month' AND YEAR(date)='$current_year' GROUP BY category";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Total Spending by Category:</h3>";
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['category'] . ": $" . number_format($row['total_amount'], 2) . "</li>";
    }
    echo "</ul>";

    $total_spending_sql = "SELECT SUM(amount) AS total_spending FROM expenses WHERE user_id='$user_id' AND MONTH(date)='$current_month' AND YEAR(date)='$current_year'";
    $total_spending_result = mysqli_query($conn, $total_spending_sql);
    $total_spending_row = mysqli_fetch_assoc($total_spending_result);
    $total_spending = $total_spending_row['total_spending'];
    echo "<p><strong>Total Spending for the Current Month:</strong> $" . number_format($total_spending, 2) . "</p>";
} else {
    echo "<p>No expenses recorded for the current month.</p>";
}


mysqli_close($conn);

