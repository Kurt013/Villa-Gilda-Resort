<?php
session_start();

if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit(); 
  }

$current_month = isset($_POST['month']) ? $_POST['month'] : date('n');

// Initialize variables
$total_pending = 0;
$total_reservations = 0;
$total_earnings = 0;
$pending_payment = 0;
$reserved_days = [];

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

// Prepare and execute SQL statement to retrieve data for the selected month
$sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = ? ORDER BY booking_date DESC;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_month);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    // Get the number of rows in the result set
    $resultCheck = $result->num_rows;
}

$total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, date('Y'));


if ($resultCheck > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 'pending') {
            $total_pending++;
            $pending_payment += $row['balance_amount'];
            $total_earnings += $row['deposit_amount'];
        } else if ($row['status'] == 'fully paid') {
            $total_earnings += $row['amount'];
        }
        $reserved_days[date('j', strtotime($row['booking_date']))] = true;
        $total_reservations++;
    }
    $available_days = $total_days_in_month - count($reserved_days);
} else {
    $available_days = $total_days_in_month;
}

//$monthly_sales = $total_pending + $total_earnings;

$months = [];
$total_sales = [];
for ($i = 1; $i <= date('n'); $i++) {
    $months[] = date('F', mktime(0, 0, 0, $i, 10));
    $sql_sales = "SELECT SUM(amount) as total_sales FROM bookings WHERE MONTH(booking_date) = ?";
    $stmt_sales = $conn->prepare($sql_sales);
    $stmt_sales->bind_param("i", $i);
    $stmt_sales->execute();
    $result_sales = $stmt_sales->get_result();
    $row_sales = $result_sales->fetch_assoc();
    $total_sales[] = $row_sales['total_sales'] ?? 0;
}

$conn->close();
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
    <?php include('header.php'); ?>

    <script>
        const checkTab = document.getElementById('menu');
        const checkText = document.querySelector('.home-text');

        checkTab.classList.add('bxs-dashboard');
        checkText.innerHTML = 'Dashboard';
    </script>

    <form method="POST" action="">
        <label for="month">Select Month:</label>
        <select id="month" name="month" onchange="this.form.submit()">
            <?php
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($i == $current_month) ? 'selected' : '';
                echo '<option value="' . $i . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $i, 10)) . '</option>';
            }
            ?>
        </select>
    </form>

    <div>
        <h3>Statistics for <?php echo date('F', mktime(0, 0, 0, $current_month, 10)); ?></h3>
        <p>Total pending payment: <?php echo $total_pending; ?></p>
        <p>Total reservations: <?php echo $total_reservations; ?></p>
        <p>Total available days: <?php echo $available_days; ?></p>
        <p>Total earnings: <?php echo $total_earnings; ?></p>
        <p>Pending Payment: <?php echo $pending_payment; ?></p>
    </div>

    <div style="width:50%;height:20%;text-align:center">
        <h2 class="page-header">Analytics Sales Report</h2>
        <canvas id="chartjs_line"></canvas>
    </div>

    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
        var ctx = document.getElementById("chartjs_line").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    backgroundColor: 'rgba(89, 105, 255, 0.5)',
                    borderColor: 'rgba(89, 105, 255, 1)',
                    data: <?php echo json_encode($total_sales); ?>,
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#71748d',
                        fontFamily: 'Circular Std Book',
                        fontSize: 14,
                    }
                },
            }
        });
    </script>
</body>

</html>
