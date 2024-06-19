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
  <link rel="stylesheet" type="text/css" href="styles/ourlist.css">
  <link rel="stylesheet" type="text/css" href="styles/header.css">

  <!-- Boxicon Link -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

  <!-- Remixicon Link -->
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css"
    rel="stylesheet"
  />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <?php 
    include('header.php');
    require 'fpdf/fpdf.php';
  ?>
  <script>
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('ri-menu-3-line');
    checkText.innerHTML = 'Reservation List';
  </script>

<?php
// Define the current month
$current_month = date('n');

// Initialize other variables
$resultCheck = 0; // Initialize result check
$selected_month = $current_month;
$cancelled_count = 0;
$deleted_count = 0;

if (isset($_POST['month'])) {
    // If month is selected, filter bookings for that month
    $selected_month = $_POST['month'];
    $_POST['selected_month'] = $selected_month; // Store the selected month
    $sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = $selected_month ORDER BY booking_date DESC;";
} else {
    // If form is not submitted, default to current month
    $selected_month = isset($_POST['selected_month']) ? $_POST['selected_month'] : $current_month; // Get the stored or current month
    $sql = "SELECT * FROM bookings WHERE MONTH(booking_date) = $selected_month ORDER BY booking_date DESC;";
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

$result = mysqli_query($conn, $sql);
if ($result) {
    // Get the number of rows in the result set
    $resultCheck = mysqli_num_rows($result);
}

class PDF extends FPDF
{
    function Header(){
        // Display Company Info
        $this->SetFont('Arial','B',14);
        $this->Cell(50,10,"VILLA GILDA RESORT",0,1);
        $this->SetFont('Arial','',14);
        $this->Cell(50,7,"Brgy. Caingin",0,1);
        $this->Cell(50,7,"0955 311 3451",0,1);

        // Display INVOICE text
        $this->SetY(15);
        $this->SetX(-40);
        $this->SetFont('Arial','B',18);
        $this->Cell(50,10,"RECEIPT",0,1);

        // Display Horizontal line
        $this->Line(0,48,210,48);
    }

    function body($info, $products_info){
        // Billing Details
        $this->SetY(55);
        $this->SetX(10);
        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,"Bill To: ",0,1);
        $this->SetFont('Arial','',12);
        $this->Cell(50,7,$info["Name"],0,1);
        $this->Cell(50,7,$info["contactNo"],0,1);

        // Display Invoice no
        $this->SetY(55);
        $this->SetX(-60);
        $this->Cell(50,7,"Invoice No : ".$info["invoice_no"]);

        // Display Invoice date
        $this->SetY(63);
        $this->SetX(-60);
        $this->Cell(50,7,"Invoice Date : ".$info["invoice_date"]);

        // Display Table headings
        $this->SetY(95);
        $this->SetX(10);
        $this->SetFont('Arial','B',12);
        $this->Cell(80,9,"RESERVATION DATE",1,0);
        $this->Cell(60,9,"TIME SLOT",1,0,"C");
        $this->Cell(50,9,"INCLUSION",1,0,"C");
        $this->SetFont('Arial','',12);

        // Display table product rows
        foreach($products_info as $row){
            $this->Ln();
            $this->Cell(80,9,$row["booking_date"],"LR",0);
            $this->Cell(60,9,$row["time_slot"],"R",0,"C");
            $this->Cell(50,9,$row["included"],"R",0,"C");
        }
        // Display table empty rows
        for($i=0;$i<12-count($products_info);$i++)
        {
            $this->Ln();
            $this->Cell(80,9,"","LR",0);
            $this->Cell(60,9,"","R",0,"C");
            $this->Cell(50,9,"","R",0,"C");
        }
        // Display table total row
        $this->Ln();
        $this->SetFont('Arial','B',12);
        $this->Cell(140,9,"TOTAL",1,0,"R");
        $this->Cell(50,9, 'Php '.$info["amount"],1,1,"R");
    }

    function Footer(){
        // Set footer position
        $this->SetY(-50);
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,"for VILLA GILDA RESORT",0,1,"R");
        $this->Ln(15);
        $this->SetFont('Arial','',12);
        $this->Cell(0,10,"Authorized Signature",0,1,"R");
        $this->SetFont('Arial','',10);

        // Display Footer Text
        $this->Cell(0,10,"This is a computer generated invoice",0,1,"C");
    }
}


