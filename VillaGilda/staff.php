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
  <link rel="stylesheet" type="text/css" href="styles/staff.css">
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
  <script>
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('bx-user-plus');
    checkText.innerHTML = 'Add Staff';
  </script>
</body>
</html>
