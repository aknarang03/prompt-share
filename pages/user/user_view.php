<html>

    <link rel='stylesheet' href='../../styles/fieldset_styling.css'>
    <link rel='stylesheet' href='../../styles/nav_bar.css'>

    <link rel='stylesheet' href='../../styles/all_text.css'>
    <ul>
        <li><a href='../prompts/prompts.php'>All Prompts</a></li>
        <li><a href='../post_prompt/post_prompt.php'>Post a prompt</a></li>
        <li><a href='../users/users.php'>Search users</a></li>
        <li><a href='../user/user.php?userID=<?=$loggedInUserID?>'>Profile</a></li>
        <li><a href='../edit_user/edit_user.php'>Account</a></li>
        <li><a href='../login/login.php?action=logout'>|&nbsp&nbsp&nbsp&nbspLogout</a></li>
    </ul>
    <br><br>

    <head>

        <br>
        <?php
        if ($profilePic !=null) {
            $path = "../../images/".$profilePic;
            echo "<img src=$path width=100 height=100>";
        } else {
            $path = "../../images/defaultProfilePic.png";
            echo "<img src=$path width=100 height=100>";
        }
        ?>

        <title>User <?=$username?></title>

    </head>

    <body>

        <h3> <?=$username?> </h3>

        <h4>Prompts</h4>

        <?php

            foreach ($promptsList as $currentPrompt) {

                $idPrompts = $currentPrompt["idPrompts"];
                $type = $currentPrompt["type"];
                $prompt = $currentPrompt["prompt"];
                $responseCount = $responsesModel->getResponseCountFromPromptID($idPrompts);

                $timePosted = $currentPrompt["timePosted"];
                $timestamp = strtotime($timePosted);
                $echoTimestamp = date('F j, Y g:i A', $timestamp);

                echo "<fieldset>";

                if ($type == "Writing") {
                    echo "<span style = 'font-size:14 '>Writing Prompt | $echoTimestamp</span><br>";
                } else {
                    echo "<span style = 'font-size:14 '>Drawing Prompt | $echoTimestamp</span><br>";
                }

                echo "
                <br>
                $prompt
                <br>";

                echo "
                <br>
                <div class='textbuttongroup'>
                <br><span class='link'><a href='../prompt/prompt.php?idPrompts=$idPrompts'>Responses ($responseCount)</a></span>";

                if ($loggedIn) {
                    echo "&nbsp&nbsp|&nbsp&nbsp <form method='post' action=user.php?action=deletePrompt&promptID=$idPrompts>
                    <input type='submit' value='Delete'></form>";
                }
                echo "</div>";

                echo"
                </fieldset><br>
                ";

            }

        ?>

        <h4>Responses</h4>

        <?php

            foreach ($responsesList as $currentResponse) {

                $idResponses = $currentResponse["idResponses"];
                $promptID = $currentResponse["promptID"];
                $textResponse = $currentResponse["textResponse"];
                $imgResponse = $currentResponse["imgResponseFilename"];
                
                $timePosted = $currentResponse["timePosted"];
                $timestamp = strtotime($timePosted);
                $echoTimestamp = date('F j, Y g:i A', $timestamp);

                if ($imgResponse == null) {
                    $promptType = "Writing";
                } else {
                    $promptType = "Drawing";
                }

                echo "<fieldset>";

                if ($promptType == "Writing") {
                    echo "<span style = 'font-size:14 '>Writing Response | $echoTimestamp</span><br>
                    <br><span class = 'allow_newlines'>$textResponse</span><br>";
                } else {
                    $path = "../../images/".$imgResponse;
                    echo "<span style = 'font-size:14 '>Drawing Response | $echoTimestamp</span><br>
                    <br><img src=$path width=150 height=100><br>";
                }

                echo "
                
                <br>
                <div class='textbuttongroup'>
                <br><span class='link'><a href='../prompt/prompt.php?idPrompts=$promptID'>Prompt</a></span>";

                if ($loggedIn) {
                    echo "&nbsp&nbsp|&nbsp&nbsp<form method='post' action=user.php?action=deleteResponse&responseID=$idResponses>
                    <input type='submit' value='Delete'></form>";
                }
                echo "</div>";

                echo"
                </fieldset><br>
                ";

            }

        ?>
        
    </body>

</html>