<?php
session_start(); // Start the session at the top

// $message = $_SESSION['message'];

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
    $username_reg = mysqli_real_escape_string($dbconfig, $_POST['username']);
    $details = mysqli_query($dbconfig, "SELECT `First Name`, `Last Name`, email FROM `user accounts` WHERE email='$email_reg' AND `username`='$username_reg'");
    
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
                    'HTMLPart' => '
                    <!DOCTYPE html>
<html lang="en">
<head>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Montserrat", sans-serif;
    }

    a {
      color: #4EB1CB;
    }

    body {
      background-color: #4EB1CB;
    }

    .body > div {
      text-align: center;
    }

    .card-container {
      width: 70%;
      margin: auto;
      background-color: #ffffff;
      height: 100%;
    }

    .header-card {
      text-align: center;
      width: 100%;
      height: 90px;
      background-image: url("https://scontent.fmnl33-6.fna.fbcdn.net/v/t1.15752-9/449048471_452239437525588_272269953370891782_n.png?_nc_cat=107&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHTy-oQj3Uj41iB2J4xK9LgOvJNqU_Wwy068k2pT9bDLXXgweOat34wwr2glrhynQZyblrvet-tbppoUf5Yy2Jm&_nc_ohc=gtjpG9gbncsQ7kNvgGUC6IX&_nc_ht=scontent.fmnl33-6.fna&oh=03_Q7cD1QF7hSpPEgBw3S-qbjlXY6Sk4qwW0X60UhFM6b327mzD8g&oe=66A6D4CE");
    }

    .logo {
      width: 150px;
    }

    .body-card {
      padding: 30px 0 15px;
      margin: auto;
      width: 90%;
    }

    .body-card h1 {
      font-size: 18px;
      color: #226060;
    }

    .body-card p{
      font-weight: 600;
      margin-top: 20px;
    }

    .verification__code {
      font-size: 30px;
      color: #226060;
      font-weight: bold;
      letter-spacing: 5px;
      margin: 40px auto;
      width: 100%;
      text-align: center;
      max-width: 300px;
      height: 70px;
      padding-top: 15px;
      text-wrap: nowrap;
      border-radius: 20px;
      background-color: #DBDEDA;  
    }

    .body-card .last-p {
      color: #AFADAD;
      font-style: italic;
    }

    .footer-card {
      padding: 15px;
      margin: auto;
      width: 90%;
      color: #A6A6A6;
      text-align: center;
    }

    .footer-card .first-p {
      font-weight: 600;
    }

    .footer-card .second-p {
      max-width: 300px;
      margin: 15px auto;
    }

    .bx {
      color: #ffffff;
      background-color: #2D7474;
      font-size: 25px;
      padding: 5px;
      margin-top: 20px;
      border-radius: 50%;
    }

    .icon-redirect {
      text-align: center;
    }

    .bxl-facebook {
      margin-right: 10px;
    }

    hr {
      margin: 0 auto;
      width: 90%;
    }

  </style>
