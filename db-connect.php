<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "users";

// Create a connection to the database
$mysqli = new mysqli($server, $username, $password, $database);

// Check if the connection was successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
