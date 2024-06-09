<?php 
  session_start();
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
  <link rel="stylesheet" type="text/css" href="styles/login.css">

  <!-- Boxicon Link -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);
  ?>" method="post" class="login-form">
    <div><img src="images/villa-gilda-logo2.png" class="logo" alt="Villa Gilda Resort Logo"></div>
    <div class="username-field"><input class="username" placeholder="Enter your username" type="text" name="username" required></div>
    <div class="password-field">
      <input placeholder="Enter your password" class="password" id="password" type="password" name="password" required>
      <label class="show-pass">
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
      </label>
    </div>
    <div class="login"><input class="submit-btn" type="submit" name="login" value="LOGIN"></div>
    <div class="forgot"><a class="forgot-redirect" href="#">FORGOT YOUR PASSWORD?</a></div>

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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //Allows username or email
      if (empty($_POST["username"]) || $_POST["username"] != filter_input(INPUT_POST, "username", 
                                              FILTER_SANITIZE_SPECIAL_CHARS)) {
        echo "<dialog>
                <h1>Invalid Username/Password</h1>
                <button id='exit' class='exit'>X</button>
              </dialog>
              
              <script> 
                dialog = document.querySelector('dialog');
                dialog.showModal();
              </script>
              
              ";
      } else {
        $conn = new mysqli('localhost', 'root', '', 'user accounts');
        
        $acc_username = $_POST['username'];
        $acc_password = $_POST['password'];

        $sql = 'SELECT Username, Password, Role FROM `user accounts`';
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
          if ($acc_username == $row['Username'] && $acc_password == $row['Password']) {
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role'] = $row['Role'];

            header('Location: homepage.php');
          }
        }
      }
    }
  ?>
  <script src="login.js"></script>
</body>
</html>
<?php 
  
?>