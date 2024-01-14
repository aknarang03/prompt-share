<?php

class VotesModel {
    public $votes = array();
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

    function getVoteCountFromResponseID($responseID) { 
        try {
            $stmt = $this->db->prepare('SELECT * FROM votes WHERE responseID=:responseID');
            $stmt->bindParam(':responseID', $responseID, PDO::PARAM_STR);
            $stmt->execute();
            $this->votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        } 
        return count($this->votes);  
    }

    function vote ($feedback, $responseID, $voteType) { 
        try {
    
            $this->db->beginTransaction();
            
            $stmtVote = $this->db->prepare(
                "INSERT INTO votes(feedback,voterID,responseID,voteType)  
                VALUES(:feedback,:voterID,:responseID,:voteType)" 
            );

            $voterID = $_SESSION['uid'];

            $stmtVote->execute(array(
                ':feedback' => $feedback,
                ':voterID' => $voterID,
                ':responseID' => $responseID,
                ':voteType' => $voteType
            ));

            $this->db->commit();
            return true;

        } catch (Exception $ex) {
            $this->db->rollBack();
            return false;
        }
    }
    
    function listVotes($responseID) {
        try {
            $stmt = $this->db->query("SELECT v.idVotes, v.feedback, v.voterID, v.responseID
                FROM votes v
                JOIN responses r ON r.idResponses = v.responseID
                WHERE v.responseID = '$responseID'"
            );
            $this->votes = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
 
        return $this->votes;
    }

    function deleteVote ($idVotes) { // not implemented
        try {
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('DELETE FROM votes WHERE idVotes=:idVotes');
            $stmt->bindParam(':idVotes', $idVotes, PDO::PARAM_INT);
            $stmt->execute();

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
