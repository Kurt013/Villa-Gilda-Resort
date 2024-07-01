<?php 
session_start();
if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit(); 
  }
if (isset($_SESSION['ID']) && isset($_SESSION['username'])) {

 ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort</title>

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo3.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/general.css">
  <link rel="stylesheet" type="text/css" href="styles/change-password.css">
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
    include('header.php');?>
           <div class="change-password-wrapper">
                  <form class="change-password-form" action="change-p.php" method="post">
                     <div class="form-header">
                            <h2>Please complete the following input fields to change your password.</h2>
                     </div>
                     <div class="form-body">
                            <div class="group group-1">
                                   <label>Current Password:</label>
                                   <div class="field">
                                          <input type="password"
                                                 name="op"
                                                 placeholder="Current Password">
                                          <p class="guide-text"></p>
                                   </div>
                            </div>
                            <div class="group group-2">
                                   <label>New Password:</label>
                                   <div class="field">
                                   <p class="guide-text">Password must be 8 characters or more, and include letters, numbers, and special characters</p>
                                          <input type="password"
                                                 name="np"
                                                 placeholder="New Password">
                                   </div>
                            </div>
                            <div class="group group-3">
                                   <label>Confirm New Password: </label>
                                   <div class="field">
                                          <p class="guide-text">Both passwords must match</p>
                                          
                                          <input type="password"
                                                 name="c_np"
                                                 placeholder="Confirm New Password">
                                   </div>
                            </div>
                       </div>
                       <div class="form-submit">
                                   <button class="submitBtn" type="submit">SAVE PASSWORD</button>
                            </div>
                     </form>
           </div>
              <?php if (isset($_GET['error'])) { ?>
                            <dialog class="message-popup error">
                            <div class="pop-up">
                            <div class="left-side">
                            <div class="left-side-wrapper"><i class="bx bxs-x-circle error-circle"></i></div>
                            </div>
                            <div class="right-side">
                            <div class="right-group">
                            <div class="content">
                            <h1>Invalid</h1>
                            <p><?php echo $_GET['error']; ?></p>
                            </div>
                            <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                            </div>
                            </div>
                            </div>
                            </dialog>
              <?php } ?>
              <?php if (isset($_GET['success'])) { ?>
                     <dialog class="message-popup success" >
                            <div class="pop-up">
                            <div class="left-side">
                            <div class="left-side-wrapper"><i class="bx bxs-check-circle success-circle"></i></div>
                            </div>
                            <div class="right-side">
                            <div class="right-group">
                                   <div class="content">
                                   <h1>Success</h1>
                                   <p><?php echo $_GET['success']; ?></p>
                                   </div>
                                   <button onclick="closeDialog()" class="exit-btn"><i class="bx bx-x exit"></i></button>
                            </div>
                            </div>
                            </div>
                            </dialog>
                     <?php } ?>
     <div id="tree-container">
     </div>
     <script src="popup.js"></script>
       <script>
       const checkTab = document.getElementById('menu');
       const checkText = document.querySelector('.home-text');

       checkTab.classList.add('bx-lock');
       checkText.innerHTML = 'Change Password';

       function generateTrees() {
       const treeContainer = document.getElementById('tree-container');
       treeContainer.innerHTML = `<img class="tree-1" src="elements/starting-tree-change-password.png">`;


       const screenWidth = window.innerWidth;
       let numberOfTrees = Math.floor(screenWidth / 600); // Adjust 100 according to your tree width + margin

       for (let i = 0; i < numberOfTrees; i++) {
       const tree = document.createElement('img');
       tree.src = 'elements/trees-change-password.png'; // Apply your tree class or inline styles here 
       tree.className = 'tree';
       treeContainer.appendChild(tree);
       }
       }

       // Call generateTrees initially and on window resize
       generateTrees();
       window.addEventListener('resize', generateTrees);
</script>
</body>
</html>

<?php 
}
 ?>