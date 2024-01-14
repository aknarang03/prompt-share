<?php

class ResponsesModel {
    public $responses = array();
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

    function voteUp($idResponses){

        try {
    
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('UPDATE responses 
            SET score = score + 1
            WHERE idResponses=:idResponses');

            $stmt->bindParam(':idResponses',$idResponses,PDO::PARAM_INT);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }

    }

    function voteDown($idResponses){

        try {
    
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('UPDATE responses 
            SET score = score - 1
            WHERE idResponses=:idResponses');

            $stmt->bindParam(':idResponses',$idResponses,PDO::PARAM_INT);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }

    }

    function getScoreFromID($idResponses) {
        try {
            $stmt = $this->db->prepare('SELECT score FROM responses WHERE idResponses=:idResponses');
            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function getResponderFromID($idResponses) {
        try {
            $stmt = $this->db->prepare('SELECT responderID FROM responses WHERE idResponses=:idResponses');
            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function getTimePosted($idResponses) {
        try {
            $stmt = $this->db->prepare('SELECT timePosted FROM responses WHERE idResponses=:idResponses');
            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function getResponseCountFromPromptID($promptID) { 
        try {
            $stmt = $this->db->prepare('SELECT * FROM responses WHERE promptID=:promptID');
            $stmt->bindParam(':promptID', $promptID, PDO::PARAM_INT);
            $stmt->execute();
            $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        return count($this->responses);  
    }

    function getResponseFromID($idResponses,$type) { 

        try {
            $stmt = $this->db->prepare('SELECT * FROM responses WHERE idResponses=:idResponses');
            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_STR);
            $stmt->execute();
            $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        
        if ($type == 'Writing') {
            return $this->responses[0]['textResponse']; 
        } else { // $type == 'Drawing'
            return $this->responses[0]['imgResponseFilename'];
        }

    }

    function getType($idResponses) {
        try {

            $stmt = $this->db->prepare('SELECT p.type FROM prompts p 
            JOIN responses r ON p.idPrompts = r.promptID
            WHERE r.idResponses=:idResponses');

            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
            
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
    }

    function addWritingResponse ($promptID, $textResponse) { 

        $timezone = new DateTimeZone('America/New_York');
        
        $dateTime = new DateTime('now', $timezone);
        $timePosted = $dateTime->format('Y-m-d H:i:s');

        try {
    
            $this->db->beginTransaction();
            
            $stmtResponse = $this->db->prepare(
                "INSERT INTO responses(responderID,promptID,textResponse,timePosted,score)  
                VALUES(:responderID,:promptID,:textResponse,:timePosted,:score)" 
            );

            $responderID = $_SESSION['uid']; // get logged in user's ID

            $stmtResponse->execute(array(
                ':responderID' => $responderID,
                ':promptID' => $promptID,
                ':textResponse' => $textResponse,
                ':timePosted' => $timePosted,
                ':score' => 0
            ));

            $this->db->commit();
            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }
    }

    function addDrawingResponse ($promptID, $imgResponseFilename) { 

        $timezone = new DateTimeZone('America/New_York');

        $dateTime = new DateTime('now', $timezone);
        $timePosted = $dateTime->format('Y-m-d H:i:s');

        try {
    
            $this->db->beginTransaction();
            
            $stmtResponse = $this->db->prepare(
                "INSERT INTO responses(responderID,promptID,imgResponseFilename,timePosted,score)  
                VALUES(:responderID,:promptID,:imgResponseFilename,:timePosted,:score)"
            );

            $responderID = $_SESSION['uid']; // get logged in user's ID

            $stmtResponse->execute(array(
                ':responderID' => $responderID,
                ':promptID' => $promptID,
                ':imgResponseFilename' => $imgResponseFilename,
                ':timePosted' => $timePosted,
                ':score' => 0
            ));

            $this->db->commit();
            return true;

        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            $this->db->rollBack();
            return false;
        }
    }

    function editWritingResponse ($responseID,$textResponse) { // not implemented
        try {

            $this->db->beginTransaction();
            $stmt = $this->db->prepare('UPDATE responses SET textResponse=:textResponse WHERE idResponses=:idResponses');

            $stmt->bindParam(':textResponse', $textResponse, PDO::PARAM_STR);
            $stmt->bindParam(':idResponses', $responseID, PDO::PARAM_INT);

            $stmt->execute();
            $this->db->commit();

            return true;

        } catch (PDOException $ex) {
            var_dump($ex->getMessage());
            $this->db->rollback();
            return false;
        }

    }
    
    function listResponsesByPrompt($type, $promptID) {

        if ($type == "Writing") {
            try {
                $stmt = $this->db->query("SELECT r.idResponses, r.responderID, r.promptID, r.textResponse, r.timePosted
                    FROM responses r
                    JOIN prompts p ON r.promptID = p.idPrompts
                    WHERE p.idPrompts = '$promptID'"
                );
                $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            } catch(PDOException $ex) {
                var_dump($ex->getMessage());
            }
        }

        if ($type == "Drawing") {
            try {
                $stmt = $this->db->query("SELECT r.idResponses, r.responderID, r.promptID, r.imgResponseFilename, r.timePosted
                    FROM responses r
                    JOIN prompts p ON r.promptID = p.idPrompts
                    WHERE p.idPrompts = '$promptID'"
                );
                $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            } catch(PDOException $ex) {
                var_dump($ex->getMessage());
            }
        }
            
        return $this->responses;
    }

    function listResponsesByUser($userID) {
        try {
            $stmt = $this->db->query("SELECT r.idResponses, r.responderID, r.promptID, r.textResponse, r.imgResponseFilename, r.timePosted
                FROM responses r
                JOIN users u ON u.idUsers = r.responderID
                WHERE r.responderID = '$userID'"
            );
            $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }
        return $this->responses;
    }

    function getTopVoted($promptID) { // currently doesn't account for ties
        try {
            $stmt = $this->db->query("SELECT r.promptID, v.responseID, COUNT(v.responseID) as voteCount 
            FROM votes v 
            JOIN responses r ON v.responseID = r.idResponses 
            WHERE r.promptID = $promptID
            GROUP BY responseID 
            ORDER BY voteCount DESC;"
            );
            $this->responses = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $ex) {
            var_dump($ex->getMessage());
        }

        return $this->responses[0]['responseID']; // the first element in the list is the one with the most votes
    }

    function deleteResponse ($idResponses) {
        try {
            
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('DELETE FROM responses WHERE idResponses=:idResponses');
            $stmtVotes = $this->db->prepare('DELETE FROM votes WHERE responseID=:responseID');

            $stmt->bindParam(':idResponses', $idResponses, PDO::PARAM_INT);
            $stmtVotes->bindParam(':responseID',$idResponses,PDO::PARAM_INT);

            $stmtVotes->execute();
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