if (isset($_POST['status']) && isset($_POST['booking_id'])) {
    $status = $_POST['status'];
    $booking_id = $_POST['booking_id'];
    $cancelled_bookings = array();
    $update_sql = "UPDATE bookings SET status = '$status' WHERE id = $booking_id;";
    if ($status == 'cancelled') {
        $delete_sql = "DELETE FROM bookings WHERE id = $booking_id;";
        if ($conn->query($delete_sql) === TRUE) {
            // Increment the cancelled count
            $cancelled_count++;
            $deleted_count++;
        // Store cancelled booking information
        $cancelled_booking_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bookings WHERE id = $booking_id"));
        array_push($cancelled_bookings, $cancelled_booking_info);
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        // If status is fully paid, update the status in the database
        $update_sql = "UPDATE bookings SET status = '$status' WHERE id = $booking_id;";
        $conn->query($update_sql);
    }

    // If status is fully paid, generate the invoice
    if ($status == 'fully paid') {

        $result_invoice = $conn->query("SELECT * FROM bookings WHERE id = $booking_id");
        $row_invoice = $result_invoice->fetch_assoc();

        $info = [
            "Name" => $row_invoice["lastName"] . ', ' . $row_invoice["firstName"],
            "contactNo" => $row_invoice["contactNo"],
            "invoice_date" => date('d-m-Y'),
            "invoice_no" => str_pad($row_invoice["id"], 7, '0', STR_PAD_LEFT),
            "amount" => $row_invoice["amount"],
        ];

        $products_info = [
            [
                "booking_date" => $row_invoice["booking_date"],
                "time_slot" => $row_invoice["time_slot"],
                "included" => $row_invoice["included"],
            ]
        ];

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->body($info, $products_info);

        $invoice_filename = 'invoice_'.$row_invoice["id"].'.pdf';
        $pdf->Output('F', $invoice_filename);
    }
}
?>


<form class="select-month" method="POST" action="">
    <label for="month">Select Month:</label>
    <select id="month" name="month" onchange="this.form.submit()">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $selected = ($i == $selected_month) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.date('F', mktime(0, 0, 0, $i, 10)).'</option>';
        }
        ?>
    </select>
    <input type="hidden" name="selected_month" value="<?php echo $selected_month; ?>">
</form>

<?php
 $number = 1;
 $child = 1;

    if ($resultCheck > 0){
?>
<table id="myTable">
    <thead>
    <tr class="headerRow">
        <th>#</th>
        <th>Name</th>
        <th>Booking Date</th>
        <th class="mobile">Time Slot</th>
        <th class="tablet">Inclusion</th>
        <th class="tablet">Contact Number</th>
        <th class="tablet">Email</th>
        <th class="tablet">Total Amount</th>
        <th class="tablet">Status</th>
        <th class="tablet">Receipt</th>
    </tr>
    </thead>
    <tbody>
    <?php
        while ($row = mysqli_fetch_assoc($result)){
            echo '<tr class="row">
                <td class="id"><button class="btn toggle-'.$child.'" onclick="toggleSub('.$child.');">+</button><span>'.$number++. '</span></td>
                <td class="name">'.$row["lastName"] . ", " . $row["firstName"].'</td>
                <td>'.$row["booking_date"].'</td>
                <td class="time mobile">'.$row["time_slot"].'</td>
                <td class="tablet">'.$row["included"].'</td>
                <td class="tablet">'.$row["contactNo"].'</td>
                <td class="email tablet">'.$row["email"].'</td>
                <td class="tablet">'.$row["amount"].'.00</td>
                <td class="tablet">
                    <form method="POST" action="">
                        <input type="hidden" name="booking_id" value="'.$row["id"].'">
                        <input type="hidden" name="month" value="'.$selected_month.'">
                        <label for="status"></label>
                        <select id="status" name="status" onchange="this.form.submit()">
                            <option value="pending" '.($row["status"] == "pending" ? "selected" : "").'>Pending</option>
                            <option value="fully paid" '.($row["status"] == "fully paid" ? "selected" : "").'>Fully Paid</option>
                            <option value="cancelled" '.($row["status"] == "cancelled" ? "selected" : "").'>Cancelled</option>
                        </select>
                </form>
                </td>
                <td class="tablet">';
            if ($row["status"] === "fully paid") {
                echo '<a href="invoice_'.$row["id"].'.pdf" target="_blank"><i class="ri-receipt-fill fully-paid"></i></a>';
            }
            else {
                echo "<i class='ri-receipt-fill pending'></i>";
            }


            echo'</td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td class="slot" colspan="2">
                    Time Slot:
                </td>
                <td class="slot" colspan="3">
                    '.$row["time_slot"].'
                </td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td colspan="2">
                    Inclusion:
                </td>
                <td colspan="3">
                    '.$row["included"].'
                </td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td colspan="2">
                    Contact Number:
                </td>
                <td colspan="3">
                    '.$row["contactNo"].'
                </td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td colspan="2">
                    Email:
                </td>
                <td colspan="3">
                    '.$row["email"].'
                </td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td colspan="2">
                    Total Amount:
                </td>
                <td colspan="3">
                    '.$row["amount"].'.00
                </td>
            </tr>
            <tr class="child num-'.$child.' hidden">
                <td colspan="2">
                    Status:
                </td>
                <td colspan="3">
                    <form method="POST" action="">
                        <input type="hidden" name="booking_id" value="'.$row["id"].'">
                        <input type="hidden" name="month" value="'.$selected_month.'">
                        <label for="status"></label>
                        <select id="status" name="status" onchange="this.form.submit()">
                            <option value="pending" '.($row["status"] == "pending" ? "selected" : "").'>Pending</option>
                            <option value="fully paid" '.($row["status"] == "fully paid" ? "selected" : "").'>Fully Paid</option>
                            <option value="cancelled" '.($row["status"] == "cancelled" ? "selected" : "").'>Cancelled</option>
                        </select>
                    </form>
                </td>
            <tr class="child num-'.$child++.' hidden">
                <td colspan="2">
                    Receipt:
                </td>
                <td colspan="3">';
                    if ($row["status"] == "fully paid") {
                    echo '<a href="invoice_'.$row["id"].'.pdf" target="_blank"><i class="ri-receipt-fill fully-paid"></i></a>';
                }
                else {
                    echo "<i class='ri-receipt-fill pending'></i>";
                }
                echo'</td>
            </tr>';
        }
    } else {
        echo '
            <div class="no-reservation-content">
                <img src="elements/no-reservation-pic.png" class="no-reservation" alt="Cluster of seashells">
                <h1>No Reservations Found</h1>
                <p>It looks like there are no reservations made for the month you selected.</p>
                <a class="reserve-now" href="reserve.php">RESERVE NOW</a>
            </div>
        ';
    }
    ?>
    </body>
