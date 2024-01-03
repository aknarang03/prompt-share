<html>

<script type="text/javascript" src="../../scripts/post_char_amts.js"></script>
<link rel='stylesheet' href='../../styles/fieldset_styling.css'>

    <head>

        <title>Votes</title>

    </head>

    <link rel='stylesheet' href='../../styles/all_text.css'>

    <body>

        <h3> Votes </h3>

        <?php

            $responder = $usersModel->getUsernameFromID($responderID);
            if ($type == "Writing") {
                echo "<span class=allow_newlines>$response</span><br>";
            } else { // type == "Drawing"
                $path = "../../images/".$response;
                echo "<img src=$path width=200>";
            }
            echo "
            <br>
            <span style='font-size:14 '> Response by <a href='../user/user.php?userID=$responderID'>$responder</a></span>
            <br>";
        ?>

        <form method="post" onSubmit='return checkVote(this)'> 
        <br>
        <?php

            if ($uid != $responderID) {

                echo "
                <textarea id='feedback' name='feedback' rows='1' cols='30' value='Optional Feedback'></textarea>
                <br><input type='submit' value='Vote'><br><br><br>
                ";
            }
        
        ?>
        </form>
        <?=$message;?>

        <?php

            foreach ($votesList as $currentVote) {

                $idVotes = $currentVote["idPrompts"];
                $feedback = $currentVote["feedback"];
                $voterID = $currentVote["voterID"];
                $responseID = $currentPrompt["responseID"];

                $username = $usersModel->getUsernameFromID($voterID);

                echo "<fieldset>";

                echo "
                <a href='../user/user.php?userID=$voterID'>$username</a> voted
                <br>
                $feedback
                <br>

                </fieldset><br>
                ";

            }

        ?>
        
    </body>
    
    <?php echo "<br><a href='../prompt/prompt.php?idPrompts=$promptID'>Back</a>" ?>

</html>