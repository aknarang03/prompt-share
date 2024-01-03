<html>

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
    <br><br><br>

    <head>

        <title>Users</title>

    </head>

    <body>

        <form method="post" action=users.php?action=search>

            <fieldset>

                <label for="username"> Search for user (enter username): </label>
                <input type="text" name="username" value=""><br>

                <?=$message?>

            </fieldset>
        
        </form>

        <fieldset>
        
            <h3>Users</h3>

            <?php

                foreach ($usersList as $user) {
                    $displayName = $user["displayName"];
                    $userID = $user["idUsers"];
                    echo "<a href='../user/user.php?userID=$userID'>$displayName</a><br>";
                }

            ?>

            <br>
        </fieldset>
       
    </body>

</html>