</head>
<body>
  <div class="card-container">
    <div class="header-card">
      <img class="logo" src="https://scontent.fmnl33-5.fna.fbcdn.net/v/t1.15752-9/448719767_828880522525898_6091539274430163876_n.png?_nc_cat=105&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeE6YdCc0x9jPYeyi28KzECuIvAw4C22rBUi8DDgLbasFVBjbjgnw15IgJGzlWW1pYwgBEJMc8tzmog5ZRma_PI2&_nc_ohc=LbHntbutHp4Q7kNvgHKBqfL&_nc_ht=scontent.fmnl33-5.fna&oh=03_Q7cD1QEibZYmJGLG4oiXTvsC_1FDjI23cqQlJP9Zat3KGlPnQg&oe=66A6F266" alt="Villa Gilda Logo">
    </div>
    <div class="body-card">
      <h1>Hi '.$username.' ,</h1>
      <p>We&apos;d been told that you&apos;d like to reset the password for your account.</p>
      <p>If you made such request, go back to the website and enter the verification code below.</p>
      <div class="verification__code">'.$verification_code.'</div>
      <p class="last-p">If you believe you have received this email in error, please disregard this email or <a class="notif-link" href="">notify us.</a></p>
      <div class="icon-redirect">
        <a href=""><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAOdJREFUSEvt1CFOQ0EQxvFfBSHoguEIxSEqMBgOQELKDUpAYlA1FXgcTbB1xRBsBQJLD1ASJCS0VyCBl4xompLNvryXVHTVZHfn+8/O7ExDzatRs761AxzgFm3s4hND9P7LRM4L9vGB7SWxR3SqANzjcoXQCOdVAN5wGEIvOMFP6pPkpOgbeyF4hUFKvDjPAczRDNELPFQBKHLbCqEb7IT9jEnYrxiXrUHxQ84SkV7jrk7AKZ7KAvo4DuejvybbCnuKr7C7eC8LWPSbRfcWe5UVeQPIarRNDZKTpfYUJSNYdSFnmq4n4BdfByoZ/9M5/QAAAABJRU5ErkJggg=="/></i></a>
        <a href=""><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUVJREFUSEvt1T0vREEUxvHfIiKhUItCoZAofQOJSkFCg0RFo0RCpaFQoNBQe+loJDolvoGodCQSX0DhdeTumtzsmrvLdm43OWee/3nOzJlb0uSv1GR9/4Bkh+MWDWMf3TjAKl6SCt8JY1hHO+ZwGUIx4AE9keAVJvGYgLRhE0tR3h3684D3KkJPGMd1DUgfTjBUJf5VfOygGiDkhDatYCcnEsCH6KoBLwwo7z/DNF6xi/lE6+oGBL3bzPVAgcNvCFBAt5KSBFwgVNqbUL3PnI3k8pKALWzgGKM1IOeYwRoW6wVsYznbtICw7sjWz5ngXrYOsV8Bgs4gTjPBCdxEFf8JIOh1Ztc0OIi/0M54kisz9tOgxS1K3Z5CDsIAteRsl88gBcg7ePt8+FrzT8URpiJIPQ5iQBAPN282D0hV2VD8/5eZbNsHnRQ+GcOlqXcAAAAASUVORK5CYII="/></a>
      </div>
    </div>
    <hr>
    <div class="footer-card">
      <p class="first-p">@ Gilda Private Resort, Purok 2, Brgy. Caingin, Santa Rosa, Laguna</p>
      <p class="second-p">This message was sent to <a href="mailto:celinebatumbakal@gmail.com">celinebatumbakal@gmail.com</a></p>
      <p>To help keep your account secure, please don&apos;t forward this email.</p>
    </div>
  </div>
</body>
</html>
                    ',
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
        $message = 'Username and/or email address not found.';
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
  <link rel="icon" href="images/villa-gilda-logo3.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/general.css">
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
            <?php if (!$showVerificationForm) : ?>
                <form class="form-field" role="form" method="POST">
                    <div class="lock-container"><i class="bx bxs-lock lock-icon"></i></div>

                    <div class="form-group">
                        <h1>Forgot Your Password?</h1>
                        <p>Not to worry, enter the username and email address you registered with and we’ll help you reset your password</p>
                        <div class="input-wrapper">
                            <i class="bx bxs-user icon"></i>
                            <input class="form-control form-border" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Username" required>
                        </div>
                        <div class="input-wrapper">
                            <i class="bx bxs-envelope icon"></i>
                            <input class="form-control form-border" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email" required>
                        </div>
                    </div>

                    <?php if ($message <> "") {
                        echo "<div class='error-message'>" . $message . "</div>";
                    } ?>
                    <?php if (isset($message_success)) {
                        echo "<div class='message-success'>" . $message_success . "</div>";
                    } ?>
                    <div class="bottom-part">
                        <a class="btn" href="index.php">Back</a>
                        <button type="submit" class="btn-2" name="submit">Submit</button>
                    </div>
                </form>
            <?php else : ?>
                <form class="form-field-2" role="form" method="POST" action="forgot_password_reset.php">
                    <h1>OTP Verification</h1>
                    <div class="submit-group">
                        <p>A One-Time Passcode has been sent to your email. Please enter the OTP below to reset your password. </p>
                        <input maxlength="6" minlength="6" title="Please only enter numbers from 0-9" pattern="[0-9]+" class="form-control-verify" id="verification_code" name="verification_code" placeholder="Enter the OTP" required>
                        
                        <?php if ($message <> "") {
                        echo "<div class='error-message" . $message . "</div>";
                        } ?>

                        <div class="bottom-part-2">
                            <button type="submit" class="btn-3" name="submit_verification_code">Verify OTP</button>
                            <p class="btn-4">Didn't receive a code? Please check your spam folder</p>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
</body>
</html>
