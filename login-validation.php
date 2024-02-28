<?php
session_start();

// Redirect to dashboard if user is already logged in
if(isset($_SESSION['username'])){
  header("Location: dashboard.php");
  exit();
}

include "db-connect.php";

// Handle account verification
if (isset($_GET['verification'])) {
  $verification_code = mysqli_real_escape_string($mysqli, $_GET['verification']);
  $query = "SELECT * FROM users WHERE code='$verification_code'";
  $result = mysqli_query($mysqli, $query);

  if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE users SET code='' WHERE code='$verification_code'";
    if (mysqli_query($mysqli, $query)) {
      $_SESSION['account-verified'] = "Account verification has been successfully completed";
    } else {
      $_SESSION['error-verified'] = "Failed to verify account. Please try again.";
    }
  } else {
    $_SESSION['error-verified'] = "Invalid verification code. Please try again.";
  }
  header("Location: login.php");
  exit();
}

// Handle login form submission
if(isset($_POST['username']) && isset($_POST['password'])){
  // Check username and password against database
  $username = mysqli_real_escape_string($mysqli, $_POST['username']);
  $password = mysqli_real_escape_string($mysqli, $_POST['password']);

  // Validate input
  if(empty($username) || empty($password)){
    $error = "Please fill in all required fields";
    header("Location: login.php?error=$error");
    exit();
  }

  // Check if the user exists in the database
  $query = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($mysqli, $query);

  if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];
    if(password_verify($password, $hashed_password)){
      // User exists and password is correct, create session and cookie
      $session_token = session_id();

      // Insert session into database
      $query = "INSERT INTO sessions (session_id, username, session_token) VALUES('$session_token', '$username', '$session_token')";
      if (mysqli_query($mysqli, $query)) {
        setcookie("session_token", $session_token, time() + 3600, "/"); // Set cookie to expire in 1 hour
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You have successfully logged in!";
        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Failed to create session. Please try again.";
        header("Location: login.php?error=$error");
        exit();
      }
    }
  }
  
  // Invalid username or password
  $_SESSION["invalid_account"] = "Invalid Username or Password";
  header("Location: login.php");
  exit();
}
?>
