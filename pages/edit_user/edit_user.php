<?php
require_once '../../models/users_model.php';
require_once '../../models/credentials_model.php';

session_start();
if (!isset($_SESSION['uid'])){
    header('Location: ../login/login.php');
}

$uid = $_SESSION['uid'];

$credentialsModel = new CredentialsModel();
$usersModel = new UsersModel();

$tzlist = timezone_identifiers_list();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailTaken = false;
    $usernameTaken = false;
    $getvars = $_GET;
    
    if (isset($getvars["action"])) {

        // check if email / username taken
        if ($credentialsModel->usernameTaken($_POST['username'])) {
            $usernameTaken = true;
            $message = "username taken";
        }
        if ($credentialsModel->emailTaken($_POST['email'])) {
            $emailTaken = true;
            $message = "email taken";
        }

        // JS will handle making sure email and username are valid otherwise

        if ($getvars["action"] == 'updateEmail') {

            if ($_POST['email'] == null){
                $message = "Please enter an email";
            }

            else if (!$emailTaken) {
                $result = $credentialsModel->updateEmail($_POST['email']);
                
                if ($result) {
                    $message = "Update Successful";
                    header('Location: ../login/login.php?action=logout');
                } else {
                    $message = "Update Failed";
                }
            }

        }

        else if ($getvars["action"] == 'updateTimezone') {

            $timezone = $_POST['timezone'];
    
            $result = $usersModel->updateTimezone($timezone);
            if ($result) {
                $message = "Timezone Updated";
                header('Location: ../login/login.php?action=logout');
            } else {
                $message = "Update Timezone Failed";
            }

        }

        else if ($getvars["action"] == 'updateUsername') {

            if ($_POST['username'] == null){
                $message = "Please enter a username";
            }

            else if (!$usernameTaken) {
                $result = $credentialsModel->updateUsername($_POST['username']);
                
                if ($result) {
                    $message = "Update Successful";
                    header('Location: ../login/login.php?action=logout');
                } else {
                    $message = "Update Failed";
                }
            }

        } else if ($getvars["action"] == 'updatePassword') {

            if ($_POST['password'] == null){
                $message = "Please enter a password";
            }
            
            else {
                $result = $credentialsModel->updatePassword($_POST['password']);
                    
                    if ($result) {
                        $message = "Update Successful";
                        header('Location: ../login/login.php?action=logout');
                    } else {
                        $message = "Update Failed";
                    }
            }

        } else if ($getvars["action"] == 'deleteUser') {

            $result = $usersModel->deleteUser();
                if ($result) {
                    $message = "Account Deleted";
                    header('Location: ../login/login.php?action=logout');
                } else {
                    $message = "Delete User Failed";
                }

        } else if ($getvars["action"] == 'addProfilePic') {

            $file = $usersModel->getProfilePicture($uid);
            $deleteFile = false;
            if ($file != null) {
                $targetDir = "../../images/";
                $fileToDelete = $targetDir.$file;
                $deleteFile = true;
            }

            if ($_FILES['profilePic']['tmp_name'] != "") {
                $unique = uniqid();
                $uploadedFile = $unique.$_FILES['profilePic']['name'];
                $result = $usersModel->updateProfilePicture($uploadedFile);
            }

            if ($result) {
                if ($deleteFile) {unlink($fileToDelete);}
                saveFile($unique);
                $message = "Profile Picture Updated";
            } else {
                $message = "Update Failed";
            }

        }

    }

}

function saveFile($unique) {
    $targetDir = "../../images/";
    $targetFile = $targetDir . $unique . basename( $_FILES["profilePic"]["name"] );
    $uploaded = move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile);

    if (!$uploaded) {
        $message =  "Error uploading file";
    }
}

include 'edit_user_view.php';
?>