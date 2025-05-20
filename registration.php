<?php
session_start();
require_once('classes/database.php');
$con = new database();
 
$sweetAlertConfig="";
if(isset($_POST['register'])){
   $email = $_POST['email'];
  $username=$_POST['username'];
  $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
 
  $firstname= $_POST['first_name'];
  $lastname= $_POST['last_name'];
 
  $userID=$con->signupUser($firstname,$lastname,$username,$email,$password);
  if($userID){
    $sweetAlertConfig= "
    <script>
    Swal.fire({
      icon: 'success',
      title:'Registration Successful',
      text:'Your Account has been created successfuly!',
      confirmButtonText:'OK'})
      .then((result)=>{if(result.isConfirmed){window.location.href ='login.php';}
    });
    </script>";}else{
      $_SESSION['error']="Sorry, There was an error signing up.";
    }
  }
 
 
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Registration</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">Admin Registration</h2>
    <form id="registrationForm" method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name" required>
        <div class="invalid-feedback">First name is required.</div>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name" required>
        <div class="invalid-feedback">Last name is required.</div>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
        <div class="invalid-feedback">Username is required.</div>

      </div>
       <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        <div class="invalid-feedback">Email is required.</div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        <div class="invalid-feedback">Password must be at least 6 characters long, include an uppercase letter, a number, and a special character.</div>      
      </div>
      <button type="submit" id="registerButton" name="register" class="btn btn-primary w-100">Register</button>

    </form>
  </div>
  
  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>
  <script>
  // Function to validate individual fields
  function validateField(field, validationFn) {
    field.addEventListener('input', () => {
      if (validationFn(field.value)) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
      } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
      }
    });
  }

  // Validation functions for each field
  const isNotEmpty = (value) => value.trim() !== '';
  const isPasswordValid = (value) => {
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
    return passwordRegex.test(value);
  };

  // Real-time username validation using AJAX
  let isUsernameValid = false; // Track username validity
  let isEmailValid = false;    // Track email validity

  const checkUsernameAvailability = (usernameField) => {
    usernameField.addEventListener('input', () => {
      const username = usernameField.value.trim();
      const registerButton = document.getElementById('registerButton');

      if (username === '') {
        usernameField.classList.remove('is-valid');
        usernameField.classList.add('is-invalid');
        usernameField.nextElementSibling.textContent = 'Username is required.';
        registerButton.disabled = true;
        isUsernameValid = false;
        return;
      }

      
      fetch('ajax/check_username.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(username)}`,
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.exists) {
          usernameField.classList.remove('is-valid');
          usernameField.classList.add('is-invalid');
          usernameField.nextElementSibling.textContent = 'Username is already taken.';
          registerButton.disabled = true;
          isUsernameValid = false;
        } else if (data.error) {
          usernameField.classList.remove('is-valid');
          usernameField.classList.add('is-invalid');
          usernameField.nextElementSibling.textContent = data.error;
          registerButton.disabled = true;
          isUsernameValid = false;
        } else {
          usernameField.classList.remove('is-invalid');
          usernameField.classList.add('is-valid');
          usernameField.nextElementSibling.textContent = '';
          registerButton.disabled = false;
          isUsernameValid = true;
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        registerButton.disabled = true;
        isUsernameValid = false;
      });
    });
  }

  // Real-time email validation using AJAX
  const checkEmailAvailability = (emailField) => {
    emailField.addEventListener('input', () => {
      const email = emailField.value.trim();
      const registerButton = document.getElementById('registerButton');

      if (email === '') {
        emailField.classList.remove('is-valid');
        emailField.classList.add('is-invalid');
        emailField.nextElementSibling.textContent = 'Email is required.';
        registerButton.disabled = true;
        isEmailValid = false;
        return;
      }

      fetch('ajax/check_email.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}`,
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.exists) {
          emailField.classList.remove('is-valid');
          emailField.classList.add('is-invalid');
          emailField.nextElementSibling.textContent = 'Email is already taken.';
          registerButton.disabled = true;
          isEmailValid = false;
        } else if (data.error) {
          emailField.classList.remove('is-valid');
          emailField.classList.add('is-invalid');
          emailField.nextElementSibling.textContent = data.error;
          registerButton.disabled = true;
          isEmailValid = false;
        } else {
          emailField.classList.remove('is-invalid');
          emailField.classList.add('is-valid');
          emailField.nextElementSibling.textContent = '';
          registerButton.disabled = false;
          isEmailValid = true;
        }
      })
      .catch((error) => {
        console.error('Error:', error);
        registerButton.disabled = true;
        isEmailValid = false;
      });
    });
  }

  // Get form fields
  const firstName = document.getElementById('first_name');
  const lastName = document.getElementById('last_name');
  const username = document.getElementById('username');
  const email = document.getElementById('email');
  const password = document.getElementById('password');

  // Attach real-time validation to each field
  validateField(firstName, isNotEmpty);
  validateField(lastName, isNotEmpty);
  validateField(password, isPasswordValid);
  checkUsernameAvailability(username);
  checkEmailAvailability(email);

  // Form submission validation
  document.getElementById('registrationForm').addEventListener('submit', function (e) {
    let isValid = true;

    // Validate all fields on submit
    [firstName, lastName, username, email, password].forEach((field) => {
      if (!field.classList.contains('is-valid')) {
        field.classList.add('is-invalid');
        isValid = false;
      }
    });

    // Prevent submission if username is not valid (already exists or invalid)
    if (!isUsernameValid) {
      username.classList.add('is-invalid');
      username.nextElementSibling.textContent = username.value.trim() === '' ? 'Username is required.' : 'Username is already taken.';
      isValid = false;
    }

    // Prevent submission if email is not valid (already exists or invalid)
    if (!isEmailValid) {
      email.classList.add('is-invalid');
      email.nextElementSibling.textContent = email.value.trim() === '' ? 'Email is required.' : 'Email is already taken.';
      isValid = false;
    }

    // If all fields are valid, submit the form
    if (!isValid) {
      e.preventDefault();
    }
  });
