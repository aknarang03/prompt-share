<html><head><title>Post Prompt</title></head>

<script type="text/javascript" src="../../scripts/post_char_amts.js"></script>

    <link rel='stylesheet' href='../../styles/all_text.css'>

    <link rel='stylesheet' href='../../styles/nav_bar.css'>
    <ul>
        <li><a href='../prompts/prompts.php'>All Prompts</a></li>
        <li><a href='../post_prompt/post_prompt.php'>Post a prompt</a></li>
        <li><a href='../users/users.php'>Search users</a></li>
        <li><a href='../user/user.php?userID=<?=$uid?>'>Profile</a></li>
        <li><a href='../edit_user/edit_user.php'>Account</a></li>
        <li><a href='../login/login.php?action=logout'>|&nbsp&nbsp&nbsp&nbspLogout</a></li>
    </ul>
    <br><br><br>

    <form method="post" action=post_prompt.php?action=add onSubmit="return checkPrompt(this)">

        <fieldset>

            <legend>Post Prompt</legend>

            Type:
            <select name = "type" id = "type">
            <option value="Writing">Writing</option>
            <option value="Drawing">Drawing</option>
            </select> <br><br>
            
            <label for="prompt"> Prompt: </label>
            <textarea id="prompt" name="prompt" rows="4" cols="50"></textarea>
            
            <br><br><input type="submit" value="Add">

        </fieldset>

    </form>

    <?= $message ?>

</html>