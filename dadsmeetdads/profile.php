
<?php
include 'top.php';


$debug = false;
$friend = true;

$query = "SELECT fnkSubjectDad, fnkTargetDad,fldTargetFirst,fldTargetLast,fldTargetPic FROM tblFriends WHERE fnkTargetDad LIKE '" . $_GET['user'] . "' AND fnkSubjectDad LIKE '" . $_SESSION['userName'] . "'";
$numFriends = $thisDatabase->select($query);
//print_r($numFriends);

if (isset($_GET['block'])) {
    print '<p style="text-align:center;color:red;padding:20px;background-color:white">Sorry, dad, but only administrators can use this function at this time.</p>';
}

foreach ($numFriends as $oneFriends) {
    if ($_GET['user'] == $oneFriends['fnkTargetDad']) {
        $friend = false;
    }
}

//print "the user is ".$_GET['user'];
if (!isset($_GET['user']) OR ($_GET['user'] == $_SESSION['userName'])) {
    $friend = false;
}
if (isset($_POST['btnAddDad'])) {
    $friend = false;
}

//Checks which profile we are looking at
if (isset($_GET['user'])) {
    $userName = $_GET['user'];
} else {
    $userName = $_SESSION['userName'];
}

if (isset($_GET['btnSearch'])) {
    header('Location: search.php?search=' . $_GET['txtSearch'] . '');
}


$query = "SELECT fldFirstName, fldLastName, fldUserName,fldPicName FROM tblUsers WHERE fldUserName NOT LIKE '".$_SESSION['userName']."' ORDER BY RAND()";
$randDad = $thisDatabase->select($query);
//print_r($randDad);
//Selects some random friends of the user whose profile is being shown

    $query = "SELECT fnkTargetDad, fldTargetFirst,fldTargetLast,fldTargetPic FROM tblFriends WHERE fnkSubjectDad LIKE '" . $userName . "' ORDER BY RAND()";
//print $query;
    $friends = $thisDatabase->select($query);


//Pulls the user's likes from the database
$query = "SELECT fldHiking,fldSailing,fldGrilling,fldReading,fldTelevision,fldChillaxing,fldSkiing,fldPigskin FROM tblLikes WHERE fnkUserId LIKE '" . $userName . "'";
//print $query;
$results = $thisDatabase->select($query);
//print_r($results);

$hiking = $results[0]['fldHiking'];
$sailing = $results[0]['fldSailing'];
$grilling = $results[0]['fldGrilling'];
$reading = $results[0]['fldReading'];
$television = $results[0]['fldTelevision'];
$chillaxing = $results[0]['fldChillaxing'];
$skiing = $results[0]['fldSkiing'];
$pigskin = $results[0]['fldPigskin'];

//Pulls everything that will be displayed about the user
$query = "SELECT fldUserName, fldFirstName, fldLastName, fldBio, fldCity, pmkEmail,fldPicName, fldGender FROM tblUsers WHERE fldUserName LIKE '" . $userName . "'";
$results = $thisDatabase->select($query);


//print_r($results);
$firstName = $results[0]['fldFirstName'];
$lastName = $results[0]['fldLastName'];
$bio = $results[0]['fldBio'];
$city = $results[0]['fldCity'];
$email = $results[0]['pmkEmail'];
$ProfilePic = $results[0]['fldPicName'];
$gender = $results[0]['fldGender'];
$adjective = $results[0]['fldAdjective'];
//print "The profile pic is: ".$ProfilePic;

if (isset($_POST['btnAddDad'])) {

    $data = array($_SESSION['userName'], $_GET['user'], $firstName, $lastName, $ProfilePic);
    $query = 'DELETE FROM tblFriends WHERE fnkSubjectDad LIKE "' . $data[0] . '" AND fnkTargetDad LIKE "' . $data[1] . '"';
    //print $query;
    $results = $thisDatabase->delete($query);
    $query = 'INSERT INTO tblFriends(fnkSubjectDad,fnkTargetDad,fldTargetFirst,fldTargetLast,fldTargetPic,fldNew) VALUES("' . $data[0] . '","' . $data[1] . '","' . $data[2] . '","' . $data[3] . '","' . $data [4] . '",1)';
    //print $query;
    $results = $thisDatabase->insert($query);
}

