<?php

require_once '../../models/prompts_model.php';
require_once '../../models/responses_model.php';
require_once '../../models/users_model.php';
require_once '../../models/votes_model.php';

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$uid = $_SESSION['uid'];

$promptsModel = new PromptsModel();
$responsesModel = new ResponsesModel();
$usersModel = new UsersModel();
$votesModel = new VotesModel();

$getvars = $_GET;
$idPrompts=$getvars['idPrompts'];

$promptInfo = $promptsModel->getPromptInfoFromID($idPrompts);
$prompt = $promptInfo['prompt'];
$type = $promptInfo['type'];
$posterID = $promptInfo['posterID'];
$timePosted = $promptInfo['timePosted'];
$posterUsername = $usersModel->getUsernameFromID($posterID);
$posterPfp = $usersModel->getProfilePicture($posterID);

$timestamp = strtotime($timePosted);
$echoTimestamp = date('F j, Y g:i A', $timestamp);

$responsesList = $responsesModel->listResponsesByPrompt($type,$idPrompts);

// GET TOP VOTED RESPONSE
$topVotedID = $responsesModel->getTopVoted($idPrompts);
$topVotedResponse = $responsesModel->getResponseFromID($topVotedID,$type);
$topVotedUserID = $responsesModel->getResponderFromID($topVotedID);
$topVotedUser = $usersModel->getUsernameFromID($topVotedUserID);
$topVotedUserPfp = $usersModel->getProfilePicture($topVotedUserID);

$showEditPromptForm = false;
$showEditResponseForm = false;
$responseToEdit = -1;

function saveFile($unique) {
    $targetDir = "../../images/";
    $targetFile = $targetDir . $unique . basename( $_FILES["response"]["name"] );
    $uploaded = move_uploaded_file($_FILES["response"]["tmp_name"], $targetFile);

    if (!$uploaded) {
        $message =  "Error uploading file";
    }
}

function getMinutesBtwn($timePosted) {

    $tz = new DateTimeZone('America/New_York');
    $timePostedUnformat = DateTime::createFromFormat('Y-m-d H:i:s', $timePosted, $tz);
    $timeNow = new DateTime('now', $tz);
    $timeBtwn = $timeNow->diff($timePostedUnformat);
    $minutesBtwn = ($timeBtwn->d * 24 * 60) + ($timeBtwn->h * 60) + $timeBtwn->i;
    
    return $minutesBtwn;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($getvars["action"])) {

        if ($getvars["action"]=="addResponse") {
 
            $response = $_POST['response'];

            if ($type == "Writing") {
                $result = $responsesModel->addWritingResponse($idPrompts,$response);
            }
            
            if ($type == "Drawing" && $_FILES['response']['tmp_name'] != "") {
                $unique = uniqid();
                $uploadedFile = $unique.$_FILES['response']['name'];
                $result = $responsesModel->addDrawingResponse($idPrompts,$uploadedFile);
            }

            if ($result) {
                saveFile($unique);
                $message = "Response Successful";
                header('Location: ../prompt/prompt.php?idPrompts='.$idPrompts);
            } else {
                $message = "Response Failed";
            }
        }

        if ($getvars["action"] == 'deleteResponse') {

            $id = $getvars["responseID"];
            if ($type == "Drawing") { // if the response user is deleting is an image, find the image file
                $file = $responsesModel->getResponseFromID($id,$type);
                $targetDir = "../../images/";
                $fileToDelete = $targetDir.$file;
            }

            $result = $responsesModel->deleteResponse($id); // delete image filename from database
                if ($result) {
                    if ($type == "Drawing") {unlink($fileToDelete);} // delete the found image from images directory
                    $message = "Response Deleted";
                    header('Location: ../prompt/prompt.php?idPrompts='.$idPrompts);
                } else {
                    $message = "Delete Response Failed";
                }
                

        }

        if ($getvars["action"] == 'editPrompt') {

            // Note: even though edit button will hide after some time, this still needs to be checked just in case someone stayed on the page
            // for over the allotted time and hasn't reloaded so they still have the button.

            $timePosted = $promptsModel->getTimePosted($getvars['idPrompts']);
            $minutesBtwn = getMinutesBtwn($timePosted);

            if ($minutesBtwn <=5) {
                $showEditPromptForm = true;
            } else {
                $message = "This prompt cannot be edited as over 5 minutes have elapsed since posting.";
            }

        }

        if ($getvars["action"] == 'editResponse') {

            $responseToEdit = $getvars['responseID'];

            $timePosted = $responsesModel->getTimePosted($getvars['responseID']);
            $minutesBtwn = getMinutesBtwn($timePosted);

            if ($minutesBtwn <=15) {
                $showEditResponseForm = true;
            } else {
                $message = "This response cannot be edited as over 5 minutes have elapsed since posting.";
            }

        }

        if ($getvars["action"] == 'completePromptEdit') {
 
            if ($_POST['button'] == 'Edit') {

                $timePosted = $promptsModel->getTimePosted($getvars['idPrompts']);
                $minutesBtwn = getMinutesBtwn($timePosted);
        
                if ($minutesBtwn <=5) { 
                    $promptsModel->editPrompt($idPrompts,$_POST['prompt']);
                    $showEditPromptForm = false;
                    header('Location: ../prompt/prompt.php?idPrompts='.$idPrompts);
                } else {
                    $message = "This response cannot be edited as over 5 minutes have elapsed since posting.";
                }

            }
            
        }

        if ($getvars["action"] == 'completeResponseEdit') {
 
            if ($_POST['button'] == 'Edit') {

                $responseID = $getvars['responseID'];
                $timePosted = $responsesModel->getTimePosted($responseID);
                $minutesBtwn = getMinutesBtwn($timePosted);
        
                if ($minutesBtwn <=15) { 
                    $responsesModel->editWritingResponse($responseID,$_POST['response']);
                    $showEditPromptForm = false;
                    header('Location: ../prompt/prompt.php?idPrompts='.$idPrompts);
                } else {
                    $message = "This response cannot be edited as over 15 minutes have elapsed since posting.";
                }

                $responseToEdit = -1;

            }
            
        }

    }
} 

include 'prompt_view.php';

?>