<?php 
  session_start();

  if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit(); 
  }

  ob_start();
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
    <div class="form-wrapper">
      <form class="addStaff" action="" method="post">
        <div class="form-group first">
          <label for="firstName">First name:</label>
          <input type="text" name="firstName" class="firstName" id="firstName" placeholder="Enter new staff member's first name" required>
        </div>
        <div class="form-group second">
          <label for="lastName">Last name:</label>
          <input type="text" name="lastName" class="lastName" id="lastName" placeholder="Enter new staff member's last name" required>
        </div>
        <div class="form-group user">
          <label for="username">Username:</label>
          <input type="text" name="username" class="username" id="username" placeholder="Enter new staff member's username" required>
        </div>
        <div class="form-group password">
          <label for="password">Password:</label>
          <input type="password" name="password" class="password" id="password" placeholder="Enter new staff member's password" required>
        </div>
        <div class="form-group confirm-pass">
          <label for="confirm-password">Confirm Password:</label>
          <input type="password" name="confirm-password" class="confirm-password" id="confirm-password" placeholder="Re-type password" required>
        </div>
        <div class="addStaff-container">
          <input type="submit" name="addStaff" value="ADD STAFF" class="addStaff-submit">
          </div>
      </form>
    </div>

  <?php 
    include('header.php');

  /* INSERT ADMIN ACCOUNT
  Username: CelineAlmodovar01
  Password: VillaGildaResort
  RUN ONCE!!
  */

  // Establish Connection 
  $conn = new mysqli('localhost', 'root', '', 'villa gilda');

  /*
  $password = 'VillaGildaResort';
  $encryptedPass = password_hash($password, PASSWORD_BCRYPT);
  $sql = "INSERT INTO `user accounts` (`First name`, `Last name`, `Username`, `Password`, `Role`) VALUES ('Celine', 'Almodovar', 'CelineAlmodovar01', '{$encryptedPass}', 'Admin')";
  
  $conn->query($sql);


  /* Add Staffs */
  if (isset($_POST['addStaff'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $role = 'staff';

    if ($confirmPassword === $password) {
      $encryptedPass = password_hash($password, PASSWORD_BCRYPT);

      $sqlAdd = "INSERT INTO `user accounts` (`First Name`, `Last Name`, `Username`, `Password`, `Role`) VALUES ('{$firstName}', '{$lastName}', '{$username}', '{$encryptedPass}', '{$role}')";
      $conn->query($sqlAdd);

      // if ($conn->query($sqlAdd) === TRUE) {
      // Dito dialog
      // }
    }
  }

  /* Show the list of staffs*/
  $sqlShow = 'SELECT ID, `First Name`, `Last Name`, Username FROM `user accounts` WHERE Role = "staff"';
  $result = $conn->query($sqlShow);
  $numStaff = mysqli_num_rows($result);
  $number = 1;
  echo '
  <div class="staff-list">
    <div class="list-title">
      <h2>Staff List( '.$numStaff.' )</h2> <hr class="line">
    </div>
    <div class="list-body">
    ';

  while ($row = $result->fetch_assoc()) {
      echo "
      <div class='staff-card'>
        <h3>S".$number++."</h3>
        <div class='staff-info'>
          <div class='list-prof'>
            <div class='wrapper-pane staff-size'>
              <i class='bx bxs-user user-2 user-staff icon-staff-size'></i>
            </div>
            <div class='info'>
              <p>{$row['First Name']} {$row['Last Name']}</td>
              <p class='username-staff'>{$row['Username']}</p>
            </div>
          </div>
          <form class='delete' action='' method='post'>
            <input type='hidden' name='deleteID' value='{$row['ID']}'>
            <button type='submit' name='deleteButton' class='delete-button'>
              <i class='bx bxs-trash'></i>
            </button>
          </form>
        </div>
      </div>
      ";
  }

  echo '
      </div>

    </div>
  ';

  // Handle deletion logic
  if (isset($_POST['deleteButton'])) {
      $deleteID = $_POST['deleteID'];
      // Perform deletion query
      $sqlDelete = "DELETE FROM `user accounts` WHERE ID = $deleteID";
      if ($conn->query($sqlDelete) === TRUE ) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
      } else {
          echo "Error deleting record: " . $conn->error;
      }
  }

  ?>

  <script>
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('bx-user-plus');
    checkText.innerHTML = 'Add Staff';
  </script>
</body>
</html>
<?php 
  ob_end_flush();
?>