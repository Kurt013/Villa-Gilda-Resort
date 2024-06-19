<?php 
session_start();

if (isset($_POST['submit']) || empty($_SESSION['role'])) {
    session_destroy();
    header('Location: index.php');
    exit(); 
  }

if (isset($_SESSION['ID']) && isset($_SESSION['username'])) {

    $sname = "localhost";
    $uname = "root";
    $password = "";

    $db_name = "villa gilda"; // Ensure the database name does not have spaces.

    $conn = mysqli_connect($sname, $uname, $password, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['op']) && isset($_POST['np']) && isset($_POST['c_np'])) {

        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $op = validate($_POST['op']);
        $np = validate($_POST['np']);
        $c_np = validate($_POST['c_np']);
        
        if (empty($op)) {
            header("Location: change-password.php?error=Old Password is required");
            exit();
        } else if (empty($np)) {
            header("Location: change-password.php?error=New Password is required");
            exit();
        } else if ($np !== $c_np) {
            header("Location: change-password.php?error=The confirmation password does not match");
            exit();
        } else {
            $id = $_SESSION['ID'];

            $sql = "SELECT Password FROM `user accounts` WHERE ID='$id'";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($op, $row['Password'])) {
                    $np_hashed = password_hash($np, PASSWORD_DEFAULT);
                    $sql_2 = "UPDATE `user accounts` SET Password='$np_hashed' WHERE ID='$id'";
                    if (mysqli_query($conn, $sql_2)) {
                        header("Location: change-password.php?success=Your password has been changed successfully");
                        exit();
                    } else {
                        header("Location: change-password.php?error=Failed to update password");
                        exit();
                    }
                } else {
                    header("Location: change-password.php?error=Incorrect old password");
                    exit();
                }
            } else {
                header("Location: change-password.php?error=User not found");
                exit();
            }
        }
    } else {
        header("Location: change-password.php");
        exit();
    }
}

include('header.php');

?>
