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
    
$apikey = '8de25420f468ac0e0637dc1a9872bda2';
$apisecret = '4b88a8e71352e99527617a3a8d39099a';
$mj = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);

$message = "";
$showVerificationForm = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $recipient_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = $_POST['username'];

    $email_reg = mysqli_real_escape_string($dbconfig, $_POST['email']);
    $username_reg = mysqli_real_escape_string($dbconfig, $_POST['username']);
    $details = mysqli_query($dbconfig, "SELECT `First Name`, `Last Name`, email FROM `user accounts` WHERE email='$email_reg' AND `username`='$username_reg'");
    
    $detailFetch = $details->fetch_assoc();

    if (mysqli_num_rows($details) > 0) {
        mysqli_query($dbconfig, "DELETE FROM forget_password WHERE email='$email_reg'");
        $verification_code = mt_rand(100000, 999999);
        $sql_insert = mysqli_query($dbconfig, "INSERT INTO forget_password(email, temp_key) VALUES('$email_reg', '$verification_code')");
      
          $body = [
            'Messages' => [
                [
                    'From' => ['Email' => 'resortvillagilda@gmail.com'],
                    'To' => [['Email' => $email_reg]],
                    'Subject' => 'Verification Code --- DO NOT SHARE!',
                    'TextPart' => "$username's Verification Code: $verification_code",
                    'HTMLPart' =>
          '<html>
              <head>
                  <style>
                      * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                        font: 14px / 1.2 "Montserrat", "Helvetica", sans-serif;
                      }

                      a {
                        color: #4EB1CB;
                        word-break: break-all;
                      }

                      .card-container  {
                        width: 100%;
                        max-width: 700px;
                        margin: auto;
                        background-color: #ffffff;
                      }

                      .header-card {
                        text-align: center;
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
                        font-weight: bold;
                        color: #226060;
                      }

                      .body-card p{
                        font-weight: 600;
                        margin-top: 20px;
                      }

                      .verification__code {
                        letter-spacing: 5px;
                        font-size: 30px;
                        font-weight: bold;
                        margin: 40px auto;
                        width: 100%;
                        text-align: center;
                        max-width: 300px;
                        padding: 20px;
                        border-radius: 20px;
                        background-color: #DBDEDA;  
                        color: #226060;
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

                      .icon {
                        width: 50px;
                        padding: 5px;
                        margin-top: 20px;
                        border-radius: 50%;
                      }

                      .icon-redirect {
                        text-align: center;
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
                        <h1>Hi '.$detailFetch['First Name'].' '.$detailFetch['Last Name'].',</h1>
                        <p>We&apos;d been told that you&apos;d like to reset the password for your account.</p>
                        <p>If you made such request, go back to the website and enter the verification code below.</p>
                        <div class="verification__code">'.$verification_code.'</div>
                        <p class="last-p">If you believe you have received this email in error, please disregard this email or <a class="notif-link" href="https://mail.google.com/mail/?view=cm&to=resortvillagilda@gmail.com&su=Notify%20the%20Resort">notify us.</a></p>
                        <div class="icon-redirect">
                          <a href="https://www.facebook.com/profile.php?id=100092186237360"><img class="icon" src="https://scontent.fmnl9-3.fna.fbcdn.net/v/t1.15752-9/448805439_1033824851691076_1924524101978291005_n.png?_nc_cat=100&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeE9aPRH_OGP0V0aJCnFpQkPEDWtl9pklFYQNa2X2mSUVomkxi-0lupO9jucL73DWWrv9hh_LvmB2yLwLuIjefU9&_nc_ohc=TsTB9zB4jLgQ7kNvgGSQWqG&_nc_ht=scontent.fmnl9-3.fna&oh=03_Q7cD1QE_069nPaOOI8Rzn--Jybq7xY8MU05G2WRV1G3WVrgd-w&oe=66A7C320"/></a>
                          <a href="mailto:resortvillagilda@gmail.com"><img class="icon" src="https://scontent.fmnl9-3.fna.fbcdn.net/v/t1.15752-9/448665658_1005997041102081_7020963237239717707_n.png?_nc_cat=111&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeHE3_QUTfqAlZQW0Rswo88XGtPR5f8vZN8a09Hl_y9k31S3U4Gm_a6p7llRzxhqFwpjlpw6oeY4LEbkxg1KoMnL&_nc_ohc=kU_V_p-0oD4Q7kNvgFQNKIz&_nc_ht=scontent.fmnl9-3.fna&oh=03_Q7cD1QFx3CUFMyd_D6tKluLkL7xxQt7OyuHRMIptfVyF0AxQEA&oe=66A7C3D9"/></a>
                        </div>
                      </div>
                      <hr>
                      <div class="footer-card">
                        <p class="first-p">@ Gilda Private Resort, Purok 2, Brgy. Caingin, Santa Rosa, Laguna</p>
                        <p class="second-p">This message was sent to <a href="mailto:'.$email_reg.'">'.$email_reg.'</a></p>
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

  <meta name="robots" content="noindex, nofollow" />

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
                    <div class="lock-container"><i class="bx bx-lock lock-icon"></i></div>

                    <div class="form-group">
                        <h1>Forgot Your Password?</h1>
                        <p>Not to worry, enter the username and email address you registered with and we'll help you reset your password</p>
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
