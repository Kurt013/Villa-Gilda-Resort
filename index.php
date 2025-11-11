<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort</title>

  <meta name="robots" content="noindex, nofollow" />

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo3.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/general.css">
  <link rel="stylesheet" type="text/css" href="styles/login.css">

  <!-- Boxicon Link -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

<!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<form method="post" class="login-form">
    <div><img src="images/villa-gilda-logo2.png" class="logo" alt="Villa Gilda Resort Logo"></div>   
    <p class='error-message visibility'>The password or username you entered is incorrect. Please try again. </p>
    
    <?php
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        displayErrorDialog();
        exit;
    }

    $conn = new mysqli(
        $_ENV['DB_HOST'] ?? 'localhost',
        $_ENV['DB_USER'] ?? 'root',
        $_ENV['DB_PASS'] ?? '',
        $_ENV['DB_NAME'] ?? 'villa gilda'
    );

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT ID, `First name`, `Last name`, Username, Password, Role FROM `user accounts` WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['Password'])) {
            session_regenerate_id(true); // prevent session fixation
            $_SESSION['ID'] = $row['ID'];
            $_SESSION['firstName'] = $row['First name'];
            $_SESSION['lastName'] = $row['Last name'];
            $_SESSION['role'] = $row['Role'];

            header("Location: homepage.php");
            exit;
        }
    }

    displayErrorDialog(); // generic error message

    $stmt->close();
    $conn->close();
}

?>  
    <div class="username-field"><input class="username" placeholder="Enter your username" type="text" name="username" readonly onfocus="this.removeAttribute('readonly');" onblur="this.setAttribute('readonly','');" required></div>
    <div class="password-field">
      <input placeholder="Enter your password" class="password" id="password" type="password" name="password" readonly onfocus="this.removeAttribute('readonly');" onblur="this.setAttribute('readonly','');" required>
      <!-- <label class="show-pass">
        <i class='bx bx-hide passwordState'></i>
        <input onclick="
          const icon = document.querySelector('.passwordState').classList;
          const text = document.getElementById('password');

          if (icon.contains('bx-show')) {
            icon.remove('bx-show');
            icon.add('bx-hide');
            text.type = 'password';
          }
          else {
            icon.remove('bx-hide');
            icon.add('bx-show');
            text.type = 'text';
          }
        " type="checkbox" class="show-pass-toggle" id="show-pass-toggle">
      </label> -->
    </div>
    <div class="login"><input class="submit-btn" type="submit" name="login" value="LOGIN"></div>
    <div class="forgot"><a class="forgot-redirect" href="forget_password.php">FORGOT YOUR PASSWORD?</a></div>

    <!-- Icons or Design for the form -->
    <div class="tree-1"><img class="tree-pos" src="elements/tree.png" alt="A tree made as a background for the border"></div>
    <div class="tree-2"><img class="tree-pos-2" src="elements/tree.png" alt="A tree made as a background for the border"></div>
    <div class="sun"></div>
    <div class="custom-shape-divider-bottom-1717433957">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
      </svg>
    </div>
  </form>
  <?php
  //   $conn = new mysqli('localhost', 'root', '', 'villa gilda');

  
  // $password = 'VillaGildaResort';
  // $encryptedPass = password_hash($password, PASSWORD_BCRYPT);
  // $sql = "INSERT INTO `user accounts` (`First name`, `Last name`, `Username`, `Password`, `Role`) VALUES ('Celine', 'Almodovar', 'CelineAlmodovar01', '{$encryptedPass}', 'admin')";
  
  // $conn->query($sql);
  function displayErrorDialog() {
    echo "<script>
        errorMessage = document.querySelector('.error-message');
        errorMessage.classList.remove('visibility');
        </script>";
  }
  ?>
  <script src="login.js"></script>
</body>
</html>
<?php 
  
?>