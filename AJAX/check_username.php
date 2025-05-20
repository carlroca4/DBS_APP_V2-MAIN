<?php
require_once('../classes/database.php');
$con = new database();

if (isset($_POST['username']))  {
    $username = trim($_POST['username']);
    if ($username === '') {
        echo json_encode(['error' => 'Username is required.']);
    } else if ($con->isUsernameExists($username)) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['error'=> 'Invalid Request']);
}