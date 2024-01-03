<html>
    
    <head><title>Register</title></head>

    <link rel='stylesheet' href='../../styles/all_text.css'>
    
    <script type="text/javascript" src="../../scripts/credentials.js"></script>

        <form method="post" id="register" action=register.php?action=add onSubmit="return validate(this)">

            <fieldset>

                <legend>Register</legend>

                <label for="email"> email: </label>
                <input type="text" id = "email" name="email" value=<?=$email?> ><br>

                <label for="username"> username (display name): </label>
                <input type="text" id = "username" name="username" value=<?=$username?>><br>

                <label for="password"> password: </label>
                <input type="password" id = "password" name="password" value=""><br>
                
                <br><input type="submit" value="Register">

            </fieldset>

        </form>

        <?= $message ?>

        <br><a href="../login/login.php">Login</a>

</html>