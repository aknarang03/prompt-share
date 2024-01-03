<html>

<script type="text/javascript" src="../../scripts/post_char_amts.js"></script>

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

        <title>Prompt</title>

    </head>

    <body>

        <h3> <?=$type?> Prompt </h3>

        <?php

            if ($prompt!=null) {

                // DETERMINE THE TIME STRING
                // maybe make a method in prompt.php that can be called from here

                $timePosted = $promptsModel->getTimePosted($idPrompts);
                $tz = new DateTimeZone('America/New_York');
                $timePostedUnformat = DateTime::createFromFormat('Y-m-d H:i:s', $timePosted, $tz);
                $timeNow = new DateTime('now', $tz);
                $timeBtwn = $timeNow->diff($timePostedUnformat);

                if ($timeBtwn->d < 1) { // if it has not been a day + yet

                    if ($timeBtwn->h < 1) { // if it has not been 1 hour yet
                        if ($timeBtwn->i == 1) {
                            $timeStr = "$timeBtwn->i minute ago";
                        } else {
                            $timeStr = "$timeBtwn->i minutes ago";
                        }  

                    } else { // it has been at least an hour
                        if ($timeBtwn->i == 1) { $minstr = "minute";}
                        else { $minstr = "minutes"; }
                        if ($timeBtwn->h == 1) {
                        $timeStr = "$timeBtwn->h hour $timeBtwn->i $minstr ago";
                        } else {
                        $timeStr = "$timeBtwn->h hours $timeBtwn->i $minstr ago";
                        }
                    }

                } else { // it has been at least a day
                    $timeStr = "$timeBtwn->d days ago";
                    if ($timeBtwn->d == 1) {
                        $timeStr = "$timeBtwn->d day ago";
                    } else {
                        $timeStr = "$timeBtwn->d days ago";
                    }
                }

                $minutesBtwn = ($timeBtwn->d * 24 * 60) + ($timeBtwn->h * 60) + $timeBtwn->i;
                $showEdit = ($minutesBtwn <= 15);

                if (!$showEditPromptForm) { // regular view

                    echo "<span class=allow_newlines>$prompt</span>";
                    echo "<br><div class='textbuttongroup'> <font size='-1'>Posted by $posterUsername";
                    //echo " | $echoTimestamp ($timeStr)";
                    echo " $timeStr";
                    if ($uid==$posterID && $showEdit) {
                        echo"&nbsp&nbsp|&nbsp&nbsp</font> <form method='post' action=prompt.php?action=editPrompt&idPrompts=$idPrompts>
                        <input type='submit' value='Edit'></form>";
                    } else {
                        echo "</font>";
                    }
                    echo "</div>";

                } else { // editing view

                    //onSubmit='return checkPrompt(this)'
                    //<input type='submit' name='button' value='Cancel'>

                    echo "
                    <form method='post' action=prompt.php?action=completePromptEdit&idPrompts=$idPrompts onSubmit='return checkPrompt(this)'> 
                    <fieldset><legend>Edit Prompt</legend>
                    <textarea id='prompt' name='prompt' rows='4' cols='50'>$prompt</textarea>
                    <br><input type='submit' name ='button' value='Edit'>
                    <input type='submit' name='button' value='Cancel'<br>
                    </fieldset>
                    ";

                }
                
            } 
            else { echo "This prompt does not exist or has been deleted."; } // prompt is null

            if ($message != null) { echo $message; }
            
        ?>

        <?php
            if ($topVotedResponse != null) {
            echo "<br><br>Winner:";
            echo "<br><br><fieldset> ";

            if ($topVotedUserPfp !=null) {
                $path = "../../images/".$topVotedUserPfp;
                echo "<img style='float: left;margin: 0 15px 0 0;' src=$path width=90 height=90>";
            } else {
                $path = "../../images/defaultProfilePic.png";
                echo "<img src=$path width=50 height=50>";
            }

            if ($type == 'Writing') {
                echo "<span class='allow_newlines'>$topVotedResponse</span><br>";
            } else {
                $path = "../../images/".$topVotedResponse;
                echo "<img src=$path width=200>";
            }

            echo "<br> 
            <font size='-1'>Response by <a href='../user/user.php?userID=$topVotedUserID'>$topVotedUser</a></font>
            </fieldset>";
            }

        ?>

        <br><br>
        <?php
        if ($prompt != null) {
            echo "<fieldset><legend>Respond:</legend>";

                if ($type == "Writing") {

                    echo "
                    <form method='post' action=prompt.php?action=addResponse&idPrompts=$idPrompts onSubmit='return checkWritingResponse(this)'> 
                    <textarea id='response' name='response' rows='4' cols='50'></textarea>
                    <br><input type='submit' value='Submit'><br>
                    ";
                    
                } else { // $type == "Drawing"

                    echo "
                    <form method='post' enctype='multipart/form-data' action=prompt.php?action=addResponse&idPrompts=$idPrompts>
                    <label>Upload Photo</label>
                    <input type='file' id='response' name='response' 
                    accept='image/jpeg,image/png' /><br>
                    <input type='submit' value='Upload'><br>";
                }

                echo "</fieldset>";
        }

        ?>
        </form>
        <br>

        <?php
            
            foreach ($responsesList as $currentResponse) {

                $idResponses = $currentResponse["idResponses"];
                $responderID = $currentResponse["responderID"];

                $timePosted = $currentResponse["timePosted"];
                $timestamp = strtotime($timePosted);
                $echoTimestamp = date('F j, Y g:i A', $timestamp);

                // DETERMINE THE TIME STRING
                $timePosted = $responsesModel->getTimePosted($idResponses);
                $tz = new DateTimeZone('America/New_York');
                $timePostedUnformat = DateTime::createFromFormat('Y-m-d H:i:s', $timePosted, $tz);
                $timeNow = new DateTime('now', $tz);
                $timeBtwn = $timeNow->diff($timePostedUnformat);
                
                if ($timeBtwn->d < 1) { // if it has not been a day + yet

                    if ($timeBtwn->h < 1) { // if it has not been 1 hour yet
                        if ($timeBtwn->i == 1) {
                            $timeStr = "$timeBtwn->i minute ago";
                        } else {
                            $timeStr = "$timeBtwn->i minutes ago";
                        }  

                    } else { // it has been at least an hour
                        if ($timeBtwn->i == 1) { $minstr = "minute";}
                        else { $minstr = "minutes"; }
                        if ($timeBtwn->h == 1) {
                        $timeStr = "$timeBtwn->h hour $timeBtwn->i $minstr ago";
                        } else {
                        $timeStr = "$timeBtwn->h hours $timeBtwn->i $minstr ago";
                        }
                    }

                } else { // it has been at least a day
                    $timeStr = "$timeBtwn->d days ago";
                    if ($timeBtwn->d == 1) {
                        $timeStr = "$timeBtwn->d day ago";
                    } else {
                        $timeStr = "$timeBtwn->d days ago";
                    }
                }

                $minutesBtwn = ($timeBtwn->d * 24 * 60) + ($timeBtwn->h * 60) + $timeBtwn->i;
                $showEdit = ($minutesBtwn <= 15);

                if ($type == "Writing") {
                    $response = $currentResponse["textResponse"];
                } else { // type == "Drawing"
                    $response = $currentResponse["imgResponseFilename"];
                }

                $responderUsername = $usersModel->getUsernameFromID($responderID);
                $numVotes = $votesModel->getVoteCountFromResponseID($idResponses);

                if ($idResponses != $responseToEdit) { // regular view

                    echo "
                    <fieldset>";

                    if ($type == "Writing") {
                        echo "<br><span class=allow_newlines>$response</span>";
                    } else {
                        $path = "../../images/".$response;
                        echo "<img src=$path width=200>"; // scales down image preserving aspect ratio
                    }

                    // if it's the user's response display View Votes instead of Vote as they can't vote on their own response
                    if ($uid==$responderID) {$votestr = "View Votes";}
                    else {$votestr = "Vote";}

                    echo "
                    <br><br>
                    <font size='-1'>Response by <a href='../user/user.php?userID=$responderID'>$responderUsername</a>";
                    //echo" | $echoTimestamp ($timeStr)";
                    echo " $timeStr";
                    echo "
                    </font>
                    <br><br>

                    <div class='textbuttongroup'>

                    <span class=link'>Votes: $numVotes | <a href='../votes/votes.php?responseID=$idResponses&userID=$responderID&type=$type&promptID=$idPrompts'>$votestr</a></span>";

                    if ($uid==$responderID) {

                        if ($type=="Writing" && $showEdit) {
                            echo"&nbsp&nbsp|&nbsp&nbsp <form method='post' action=prompt.php?action=editResponse&idPrompts=$idPrompts&responseID=$idResponses>
                            <input type='submit' value='Edit'></form>";
                        }

                        echo"&nbsp&nbsp|&nbsp&nbsp <form method='post' action=prompt.php?action=deleteResponse&idPrompts=$idPrompts&responseID=$idResponses>
                        <input type='submit' value='Delete'></form>";
                    }
                    echo "</div>";

                    echo"
                    </fieldset><br>
                    ";

                } else { // edit view

                    echo "
                    <form method='post' action=prompt.php?action=completeResponseEdit&idPrompts=$idPrompts&responseID=$idResponses onSubmit='return checkWritingResponse(this)'> 
                    <fieldset><legend>Edit Response</legend>
                    <textarea id='response' name='response' rows='4' cols='50'>$response</textarea>
                    <br><input type='submit' name ='button' value='Edit'>
                    <input type='submit' name='button' value='Cancel'<br>
                    </fieldset>
                    ";

                }

            }

        ?>
        
    </body>

</html>