</table>
<script>
$(document).ready(function(){
    $('select[name="status"]').change(function(){
        var booking_id = $(this).closest('form').find('input[name="booking_id"]').val();
        var month = $(this).closest('form').find('input[name="month"]').val();
        var status = $(this).val();
        
        console.log("Booking ID: " + booking_id + ", Month: " + month + ", Status: " + status); 
        
        $.ajax({
            url: '', 
            method: 'POST',
            data: {booking_id: booking_id, status: status},
            success: function(response){
                console.log('Status updated successfully.');
            },
            error: function(xhr, status, error){
                console.error('Error occurred while updating status: ' + error);
            }
        });
    });
});


function toggleSub(n) {
    let selectorName = '.num-' + n;
    const btn = document.querySelectorAll(selectorName);

    let maximize = '.toggle-' + n;
    const maxBtn = document.querySelectorAll(maximize);

    for (let i=0; i < btn.length; i++) {
        btn[i].classList.toggle('hidden');
    }

    for (let i=0; i < maxBtn.length; i++) {
        maxBtn[i].classList.toggle('minimize');

        if (maxBtn[i].innerHTML === '+')
            maxBtn[i].innerHTML = '-';
        else
            maxBtn[i].innerHTML = '+';
    }
}

function onPageReloadOrResize() {
    let screenWidth = window.innerWidth;
    const colspan = document.querySelectorAll('.name');
    const contentHeader = document.querySelector('.headerRow');

    const btn = document.querySelectorAll('.btn');

    if (screenWidth >= 992) {
        for (let i=0; i < btn.length; i++) {
            btn[i].classList.add('hidden');
        }
    }
    else {
        for (let i=0; i < btn.length; i++) {
            btn[i].classList.remove('hidden');
        }
    }


    if (screenWidth >= 768) {
        for (let i=0; i < colspan.length; i++) {
            colspan[i].colSpan = '1';
        }
        contentHeader.innerHTML = `
            <th>#</th>
            <th>Name</th>
            <th>Booking Date</th>
            <th class="mobile">Time Slot</th>
            <th class="tablet">Inclusion</th>
            <th class="tablet">Contact Number</th>
            <th class="tablet">Email</th>
            <th class="tablet">Total Amount</th>
            <th class="tablet">Status</th>
            <th class="tablet">Receipt</th>
        `;
    }
    else {
        for (let i=0; i < colspan.length; i++) {
            colspan[i].colSpan = '2';
        }
        contentHeader.innerHTML = `
            <th>#</th>
            <th>Name</th>
            <th></th>
            <th>Booking Date</th>
            <th class="mobile">Time Slot</th>
        `;
    }
}

window.addEventListener('load', onPageReloadOrResize);

window.addEventListener('resize', onPageReloadOrResize);
</script>
</body>
</html>
