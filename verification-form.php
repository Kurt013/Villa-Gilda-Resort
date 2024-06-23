<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Villa Gilda Resort</title>

  <!-- Favicon -->
  <link rel="icon" href="images/villa-gilda-logo.png">

  <!-- Stylesheets -->
  <link rel="stylesheet" type="text/css" href="styles/homepage.css">
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
require 'vendor/autoload.php';
use \Mailjet\Client;
use \Mailjet\Resources;

// Replace with your Mailjet API credentials
$apikey = '6b8cdf4ca54d43ee5c75b5e0e66e8b15';
$apisecret = '2fdf18e2ab4653c4d4e1296e3d775af8';

$mj = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);

if (isset($_POST['submit'])) {
    // Sanitize and use $_POST['email'] as the recipient
    $recipient_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => 'ResortVillaGilda@gmail.com'
                ],
                'To' => [
                    [
                        'Email' => $recipient_email
                    ]
                ],
                'Subject' => 'Verification Code --- DO NOT SHARE!',
                'TextPart' => "{$_POST['username']}'s Verification Code: 123456",
                'HTMLPart' => "
                <div style='
                    width: 300px;
                    height: 500px;
                    color: white;
                    background-color: black;
                '>
                    <h1>Verification Code: 123456</h1>
                    <p>Villa Gilda Resort</p>
                </div>
                ",
            ]
        ]
    ];

    try {
        // Send email via Mailjet API
        $response = $mj->post(Resources::$Email, ['body' => $body]);

        // Handle the response
        if ($response->success()) { 
            echo '<h1>Email sent successfully</h1>';
            header('Refresh: 0');
        } else {
            echo '<h1>Failed to send email</h1>';
            var_dump($response->getData()); // Output Mailjet API response for debugging
        }
    } catch (Exception $e) {
        echo '<h1>Error sending email</h1>';
        echo 'Caught exception: ' . $e->getMessage();
    }
}
?>    

</body>
</html>