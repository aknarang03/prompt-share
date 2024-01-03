<?php
require_once '../../models/users_model.php';
require_once '../../models/prompts_model.php';
require_once '../../models/responses_model.php';


$promptsModel = new PromptsModel();
$responsesModel = new ResponsesModel();
$usersModel = new UsersModel();

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$getvars = $_GET;

$idUsers=$getvars['userID'];
$loggedInUserID = $_SESSION['uid'];

$loggedIn = ($loggedInUserID == $idUsers);

$username = $usersModel->getUsernameFromID($idUsers);
$profilePic = $usersModel->getProfilePicture($idUsers);

// if someone edits the link to have a user ID that doesn't link to anyone, redirect away
if ($username == null){ // the session must be set because we already checked for that
    header('Location: ../prompts/prompts.php'); // so redirect to prompts / main page
}

$promptsList = $promptsModel->listPromptsByUser($idUsers);
$responsesList = $responsesModel->listResponsesByUser($idUsers);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $getvars = $_GET;
    
    if (isset($getvars["action"])) {

        if ($getvars["action"] == 'deletePrompt') {

            $id = $getvars["promptID"];

            $result = $promptsModel->deletePrompt($id);
                if ($result) {
                    $message = "Prompt Deleted";
                    header('Location: ../user/user.php?userID='.$loggedInUserID);
                } else {
                    $message = "Delete Prompt Failed";
                }

        }

        if ($getvars["action"] == 'deleteResponse') {

            $id = $getvars["responseID"];
            $type = $responsesModel->getType($id);
            
            if ($type == "Drawing") {
                $file = $responsesModel->getResponseFromID($id,$type);
                $targetDir = "../../images/";
                $fileToDelete = $targetDir.$file;
            }

            $result = $responsesModel->deleteResponse($id);
                if ($result) {
                    if ($type == "Drawing") {unlink($fileToDelete);}
                    $message = "Response Deleted";
                    header('Location: ../user/user.php?userID='.$loggedInUserID);
                } else {
                    $message = "Delete Response Failed";
                }

        }

    }
} 

include 'user_view.php';
?>