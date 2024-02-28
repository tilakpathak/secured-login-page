<?php
session_start();
require_once("db-connect.php");

$msg="";

if (isset($_GET['reset'])) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE code = ?");
    $stmt->bind_param("s", $_GET['reset']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        if (isset($_POST['change-password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $confirm_password = password_hash($_POST['confirm-password'], PASSWORD_DEFAULT);

            if ($password === $confirm_password) {
                $stmt = $mysqli->prepare("UPDATE users SET password=?, code='' WHERE code=?");
                $stmt->bind_param("ss", $password, $_GET['reset']);
                $stmt->execute();

                if ($stmt) {
                    $_SESSION['success'] = "New Password has been activated.";
                    header("Location: login.php");
                    exit();
                }
            } else{
                $_SESSION['cpassword_error'] = "Passwords do not match.";
                header("Location: register.php");
                exit();
            }
        }
    } else {
        http_response_code(404);
        $msg = "Reset Link does not exist.";
        header("Location:login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div id="error-message"></div>
    <?php if (isset($_SESSION['error-message'])) : ?>
        <div class="error-message"><?php echo $_SESSION['error-message']; ?></div>
        <?php unset($_SESSION['error-message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success-message"><?php echo $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <div class="container">
        <div class="img">
            <img src="img/newpassword.svg">
        </div>
    
        <div class="login-content">
            <form name="login-form" onsubmit="return validateForm()" action="change-password.php" method="POST">
            <h2>Change Password</h2>
                <h6>Note: Insert your New Password and Confirm Password</h6>

        <div class="input-div pass">
            <div class="i">
                <i class="fas fa-lock"></i>
            </div>
            <div class="div">
                <h5>New Password</h5>
                <input type="password" name="password" class="input" id="password" required onfocus="showEyeIcon()">
                <span>
                    <i class="fa fa-eye" id="eye-icon" onclick="toggle()" style="display: none;"></i>
                </span>
            </div>
        </div>

        <div class="input-div pass">
            <div class="i">
                <i class="fas fa-lock"></i>
            </div>
            <div class="div">
                <h5>Confirm New Password</h5>
                <input type="password" name="confirm-password" class="input" id="confirm-password" required>
            </div>
        </div>
        <?php
        if (isset($_SESSION['cpassword_error'])) {
            echo '<p id="cpassword_error" style="color:red;font-size:small;">' . $_SESSION['cpassword_error'] . '</p>';
            unset($_SESSION['cpassword_error']);
          }
          ?>

        <button id="login-button" type="submit" class="btn btn-primary" aria-label="Login" name="change-password">
            <span>Change Password</span>
        </button>

        <div class="account-login" id="register-button">
            <label for="">Redirect to login?<a href="login.php" class="Register-link">Login</a></label>
        </div>
        </form>
    </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>

    <!-- password showing script -->
    <script>
        let state = false;

        function toggle() {
            if (state) {
                document.getElementById("password").setAttribute("type", "password");
                document.getElementById("eye-icon").style.color = '#ccc';
                document.getElementById("eye-icon").classList.remove("fa-eye");
                document.getElementById("eye-icon").classList.add("fa-eye-slash");
                state = false;
            } else {
                document.getElementById("password").setAttribute("type", "text");
                document.getElementById("eye-icon").style.color = '#57C5B6';
                document.getElementById("eye-icon").classList.remove("fa-eye-slash");
                document.getElementById("eye-icon").classList.add("fa-eye");
                state = true;
            }
        }

        function showEyeIcon() {
            document.getElementById("eye-icon").style.display = "block";
        }

        function hideEyeIcon() {
            if (document.getElementById("password").value === '') {
                document.getElementById("eye-icon").style.display = "none";
            }
        }

        document.getElementById("password").addEventListener("input", function() {
            if (this.value === '') {
                hideEyeIcon();
            } else {
                showEyeIcon();
            }
        });
    </script>
</body>

</html>