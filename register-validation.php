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

require 'emailverification/autoload.php';
include 'db-connect.php';

$msg = "";
$verification_code = uniqid(); // Generate a unique verification code

// Create a database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'users';

$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_error) {
    die('Error connecting to database: ' . $mysqli->connect_error);
}

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

    // validate form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: register.php");
        exit();
    } elseif (mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $_SESSION['email-error'] = "This email address has already been used.";
        header("Location: register.php");
        exit();
    } elseif (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password) || !preg_match("#[a-z]+#", $password)) {
        $_SESSION['password_error'] = "Password must meet requirements";
        header("Location: register.php");
        exit();
    } elseif ($password != $_POST['c_password']) {
        $_SESSION['cpassword_error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }elseif (empty($_POST['g-recaptcha-response'])) {
        $_SESSION['recaptcha_error'] = "Please complete the reCAPTCHA.";
        header("Location: register.php");
        exit();
    } else {
        // Captcha verification
        $recaptcha_secret = '6LfqmzclAAAAAHG0y4Iqlx3ERu_q0f4FsWo1wuUj';
        $recaptcha_response = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_response
        );
        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result);
        if ($response->success == true) {

            // Captcha verification successful, proceed with registration

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $code = uniqid();

            // hashing the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


            // Check if the username already exists
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Username already exists, display error message
                $_SESSION['user-error'] = "The username already exists";
                header("Location: register.php");
                exit();
            } else {
                // Username doesn't exist, insert the new user into the database
                $stmt = $mysqli->prepare("INSERT INTO users (username, password, email, code) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hashed_password, $email, $code);
                if ($stmt->execute()) {
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
                        $mail->setFrom('fuck@ismt.edu.np', 'Tilak');
                        $mail->addAddress($email);

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Email Verification';
                        $mail->Body    = 'Here is the verification link <b><a href="http://localhost/secured-login-page/?verification=' . $code . '">http://localhost/secured-login-page/?verification=' . $code . '</a></b>';

                        $mail->send();

                        // Registration successful
                        $_SESSION['registration-success'] = "Registration successful! Please check your email to verify your account.";
                        header("Location: login.php?success=1");
                        exit();
                    } catch (Exception $e) {
                        $_SESSION['error_message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        header("Location: register.php");
                        exit();
                    }
                } 
            }
        }
    }
}
