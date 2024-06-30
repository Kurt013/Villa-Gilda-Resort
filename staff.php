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
  <link rel="icon" href="images/villa-gilda-logo3.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="styles/general.css">
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
    <div class="form-wrapper">
      <form class="addStaff" method="post">
      <div class="field-wrapper">
          <div class="form-group first">
            <label for="firstName">First name:</label>
            <input type="text" name="firstName" class="firstName" id="firstName" placeholder="Enter new staff member's first name" required>
          </div>
          <div class="form-group second">
            <label for="lastName">Last name:</label>
            <input type="text" name="lastName" class="lastName" id="lastName" placeholder="Enter new staff member's last name" required>
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" class="email" id="email" placeholder="Enter new staff member's email" required>
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
      </div>
        <div class="addStaff-container">
          <input type="submit" name="addStaff" value="ADD STAFF" class="addStaff-submit">
        </div>
      </form>
    </div>

<!-- 
  <dialog class="confirm-delete" id="confirmDeleteDialog" open>
    <div class="header-delete">
      <h1>Confirm Delete</h1>
      <button id="exitDialog" class="exit"><i class="bx bx-x"></i></button>
    </div>
    <div class="body-delete">
      <div class="first-half">
        <div class="exclamation">
          !
        </div>
      </div>
      <div class="second-half">
        <div class="first-div">
          <p class="content" id="deleteConfirmationText">Are you sure you want to delete the staff member with username 'username'?</p>
        </div>
        <div class="second-div">
          <button id="cancelDelete" class="exit">NO</button>
          <form id="deleteForm" method="post">
            <input type="hidden" name="deleteID" id="deleteID">
            <button type="submit" name="delete-confirm" value="delete" class="delete-confirm">DELETE</button>
          </form>
        </div>
      </div>
    </div>
  </dialog> -->

  <?php 

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
  function is_valid_password($password) {
    // Length check
    if (strlen($password) < 8) {
        return false;
    }
    
    // Character types check
    if (!preg_match('/[A-Za-z]/', $password) || // contains at least one letter
        !preg_match('/\d/', $password) ||      // contains at least one number
        !preg_match('/[^A-Za-z\d]/', $password) // contains at least one special character
    ) {
        return false;
    }
    
    return true;
}
  if (isset($_POST['addStaff'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $role = 'staff';

    if ($confirmPassword === $password) {
      if (is_valid_password($password)){
        $encryptedPass = password_hash($password, PASSWORD_BCRYPT);

        $sqlAdd = "INSERT INTO `user accounts` (`First Name`, `Last Name`, `Username`, `Password`, `email`, `Role`) VALUES ('{$firstName}', '{$lastName}', '{$username}', '{$encryptedPass}', '{$email}', '{$role}')";
        try {
          $conn->query($sqlAdd);
        }
        catch(mysqli_sql_exception $e) {

        }
        // if ($conn->query($sqlAdd) === TRUE) {
        // Dito dialog
        // }
    }else {
      echo "<div class='error-message'>Password must be 8 characters or more, and include letters, numbers, and special characters</div>";
    }
  } else {
    echo "<div class='error-message'>Password do not match!</div>";
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
      <h2>Staff List ( '.$numStaff.' )</h2> <hr class="line">
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
          <form class='delete' method='post'>
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
      if (!$conn->query($sqlDelete))
        echo "Error deleting record: " . $conn->error;
      header("Refresh: 0");
      exit;
    }
  ?>
  <script src="popup.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
    const checkTab = document.getElementById('menu');
    const checkText = document.querySelector('.home-text');

    checkTab.classList.add('bx-user-plus');
    checkText.innerHTML = 'Add Staff';
    });

    /* Add Active State */
    const userRole = "<?php echo $_SESSION['role']; ?>";

    const currentTabBg = document.querySelector('li:nth-child(5) .nav-admin');
    const currentTabBg2 = document.querySelector('li:nth-child(5) .nav-staff');
    const currentTabLetter = document.querySelectorAll('li:nth-child(5) .nav-block > *');

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
<?php 
  ob_end_flush();
?>