<?php

require_once '../../models/prompts_model.php';
require_once '../../models/users_model.php';
require_once '../../models/responses_model.php';

$promptsModel = new PromptsModel();
$usersModel = new UsersModel();
$responsesModel = new ResponsesModel();

$promptsList = $promptsModel->listPrompts();

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$loggedInUser = $_SESSION['displayName'];
$uid = $_SESSION['uid'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $getvars = $_GET;
    
    if (isset($getvars["action"])) {

        if ($getvars["action"] == 'deletePrompt') {

            $id = $getvars["promptID"];

            $result = $promptsModel->deletePrompt($id);
                if ($result) {
                    $message = "Prompt Deleted";
                    header('Location: ../prompts/prompts.php');
                } else {
                    $message = "Delete Prompt Failed";
                }
                

        }

    }
} 

include 'prompts_view.php';

?>