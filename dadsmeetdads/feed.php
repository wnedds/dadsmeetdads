
<h3 class='Profile'><?php print $firstName ?>'s Message Board:</h3>

<div id="feedContainer" style="overflow:auto;">
    <form action="<?php print $phpSelf . "?user=" . $_GET['user']?>" method="post">
    <?php
    $query = "SELECT fnkSubjectDad, fnkTargetDad,fldSubjectFirst,fldSubjectLast,fldSubjectPic,fldPostText,fldPostDate FROM tblPosts WHERE fnkTargetDad LIKE '" . $userName . "' ORDER BY fldPostDate DESC";
//print $query;
    $postMessages = $thisDatabase->select($query);

    if (empty($postMessages)) {
        print 'There is nothing here!';
    } else {
        //print $friends[0]["fnkTargetDad"];
        foreach ($postMessages as $onePost) {
            //print $friends[$friendIndex]["fnkTargetDad"];
            $postTime = strtotime($onePost["fldPostDate"]);
            $finalPostTime = date('m\/d\/y', $postTime);
            echo '<div class="topContainer" style="width:600px;padding:0;min-height:100px;overflow:auto;">';
            echo '<div class="postContainer" style="float:left;margin-right:20px;">';
            echo '<a href="../dadsmeetdads/profile.php?user=' . $onePost["fnkSubjectDad"] . '">';
            echo '<div id="infoContainer" style="float:left;width:80px;height:80px">';
            echo '<img src="images/'.$onePost["fldSubjectPic"].'" alt="" style="max-width: 80px;max-height:80px;margin-auto"></div>';
            echo "</a></div><div class='messageContainer' style='float:left;border:2px #709fcf solid;width:490px;border-radius:2px;'>";
            echo "<p style='text-align:left;margin:10px;margin-bottom:0'>";
            echo "<strong>Posted by " . $onePost['fldSubjectFirst'] . " " . $onePost["fldSubjectLast"] . " on " . $finalPostTime . "</strong>";
            echo "</p><p style='font-size:.9em;padding:10px;padding-top:0'><br>".$onePost['fldPostText']."</p></div></div><br>";
            echo "";
        }
    }
    ?>
    </form>
</div>

<br>


