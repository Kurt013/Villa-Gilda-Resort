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
  <link rel="icon" href="images/villa-gilda-logo3.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/general.css">
  <link rel="stylesheet" type="text/css" href="styles/reserve.css">
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
  <link href="calendar.css" type="text/css" rel="stylesheet"/>
</head>
<body>

<span class="reserve-banner"> Select a date to reserve </span>

<?php
include('header.php');
include 'Calendar.php';
include 'Booking.php';
include 'BookableCell.php';
 
 
$booking = new Booking(
    'villa gilda',
    'localhost',
    'root',
    ''
);
 
$bookableCell = new BookableCell($booking);
 
$calendar = new Calendar();
 
$calendar->attachObserver('showCell', $bookableCell);
 
$bookableCell->routeActions();

echo $calendar->show();

?>
  <div class="legends">
      <p class="available">Available</p>
      <p class="reserved">Reserved</p>
  </div>

  <script>
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('bx-calendar');
    checkText.innerHTML = 'Reserve';

    const iden = document.querySelectorAll('.cell');

    for (let i=0; i < iden.length; i++) {
      if (iden[i].querySelector('.open'))
        iden[i].classList.add('hasOpen');
      else if (iden[i].querySelector('.booked'))
        iden[i].classList.add('hasBooked');

    }

    const userRole = "<?php echo $_SESSION['role']; ?>";

    const currentTabBg = document.querySelector('li:nth-child(3) .nav-admin');
    const currentTabBg2 = document.querySelector('li:nth-child(3) .nav-staff');
    const currentTabLetter = document.querySelectorAll('li:nth-child(3) .nav-block > *');

    if (userRole === "admin") {
      currentTabBg.style.backgroundColor = "#52C8C8";
    }
    else {
      currentTabBg2.style.backgroundColor = "#F4CB26";
    }

    for (let i=0; i < currentTabLetter.length; i++) {
      currentTabLetter[i].style.color = "#226060";
    }
  </script>
</body>
</html>
