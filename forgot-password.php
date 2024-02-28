<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// SMTP settings
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = 'tilak@ismt.edu.np';
$smtp_password = 'fuck@you';

session_start();

if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

$success_message = '';
$error_message = '';


if (isset($_GET['success']) && $_GET['success'] == 1) {
    if (isset($_SESSION['registration-success'])) {
        echo '<div id="success-message-container" class="slide-down">';
        echo '<p class="success-message">' . $_SESSION['registration-success'] . '</p>';
        echo '</div>';
        unset($_SESSION['registration-success']);
    }
}

//Load Composer's autoloader
require 'emailverification/autoload.php';

include 'db-connect.php';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $code = mysqli_real_escape_string($mysqli, md5(rand()));

    if (mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $query = mysqli_query($mysqli, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

        if ($query) {
            // Email verification link to the user
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'tilak@ismt.edu.np';                     //SMTP username
                $mail->Password   = 'fuck@you';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('tilak@ismt.edu.np', 'Tilak');
                $mail->addAddress($email);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Change Password';
                $mail->Body    = 'Here is the link to create new password <b><a href="http://localhost/secured-login-page/change-password.php?reset=' . $code . '">http://localhost/secured-login-page/change-password.php?reset=' . $code . '</a></b>';

                $mail->send();
            } catch (Exception $e) {
                $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            $_SESSION['registration-success'] = "We've sent a verification link to your email address";
                header("Location: forgot-password.php?success=1");
                exit();     
        }else{
            $_SESSION["email-error"] = "$email - This email address was not found.";
            header("Location: forgot-password.php?error=1");
            exit();
    } }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #error-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background-color: #f44336;
            color: #fff;
            text-align: center;
            display: none;
        }

        #error-container.slide-down {
            display: block;
            animation: slide-down 0.5s ease;
        }

        @keyframes slide-down {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div id="error-container"></div>

    <div class="container">
        <div class="img">
            <img src="img/forgotpassword.svg">
        </div>
        <div class="login-content">
            <form name="login-form" onsubmit="return validateForm()" action="" method="POST">

                <div class="div">
                    <h1>Forgot Password</h1>
                    <h5>Please enter your email address here to get the reset password link</h5>
                </div>

                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="email" class="input" name="email" required oninput="hideEmailError()">
                    </div>
                </div>
                <?php
                if (isset($_SESSION['email-error'])) {
                    echo '<p id="email-error" style="color:red;font-size:small;">' . $_SESSION['email-error'] . '</p>';
                    unset($_SESSION['email-error']);
                }
                ?>

                <button name="submit" class="btn" type="submit">Send Reset Link</button>
                <div class="account-login" id="register-button">
                    <label for="">Back to Login?<a href="login.php" class="Register-link">Login</a></label>
                </div>
            </form>

        </div>
    </div>
    <!-- //form section start -->
    <script type="text/javascript" src="js/main.js"></script>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function(c) {
            $('.alert-close').on('click', function(c) {
                $('.main-mockup').fadeOut('slow', function(c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>

    <script>
        function showError(message) {
            const errorContainer = document.getElementById('error-container');
            errorContainer.innerText = message;
            errorContainer.classList.add('slide-down');
            setTimeout(() => {
                errorContainer.classList.remove('slide-down');
            }, 5000);
        }

        function hideEmailError() {
            var emailError = document.getElementById("email-error");
            if (emailError !== null) {
                emailError.style.display = "none";
            }
        }
    </script>

</body>

</html>