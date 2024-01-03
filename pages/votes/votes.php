<?php
require_once '../../models/votes_model.php';
require_once '../../models/users_model.php';
require_once '../../models/responses_model.php';

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$votesModel = new VotesModel();
$usersModel = new UsersModel();
$responsesModel = new ResponsesModel();

$getvars = $_GET;
$responseID=$getvars['responseID'];
$responderID=$getvars['userID'];
$type=$getvars['type'];
$promptID=$getvars['promptID'];

$votesList = $votesModel->listVotes($responseID);
$response = $responsesModel->getResponseFromID($responseID,$type);

$uid = $_SESSION['uid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $feedback = $_POST['feedback'];
    $result = $votesModel->vote($feedback,$responseID);

    if ($result) {
        $message = "Response Successful";
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        // if the result failed, it's likely because of the unique index on responseID + voterID
        $message = "Vote Failed. You may have already voted for this response.";
    }
    
} 

include 'votes_view.php';
?>