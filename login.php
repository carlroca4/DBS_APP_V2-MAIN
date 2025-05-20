<?php
session_start();
require_once 'classes/database.php';
$con = new database();
$sweetAlertConfig = "";
if (isset($_POST['Login'])) {
    $username = ($_POST['username']);
    $password = ($_POST['password']);
    $user = $con->loginUser($username, $password);
    if ($user) { 
        $_SESSION['admin_ID'] = $user['admin_id'];
        $_SESSION['admin_FN'] = $user['admin_FN'];
        $sweetAlertConfig = '<script>
        Swal.fire({
            icon: "success",
            title: "Login Successful",
            text: "Welcome, ' . addslashes(htmlspecialchars($user['admin_FN'])) . '!",
            confirmButtonText: "Continue"
        }).then(() => {
            window.location.href = "index.php";
        });
        </script>';
    } else {
        $sweetAlertConfig = '<script>
        Swal.fire({
            icon: "error",
            title: "Login Failed",
            text: "Invalid username or password."
        });
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Hello Admin</a>
    <div class="d-flex ms-auto">

    </div>
  </div>
</nav>
  <div class="container py-5">
    <h2 class="mb-4 text-center">User Login</h2>
    <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" name="Login" class="btn btn-primary w-100">Login</button>
    </form>
    <?php echo $sweetAlertConfig; ?>
    
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>