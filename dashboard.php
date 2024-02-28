<?php
session_start();
if(!isset($_SESSION['username']) && !isset($_COOKIE['session_token'])){
  header("Location: login.php");
  exit();
}

if(isset($_COOKIE['session_token'])){
  // Verify session token against database
  $session_token = $_COOKIE['session_token'];

  // Connect to database
  $server = "localhost";
  $username = "root";
  $password = "";
  $database = "users";
  $mysqli = new mysqli($server, $username, $password, $database);

  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  // Check if the session token exists in the database
  $result = mysqli_query($mysqli, "SELECT * FROM sessions WHERE session_token='$session_token'");

  if(mysqli_num_rows($result) == 0){
    // Invalid session token, redirect to login page
    header("Location: login.php");
    exit();
  }

  $row = mysqli_fetch_assoc($result);
  $username = $row['username'];

  // Fetch user's email from database
  $result = mysqli_query($mysqli, "SELECT email FROM users WHERE username='$username'");
  $row = mysqli_fetch_assoc($result);
  $email = $row['email'];
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
      background-color: #fff;
		}

		.container {
			display: flex;
			flex-direction: column;
			align-items: center;
			height: 100vh;
		}

		.header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			width: 100%;
			padding: 20px;
			background-color: #eee;
		}

		.header h1 {
			margin: 0;
			font-size: 24px;
			font-weight: bold;
			color: #333;
		}

		.welcome-msg {
			margin-top: 50px;
			text-align: center;
		}

		.welcome-msg h2 {
			margin-top: 0;
			font-size: 36px;
			font-weight: bold;
			color: #333;
			text-shadow: #333;
		}

    #username{
      margin-top: 0;
			font-size: 36px;
			font-weight: bold;
			color: #27E1C1;
			text-shadow: #333;
    }
		.welcome-msg p {
			margin-top: 20px;
			font-size: 20px;
			color: #27E1C1;
			text-shadow: #333;
		}

		.logout-btn {
			background-color: #333;
			color: #fff;
			border: none;
			padding: 10px 20px;
			font-size: 16px;
			margin-top: 20px;
			cursor: pointer;
		}

		.logout-btn:hover {
			background-color: red;
		}

    img {
  width: 400px;
  height: 400px;
  
}

	</style>
</head>
<body>
	<div class="container">
		<div class="content">
			<div class="welcome-msg">
				<h2>:Welcome To The Team:</h2> 
        <p id="username"><?php echo $username; ?></p>
				<p>Email: <?php echo $email; ?></p>
				<button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
			</div>
      <img src="img/nature.svg">
		</div>
	</div>
</body>
</html>
