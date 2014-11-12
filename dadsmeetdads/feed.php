
<h3 class='Profile'><?php print $firstName ?>'s Message Board:</h3>

<div id="postContainer" style="overflow:auto;">
    <?php
    $query = "SELECT fnkSubjectDad, fnkTargetDad,fldSubjectFirst,fldSubjectLast,fldSubjectPic,fldPostText,fldPostDate FROM tblPosts WHERE fnkTargetDad LIKE '" . $userName . "' AND fnkSubjectDad LIKE '" . $_SESSION['userName'] . "' ORDER BY fldPostDate DESC";
//print $query;
    $postMessages = $thisDatabase->select($query);

    if (empty($postMessages)) {
        print 'There is nothing here!';
    } else {
        //print $friends[0]["fnkTargetDad"];
        foreach ($postMessages as $onePost) {
            //print $friends[$friendIndex]["fnkTargetDad"];
            $postTime = strtotime($onePost["fldPostDate"]);
            $finalPostTime = date('d\/m\/y', $postTime);
            echo '<div class="topContainer" style="width:560px;padding:0;min-height:100px;overflow:auto;">';
            echo '<div class="postContainer" style="float:left;margin-right:20px;">';
            echo '<a href="../dadsmeetdads/profile.php?user=' . $onePost["fldSubjectDad"] . '">';
            echo '<div id="infoContainer" style="float:left">';
            echo '<img src="images/' . $onePost["fldSubjectPic"] . '" alt="" height="90" width="90"></div>';
            echo "</a>";
            echo "</div>";
            echo "<div class='messageContainer' style='float:left;border:2px #709fcf solid;width:440px;border-radius:2px;'>";
            echo "<p style='text-align:left;margin:10px;'>";
            echo "<strong>Posted by " . $onePost['fldSubjectFirst'] . " " . $onePost["fldSubjectLast"] . " on " . $finalPostTime . "</strong>";
            echo "</p>";
            echo "<p style='font-size:.9em;padding:10px'>";
            echo "<br>";
            echo $onePost['fldPostText'];
            echo "</p>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
            echo "<br>";
        }
    }
    ?>      
</div>

<br>


