<html>

    <link rel='stylesheet' href='../../styles/fieldset_styling.css'>
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

    <head>

        <title>Prompts</title>

    </head>

    <body>

        <h3> Prompts </h3>
        
        <div style = 'font-style:italic '>
        Hello, <?=$loggedInUser?><br><br>
        </div>

        <?php

            foreach ($promptsList as $currentPrompt) {

                $idPrompts = $currentPrompt["idPrompts"];
                $posterID = $currentPrompt["posterID"];
                $type = $currentPrompt["type"];
                $prompt = $currentPrompt["prompt"];

                $timePosted = $currentPrompt["timePosted"];
                $timestamp = strtotime($timePosted);
                $echoTimestamp = date('F j, Y g:i A', $timestamp);

                $username = $usersModel->getUsernameFromID($posterID);
                $profilePic = $usersModel->getProfilePicture($posterID);
                $responseCount = $responsesModel->getResponseCountFromPromptID($idPrompts);

                echo "<fieldset>";

                if ($profilePic !=null) {
                    $path = "../../images/".$profilePic;
                    echo "<img style='float: left; margin: 5px 15px 5px 0px;' src=$path width=90 height=90>";
                } else {
                    $path = "../../images/defaultProfilePic.png";
                    echo "<img style='float: left; margin: 5px 15px 5px 0px;' src=$path width=90 height=90>";
                }

                
                if ($type == "Writing") {
                    echo "<span style = 'font-size:14 '>Writing Prompt</span>";
                } else {
                    echo "<span style = 'font-size:14 '>Drawing Prompt</span>";
                }

                echo "
                
                <div style = 'font-size:12 '>
                Posted by <a href='../user/user.php?userID=$posterID'>$username</a> | $echoTimestamp
                </div>

                <br>
                <span class='allow_newlines'>$prompt</span>
                <br><br>

                <div class='textbuttongroup'>
                <span class='link'><a href='../prompt/prompt.php?idPrompts=$idPrompts'>Responses ($responseCount)</a></span>";
                if ($uid==$posterID) {
                    echo"&nbsp&nbsp|&nbsp&nbsp <form method='post' action=prompts.php?action=deletePrompt&promptID=$idPrompts>
                    <input type='submit' value='Delete'></form>";
                }
                echo "</div>";
                
                echo "
                </fieldset><br>
                ";

            }

        ?>
        
    </body>

</html>