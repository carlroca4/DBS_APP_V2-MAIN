<?php 

class database {

    function opencon(): PDO {
        $pdo = new PDO(
            dsn:'mysql:host=localhost;dbname=dbs_app',
            username: 'root',
            password: ''
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    function signupUser($firstname, $lastname, $username, $password) {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO admin (admin_FN, admin_LN, admin_username, admin_password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $username, $password]);
            $userId = $con->lastInsertId();
            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
}
