<?php

class CredentialsModel {
    public $credentials = array();
    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;port=8889;dbname=prompts_db;charset=utf8', 'promptsiteuser', 'siteuserpass1');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    function updateEmail ($email) {

        $userID = $_SESSION['uid'];

        try {

            $this->db->beginTransaction();
            $stmt = $this->db->prepare('UPDATE credentials SET email=:email WHERE userID=:userID');

            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    }

    function searchByName ($username) {
        try {
            $stmt = $this->db->prepare('SELECT userID FROM credentials WHERE username=:username');
            $stmt->bindParam(':username',$username,PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function updatePassword ($password) {

        $userID = $_SESSION['uid'];

        try {

            $this->db->beginTransaction();
            $stmt = $this->db->prepare('UPDATE credentials SET password=PASSWORD(:password) WHERE userID=:userID');

            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }


    }

    function updateUsername ($username) {

        $userID = $_SESSION['uid'];

        try {

            $this->db->beginTransaction();
            $stmtUser = $this->db->prepare('UPDATE users SET displayName=:displayName WHERE idUsers=:idUsers');
            $stmtCredential = $this->db->prepare('UPDATE credentials SET username=:username WHERE userID=:userID');

            $stmtUser->bindParam(':idUsers', $userID, PDO::PARAM_INT);
            $stmtUser->bindParam(':displayName', $username, PDO::PARAM_STR);
            $stmtCredential->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmtCredential->bindParam(':username', $username, PDO::PARAM_STR);

            $stmtUser->execute();
            $stmtCredential->execute();
            $this->db->commit();

            return true;

        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    }

    public function authenticate($usernameOrEmail, $password) {

        $isUsername = true;

        if (str_contains($usernameOrEmail,"@")) {
            $isUsername = false;
        }

        try { // use email to login if user entered it, otherwise use username

            if ($isUsername) {
                $stmt = $this->db->prepare('SELECT userID FROM credentials WHERE username=:username and password=PASSWORD(:password)');
                $stmt->bindParam(':username', $usernameOrEmail, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            } else {
                $stmt = $this->db->prepare('SELECT userID FROM credentials WHERE email=:email and password=PASSWORD(:password)');
                $stmt->bindParam(':email', $usernameOrEmail, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            }

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && sizeof($result)>0) {
                return $result[0]['userID'];
            } else {
                return false;
            }

        } catch(PDOException $ex) {
             var_dump($ex->getMessage());
        }

    }

    public function emailTaken($email) {

        try {

            $stmt = $this->db->prepare('SELECT email FROM credentials where email=:email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && sizeof($result)>0) {
                return true;
            } else {
                return false;
            }

        } catch(PDOException $ex) {
             var_dump($ex->getMessage());
        }

    }

    public function usernameTaken($username) {

        try {

            $stmt = $this->db->prepare('SELECT username FROM credentials where username=:username');
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && sizeof($result)>0) {
                return true;
            } else {
                return false;
            }

        } catch(PDOException $ex) {
             var_dump($ex->getMessage());
        }

    }
    
}

?>
