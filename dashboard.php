<?php
session_start();

if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
//$cancelled_count = isset($_SESSION['cancelled_count']) ? $_SESSION['cancelled_count'] : 0;
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
$sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = YEAR(CURDATE()) ORDER BY booking_date DESC;";
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

$earnings_annually = 0;
$sql_annual = "SELECT SUM(amount) as total_earnings FROM bookings WHERE YEAR(booking_date) = YEAR(CURDATE())";
$result_annual = $conn->query($sql_annual);
if ($result_annual && $row_annual = $result_annual->fetch_assoc()) {
    $earnings_annually = $row_annual['total_earnings'];
}
//$monthly_sales = $total_pending + $total_earnings;

$months = [];
$total_sales = [];
for ($i = 1; $i <= $current_month; $i++) {
    $months[] = date('F', mktime(0, 0, 0, $i, 10));
    $sql_sales = "SELECT SUM(amount) as total_sales FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = YEAR(CURDATE())";
    $stmt_sales = $conn->prepare($sql_sales);
    $stmt_sales->bind_param("i", $i);
    $stmt_sales->execute();
    $result_sales = $stmt_sales->get_result();
    $row_sales = $result_sales->fetch_assoc();
    $total_sales[] = $row_sales['total_sales'] ?? 0;
}

$sql_time_slots = "SELECT time_slot, COUNT(*) as count FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = YEAR(CURDATE()) GROUP BY time_slot";
$stmt_time_slots = $conn->prepare($sql_time_slots);
$stmt_time_slots->bind_param("i", $current_month);
$stmt_time_slots->execute();
$result_time_slots = $stmt_time_slots->get_result();

$time_slot_data = [];
$time_slots = ['8am - 5pm', '12nn - 8pm', '2pm - 10pm', 'overnight', '22 hours'];

foreach ($time_slots as $slot) {
    $time_slot_data[$slot] = 0; // Initialize with zero
}

