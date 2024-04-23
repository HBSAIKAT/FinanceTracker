<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #555;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content h2 {
            color: #333;
        }

        .content .block {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .content .block h3 {
            color: #333;
        }

        .content .block p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Expense Tracker</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="expense_form.php">Add Expense</a></li>
            <li><a href="budget_form.php">Set Budget</a></li>
            <li><a href="expense_summary.php">Expense Summary</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Welcome to Your Expense Tracker Dashboard</h2>
        <!-- Recent Expenses block -->
        <div class="block">
            <h3>Recent Expenses</h3>
            <?php
            include 'db_connect.php';
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.html");
                exit();
            }

            $user_id = $_SESSION['user_id'];
            $sql = "SELECT * FROM expenses WHERE user_id='$user_id' ORDER BY date DESC LIMIT 5";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<ul>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li>" . $row['description'] . " - $" . $row['amount'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No recent expenses.</p>";
            }
            mysqli_close($conn);
            ?>
        </div>

        <!-- Monthly Budget block -->
<div class="block">
    <h3>Monthly Budget</h3>
    <?php
    include 'db_connect.php';

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.html");
        exit();
    }

    // Get user ID
    $user_id = $_SESSION['user_id'];

    // Fetch monthly budget from the database
    $query = "SELECT amount FROM budgets WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $monthly_budget = $row['amount'];
    } else {
        // Default value if no budget set
        $monthly_budget = 0;
    }

    // Fetch total spending for the current month from the database
    $query = "SELECT SUM(amount) AS total_spending FROM expenses WHERE user_id = $user_id AND MONTH(date) = MONTH(CURRENT_DATE())";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $total_spending = $row['total_spending'];
    } else {
        // Default value if no spending recorded for the current month
        $total_spending = 0;
    }

    // Calculate spending progress
    if ($monthly_budget > 0) {
        $spending_progress = ($total_spending / $monthly_budget) * 100;
    } else {
        $spending_progress = 0;
    }

    echo "<p>Monthly Budget: $" . $monthly_budget . "</p>";
    echo "<p>Total Spending: $" . $total_spending . "</p>";
    echo "<p>Spending Progress: " . round($spending_progress, 2) . "%</p>";

    ?>
</div>


        <!-- Expense Summary block -->
        <div class="block">
            <h3>Expense Summary</h3>
            <?php
            include 'db_connect.php';
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.html");
                exit();
            }
            $current_month = date('m');
            $current_year = date('Y');

            $expense_summary_sql = "SELECT category, SUM(amount) AS total_amount FROM expenses WHERE user_id='$user_id' AND MONTH(date)='$current_month' AND YEAR(date)='$current_year' GROUP BY category";
            $expense_summary_result = mysqli_query($conn, $expense_summary_sql);

            if (mysqli_num_rows($expense_summary_result) > 0) {
                echo "<ul>";
                while ($row = mysqli_fetch_assoc($expense_summary_result)) {
                    echo "<li>" . $row['category'] . ": $" . $row['total_amount'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No expenses recorded for the current month.</p>";
            }
            ?>
        </div>
    </div>

    </body>
</html>
