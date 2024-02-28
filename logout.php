<?php
session_start();
require_once('db-connect.php');

$username = $_SESSION['username'];
$session_id = session_id();
$sql = "DELETE FROM sessions WHERE username='$username' AND session_id='$session_id'";
$result = mysqli_query($mysqli, $sql);
if (!$result) {
    die('Error deleting session data: ' . mysqli_error($mysqli));
}
setcookie('user', '', time() - 600, '/');

session_unset();
session_destroy();

// Redirect the user to the login page
header('Location: login.php');
exit;
?>