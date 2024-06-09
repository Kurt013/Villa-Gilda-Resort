<?php
session_start();

if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Gilda Resort</title>

    <!-- Favicon -->
    <link rel="icon" href="images/villa-gilda-logo.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="styles/dashboard.css">
    <link rel="stylesheet" type="text/css" href="styles/header.css">

    <!-- Boxicon Link -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Remixicon Link -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include('header.php');
    ?>
    <script>
        const checkTab = document.getElementById('menu');
        const checkText = document.querySelector('.home-text');

        checkTab.classList.add('bxs-dashboard');
        checkText.innerHTML = 'Dashboard';
    </script>

    <?php

    $current_month = date('n');

    // Initialize other variables
    $resultCheck = 0; // Initialize result check
    $selected_month = $current_month;

    if (isset($_POST['month'])) {
        // If month is selected, filter bookings for that month
        $selected_month = $_POST['month'];
        $_POST['selected_month'] = $selected_month; // Store the selected month
        $sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = ? ORDER BY booking_date DESC;";
    } else {
        // If form is not submitted, default to current month
        $selected_month = isset($_POST['selected_month']) ? $_POST['selected_month'] : $current_month; // Get the stored or current month
        $sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = ? ORDER BY booking_date DESC;";
    }

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "villa gilda";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_month);

    // Execute SQL statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result) {
        // Get the number of rows in the result set
        $resultCheck = $result->num_rows;
    }

    ?>
    <form method="POST" action="">
        <label for="month">Select Month:</label>
        <select id="month" name="month" onchange="this.form.submit()">
            <?php
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($i == $selected_month) ? 'selected' : '';
                echo '<option value="' . $i . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $i, 10)) . '</option>';
            }
            ?>
        </select>
        <input type="hidden" name="selected_month" value="<?php echo $selected_month; ?>">
    </form>

    <?php
    $total_pending = 0;
    $total_reservations = 0;
    $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $selected_month, date('Y'));
    $pending_payment = 0;
    $total_earnings = 0;
    if ($resultCheck > 0) {
        $reserved_days = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['status'] == 'pending') { // Assuming the status column is 'status'
                $total_pending++;
                $pending_payment += $row['balance_amount'];
            }
            else if ($row['status'] == 'fully paid'){
                $total_earnings += $row['amount'];
                $total_earnings += $row['deposit_amount'];
            }
            $reserved_days[date('j', strtotime($row['booking_date']))] = true;
            $total_reservations++;
        }
        $available_days = $total_days_in_month - count($reserved_days);
    } else {
        $available_days = $total_days_in_month;
    }

    echo "Total pending payment: $total_pending<br>";
    echo "Total reservations: $total_reservations<br>";
    echo "Total available days: $available_days <br>";
    echo "Total eanings: $total_earnings <br>";
    echo "Pending Payment: $pending_payment <br>";

    ?>
</body>

</html>
