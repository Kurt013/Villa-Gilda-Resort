<?php
session_start();
$message = "";
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'villa gilda'; // Adjusted to remove the space
$dbconfig = mysqli_connect($host, $username, $password, $database);

if (!$dbconfig) {
    die("An error occurred when connecting to the database: " . mysqli_connect_error());
}

// Handle verification code submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_verification_code'])) {
    $key = $_POST['verification_code'];
    $email = $_SESSION['email'];
    $check = mysqli_query($dbconfig, "SELECT * FROM forget_password WHERE email='$email' and temp_key='$key'");

    if (!$check) {
        $message = "Database query failed: " . mysqli_error($dbconfig);
    } else if (mysqli_num_rows($check) != 1) {
        $message = "Invalid verification code.";
        header('Location: forget_password.php');
        exit;
      } else {
        $_SESSION['verification_code'] = $key;
        $message_success = "Verification code accepted. Please enter your new password.";
    }
}

// Handle password reset submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  if (isset($_SESSION['email']) && isset($_SESSION['verification_code'])) {
      $email = $_SESSION['email'];
      $key = $_SESSION['verification_code'];

      $password1 = mysqli_real_escape_string($dbconfig, $_POST['password1']);
      $password2 = mysqli_real_escape_string($dbconfig, $_POST['password2']);

      if ($password1 == $password2) {
          $result = mysqli_query($dbconfig, "SELECT * FROM `user accounts` WHERE `email`='$email'");
          if ($result && mysqli_num_rows($result) == 1) {
              $row = mysqli_fetch_assoc($result);
              // Verify if the entered password matches the stored hashed password
              if (password_verify($password1, $row['Password'])) {
                  $message = "You cannot reuse the same password.";
              } else {
                  // Proceed with updating the password
                  $password_hash = password_hash($password1, PASSWORD_DEFAULT);
                  $delete_query = mysqli_query($dbconfig, "DELETE FROM forget_password WHERE email='$email' AND temp_key='$key'");
                  $update_query = mysqli_query($dbconfig, "UPDATE `user accounts` SET `Password`='$password_hash' WHERE `email`='$email'");

                  if ($update_query) {
                      $message_success = "New password has been set for " . $email;
                      session_unset();
                      session_destroy();
                  } else {
                      $message = "Error updating password: " . mysqli_error($dbconfig);
                  }
              }
          } else {
              $message = "Error fetching user data.";
          }
      } else {
          $message = "Passwords do not match.";
      }
  } else {
      $message = "Session expired or invalid. Please try again.";
  }
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort || Reset Password</title>

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/forgot_password_reset.css">

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

<div class="container">
    <div class="row">   
        <div class="col-md-4">
            <form role="form" method="POST">
                <label>Please enter your new password</label><br><br>
                <div class="form-group">
                    <input type="password" class="form-control" id="pwd" name="password1" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="pwd" name="password2" placeholder="Re-type Password">
                </div>
                <?php if ($message != "") {
                    echo "<div class='alert alert-danger' role='alert'>
                    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                    <span class='sr-only'>Error:</span>" . $message . "</div>";
                } ?>
                <?php if (isset($message_success)) {
                    echo "<div class='alert alert-success' role='alert'>
                    <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
                    <span class='sr-only'>Success:</span>" . $message_success . "</div>";
                } ?>
                <button type="submit" class="btn btn-primary pull-right" name="submit" style="display: block; width: 100%;">Save Password</button>
                <label>This link will work only once for a limited time period.</label>
                <div><a href="index.php">Back to Login</a></div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
