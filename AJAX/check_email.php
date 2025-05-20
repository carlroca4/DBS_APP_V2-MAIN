<?php
require_once '../classes/database.php';
$con = new database();

header('Content-Type: application/json');

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    if ($email === '') {
        echo json_encode(['error' => 'Email is required.']);
    } else if ($con->isEmailExists($email)) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['error'=> 'Invalid Request']);
}
?>
