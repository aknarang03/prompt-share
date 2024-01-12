<html><head><title>Account</title></head>
<script type="text/javascript" src="../../scripts/credentials.js"></script>
<link rel='stylesheet' href='../../styles/nav_bar.css'>
<link rel='stylesheet' href='../../styles/all_text.css'>

    <ul>
        <li><a href='../prompts/prompts.php'>All Prompts</a></li>
        <li><a href='../post_prompt/post_prompt.php'>Post a prompt</a></li>
        <li><a href='../users/users.php'>Search users</a></li>
        <li><a href='../user/user.php?userID=<?=$uid?>'>Profile</a></li>
        <li><a href='../edit_user/edit_user.php'>Account</a></li>
        <li><a href='../login/login.php?action=logout'>|&nbsp&nbsp&nbsp&nbspLogout</a></li>
    </ul>
    <br><br>

    <h3>User Settings</h3>

    <form method="post" action=edit_user.php?action=updateTimezone>

        Timezone:
        <select name = "timezone" id = "timezone">
        <?php 
            foreach ($tzlist as $tz) {
                echo "<option value=$tz>$tz</option>";
            }
        ?>
        </select>
            
        <input type="submit" value="Update">
        <br><br>

    </form>

    <form method="post" action=edit_user.php?action=updateEmail onSubmit="return validateEmail(this)">

        <fieldset>

            <label for="email"> new email: </label>
            <input type="text" id="email" name="email" value=""><br>
            
            <br><input type="submit" value="Update">

        </fieldset>

    </form>

    <form method="post" action=edit_user.php?action=updateUsername onSubmit="return validateUsername(this)">

        <fieldset>

            <label for="username"> new username (display name): </label>
            <input type="text" id="username" name="username" value=""><br>
            
            <br><input type="submit" value="Update">

        </fieldset>

    </form>

    <form method="post" action=edit_user.php?action=updatePassword onSubmit="return validatePassword(this)">

        <fieldset>

            <label for="password"> new password: </label>
            <input type="password" id="password" name="password" value=""><br>
            
            <br><input type="submit" value="Update">

        </fieldset>

    </form>

    You will be asked to login again. <br>

    <form method="post" action=edit_user.php?action=deleteUser>

        <br>Delete Account <input type="submit" value="Delete">
        <br>This will delete all of your data.

    </form>

    <br>

    <form method='post' enctype='multipart/form-data' action=edit_user.php?action=addProfilePic>
        <label>Upload Profile Picture</label>
        <input type='file' id='profilePic' name='profilePic' 
        accept='image/jpeg,image/png' /><br>
        <input type='submit' value='Upload'><br>
    </form>
    
    <br>

    <?= $message ?>

</html>