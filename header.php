<header>
  <div class="first-section">
    <a href="homepage.php"><div><img class="logo" src="images/villa-gilda-logo2.png" alt="Villa Gilda Logo"></div></a>
    <h1>RESERVATION SYSTEM</h1>
  </div>
  <div class="second-section">
      <label class="menu-field menu-color">
        <input type="checkbox" class="menu-toggle" id="menu-toggle" onclick="
          const icon = document.querySelector('.change');
          const menuColor = document.querySelector('.menu-color');
          const toggleCheck = document.querySelector('.menu-toggle');
          const menuIcon = document.querySelector('.menu-icon').classList;

          showMenu('.navigation-pane');
          //For desktops
          if (toggleCheck.checked) {
            // icon.classList.remove('bxs-down-arrow');
            // icon.classList.add('bxs-up-arrow');
            menuColor.style.backgroundColor = '#368989';
            icon.classList.add('down');
            menuIcon.remove('bx-menu');
            menuIcon.add('bx-x');
            openLeft = true;
          }
          else {
            // icon.classList.remove('bxs-up-arrow');
            // icon.classList.add('bxs-down-arrow');
            menuColor.style.backgroundColor = '#4CABAB';
            menuIcon.remove('bx-x');
            icon.classList.remove('down');
            menuIcon.add('bx-menu');
            openLeft = false;
          }
        ">
        <div class="menu-appear"><i class="bx bx-menu menu-icon"></i></div>
        <div class="menu-disappear"><i class="bx bx-menu"></i></div>
        <p class="menu-text">Menu</p>
        <p class="div-2"><i class="bx bxs-down-arrow change"></i></p>
      </label>
    <div class="second-div">
      <i class="bx menu-home" id="menu"></i>
      <p class="home-text"></p>
    </div>
    <label class="third-div third-color">
      <input type="checkbox" class="user-prof-toggle" name="toggle-user" onclick="
        const changeBtnUser = document.querySelector('.third-color');        

        showMenu('.pane');

        if (checkToggle.checked) {
          changeBtnUser.style.backgroundColor = '#368989';
          openRight = true;
        }
        else {
          changeBtnUser.style.backgroundColor = '#4CABAB';
          openRight = false;
        }
      ">
      <i class="bx bx-user-circle"></i>
    </label>
</header>
<!--Left Navigation Bar-->
<div class="navigation-pane <?php 
        if ($_SESSION['role'] == 'admin') {
          echo"navigation-pane-admin";
        }
        else {
          echo"navigation-pane-staff";
        }
      ?>">
  <div class="user-detail">
    <div class="wrapper"><i class="bx bxs-user user-detail-prof <?php 
      if ($_SESSION['role'] == 'admin') {
        echo'user-admin';
      }
      else {
        echo'user-staff';
      }?>"></i>
    </div>
    <div class="wrapper-2">
      <h2 class="user-username">
        <?php
          echo "{$_SESSION['firstName']} {$_SESSION['lastName']}";
        ?>
      </h2>
      <p class="user-role">
        <?php
          echo $_SESSION['role'];
        ?>
      </p>
    </div>
  </div>
  <nav class="nav-list">
    <ul class="list">
      <li>
        <a href="homepage.php" class="nav-block <?php 
        if ($_SESSION['role'] == 'admin') {
          echo"nav-admin";
        }
        else {
          echo"nav-staff";
        }
        ?>">
          <i class="bx bx-home"></i>
          <p class="loc">Home</p>
        </a>
      </li>
      <li class="
      <?php if ($_SESSION['role'] != 'admin') echo'invisible'; ?>
      ">
        <a href='dashboard.php' class='nav-block <?php 
            if ($_SESSION['role'] == 'admin') {
              echo"nav-admin";
            }
            ?>'>
          <i class='bx bxs-dashboard'></i>
          <p class='loc'>Dashboard</p>
        </a>
      </li>
      <li>
        <a href="reserve.php" class="nav-block active-admin <?php 
          if ($_SESSION['role'] == 'admin') {
            echo"nav-admin";
          }
          else {
            echo"nav-staff";
          }
          ?>">
          <i class="bx bx-calendar"></i>
          <p class="loc">Reserve</p>
        </a>
      </li>
      <li class='<?php if ($_SESSION['role'] != 'admin') echo'staff'; ?>'>
        <a href="ourlist.php" class="nav-block <?php 
            if ($_SESSION['role'] == 'admin') {
              echo"nav-admin";
            }
            else {
              echo"nav-staff";
            }
            ?>">
          <i class="ri-menu-3-line"></i>
          <p class="loc">Reservation List</p>
        </a>
      </li>
        <li class="
          <?php if ($_SESSION['role'] != 'admin') echo'invisible'; ?>
        ">
          <a href='staff.php' class='nav-block <?php 
            if ($_SESSION['role'] == 'admin') {
              echo"nav-admin";
            }
            ?>'>
            <i class='bx bx-user-plus'></i>
            <p class='loc'>Add Staff</p>
          </a>
        </li>
      
    </ul>
    <!-- Duplicate of Right Side Bar (for Mobile or Smaller Screen Versions) -->
    <ul class="user-settings">
      <li>
        <a class="user-redirect-dup redirect" href="change-password.php">
          <i class='bx bx-lock'></i>
          <p class="loc">Change Password</p>
        </a>
      </li>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label class="user-redirect-dup redirect">
          <input type="submit" class="user-submit" name='submit' value="submit">
          <i class='bx bxs-log-out'></i>
          <p class="loc">Logout</p>
        </label>
      </form>
    </ul>
  </nav>
