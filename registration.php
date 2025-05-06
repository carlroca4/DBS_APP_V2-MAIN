<?php
session_start();
require_once('classes/database.php');
$con = new database();
 
$sweetAlertConfig="";
if(isset($_POST['register'])){
  $username=$_POST['username'];
  $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
 
  $firstname= $_POST['first_name'];
  $lastname= $_POST['last_name'];
 
  $userID=$con->signupUser($firstname,$lastname,$username,$password);
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
  <link rel="stylesheet" href=./package/dist/sweetalert2.css>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">Admin Registration</h2>
    <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name" required>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    </form>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="./package/dist/sweetalert2.js">
  </script>
  <?php if(isset($_SESSION['error'])): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '<?php echo addslashes($_SESSION['error']);?>',
      confirmButtonText: 'OK'
    })
  </script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php echo $sweetAlertConfig; ?>
</body>
</html>