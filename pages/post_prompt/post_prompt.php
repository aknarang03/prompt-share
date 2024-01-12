<?php

require_once '../../models/prompts_model.php';
//require_once '../../models/users_model.php';

$promptsModel = new PromptsModel();
//$usersModel = new UsersModel();

$message = "";
$type = "";
$prompt="";

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$uid = $_SESSION['uid'];
//$userTimezone = $usersModel->getTimezoneFromID($uid);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $getvars = $_GET;
    
    if (isset($getvars["action"]) && $getvars["action"] == 'add') {

        $type = $_POST['type'];
        $prompt=$_POST['prompt'];
      
        $result = $promptsModel->addPrompt($type,$prompt);

        if ($result) {
            $message = "Add Successful";
            echo "<meta http-equiv='refresh' content='0,URL='./prompts/prompts.php'";
        } else {
            $message = "Add Failed";
        }

    }

} 

include 'post_prompt_view.php';
?>