<script type="text/javascript">
    $(function() {
        $('.like').click(function() {
            $.get('like.php', function() {

            });
            return false;
        });
    });
</script>
<?php
include 'top.php';
$debug = false;
$friend = true;
$query = "SELECT fnkSubjectDad, fnkTargetDad,fldTargetFirst,fldTargetLast,fldTargetPic FROM tblFriends WHERE fnkTargetDad LIKE '" . $_GET['user'] . "' AND fnkSubjectDad LIKE '" . $_SESSION['userName'] . "'";
$numFriends = $thisDatabase->select($query);
//print_r($numFriends);

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

//Counts how many users are in the database
$query = "SELECT COUNT(pmkUserId) FROM tblUsers";
$numDad = $thisDatabase->select($query);

//Counts how many users are in the database
$query = "SELECT fldFirstName, fldLastName, fldUserName,fldPicName FROM tblUsers ORDER BY RAND()";
$randDad = $thisDatabase->select($query);
//print_r($randDad);
//Selects some random friends of the user whose profile is being shown

if (isset($_GET['user'])) {
    $query = "SELECT fnkTargetDad, fldTargetFirst,fldTargetLast,fldTargetPic FROM tblFriends WHERE fnkSubjectDad LIKE '" . $_GET['user'] . "' ORDER BY RAND()";
//print $query;
    $friends = $thisDatabase->select($query);
} else {
    $query = "SELECT fnkTargetDad, fldTargetFirst,fldTargetLast,fldTargetPic FROM tblFriends WHERE fnkSubjectDad LIKE '" . $_SESSION['userName'] . "' ORDER BY RAND()";
//print $query;
    $friends = $thisDatabase->select($query);
}



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
$adjective = 'cool';
//print "The profile pic is: ".$ProfilePic;

if (isset($_POST['btnAddDad'])) {

    $data = array($_SESSION['userName'], $_GET['user'], $firstName, $lastName, $ProfilePic, $city);
    $query = 'DELETE FROM tblFriends WHERE fnkSubjectDad LIKE "' . $data[0] . '" AND fnkTargetDad LIKE "' . $data[1] . '"';
    //print $query;
    $results = $thisDatabase->delete($query);
    $query = 'INSERT INTO tblFriends(fnkSubjectDad,fnkTargetDad,fldTargetFirst,fldTargetLast,fldTargetPic) VALUES("' . $data[0] . '","' . $data[1] . '","' . $data[2] . '","' . $data[3] . '","' . $data [4] . '")';
    //print $query;
    $results = $thisDatabase->insert($query);
}


/*
  //$query = "SELECT fldUserName, fldFirstName, fldLastName FROM tblUsers WHERE fldUserName LIKE '".[STUFFHERE]."'";
  //$dads = $thisDatabase->select($query);
  //Legacy code that will be removed
  $dadProfile1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31");
  $dadPic1 = array_rand($dadProfile1);
  $dadFirst1 = array("John", "Mark", "Tom", "Gary", "Neil", "Ben", " Bob", "Bill", "Ken", "Steve", "Joe", "Mike", "Rick", "Rich", "Eric", "Tim", "Chris", "David", "Owen", "Al", "Sam");
  $dadFirstName1 = array_rand($dadFirst1);
  $dadLast1 = array("Johnson", "Jackson", "Thompson", "Smith", "Neilson", "Baker", "Adams", "Gunn", "Bradford", "Mejia", "Reeves", "Brody", "Hurley", "Anderson", "Flanders", "Cooper", "Reynolds", "West", "Hoffman", "Brown", "Miller", "Wilson", "Taylor", "Davis", "Young");
  $dadLastName1 = array_rand($dadLast1);


  $dadProfile2 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31");
  $dadPic2 = array_rand($dadProfile2);
  $dadFirst2 = array("John", "Mark", "Tom", "Gary", "Neil", "Ben", " Bob", "Bill", "Ken", "Steve", "Joe", "Mike", "Rick", "Rich", "Eric", "Tim", "Chris", "David", "Owen", "Al", "Sam");
  $dadFirstName2 = array_rand($dadFirst2);
  $dadLast2 = array("Johnson", "Jackson", "Thompson", "Smith", "Neilson", "Baker", "Adams", "Gunn", "Bradford", "Mejia", "Reeves", "Brody", "Hurley", "Anderson", "Flanders", "Cooper", "Reynolds", "West", "Hoffman", "Brown", "Miller", "Wilson", "Taylor", "Davis", "Young");
  $dadLastName2 = array_rand($dadLast2);


  $dadProfile3 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31");
  $dadPic3 = array_rand($dadProfile3);
  $dadFirst3 = array("John", "Mark", "Tom", "Gary", "Neil", "Ben", " Bob", "Bill", "Ken", "Steve", "Joe", "Mike", "Rick", "Rich", "Eric", "Tim", "Chris", "David", "Owen", "Al", "Sam");
  $dadFirstName3 = array_rand($dadFirst3);
  $dadLast3 = array("Johnson", "Jackson", "Thompson", "Smith", "Neilson", "Baker", "Adams", "Gunn", "Bradford", "Mejia", "Reeves", "Brody", "Hurley", "Anderson", "Flanders", "Cooper", "Reynolds", "West", "Hoffman", "Brown", "Miller", "Wilson", "Taylor", "Davis", "Young");
  $dadLastName3 = array_rand($dadLast3);
 * */
