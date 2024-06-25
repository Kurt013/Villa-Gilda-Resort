<?php
session_start(); // Start the session at the top
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'villa gilda';
$dbconfig = mysqli_connect($host, $username, $password, $database) or die("An error occurred when connecting to the database");
require 'vendor/autoload.php';
use \Mailjet\Client;
use \Mailjet\Resources;

$apikey = '6b8cdf4ca54d43ee5c75b5e0e66e8b15';
$apisecret = '2fdf18e2ab4653c4d4e1296e3d775af8';
$mj = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);

$message = "";
$showVerificationForm = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $recipient_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = $_POST['username'];

    $email_reg = mysqli_real_escape_string($dbconfig, $_POST['email']);
    $details = mysqli_query($dbconfig, "SELECT `First Name`, `Last Name`, email FROM `user accounts` WHERE email='$email_reg'");
    
    if (mysqli_num_rows($details) > 0) {
        mysqli_query($dbconfig, "DELETE FROM forget_password WHERE email='$email_reg'");
        $verification_code = mt_rand(100000, 999999);
        $sql_insert = mysqli_query($dbconfig, "INSERT INTO forget_password(email, temp_key) VALUES('$email_reg', '$verification_code')");
        
        $body = [
            'Messages' => [
                [
                    'From' => ['Email' => 'ResortVillaGilda@gmail.com'],
                    'To' => [['Email' => $email_reg]],
                    'Subject' => 'Verification Code --- DO NOT SHARE!',
                    'TextPart' => "$username's Verification Code: $verification_code",
                    'HTMLPart' => "
                        <div style='
                            display: flex;
                            justify-content: center;
                            flex-direction: column;
                            align-items: center;
                            width: 300px;
                            height: 500px;
                            color: white;
                            background-color: black;
                        '>
                            <h1>Verification Code: $verification_code</h1>
                            <p>Villa Gilda Resort</p>
                        </div>
                    ",
                ]
            ]
        ];

        try {
            $response = $mj->post(Resources::$Email, ['body' => $body]);
            if ($response->success()) {
                $message_success = 'Email sent successfully';
                $showVerificationForm = true;
                $_SESSION['email'] = $email_reg;
                $_SESSION['verification_code'] = $verification_code;
            } else {
                $message = 'Failed to send email';
                var_dump($response->getData());
            }
        } catch (Exception $e) {
            $message = 'Error sending email: ' . $e->getMessage();
        }
    } else {
        $message = 'Email address not found.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort || Forgot Password</title>

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/forget-password.css">

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
            <?php if (!$showVerificationForm) : ?>
                <form class="form-field" role="form" method="POST">
                    <div class="lock-container"><i class="bx bxs-lock lock-icon"></i></div>

                    <div class="form-group">
                        <h1>Forgot Your Password?</h1>
                        <p>Not to worry, enter the username and email address you registered with and we’ll help you reset your password</p>
                        <div class="input-wrapper">
                            <i class="bx bxs-user icon"></i>
                            <input class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Username">
                        </div>
                        <div class="input-wrapper">
                            <i class="bx bxs-envelope icon"></i>
                            <input class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email">
                        </div>
                    </div>

                    <?php if ($message <> "") {
                        echo "<div class='alert alert-danger' role='alert'>
                              <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                              <span class='sr-only'>Error:</span>" . $message . "</div>";
                    } ?>
                    <?php if (isset($message_success)) {
                        echo "<div class='alert alert-success' role='alert'>
                              <span class='glyphicon glyphicon-ok' aria-hidden='true'></span>
                              <span class='sr-only'>Success:</span>" . $message_success . "</div>";
                    } ?>
                    <div class="bottom-part">
                        <button type="submit" class="btn" name="submit">Send Email</button>
                        <div><a href="index.php">Back to Login</a></div>
                    </div>
                </form>
            <?php else : ?>
                <form class="form-field" role="form" method="POST" action="forgot_password_reset.php">
                    <div class="form-group">
                        <label>Please enter the verification code sent to your email</label>
                        <input class="form-control" id="verification_code" name="verification_code" placeholder="Verification Code">
                    </div>

                    <?php if ($message <> "") {
                        echo "<div class='alert alert-danger' role='alert'>
                              <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                              <span class='sr-only'>Error:</span>" . $message . "</div>";
                    } ?>
                    <div class="bottom-part">
                        <button type="submit" class="btn" name="submit_verification_code">Verify Code</button>
                        <a href="index.php">Back to Login</a>
                    </div>
                </form>
            <?php endif; ?>
</div>
</body>
</html>
