<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load .env first
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start(); // Start the session at the top

// Database Credentials
$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$database = $_ENV['DB_NAME'] ?? 'villa gilda';

// Establish connection to database
$dbconfig = mysqli_connect($host, $username, $password, $database) or die("An error occurred when connecting to the database");


// Instantiation of PHPMAILER
$mail = new PHPMailer(true);

$message = "";
$showVerificationForm = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

  $recipient_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $username = trim($_POST['username']);

  $email_reg = mysqli_real_escape_string($dbconfig, $_POST['email']);
  $username_reg = mysqli_real_escape_string($dbconfig, $_POST['username']);

  // Validate email
  if (!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
      die('Invalid email');
  }

  // 1) Verify user exists
  $stmt = $dbconfig->prepare("SELECT `First Name`, `Last Name`, email FROM `user accounts` WHERE email = ? AND `username` = ? LIMIT 1");
  $stmt->bind_param('ss', $recipient_email, $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
      $detailFetch = $result->fetch_assoc();

      // 2) Delete any existing reset entries for this email
      $del_stmt = $dbconfig->prepare("DELETE FROM forget_password WHERE email = ?");
      $del_stmt->bind_param('s', $recipient_email);
      $del_stmt->execute();
      $del_stmt->close();

      // 3) Insert new verification code
      $verification_code = mt_rand(100000, 999999);
      $insert_stmt = $dbconfig->prepare("INSERT INTO forget_password (email, temp_key) VALUES (?, ?)");
      $insert_stmt->bind_param('si', $recipient_email, $verification_code); // 'si' = string, integer
      $insert_stmt->execute();
      $insert_stmt->close();

      try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_SERVER'] ?? 'smtp.gmail.com';   // Fallback if env not set
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USERNAME'] ?? '';                  // SMTP username from .env
        $mail->Password   = $_ENV['SMTP_PASSWORD'] ?? '';                  // SMTP password from .env
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'] ?? 'tls';             // tls or ssl
        $mail->Port       = $_ENV['SMTP_PORT'] ?? 587;                     // Port from env, default 587
              
        // Sender and recipient settings
        $mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['APP_NAME']);
        $mail->addAddress($detailFetch['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code -- DO NOT SHARE';
              
              
        $mail->Body = '<html>
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
                  background-image: url("https://raw.githubusercontent.com/Kurt013/Villa-Gilda-Resort/refs/heads/main/images/forgot-pw-bg.png");
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
                    <a href="https://www.facebook.com/profile.php?id=100092186237360"><img class="icon" src="https://raw.githubusercontent.com/Kurt013/Villa-Gilda-Resort/refs/heads/main/images/facebook-icon.png"/></a>
                    <a href="mailto:resortvillagilda@gmail.com"><img class="icon" src="https://raw.githubusercontent.com/Kurt013/Villa-Gilda-Resort/refs/heads/main/images/mail-icon.png"/></a>
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
          ';

        // Send the email
        if(!$mail->send()){
          $message = 'Failed to send email: ' . $mail->ErrorInfo;
        } else {
          $message_success = 'Email sent successfully';
          $showVerificationForm = true;
          $_SESSION['email'] = $email_reg;
          $_SESSION['verification_code'] = $verification_code;
        }

    } catch (Exception $e) {
        $message = 'Error sending email: ' . $e->getMessage();
    }
  } else {
    $message = 'Username and/or email address not found.';
  }

  $stmt->close();
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
