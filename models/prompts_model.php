<?php

class PromptsModel {
    public $prompts = array();
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

    function getPromptInfoFromID($idPrompts) { 

        try {
            $stmt = $this->db->prepare('SELECT * FROM prompts WHERE idPrompts=:idPrompts');
            $stmt->bindParam(':idPrompts', $idPrompts, PDO::PARAM_STR);
            $stmt->execute();
            $this->prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
            
        return $this->prompts[0];   
    }

    function getTimePosted($idPrompts) {
        try {
            $stmt = $this->db->prepare('SELECT timePosted FROM prompts WHERE idPrompts=:idPrompts');
            $stmt->bindParam(':idPrompts', $idPrompts, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function addPrompt ($type,$prompt) { 

        $tz = new DateTimeZone('America/New_York');
        $dateTime = new DateTime('now', $tz);
        $timePosted = $dateTime->format('Y-m-d H:i:s');

        try {

            $this->db->beginTransaction();
        
            $stmtPrompt = $this->db->prepare(
                "INSERT INTO prompts(posterID,type,prompt,timePosted) 
                VALUES(:posterID,:type,:prompt,:timePosted)"
            );

            $posterID = $_SESSION['uid']; // get logged in user's ID

            $stmtPrompt->execute(array(
                ':posterID' => $posterID,
                ':type' => $type,
                ':prompt' => $prompt,
                ':timePosted' => $timePosted
            ));

            $this->db->commit();
            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }

    }

    function editPrompt ($promptID,$promptText) { // not implemented

        try {

            $this->db->beginTransaction();
            $stmt = $this->db->prepare('UPDATE prompts SET prompt=:prompt WHERE idPrompts=:idPrompts');

            $stmt->bindParam(':prompt', $promptText, PDO::PARAM_STR);
            $stmt->bindParam(':idPrompts', $promptID, PDO::PARAM_INT);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    }

    public function listPrompts () { 
        try {
            $stmt = $this->db->query('SELECT * FROM prompts ORDER BY idPrompts DESC');
            $this->prompts = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        
        return $this->prompts;
    }

    function listPromptsByUser($userID) {

        try {
            $stmt = $this->db->query("SELECT p.idPrompts, p.posterID, p.type, p.prompt, p.timePosted
                FROM prompts p
                JOIN users u ON u.idUsers = p.posterID
                WHERE p.posterID = '$userID'"
            );
            $this->prompts = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        
        return $this->prompts;

    }

    function deletePrompt ($idPrompts) {

        try {

            $this->db->query('SET foreign_key_checks = 0');
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('DELETE FROM prompts WHERE idPrompts=:idPrompts');
            $stmt->bindParam(':idPrompts', $idPrompts, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();

            $this->db->query('SET foreign_key_checks = 1');

            return true;
            
        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    }

    function deletePromptAndResponses ($idPrompts) { // not implemented

        try {
            
            $this->db->beginTransaction();
            
            $stmtPrompt = $this->db->prepare('DELETE FROM prompts WHERE idPrompts=:idPrompts');
            $stmtResponses = $this->db->prepare('DELETE FROM responses WHERE promptID=:promptID');

            $stmtPrompt->bindParam(':idPrompts', $idPrompts, PDO::PARAM_INT);
            $stmtResponses->bindParam(':promptID',$idPrompts, PDO::PARAM_INT);

            $stmtResponses->execute();
            $stmtPrompt->execute();

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