while ($row = $result_time_slots->fetch_assoc()) {
    $time_slot_data[$row['time_slot']] = $row['count'];
}
/*
require 'fpdf/fpdf.php';

class reportPDF extends FPDF
{
    function Header()
    {
        global $info;
        // Display Company Info
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(50, 10, "VILLA GILDA RESORT", 0, 1);
        $this->SetFont('Arial', '', 14);
        $this->Cell(50, 7, "Brgy. Caingin", 0, 1);
        $this->Cell(50, 7, "0955 311 3451", 0, 1);

        // Display INVOICE text
        $this->SetY(15);
        $this->SetX(-60);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(50, 10, "Monthly Sales", 0, 1);

        $this->SetY(25);
        $this->SetX(-85);
        $this->Cell(50, 7, "Report Date: " . $info["report_date"]);

        // Display Horizontal line
        $this->Line(0, 48, 210, 48);
    }

    function body($info)
{
    // Set square dimensions and properties
    $squareWidth = 57;
    $squareHeight = 20;
    $startX = 10;
    $startY = 70;

    // Bordered square for Total pending payment
    $this->SetTextColor(255, 255, 255);
    $this->SetFillColor(250, 201, 0); // RGB values for '#fac900'
    $this->SetDrawColor(200, 200, 200); // Light gray border
    $this->SetLineWidth(0.2); // Border width
    $this->Rect($startX, $startY, $squareWidth, $squareHeight, 'DF');
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY($startX, $startY);
    $this->Cell($squareWidth, $squareHeight / 2, "Total pending payment", 0, 1, 'C');
    $this->SetFont('Arial', '', 12);
    $this->SetXY($startX, $startY + $squareHeight / 2);
    $this->Cell($squareWidth, $squareHeight / 2, $info["totalPending"], 0, 1, 'C');

    // Bordered square for Total Reservations
    $this->SetFillColor(12, 192, 223); // RGB values for '#fac900'
    $this->Rect($startX + $squareWidth + 10, $startY, $squareWidth, $squareHeight, 'DF');
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY($startX + $squareWidth + 10, $startY);
    $this->Cell($squareWidth, $squareHeight / 2, "Total Reservations", 0, 1, 'C');
    $this->SetFont('Arial', '', 12);
    $this->SetXY($startX + $squareWidth + 10, $startY + $squareHeight / 2);
    $this->Cell($squareWidth, $squareHeight / 2, $info["reservation"], 0, 1, 'C');

    // Bordered square for Available Days
    $this->SetFillColor(255, 145, 77); // RGB values for '#fac900'
    $this->Rect($startX + 2 * ($squareWidth + 10), $startY, $squareWidth, $squareHeight, 'DF');
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY($startX + 2 * ($squareWidth + 10), $startY);
    $this->Cell($squareWidth, $squareHeight / 2, "Available Days", 0, 1, 'C');
    $this->SetFont('Arial', '', 12);
    $this->SetXY($startX + 2 * ($squareWidth + 10), $startY + $squareHeight / 2);
    $this->Cell($squareWidth, $squareHeight / 2, $info["available"], 0, 1, 'C');

    $startX = 45;

    // Bordered square for Total Earnings
    $this->SetFillColor(0, 191, 99); // RGB values for '#fac900'
    $this->Rect($startX, $startY + $squareHeight + 10, $squareWidth, $squareHeight, 'DF');
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY($startX, $startY + $squareHeight + 10);
    $this->Cell($squareWidth, $squareHeight / 2, "Total Earnings", 0, 1, 'C');
    $this->SetFont('Arial', '', 12);
    $this->SetXY($startX, $startY + $squareHeight + 10 + $squareHeight / 2);
    $this->Cell($squareWidth, $squareHeight / 2, $info["totalEarnings"], 0, 1, 'C');

    // Bordered square for Pending Payment
    $this->SetFillColor(255, 58, 64); // RGB values for '#fac900'

    $this->Rect($startX + $squareWidth + 10, $startY + $squareHeight + 10, $squareWidth, $squareHeight, 'DF');
    $this->SetFont('Arial', 'B', 12);
    $this->SetXY($startX + $squareWidth + 10, $startY + $squareHeight + 10);
    $this->Cell($squareWidth, $squareHeight / 2, "Pending Payment", 0, 1, 'C');
    $this->SetFont('Arial', '', 12);
    $this->SetXY($startX + $squareWidth + 10, $startY + $squareHeight + 10 + $squareHeight / 2);
    $this->Cell($squareWidth, $squareHeight / 2, $info["pendingPayment"], 0, 1, 'C');
}


    function Footer()
    {
        // Set footer position
        $this->SetY(-50);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, "for VILLA GILDA RESORT", 0, 1, "R");
        $this->Ln(15);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, "Authorized Signature", 0, 1, "R");
        $this->SetFont('Arial', '', 10);

        // Display Footer Text
        $this->Cell(0, 10, "This is a computer generated report", 0, 1, "C");
    }
}
$info = [
    "report_date" => date('d-m-Y'),
    "totalPending" => $total_pending,
    "reservation" => $total_reservations,
    "available" => $available_days,
    "totalEarnings" => $total_earnings,
    "pendingPayment" => $pending_payment,
];

$reportpdf = new reportPDF();
$reportpdf->AddPage();
$reportpdf->body($info);

if (isset($_GET['generate_pdf'])) {
    $reportpdf->Output('I', 'monthly_report.pdf'); // Output to browser
    exit;
}
*/
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
    <?php include('header.php');
    ?>

    <script>
        const checkTab = document.getElementById('menu');
        const checkText = document.querySelector('.home-text');

        checkTab.classList.add('bxs-dashboard');
        checkText.innerHTML = 'Dashboard';
    </script>

    <div class="select-wrapper">
        <form class="select-month" method="POST" action="">
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
    </div>

    <!--
    <a href="?generate_pdf=true&month=<?php echo $current_month; ?>" target="_blank">Generate PDF Report</a>
    -->

    <div class="analytics">
        <div class="statistics-wrapper">
            <div class="statistics-desc">
                <!-- <h3>Statistics for <?php echo date('F', mktime(0, 0, 0, $current_month, 10)); ?></h3> -->
                <div class="box box-1">
                    <i class='bx bx-timer'></i>
                    <p>Total pending payment:</p>
                    <p><?php echo $total_pending; ?></p>
                </div>
                <div class="box box-2">
                    <i class="bx bx-calendar"></i>
                    <p>Total reservations:</p>
                    <p><?php echo $total_reservations; ?></p>
                </div>
                <div class="box box-3">
                    <i class='bx bxs-bookmark'></i>
                    <p>Total available days:</p>
                    <p><?php echo $available_days; ?></p>
                </div>
                <div class="box box-4">
                    <i class='bx bx-money'></i>
                    <p>Total earnings:</p>
                    <p><?php echo $total_earnings; ?></p>
                </div>
                <div class="box box-5">
                    <i class='bx bx-credit-card'></i>
                    <p>Pending Payment:</p>
                    <p><?php echo $pending_payment; ?></p>
                </div>
            </div>
            <div class="pie-sizing">
                <h2 class="page-header">Time Slot Chart</h2>
                <canvas id="chartjs_pie"></canvas>
            </div>
        </div>
        <div class="visual-wrapper">
            <div class="visual-desc">
                <div class="line-sizing">
                    <canvas id="chartjs_line"></canvas>
                </div>
                <div class="visual-box">
                    <i class="ri-hand-coin-fill coin"></i>
                    <p>Earnings<br> Annually</p>
                    <p><?php echo $earnings_annually; ?></p>
                </div>
            </div>
        </div>
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
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(82, 200, 200, 1)',
                    data: <?php echo json_encode($total_sales); ?>,
                    pointRadius: 5, 
                    pointBackgroundColor: 'rgba(82, 200, 200, 1)', 
                    pointBorderColor: 'rgba(82, 200, 200, 1)', 
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    onClick: (e) => e.stopPropagation(),
                    display: true,
                    position: 'top',
                    labels: {
                        fontColor: 'white',
                        fontFamily: 'Montserrat',
                        fontSize: 14,
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: 'white', // change font color here
                        },
                        gridLines: {
                            color: "#EBF0EA"
                            },
                    }],
                    yAxes: [{
                        ticks: {
                            fontColor: 'white', // change font color here
                        },
                        gridLines: {
                            color: "#EBF0EA"
                            },
                    }]
                }
            }
        });


        var ctxPie = document.getElementById("chartjs_pie").getContext('2d');
        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut', // Change from 'pie' to 'doughnut'
            data: {
                labels: <?php echo json_encode(array_keys($time_slot_data)); ?>,
                datasets: [{
                    backgroundColor: [
                        "#4EB1CB",
                        "#CF5C60",
                        "#717ECD",
                        "#4AB471",
                        "#F4CB26"
                    ],
                    hoverBackgroundColor: [
                        "rgba(78, 177, 203, 0.8)",
                        "rgba(207, 92, 96, 0.8)",
                        "rgba(113, 126, 205, 0.8)",
                        "rgba(74, 180, 113, 0.8)",
                        "rgba(244, 203, 38, 0.8)"
                    ],
                    data: <?php echo json_encode(array_values($time_slot_data)); ?>,
                }]
            },
            options: {
                cutoutPercentage: 50, // Add this option for the donut hole
                legend: {
                    onClick: (e) => e.stopPropagation(),
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: 'white',
                        fontFamily: 'Montserrat',
                        fontSize: 13,
                        fontWeight: 700
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 0 // Ensure no border around the arc
                    }
                },
            }
        });


    </script>
</body>

</html>