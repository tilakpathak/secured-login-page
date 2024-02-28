<?php
session_start();
?>


<!DOCTYPE html>
<html>

<head>
  <title>SignUp Page</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <div class="container">
    <div class="img">
      <img src="img/register.svg">
    </div>
    <div class="login-content">
      <div id="register">
        <form name="signup-form" onsubmit="return validateForm()" action="/secured-login-page/register-validation.php" method="post">
          <h2 class="title">REGISTER</h2>

          <div class="input-div one">
            <div class="i">
              <i class="fas fa-user"></i>
            </div>
            <div class="div">
              <h5>Username</h5>
              <input type="text" class="input" name="username" required oninput="hideUsernameError()">
            </div>
          </div>
          <?php
          if (isset($_SESSION['user-error'])) {
            echo '<p id="username-error" style="color:red;font-size:small;">' . $_SESSION['user-error'] . '</p>';
            unset($_SESSION['user-error']);
          }
          ?>

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


          <div class="input-div pass">
            <div class="i">
              <i class="fas fa-lock"></i>
            </div>
            <div class="div">
              <h5>Password</h5>
              <input type="password" class="input" name="password" id="password-input" required oninput="hidePasswordError()">
            </div>
            <div id="password-strength-wrapper" style="display: none;">
              <div class="password-strength-bar">
                <div class="password-strength-meter-fill"></div>
              </div>
              <div class="password-strength-label" id="pp">Password Requirements:</div>
              <div class="password-strength-rules">
                <div class="password-strength-rule" id="password-rule-uppercase">
                  <span class="password-strength-rule-icon">A</span>
                  <span class="password-strength-rule-label">At least one uppercase letter</span>
                </div>
                <div class="password-strength-rule" id="password-rule-lowercase">
                  <span class="password-strength-rule-icon">a</span>
                  <span class="password-strength-rule-label">At least one lowercase letter</span>
                </div>
                <div class="password-strength-rule" id="password-rule-number">
                  <span class="password-strength-rule-icon">1</span>
                  <span class="password-strength-rule-label">At least one number</span>
                </div>
                <div class="password-strength-rule" id="password-rule-special">
                  <span class="password-strength-rule-icon">@</span>
                  <span class="password-strength-rule-label">At least one special character</span>
                </div>
                <div class="password-strength-rule" id="password-rule-length">
                  <span class="password-strength-rule-icon">8+</span>
                  <span class="password-strength-rule-label">At least 8 characters long</span>
                </div>
              </div>
              <div id="password-strength-indicator">Password strength: </div>
            </div>
          </div>
          <?php
          if (isset($_SESSION['password_error'])) {
            echo '<p id="password_error" style="color:red;font-size:small;">' . $_SESSION['password_error'] . '</p>';
            unset($_SESSION['password_error']);
          }
          ?>


          <div class="input-div pass">
            <div class="i">
              <i class="fas fa-lock"></i>
            </div>
            <div class="div">
              <h5>Confirm Password</h5>
              <input type="password" class="input" name="c_password" required oninput="hideCPasswordError()">
              <span class="required-message" style="display:none;">Please confirm your password.</span>
            </div>
          </div>
          <?php
          if (isset($_SESSION['cpassword_error'])) {
            echo '<p id="cpassword_error" style="color:red;font-size:small;">' . $_SESSION['cpassword_error'] . '</p>';
            unset($_SESSION['cpassword_error']);
          }
          ?>
          <div class="g-recaptcha" class="g-recaptcha" data-sitekey="6LfqmzclAAAAAKlJUzzfy1NVIoUSC3T7pRqWPgc7" required data-callback="hideRecaptchaError();"></div>
          <?php
          if (isset($_SESSION['recaptcha_error'])) {
            echo '<p style="color:red;font-size:small;">' . $_SESSION['recaptcha_error'] . '</p>';
            unset($_SESSION['recaptcha_error']);
          }
          ?>


          <button id="register-button" type="submit" class="btn btn-primary" aria-label="Sign up">
            <span>Sign Up</span>
          </button>
          <label for="">Already have an account?<a href="login.php" class="Register-link">Login</a></label>
          <span id="error-message" style="display:none;">There was an error with your registration. Please try again later.</span>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="js/main.js"></script>

<!-- check username wheather its exist or not in database -->
  <script>
    function checkUsername(username) {
      return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              resolve(xhr.responseText);
            } else {
              reject(xhr.responseText);
            }
          }
        };
        xhr.open("POST", "/check-username", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("username=" + username);
      });
    }
