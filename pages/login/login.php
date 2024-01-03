<?php

require_once '../../models/users_model.php';
require_once '../../models/credentials_model.php';

// redirect user to prompts page if they are logged in
session_start();
if (isset($_SESSION['uid'])){
    header('Location: ../prompts/prompts.php');
}

$usersModel = new UsersModel();

$message = "Enter username/email and password.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $getvars = $_GET;

    if (isset($getvars["action"]) && $getvars["action"] == 'login') {

        $credentialsModel = new CredentialsModel();
        $validLogin = $credentialsModel->authenticate($_POST["usernameOrEmail"], $_POST["password"]);

        if ($validLogin) {
            session_start();
            // set up session vars
            $_SESSION['uid'] = $validLogin;
            $_SESSION['displayName'] = $usersModel->getUsernameFromID($validLogin);
            header ('Location: ../prompts/prompts.php');
        } else {
            $message = "Invalid username/email and/or password.";
        }
    }

}

else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $getvars = $_GET;

    // LOGOUT
    if (isset($getvars["action"]) && $getvars["action"] == 'logout') {
        session_start();
        $_SESSION = array();
        session_destroy();
        session_unset();
        $message = "Logged out";
    }
    
}

include 'login_view.php'

?>
