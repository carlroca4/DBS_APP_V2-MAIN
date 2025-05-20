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

    function signupUser($firstname, $lastname, $username, $email, $password) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO admin (admin_FN, admin_LN, admin_username, user_email, admin_password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $username, $email, $hashedPassword]);
            $userId = $con->lastInsertId();
            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function isUsernameExists($username) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM admin WHERE admin_username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    function isEmailExists($email) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT COUNT(*) FROM admin WHERE user_email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    function loginUser($username, $password) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM admin WHERE admin_username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['admin_password'])) {
            return $user;
        } else {
            return false;
        }
    }
}
