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
  <link rel="stylesheet" type="text/css" href="styles/homepage.css">
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
</head>
<body>
  <?php 
    include('header.php');
  ?>
    <div class="desc-content">
      <div class="content-wrapper">
        <img class="home-logo" src="images/villa-gilda-logo3.png" alt="Villa Gilda Logo">
        <h1>Good day, <?php echo ($_SESSION['role'] == 'admin') ?
                              "<span style='color: #F4D248;'>{$_SESSION['firstName']}&nbsp;!</span>" : "<span style='color: #52C8C8;'>{$_SESSION['firstName']}&nbsp;!</span>"; ?></h1>
                      <?php echo ($_SESSION['role'] == 'admin') ?
                      "<p>Ready to get started? Manage reservations, update bookings, and oversee resort operations in just a few clicks.</p>" :
                      "<p>Ready to get started? Manage bookings with ease and create new reservations.</p>"?>
      </div>
<?php 
        if ($_SESSION['role'] == 'admin') {
          echo"
          <div><a href='dashboard.php' class='redirect-button'>MONITOR DASHBOARD</a></div>
          ";
        }
    ?>  
      <div><a href="reserve.php" class="redirect-button">MANAGE RESERVATIONS</a></div>
      <div><a href="ourlist.php" class="redirect-button">SEE RESERVATION LIST</a></div>  
    </div>
    <div class="homepage-images">
      <img class="resort-image" src="images/homepage.png" alt="resort-place">
    </div>

    <div class="border-wrapper">
        <div class="yellow-border"></div>
        <div class="blue-border"></div>
      </div>

  <script>
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('bx-home');
    checkText.innerHTML = 'Home';

    const userRole = "<?php echo $_SESSION['role']; ?>";

    const currentTabBg = document.querySelector('li:nth-child(1) .nav-admin');
    const currentTabBg2 = document.querySelector('li:nth-child(1) .nav-staff');
    const currentTabLetter = document.querySelectorAll('li:nth-child(1) .nav-block > *');

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