if (isset($_POST['btnPost'])) {

    $post = htmlentities($_POST['txtPost'], ENT_QUOTES, "UTF-8");
    if ($post == '') {
        print '<p style="text-align:center;color:red;padding:20px;background-color:white">You need to write something first!</p>';
    } else {
        $data = array($_SESSION['userName'], $_GET['user'], $_SESSION['firstName'], $_SESSION['lastName'], $_SESSION['profilePic'], $post);
        //$query = 'DELETE FROM tblPosts WHERE fnkSubjectDad LIKE "' . $data[0] . '" AND fnkTargetDad LIKE "' . $data[1] . '"';
        //print $query;
        //$results = $thisDatabase->delete($query);
        $query = 'INSERT INTO tblPosts(fnkSubjectDad,fnkTargetDad,fldSubjectFirst,fldSubjectLast,fldSubjectPic,fldPostText,fldNew) VALUES("' . $data[0] . '","' . $data[1] . '","' . $data[2] . '","' . $data[3] . '","' . $data [4] . '","' . $data [5] . '",1)';
        //print $query;
        $results = $thisDatabase->insert($query);
        unset($_POST['txtPost']);
    }
}

$query = "SELECT fnkSubjectDad, fnkTargetDad,fldSubjectFirst,fldSubjectLast,fldSubjectPic,fldPostText,fldPostDate FROM tblPosts WHERE fnkTargetDad LIKE '" . $_SESSION['userName'] . "' AND fldNew = 1 AND fnkSubjectDad NOT LIKE '" . $_SESSION['userName'] . "' ORDER BY fldPostDate DESC";
//print $query;
    $postMessages = $thisDatabase->select($query);
$query = "SELECT fldFirstName, fldLastName, fldPicName, fldUserName FROM tblFriends LEFT JOIN tblUsers ON fnkSubjectDad = fldUserName WHERE fnkTargetDad LIKE '" . $_SESSION['userName'] . "' AND fldNew = 1";
//print $query;
    $friendMessages = $thisDatabase->select($query);
$notesNum =  count($friendMessages)+count($postMessages);
    
?>

