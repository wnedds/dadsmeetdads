
<h3 class='Profile'>New posts on your profile:</h3>

<div id="postContainer" style="overflow:auto;">
    <?php
    /*$query = "SELECT fnkSubjectDad, fnkTargetDad,fldSubjectFirst,fldSubjectLast,fldSubjectPic,fldPostText,fldPostDate FROM tblPosts WHERE fnkTargetDad LIKE '" . $userName . "' AND fldNew = 1 AND fnkSubjectDad NOT LIKE '" . $userName . "' ORDER BY fldPostDate DESC";
//print $query;
    $postMessages = $thisDatabase->select($query)*/

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
            echo '<div id="infoContainer" style="float:left;width:90px;height:90px">';
            echo '<img src="images/' . $onePost["fldSubjectPic"] . '" alt="" style="max-width: 90px;max-height:90px;margin-auto"></div>';
            echo "</a></div>";
            echo "<div class='messageContainer' style='float:left;border:2px #709fcf solid;width:480px;border-radius:2px;'>";
            echo "<p style='text-align:left;margin:10px;margin-bottom:0'>";
            echo "<strong>Posted by " . $onePost['fldSubjectFirst'] . " " . $onePost["fldSubjectLast"] . " on " . $finalPostTime . "</strong>";
            echo "</p><p style='font-size:.9em;padding:10px;padding-top:0'><br>".$onePost['fldPostText']."</p></div></div><br><br>";
        }
    }
    
    
    $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            $query = 'UPDATE tblPosts SET fldNew = 0 WHERE fnkTargetDad LIKE "'.$_SESSION['userName'].'"';
            //print $query;
            $results = $thisDatabase->insert($query);
// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug) {
                print "<p>transaction complete ";
            }
        } catch (PDOException $e) {
            $thisDatabase->db->rollback();
            if ($debug) {
                print "Error!: " . $e->getMessage() . "</br>";
            }
            $errorMsg[] = "An error occured. Please wait a few minutes and try again.";
        }
    ?>      
</div>

<br>
<h3 class='Profile'>Dads who recently added you as a friend</h3>

<div id="friendContainer" style="overflow:auto;">
    <?php
    /*$query = "SELECT fnkSubjectDad, fnkTargetDad,fldTargetFirst,fldTargetLast,fldTargetPic FROM tblPosts WHERE fnkTargetDad LIKE '" . $userName . "' ORDER BY fldPostDate DESC";
//print $query;
    $friendMessages = $thisDatabase->select($query);*/

    if (empty($friendMessages)) {
        print 'There is nothing here!';
    } else {
        //print $friends[0]["fnkTargetDad"];
        foreach ($friendMessages as $oneResult) {
    print '<div style="height:70px">';
    print '<a href="../dadsmeetdads/profile.php?user='.$oneResult['fldUserName'].'" style="text-decoration: none;color:black;"><img src="images/'.$oneResult['fldPicName'].'" alt="" style="height:70px;float:left">';
    print '<p style="padding: 20px;margin-left: 70px;">'.$oneResult['fldFirstName'].' ';
    print $oneResult['fldLastName'].' has added you as a friend. Add them back?</p></a>';
    print '</div><br>';
}
    }
    
       $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            $query = 'UPDATE tblFriends SET fldNew = 0 WHERE fnkTargetDad LIKE "'.$_SESSION['userName'].'"';
            //print $query;
            $results = $thisDatabase->insert($query);
// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug) {
                print "<p>transaction complete ";
            }
        } catch (PDOException $e) {
            $thisDatabase->db->rollback();
            if ($debug) {
                print "Error!: " . $e->getMessage() . "</br>";
            }
            $errorMsg[] = "An error occured. Please wait a few minutes and try again.";
        }
    ?>      
</div>

<br>
