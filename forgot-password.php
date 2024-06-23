<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort</title>

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/forgot-password.css">

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
    <form action="verification-form.php" method="post">
      <div class="username-field">
        <label for="username">Username</label>
        <input name="username" id="username" type="text">
      </div>
      <div class="email-field">
        <label for="email">Email</label>
        <input name="email" id="email" type="text">
      </div>
      <input name="submit" value="submit" type="submit">
    </form>

  <?php 
  //Check each column's email address to see if it matches one of the accounts
// Establish Database Connection
$conn = new mysqli('localhost', 'root', '', 'villa gilda');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// // Prepare statement
// $sql = 'SELECT `ID`, `verification_id` FROM `user accounts`';
// $result = $conn->query($sql);

// // Check if there are any results
// if ($result->num_rows > 0) {
//     // Output data of each row
//     while ($row = $result->fetch_assoc()) {
//         do {
//             $rand_num = mt_rand(100000, 999999);
//             // Check if the generated number already exists in the table
//             $check_sql = "SELECT COUNT(*) AS count FROM `user accounts` WHERE `verification_id` = {$rand_num}";
//             $check_result = $conn->query($check_sql);
//             $check_row = $check_result->fetch_assoc();
//         } while ($check_row['count'] > 0); // Repeat if the number exists
        
//         // Update the verification_id for the current row
//         $user_id = $row['ID'];
//         $update_sql = "UPDATE `user accounts` SET `verification_id` = {$rand_num} WHERE `ID` = {$user_id}";
//         $conn->query($update_sql);
//     }
// } else {
//     echo "0 results";
// }

// // Close connection
// $conn->close();



  ?>
</body>
</html>