<div id="wrapper">

    <section id="rightwrap">
        Dads you should meet:
        <ol>
            <li><?php print "<a href='profile.php?user=" . $randDad[0]['fldUserName'] . "'><div style='width:90px;height:90px'><img src='images/" . $randDad [0]['fldPicName'] . "' alt='' style='max-height:90px;max-width:90px'></div>"; ?><br><p><?php print "" . $randDad[0]['fldFirstName'] . " " . $randDad[0]['fldLastName'] . "</a>"; ?></p><br></li>

            <li><?php print "<a href='profile.php?user=" . $randDad[1]['fldUserName'] . "'><div style='width:90px;height:90px'><img src='images/" . $randDad [1]['fldPicName'] . "' alt='' style='max-height:90px;max-width:90px'></div>"; ?><br><p><?php print "" . $randDad[1]['fldFirstName'] . " " . $randDad[1]['fldLastName'] . "</a>"; ?></p><br></li>

            <li><?php print "<a href='profile.php?user=" . $randDad[2]['fldUserName'] . "'><div style='width:90px;height:90px'><img src='images/" . $randDad [2]['fldPicName'] . "' alt='' style='max-height:90px;max-width:90px'></div>"; ?><br><p><?php print "" . $randDad[2]['fldFirstName'] . " " . $randDad[2]['fldLastName'] . "</a>"; ?></p><br></li>
        </ol>
    </section>
    <section id="leftwrap">
        <form method='get' action='search.php'>
        <ol>
            <li>
                <div >
                    <?php
                    if ($_SESSION['profilePic'] != '') {
                        print "<a href='profile.php'><img src='images/" . $_SESSION['profilePic'] . " " . "' alt='' height='100' style='border:solid thick #00c9ec; border-radius: 2px'; max-width:250px></a>";
                    } else {
                        print "<a href='profile.php'><img src='images/dadsmeetnodad.jpg' alt='' height='100' style='border:solid thick #00c9ec; border-radius: 2px'; max-width:250px></a>";
                    }
                    ?>
                </div>

            </li>
            <li>
                <div>
                    <?php print "Heyo, " . $_SESSION['firstName'] . "!"; ?>
                </div>
                <br>
            </li>
            <li>
                <p><a href='profile.php'>See your Profile</a></p>
            </li>
            <li>
                <p><a href='edit.php'>Edit your Profile</a></p>
            </li>
            <li>
                <p><a href='profile.php?tab=notes'>See New Notes<?php if ($notesNum > 0) {print " (".$notesNum.")";} ?></a></p>
            </li>
            <li>
                <input type='text' placeholder='Search for dads' id='txtDadSearch' name='txtDadSearch' required='required' style="width:200px"><input type='submit' value='Search' name='btnSearch' id='btnSearch' class='btnDad' style='padding: 6px 6px 6px 6px;margin-left:20px;'>
            </li>
        </ol>
        </form>
    </section>

    <section id="main">
        <div id='profiletop'>
            <div id='profilePic'>
                <img src='../dadsmeetdads/images/<?php print $ProfilePic ?>' style ='max-width:200px;max-height: 200px;margin:auto; border: solid medium #00c9ec;' alt=''>
            </div>

            <h1 class='Profile' >Profile of <?php print $firstName . " " . $lastName ?></h1>


            <?php
            if ($friend == true) {
                print "<form method='post' action='" . $phpSelf . "?user=" . $_GET['user'] . "''><input type='hidden' name='user' value='" . $_GET['user'] . "'><input type='submit' value='Add this Dad' name='btnAddDad' id='btnAddDad' class='btnDad'></form>";
            } else {
                if (isset($_POST['btnAddDad'])) {
                    print 'You have successfully added this dad!';
                } {
                    print "<div><form method='post' action='" . $phpSelf . "?user=" . $userName . "'><input type='hidden' name='user' value='" . $_GET['user'] . "'><textarea name='txtPost' style='width:380px;float:right;height:100px;'></textarea><br><input type='submit' value='Post Message' name='btnPost' id='btnPost' style='margin:0;margin-left:240px;margin-top:15px;' class='btnDad'></form></div>";
                }
            }
            ?>

        </div>
            <?php
            print '<ol style="height: 40px;padding:0;margin-bottom:20px;">';

            if (($_GET['tab'] == 'feed') OR (!isset($_GET['tab']))) {
                print '     <li class="activetab">Post Feed</li>';
            } else {
                print '     <li class="tab"><a href="profile.php?user=' . $userName . '&tab=feed"><p>Post Feed</p></a></li>';
            }
            if ($_GET['tab'] == 'info') {
                print '     <li class="activetab">DadFacts</li>';
            } else {
                print '     <li class="tab"><a href="profile.php?user=' . $userName . '&tab=info"><p>DadFacts</p></a></li>';
            }
            if ($_GET['tab'] == 'friends') {
                print '     <li class="activetab">Pals</li>';
            } else {
                print '     <li class="tab"><a href="profile.php?user=' . $userName . '&tab=friends"><p>Pals</p></a></li>';
            }


            print '</ol>';

            if (($_GET['tab'] == 'feed') OR (!isset($_GET['tab']))) {
                include 'feed.php';
            } elseif ($_GET['tab'] == 'info') {
                include 'info.php';
            } elseif ($_GET['tab'] == 'friends') {
                include 'friends.php';
            } elseif ($_GET['tab'] == 'notes') {
                include 'notifications.php';
            }
            ?> 
    </section>


</div>
<?php include 'footer.php' ?>