?>

<div id="wrapper">

    <section id="rightwrap">
        Dads you should meet:
        <ol>
            <li><?php print "<a href='profile.php?user=" . $randDad[0]['fldUserName'] . "'><img src='images/" . $randDad [0]['fldPicName'] . "' alt='' height='90' width='70'>"; ?><br><p><?php print "" . $randDad[0]['fldFirstName'] . " " . $randDad[0]['fldLastName'] . "</a>"; ?></p><br></li>

            <li><?php print "<a href='profile.php?user=" . $randDad[1]['fldUserName'] . "'><img src='images/" . $randDad [1]['fldPicName'] . "' alt='' height='90' width='70'>"; ?><br><p><?php print "" . $randDad[1]['fldFirstName'] . " " . $randDad[1]['fldLastName'] . "</a>"; ?></p><br></li>

            <li><?php print "<a href='profile.php?user=" . $randDad[2]['fldUserName'] . "'><img src='images/" . $randDad [2]['fldPicName'] . "' alt='' height='90' width='70'>"; ?><br><p><?php print "" . $randDad[2]['fldFirstName'] . " " . $randDad[2]['fldLastName'] . "</a>"; ?></p><br></li>
        </ol>
    </section>
    <section id="leftwrap">
        <ol>
            <li>
                <div >
                    <?php
                    if ($_SESSION['profilePic'] != '') {
                        print "<a href='profile.php'><img src='images/" . $_SESSION['profilePic'] . " " . "' alt='' height='100' style='border:solid thick #00c9ec; border-radius: 3px'; max-width:250px></a>";
                    } else {
                        print "<a href='profile.php'><img src='images/dadsmeetnodad.jpg' alt='' height='100' style='border:solid thick #00c9ec; border-radius: 3px'; max-width:250px></a>";
                    }
                    ?>
                </div>
            </li>
            <li>
                <div>
                    <?php print "Heyo, " . $_SESSION['firstName'] . "!"; ?>
                </div>
            </li>
            <li>
                <p><a href='profile.php'>See your profile</a></p>
            </li>
            <li>
                <p><a href='edit.php'>Edit your profile</a></p>
            </li>
            <li>
                <form method='get' action='search.php'><input type='text' placeholder='Search for dads' id='txtDadSearch' name='txtDadSearch' required='required'><input type='submit' value='Search' name='btnSearch' id='btnSearch' class='btnDad' style='padding: 6px 6px 6px 6px;'></form>
            </li>
        </ol>
    </section>

    <section id="main">
        <div id='profiletop'>
            <div id='profilePic'>
                <img src='../dadsmeetdads/images/<?php print $ProfilePic ?>' style ='max-width:200px;max-height: 200px;margin:auto; border: solid medium #00c9ec;' alt=''>
            </div>
            <h1 class='Profile' >Profile of <?php print $firstName . " " . $lastName ?></h1>
            <br>

            <?php
            if ($friend == true) {
                print "<form method='post' action='" . $phpSelf . "?user=" . $_GET['user'] . "' ><input type='hidden' name='user' value='" . $_GET['user'] . "'><input type='submit' value='Add this Dad' name='btnAddDad' id='btnAddDad' class='btnDad'></form>";
            } elseif (!isset($_GET['user'])) {
                print '';
            } else {
                if (isset($_POST['btnAddDad'])) {
                    print 'You have successfully added this dad!';
                }
                if (!isset($_GET['user'])) {
                    print "<div><form method='post' action='" . $phpSelf . "?user=" . $_GET['user'] . "' ><input type='hidden' name='user' value='" . $_GET['user'] . "'><textarea name='txtPost' style='width:400px;float:right;height:100px;'></textarea><br><input type='submit' value='Post Message' name='btnPost' id='btnPost'style='margin-left: 250px;' class='btnDad'></form></div>";
                }
            }
            ?>

        </div>
        
        <ol style="height: 20px;">
<?php
        if (($_GET['tab'] == 'feed') OR (!isset($_GET['tab']))) {
                    print '     <li class="activetab">Post Feed</li>';
                } else {
                    print '     <li class="tab"><a href="profile.php?user='.$userName.'&tab=feed">Post Feed</a></li>';
                }
                if ($_GET['tab'] == 'info') {
                    print '     <li class="activetab">DadFacts</li>';
                } else {
                    print '     <li class="tab"><a href="profile.php?user='.$userName.'&tab=info">DadFacts</a></li>';
                }
                if ($_GET['tab'] == 'friends') {
                    print '     <li class="activetab">Pals</li>';
                } else {
                    print '     <li class="tab"><a href="profile.php?user='.$userName.'&tab=friends">Pals</a></li>';
                }
            
            ?>
        </ol>
 
        <?php
        if (($_GET['tab'] == 'feed') OR (!isset($_GET['tab']))) {
            include 'feed.php';
        } elseif ($_GET['tab'] == 'info') {
            include 'info.php';
        } elseif ($_GET['tab'] == 'friends') {
            include 'friends.php';
        }

        ?> 

            </section>


</div>
<?php include 'footer.php' ?>