</div>

<!-- Right Side Bar -->
<div class="pane">
  <div class="user-pane">
    <div class="wrapper-pane wrapper-size"><i class="bx bxs-user user-2 <?php 
      if ($_SESSION['role'] == 'admin') {
        echo'user-admin';
      }
      else {
        echo'user-staff';
      }?>"></i></div>
    <div class="wrapper-pane-2">
      <h2 class="user-pane-username">
        <?php
          echo "{$_SESSION['firstName']} {$_SESSION['lastName']}";
        ?>
      </h2>
      <p class="user-pane-role">
        <?php
          echo $_SESSION['role'];
        ?>
      </p>
    </div>
  </div>
  <nav class="user-nav-list">
    <ul class="user-list">
      <li class="user-block">
        <a class="user-redirect redirect-alternate" href="change-password.php">
          <i class='bx bx-lock'></i>
          <p>Change Password</p>
        </a>
      </li>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="user-block">
          <label class="user-redirect redirect-alternate">
            <input type="submit" class="user-submit" name='submit' value="submit">
            <i class='bx bxs-log-out'></i>
            <p>Logout</p>
          </label>
      </form>
    </ul>
  </nav>
</div>
<script>
const checkToggle = document.querySelector('.user-prof-toggle');

function showMenu(togglePara) {
  const toggleMenu = document.querySelector(togglePara);
  toggleMenu.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    // Variables to track toggle state
    let openLeft = false;
    let openRight = false;

    // Event listener to show panes and update toggle button appearance
    window.addEventListener('click', function(event) {
        const navigationPane = document.querySelector('.navigation-pane');
        const userPane = document.querySelector('.pane');
        const menuToggle = document.querySelector('.menu-field');
        const userToggle = document.querySelector('.third-div');

        // Check if click occurred outside navigation pane or its toggle button
        if (!navigationPane.contains(event.target) && !menuToggle.contains(event.target)) {
            navigationPane.classList.remove('show');
            // Update toggle button appearance
            const icon = document.querySelector('.change');
            const menuColor = document.querySelector('.menu-color');
            const menuIcon = document.querySelector('.menu-icon').classList;
            const toggleCheck = document.querySelector('.menu-toggle');

            icon.classList.remove('bxs-up-arrow');
            icon.classList.add('bxs-down-arrow');
            menuColor.style.backgroundColor = '#4CABAB';
            menuIcon.remove('bx-x');
            menuIcon.add('bx-menu');
            openLeft = false;
            toggleCheck.checked = false; // Uncheck the checkbox input
        }

        // Check if click occurred outside user pane or its toggle button
        if (!userPane.contains(event.target) && !userToggle.contains(event.target)) {
            userPane.classList.remove('show');
            // Update toggle button appearance
            const changeBtnUser = document.querySelector('.third-color');
            const checkToggle = document.querySelector('.user-prof-toggle');

            changeBtnUser.style.backgroundColor = '#4CABAB';
            openRight = false;
            checkToggle.checked = false; // Uncheck the checkbox input
        }
    });
});
</script>