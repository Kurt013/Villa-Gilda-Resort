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
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

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

  <?php 
  // Establish Connection 
  $conn = new mysqli('localhost', 'root', '', 'villa gilda');

  function is_valid_password($password) {
    if (strlen($password) < 8 ||
        !preg_match('/[A-Za-z]/', $password) ||
        !preg_match('/\d/', $password) ||
        !preg_match('/[^A-Za-z\d]/', $password)) {
        return false;
    }
    return true;
  }

  function is_valid_name($name) {
    return strlen($name) >= 3 && strlen($name) <= 20;
  }

  if (isset($_POST['addStaff'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $role = 'staff';

    $emailCheck = $conn->prepare("SELECT * FROM `user accounts` WHERE `email` = ?");
    $emailCheck->bind_param("s", $email);
    $emailCheck->execute();
    $emailResult = $emailCheck->get_result();

    $usernameCheck = $conn->prepare("SELECT * FROM `user accounts` WHERE `Username` = ?");
    $usernameCheck->bind_param("s", $username);
    $usernameCheck->execute();
    $usernameResult = $usernameCheck->get_result();

    if ($confirmPassword === $password) {
      if (is_valid_password($password)) {
        $validName = true;
        if (!is_valid_name($firstName)) {
          echo '
           <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>First name must be between 3 and 20 characters.</p>
                  </div>
                  <button onclick="closeDialog()" onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          $validName = false;
        }
        if (!is_valid_name($lastName)) {
          echo '
          <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>Last name must be between 3 and 20 characters.</p>
                  </div>
                  <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          $validName = false;
        }
        if ($emailResult->num_rows > 0) {
          echo '
          <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>Email already exists!</p>
                  </div>
                  <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          $validName = false;
        }
        if ($usernameResult->num_rows > 0) {
          echo '
          <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>Username already exists!</p>
                  </div>
                  <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          $validName = false;
        }
        if ($validName) {
          $encryptedPass = password_hash($password, PASSWORD_BCRYPT);
          $sqlAdd = $conn->prepare("INSERT INTO `user accounts` (`First Name`, `Last Name`, `Username`, `Password`, `email`, `Role`) VALUES (?, ?, ?, ?, ?, ?)");
          $sqlAdd->bind_param("ssssss", $firstName, $lastName, $username, $encryptedPass, $email, $role);
          try {
            if ($sqlAdd->execute()) {
              echo'
              <dialog class="message-popup success" >
                <div class="pop-up">
                  <div class="left-side">
                    <div class="left-side-wrapper"><i class="bx bxs-check-circle success-circle"></i></div>
                  </div>
                  <div class="right-side">
                    <div class="right-group">
                      <div class="content">
                        <h1>Success</h1>
                        <p>Staff successfully added</p>
                      </div>
                      <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                    </div>
                  </div>
                </div>
              </dialog>
              ';
            }
          } catch (mysqli_sql_exception $e) {
            echo '
             <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>Email already exists!</p>
                  </div>
                  <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          $validName = false;
        }
        if ($usernameResult->num_rows > 0) {
          echo '
          <dialog class="message-popup error" >
            <div class="pop-up">
              <div class="left-side">
                <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
              </div>
              <div class="right-side">
                <div class="right-group">
                  <div class="content">
                    <h1>Invalid</h1>
                    <p>Error: ' . $e->getMessage() . '</p>
                  </div>
                  <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                </div>
              </div>
            </div>
          </dialog>
          ';
          }
        }
      }
      else {
        echo '
          <dialog class="message-popup error" >
              <div class="pop-up">
                <div class="left-side">
                  <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
                </div>
                <div class="right-side">
                  <div class="right-group">
                    <div class="content">
                      <h1>Invalid</h1>
                      <p>Password must be 8 characters or more, and include letters, numbers, and special characters.</p>
                    </div>
                    <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                  </div>
                </div>
              </div>
            </dialog>
            ';
      }
    } 
    else {
      echo '
            <dialog class="message-popup error" >
              <div class="pop-up">
                <div class="left-side">
                  <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
                </div>
                <div class="right-side">
                  <div class="right-group">
                    <div class="content">
                      <h1>Invalid</h1>
                      <p>Passwords do not match!</p>
                    </div>
                    <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                  </div>
                </div>
              </div>
            </dialog>
            ';
    }
  }

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
          <div class='delete'>
            <input type='hidden' name='deleteID' value='{$row['ID']}'>
            <button type='button' onclick='openDialog(this)' class='delete-button'>
              <i class='bx bxs-trash'></i>
            </button>
          </div>
        </div>
      </div>
      ";
  }

  echo '
    </div>
  </div>
  ';

  if (isset($_POST['deleteButton'])) {
    $deleteID = $_POST['deleteID'];
    $sqlDelete = "DELETE FROM `user accounts` WHERE ID = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $deleteID);
    if ($stmt->execute()) {
      header("Refresh: 0");
      exit;
    } else {
      echo "Error deleting record: " . $conn->error;
    }
  }
  ?>

  <dialog class="confirm-popup">
    <div class="confirm-header">
      <h1>Confirm Deletion</h1>
      <button class="exit-btn" onclick="closeDialog()"><i class="bx bx-x"></i></button>
    </div>
    <div class="confirm-body">
      <div class="left-confirm-body">
        <p>!</p>
      </div>
      <div class="right-confirm-body">
        <p>Are you sure you want to delete this staff member?</p>
      </div>
    </div>
    <div class="confirm-footer">
      <button class="cancel-btn" onclick="closeDialog()">No</button>
      <form id="confirmDeleteForm" method="post">
        <input type="hidden" name="deleteID">
        <button type="submit" name="deleteButton" class="delete-btn">Delete</button>
      </form>
    </div>
  </dialog>

  <script src="popup.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const checkTab = document.getElementById('menu');
      const checkText = document.querySelector('.home-text');

      checkTab.classList.add('bx-user-plus');
      checkText.innerHTML = 'Add Staff';

      /* Add Active State */
      const userRole = "<?php echo $_SESSION['role']; ?>";
      const currentTabBg = document.querySelector('li:nth-child(5) .nav-admin');
      const currentTabBg2 = document.querySelector('li:nth-child(5) .nav-staff');
      const currentTabLetter = document.querySelectorAll('li:nth-child(5) .nav-block > *');

      if (userRole === "admin") {
        currentTabBg.style.backgroundColor = "#52C8C8";
      } else {
        currentTabBg2.style.backgroundColor = "#F4CB26";
      }

      for (let i = 0; i < currentTabLetter.length; i++) {
        currentTabLetter[i].style.color = "#226060";
      }
    });
  </script>
</body>
</html>
<?php 
  ob_end_flush();
?>
