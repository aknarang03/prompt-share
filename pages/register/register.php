<?php

require_once '../../models/users_model.php';
require_once '../../models/credentials_model.php';

$usersModel = new UsersModel();
$credentialsModel = new CredentialsModel();

$message = '';

$email = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailTaken = false;
    $usernameTaken = false;
    $putNull = false;
    $getvars = $_GET;
    
    if (isset($getvars["action"]) && $getvars["action"] == 'add') {

        // first ensure that email and username are not taken
        // JS takes care of validity otherwise

        if ($credentialsModel->emailTaken($_POST['email'])) {
            $emailTaken = true;
            $username = $_POST['username'];
            $message = "email taken";
        }

        if ($credentialsModel->usernameTaken($_POST['username'])) {
            $usernameTaken = true;
            $email = $_POST['email'];
            $message = "username taken";
        }

        if (!$emailTaken && !$usernameTaken) { // if both are not taken, add to db

            $result = $usersModel->addUserAndCredential($_POST['email'],$_POST['username'],$_POST['password']);

            if ($result) {
                $message = "Enroll Successful";
            } else {
                $message = "Enroll Failed";
            }

        }

    }
} 

include 'register_view.php'
?>