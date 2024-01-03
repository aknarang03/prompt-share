<?php
require_once '../../models/users_model.php';
require_once '../../models/credentials_model.php';

$usersModel = new UsersModel();
$credentialsModel = new CredentialsModel();

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$uid = $_SESSION['uid'];

$usersList = $usersModel->listUsers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['username'] == null) {
        $putNull = true;
        $message = "Please enter a username.";
    }

    if (!$putNull) {

        $result = $credentialsModel->searchByName($_POST['username']);
        
        if ($result == null) {
            $message = "No user by that username";
        } else if ($result != null){
            header('Location: ../user/user.php?userID='.$result); // redirect to the found user's profile
        }
    
        } else {
            $message = "Search Failed";
        }

}

include 'users_view.php';
?>