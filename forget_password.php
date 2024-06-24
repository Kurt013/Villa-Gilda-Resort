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

    $verification_code = mt_rand(100000, 999999);
    $email_reg = mysqli_real_escape_string($dbconfig, $_POST['email']);
    $details = mysqli_query($dbconfig, "SELECT `First Name`, `Last Name`, email FROM `user accounts` WHERE email='$email_reg'");
    
    if (mysqli_num_rows($details) > 0) {
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Forgot Password</title>
</head>
<body>
<div class="container">
    <div class="row"><br><br><br>
        <div class="col-md-4"></div>
        <div class="col-md-4" style="background-color: #D2D1D1; border-radius:15px;">
            <br><br>
            <?php if (!$showVerificationForm) : ?>
                <form role="form" method="POST">
                    <div class="form-group">
                        <label>Please enter your username and email to recover your password</label><br><br>
                        <input class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Username">
                        <br>
                        <input class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email">
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
                    <button type="submit" class="btn btn-primary pull-right" name="submit" style="display: block; width: 100%;">Send Email</button>
                    <br><br>
                    <center><a href="index.php">Back to Login</a></center>
                    <br>
                </form>
            <?php else : ?>
                <form role="form" method="POST" action="forgot_password_reset.php">
                    <div class="form-group">
                        <label>Please enter the verification code sent to your email</label><br><br>
                        <input class="form-control" id="verification_code" name="verification_code" placeholder="Verification Code">
                    </div>

                    <?php if ($message <> "") {
                        echo "<div class='alert alert-danger' role='alert'>
                              <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                              <span class='sr-only'>Error:</span>" . $message . "</div>";
                    } ?>
                    <button type="submit" class="btn btn-primary pull-right" name="submit_verification_code" style="display: block; width: 100%;">Verify Code</button>
                    <br><br>
                    <center><a href="index.php">Back to Login</a></center>
                    <br>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
