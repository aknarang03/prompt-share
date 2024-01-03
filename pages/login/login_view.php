<html>
    
<link rel='stylesheet' href='../../styles/all_text.css'>

    <head><title>Login</title></head>

    <form method="post" action=login.php?action=login>

        <fieldset>

            <legend> Login </legend> <br>

            <label for="usernameOrEmail"> username or email: </label>
            <input type="text" id="usernameOrEmail" name="usernameOrEmail" value=""><br><br>

            <label for="password"> password: </label>
            <input type="password" name="password" value=""><br><br>
            
            <p><?=$message;?> 

            <hr>
            
            <input type="submit" value="Login">

        </fieldset>

    </form>

    <br><a href="../register/register.php">Register</a>

</html>