</script>

    <!-- AJAX for live checking of existing emails (inside the registration.php) (CODE STARTS HERE) -->
<script>
$(document).ready(function(){
    function toggleNextButton(isEnabled) {
        $('#nextButton').prop('disabled', !isEnabled);
    }
 
    $('#email').on('input', function(){
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: 'AJAX/check_email.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Email is already taken
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#emailFeedback').text('Email is already taken.').show();
                        $('#email')[0].setCustomValidity('Email is already taken.');
                        $('#email').siblings('.invalid-feedback').not('#emailFeedback').hide();
                        toggleNextButton(false); // ❌ Disable next button
                    } else {
                        // Email is valid and available
                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').text('').hide();
                        $('#email')[0].setCustomValidity('');
                        $('#email').siblings('.valid-feedback').show();
                        toggleNextButton(true); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#email').removeClass('is-valid is-invalid');
            $('#emailFeedback').text('').hide();
            $('#email')[0].setCustomValidity('');
            toggleNextButton(false); // ❌ Disable next button
        }
    });
 
    $('#email').on('invalid', function() {
        if ($('#email')[0].validity.valueMissing) {
            $('#email')[0].setCustomValidity('Please enter a valid email.');
            $('#emailFeedback').hide();
            toggleNextButton(false); // ❌ Disable next button
        }
    });
});
</script>
 
   <!-- AJAX for live checking of existing emails (inside the registration.php) (CODE STARTS HERE) -->
<script>
$(document).ready(function(){
    function toggleNextButton(isEnabled) {
        $('#nextButton').prop('disabled', !isEnabled);
    }
 
    $('#username').on('input', function(){
        var username = $(this).val();
        if (username.length > 0) {
            $.ajax({
                url: 'AJAX/check_username.php',
                method: 'POST',
                data: { username: username }, // Corrected key to 'username'
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Username is already taken
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#usernameFeedback').text('Username is already taken.').show();
                        $('#username')[0].setCustomValidity('Username is already taken.');
                        $('#username').siblings('.invalid-feedback').not('#usernameFeedback').hide();
                        toggleNextButton(false); // ❌ Disable next button
                    } else {
                        // Username is valid and available
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                        $('#usernameFeedback').text('').hide();
                        $('#username')[0].setCustomValidity('');
                        $('#username').siblings('.valid-feedback').show();
                        toggleNextButton(true); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#username').removeClass('is-valid is-invalid');
            $('#usernameFeedback').text('').hide();
            $('#username')[0].setCustomValidity('');
            toggleNextButton(false); // ❌ Disable next button
        }
    });
 
    $('#username').on('invalid', function() {
        if ($('#username')[0].validity.valueMissing) {
            $('#username')[0].setCustomValidity('Please enter a valid username.');
            $('#usernameFeedback').hide();
            toggleNextButton(false); // ❌ Disable next button
        }
    });
});
</script>
 

</body>
</html>