// hide the error message once the user start to type again
    function hideUsernameError() {
      var usernameError = document.getElementById("username-error");
      if (usernameError !== null) {
        usernameError.style.display = "none";
      }
    }

    function hideEmailError() {
      var emailError = document.getElementById("email-error");
      if (emailError !== null) {
        emailError.style.display = "none";
      }
    }

    function hidePasswordError() {
      var passwordError = document.getElementById("password_error");
      if (passwordError !== null) {
        passwordError.style.display = "none";
      }
    }

    function hideCPasswordError() {
      var cpasswordError = document.getElementById("cpassword_error");
      if (cpasswordError !== null) {
        cpasswordError.style.display = "none";
      }
    }

    function hideRecaptchaError() {
      var recaptchaError = document.getElementById("recaptcha_error");
      if (recaptchaError !== null) {
        recaptchaError.style.display = "none";
      }
    }

    function validateForm() {
      var username = document.forms["signup-form"]["username"].value;
      var email = document.forms["signup-form"]["email"].value;
      var password = document.forms["signup-form"]["password"].value;
      var c_password = document.forms["signup-form"]["c_password"].value;
      var errorMessage = document.getElementById("error-message");
      var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

      // Check password strength
      checkPasswordStrength();

      if (username == "") {
        document.getElementsByName("username")[0].nextElementSibling.style.display = "block";
        return false;
      } else {
        // Check if username already exists
        checkUsername(username).then(function(response) {
          // Username already exists
          document.getElementsByName("username")[0].nextElementSibling.style.display = "block";
          document.getElementsByName("username")[0].nextElementSibling.textContent = "Username already exists.";
          // Hide error message when user starts typing a new username
          document.getElementsByName("username")[0].addEventListener("input", function() {
            document.getElementsByName("username")[0].nextElementSibling.style.display = "none";
          });
        }).catch(function(error) {
          // Username is available
          document.getElementsByName("username")[0].nextElementSibling.style.display = "none";
        });
      }
      if (email == "") {
        document.getElementsByName("email")[0].nextElementSibling.style.display = "block";
        return false;
      } else {
        document.getElementsByName("email")[0].nextElementSibling.style.display = "none";
      }

      if (password == "") {
        document.getElementsByName("password")[0].nextElementSibling.style.display = "block";
        return false;
      } else if (!strongRegex.test(password)) {
        document.getElementsByName("password")[0].nextElementSibling.textContent = "Please enter a strong password.";
        document.getElementsByName("password")[0].nextElementSibling.style.display = "block";
        return false;
      } else {
        document.getElementsByName("password")[0].nextElementSibling.style.display = "none";
      }

      if (c_password == "") {
        document.getElementsByName("c_password")[0].nextElementSibling.style.display = "block";
        return false;
      } else if (password !== c_password) {
        document.getElementsByName("c_password")[0].nextElementSibling.style.display = "block";
        document.getElementsByName("c_password")[0].nextElementSibling.textContent = "Passwords do not match.";
        return false;
      } else {
        document.getElementsByName("c_password")[0].nextElementSibling.style.display = "none";
      }

      errorMessage.style.display = "none";
      return true;
    }
  </script>

  <script>
    const passwordInput = document.querySelector('#password-input');
    const passwordStrengthWrapper = document.querySelector('#password-strength-wrapper');
    const passwordStrengthLabel = document.querySelector('.password-strength-label');
    const labelHeight = passwordStrengthLabel.offsetHeight;
    const margin = 10;

    passwordInput.addEventListener('focus', showPasswordStrength);
    passwordInput.addEventListener('blur', hidePasswordStrength);
    passwordInput.addEventListener('input', checkPasswordStrength);

    function showPasswordStrength() {
      passwordStrengthWrapper.style.display = 'block';
      passwordStrengthWrapper.style.top = `${labelHeight + margin}px`;
    }

    function hidePasswordStrength() {
      passwordStrengthWrapper.style.display = 'none';
    }

    function checkPasswordStrength() {
      const password = passwordInput.value;

      const strengthCriteria = [{
          regex: /[A-Z]/,
          description: 'uppercase'
        },
        {
          regex: /[a-z]/,
          description: 'lowercase'
        },
        {
          regex: /[0-9]/,
          description: 'number'
        },
        {
          regex: /[@#$%^&+=]/,
          description: 'special'
        },
        {
          regex: /.{8,}/,
          description: 'length'
        }
      ];

      let criteriaMet = 0;
      let strengthLevel = '';

      for (let i = 0; i < strengthCriteria.length; i++) {
        const criteria = strengthCriteria[i];
        const rule = document.querySelector(`#password-rule-${criteria.description}`);

        if (criteria.regex.test(password)) {
          criteriaMet++;
          rule.classList.add('password-strength-rule-passed');
        } else {
          rule.classList.remove('password-strength-rule-passed');
        }
      }

      if (criteriaMet === strengthCriteria.length) {
        strengthLevel = 'strong';
      } else if (criteriaMet >= 3) {
        strengthLevel = 'moderate';
      } else {
        strengthLevel = 'weak';
      }

      const strengthIndicator = document.querySelector('#password-strength-indicator');
      strengthIndicator.classList.remove('password-strength-weak', 'password-strength-moderate', 'password-strength-strong');
      strengthIndicator.classList.add(`password-strength-${strengthLevel}`);
      strengthIndicator.textContent = `Password strength: ${strengthLevel}`;

    }

    // Hide the password strength wrapper initially
    hidePasswordStrength();
  </script>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>


</body>

</html>