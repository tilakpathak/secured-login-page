<?php
session_start();

if (isset($_GET['success']) && $_GET['success'] == 1) {
    if (isset($_SESSION['registration-success'])) {
        echo '<div id="success-message-container" class="slide-down">';
        echo '<p class="success-message">' . $_SESSION['registration-success'] . '</p>';
        echo '</div>';
        unset($_SESSION['registration-success']);
    } elseif (isset($_SESSION['account-verified'])) {
        echo '<div id="success-message-container" class="slide-down">';
        echo '<p class="success-message">' . $_SESSION['account-verified'] . '</p>';
        echo '</div>';
        unset($_SESSION['account-verified']);
    } elseif (isset($_SESSION['error-verified'])) { // <-- added this line
        // Show account verification error message
        echo '<div id="error-message-container" class="slide-down">';
        echo '<p class="error-message">' . $_SESSION['error-verified'] . '</p>';
        echo '</div>';
        unset($_SESSION['error-verified']);
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
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
            <img src="img/hello.svg">
        </div>
        <div class="login-content">
            <form name="login-form" onsubmit="return validateForm()" action="/secured-login-page/login-validation.php" method="POST">
                <div style="margin-top: -60px;">
                    <img src="img/welcome.svg">
                </div>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Username</h5>
                        <input type="text" name="username" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input" id="password" onfocus="showEyeIcon()" oninput="hideInvalidAccountError()">
                        <span>
                            <i class="fa fa-eye" id="eye-icon" onclick="toggle()" style="display: none;"></i>
                        </span>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['invalid_account'])) {
                    echo '<p id="invalid_account" style="color:red;font-size:small;">' . $_SESSION['invalid_account'] . '</p>';
                    unset($_SESSION['invalid_account']);
                }
                ?>

                <p><a href="forgot-password.php">Forgot Password?</a></p>
                <button id="login-button" type="submit" class="btn btn-primary" aria-label="Login">
                    <span>Login</span>
                </button>
                <div class="account-login" id="register-button">
                    <label for="">Create an account?<a href="register.php" class="Register-link">Register</a></label>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>

    <script>
        function validateForm() {
            var username = document.forms["login-form"]["username"].value;
            var password = document.forms["login-form"]["password"].value;
            var errorMessage = document.getElementById("error-message");

            if (username == "" || password == "") {
                errorMessage.innerHTML = "Please fill in all required fields.";
                errorMessage.style.display = "block";

                // Add event listeners to hide error message when user fills in required fields
                document.forms["login-form"]["username"].addEventListener("input", hideErrorMessage);
                document.forms["login-form"]["password"].addEventListener("input", hideErrorMessage);

                return false;
            }

        }

        function hideErrorMessage() {
            var username = document.forms["login-form"]["username"].value;
            var password = document.forms["login-form"]["password"].value;
            var errorMessage = document.getElementById("error-message");

            if (username != "" && password != "") {
                errorMessage.style.display = "none";
            }
        }

        function hideInvalidAccountError() {
      var cpasswordError = document.getElementById("invalid_account");
      if (cpasswordError !== null) {
        cpasswordError.style.display = "none";
      }
    }
    </script>

    <script>
        window.addEventListener("load", function() {
            var successMessage = document.querySelector(".success-message");

            if (successMessage) {
                successMessage.style.display = "block";
                successMessage.style.animation = "slideDown 0.5s ease-in-out";

                setTimeout(function() {
                    hideSuccessMessage();
                }, 10000);
            }
        });

        function hideSuccessMessage() {
            var successMessage = document.querySelector(".success-message");
            if (successMessage) {
                successMessage.style.animation = "slideUp 0.5s ease-in-out";
                setTimeout(function() {
                    successMessage.style.display = "none";
                }, 500);
            }
        }

        document.addEventListener("click", function() {
            hideSuccessMessage();
        });
    </script>


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