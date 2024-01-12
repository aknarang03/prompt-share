<?php

class UsersModel {
    public $users = array();
    private $db;

    function __construct() {
        try {
            $this->db = new PDO('mysql:host=localhost;port=8889;dbname=prompts_db;charset=utf8', 'promptsiteuser', 'siteuserpass1');
            //$this->db = new PDO('mysql:host=files.000webhost.com;port=8889;dbname=prompts_db;charset=utf8', 'promptsiteuser', 'siteuserpass1');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo "Connection failed". $e->getMessage() ."";
        }
    }

    function updateProfilePicture($filename) {

        try {
    
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('UPDATE users 
            SET profilePicFilename=:profilePicFilename 
            WHERE idUsers=:idUsers');
            
            $userID = $_SESSION['uid']; // get logged in user's ID

            $stmt->bindParam(':profilePicFilename',$filename,PDO::PARAM_STR);
            $stmt->bindParam(':idUsers',$userID,PDO::PARAM_INT);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }

    }

    function getUsernameFromID($idUsers) {
        try {
            $stmt = $this->db->prepare('SELECT displayName FROM users WHERE idUsers=:idUsers');
            $stmt->bindParam(':idUsers', $idUsers, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function getProfilePicture($idUsers) {
        try {
            $stmt = $this->db->prepare('SELECT profilePicFilename FROM users WHERE idUsers=:idUsers');
            $stmt->bindParam(':idUsers', $idUsers, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function listUsers() {
        try {
            $stmt = $this->db->query("SELECT * FROM users");
            $this->users = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        return $this->users;
    }
    

    function addUserAndCredential ($email,$username,$password) { // used for signup

        try {

            $this->db->beginTransaction();
        
            $stmtUser = $this->db->prepare(
                "INSERT INTO users(displayName) 
                VALUES(:displayName)"
            );

            $stmtCredential = $this->db->prepare(
                "INSERT INTO credentials(email,username,password,userID) 
                VALUES(:email,:username,PASSWORD(:password),:userID)"
            );

            $stmtUser->execute(array(':displayName' => $username));

            $uid = $this->db->lastInsertId();

            $stmtCredential->execute(array(
                ':email' => $email, 
                ':username' => $username,
                ':password' => $password,
                ':userID' => $uid
            ));

            $this->db->commit();
            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }

    }

    function deleteUser () { // deletes user AND all their data

        $uid = $_SESSION['uid'];

        try {
            
            $this->db->beginTransaction();
            $stmtUser = $this->db->prepare('DELETE FROM users WHERE idUsers=:idUsers');
            $stmtCredential = $this->db->prepare('DELETE FROM credentials WHERE userID=:userID');
            $stmtPrompts = $this->db->prepare('DELETE FROM prompts WHERE posterID=:posterID');
            $stmtResponses = $this->db->prepare('DELETE FROM responses WHERE responderID=:responderID');
            $stmtVotes = $this->db->prepare('DELETE FROM votes WHERE voterID=:voterID');

            $stmtUser->bindParam(':idUsers', $uid, PDO::PARAM_INT);
            $stmtCredential->bindParam(':userID', $uid, PDO::PARAM_INT);
            $stmtPrompts->bindParam(':posterID', $uid, PDO::PARAM_INT);
            $stmtResponses->bindParam(':responderID', $uid, PDO::PARAM_INT);
            $stmtVotes->bindParam(':voterID', $uid, PDO::PARAM_INT);

            $stmtVotes->execute();
            $stmtResponses->execute();
            $stmtPrompts->execute();
            $stmtCredential->execute();
            $stmtUser->execute();

            $this->db->commit();

            return true;
            
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    } 
    
}

?>
