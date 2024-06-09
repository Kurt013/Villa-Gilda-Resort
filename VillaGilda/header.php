<header>
  <div class="first-section">
    <div><img class="logo" src="images/villa-gilda-logo2.png" alt="Villa Gilda Logo"></div>
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
            icon.classList.remove('bxs-down-arrow');
            icon.classList.add('bxs-up-arrow');
            menuColor.style.backgroundColor = '#226060';
            menuIcon.remove('bx-menu');
            menuIcon.add('bx-x');
          }
          else {
            icon.classList.remove('bxs-up-arrow');
            icon.classList.add('bxs-down-arrow');
            menuColor.style.backgroundColor = '#4CABAB';
            menuIcon.remove('bx-x');
            menuIcon.add('bx-menu');
          }
        ">
        <div class="menu-appear"><i class="bx bx-menu menu-icon"></i></div>
        <div class="menu-disappear"><i class="bx bx-menu"></i></div>
        <p class="menu-text">Menu</p>
        <p class="div-2"><i class="bx bxs-down-arrow change"></i></p>
      </label>
    <div class="second-div">
      <i class="bx menu-home" id="menu"></i>
      <p class="home-text">Home</p>
    </div>
    <label class="third-div third-color">
      <input type="checkbox" class="user-prof-toggle" name="toggle-user" onclick="
        const changeBtnUser = document.querySelector('.third-color');        

        showMenu('.pane');

        if (checkToggle.checked) {
          changeBtnUser.style.backgroundColor = '#226060';
        }
        else {
          changeBtnUser.style.backgroundColor = '#4CABAB';
        }
      ">
      <i class="bx bx-user-circle"></i>
    </label>
</header>
<!--Left Navigation Bar-->
<div class="navigation-pane hide <?php 
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
          echo $_SESSION['username'];
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
        <a href="" class="nav-block <?php 
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
      <li>
        <a href="" class="nav-block <?php 
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
      <li>
        <a href="" class="nav-block <?php 
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
      <li>
        <a href='' class='nav-block <?php 
            if ($_SESSION['role'] == 'admin') {
              echo"nav-admin";
            }
            else {
              echo"invisible";
            }
            ?>'>
            <i class='bx bxs-dashboard'></i>
            <p class='loc'>Dashboard</p>
          </a>
        </li>
        <li>
          <a href='' class='nav-block <?php 
            if ($_SESSION['role'] == 'admin') {
              echo"nav-admin";
            }
            else {
              echo"invisible";
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
        <a class="user-redirect-dup redirect" href="">
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
<div class="pane hide">
  <div class="user-pane">
    <div class="wrapper-pane"><i class="bx bxs-user user-2"></i></div>
    <div class="wrapper-pane-2">
      <h2 class="user-pane-username">
        <?php
          echo $_SESSION['username'];
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
        <a class="user-redirect redirect-alternate" href="">
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
  toggleMenu.classList.toggle('hide');
}

 window.addEventListener('click', function(event) {
    // Get the pane element
    const toggleMenu = document.querySelector('.pane');
    // Check if the click occurred outside of the pane
    if (!toggleMenu.contains(event.document.querySelector('pane'))) {
      toggleMenu.classList.add('hide');
    }
  });
</script>
