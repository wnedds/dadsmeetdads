<?php
include 'top.php';
//Oh god, the logic of this

if (isset($_POST['add'])) {
    if ($_POST['tbl'] == 'u') {
        if (isset($_POST['btnSubmit'])) {
            //Uploads the file to the server, and sanitizes all the inputs
            include ('upload.php');
            //print "The uploaded file is ".$_SESSION['profilePic'];


            $ProfilePic = $_SESSION['profilePic'];
            $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
            $userName = htmlentities($_POST["txtUserName"], ENT_QUOTES, "UTF-8");
            $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
            $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
            $password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
            $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
            $adjective = htmlentities($_POST["lstAdjective"], ENT_QUOTES, "UTF-8");
            $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
            $city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
            $confirm = htmlentities($_POST["radConfirm"], ENT_QUOTES, "UTF-8");


            if (isset($_POST["chkHiking"])) {
                $hiking = true;
            } else {
                $hiking = false;
            }
            if (isset($_POST["chkSailing"])) {
                $sailing = true;
            } else {
                $sailing = false;
            }
            if (isset($_POST["chkGrilling"])) {
                $grilling = true;
            } else {
                $grilling = false;
            }
            if (isset($_POST["chkReading"])) {
                $reading = true;
            } else {
                $reading = false;
            }
            if (isset($_POST["chkTelevision"])) {
                $television = true;
            } else {
                $television = false;
            }
            if (isset($_POST["chkChillaxing"])) {
                $chillaxing = true;
            } else {
                $Chillaxing = false;
            }
            if (isset($_POST["chkSkiing"])) {
                $skiing = true;
            } else {
                $skiing = false;
            }
            if (isset($_POST["chkPigskin"])) {
                $pigskin = true;
            } else {
                $pigskin = false;
            }
            $password = sha1($password);



            $primaryKey = "";
            $dataEntered = false;
            try {
                $thisDatabase->db->beginTransaction();
                $password = sha1($password);

                if ($ProfilePic == '') {
                    $ProfilePic = 'dadsmeetnodad.jpg';
                }
                $data = array($userName, $password, $firstName, $lastName, $bio, $city, $email, $confirm, $gender, $ProfilePic, $adjective);
                $query = 'INSERT INTO tblUsers(fldUserName,fldPassword,fldFirstName,fldLastName,fldBio,fldCity,pmkEmail,fldConfirmed,fldGender,fldPicName,fldAdjective) VALUES("' . $data[0] . '","' . $data[1] . '","' . $data[2] . '","' . $data[3] . '","' . $data[4] . '","' . $data[5] . '","' . $data[6] . '","' . $data[7] . '","' . $data[8] . '","' . $data[9] . '","' . $data[10] . '")';
                print $query;
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

//Second Query

            $primaryKey = "";
            $dataEntered = false;
            try {
                $thisDatabase->db->beginTransaction();
                $data = array($userName, $hiking, $sailing, $grilling, $reading, $television, $chillaxing, $skiing, $pigskin);
                $query = 'INSERT INTO tblLikes(fnkUserId,fldHiking,fldSailing,fldGrilling,fldReading,fldTelevision,fldChillaxing,fldSkiing,fldPigskin) VALUES("' . $data[0] . '","' . $data[1] . '","' . $data[2] . '","' . $data[3] . '","' . $data[4] . '","' . $data[5] . '","' . $data[6] . '","' . $data[7] . '","' . $data[8] . '")';
                print $query;
                $results = $thisDatabase->insert($query);
                $dataEntered = $thisDatabase->db->commit();
                $dataEntered = true;
            } catch (PDOException $e) {
                $thisDatabase->db->rollback();
                if ($debug) {
                    print "Error!: " . $e->getMessage() . "</br>";
                }
                $errorMsg[] = "An error occured. Please wait a few minutes and try again.";
            }
        } else {
            ?>
            <form action="<?php print $phpSelf ?>" 
                  method="post"
                  id="frmRegister"
                  enctype="multipart/form-data" >
                <fieldset>
                    <label for="txtUserName" class="required">Username: 
                        <input type="text" id="txtUserName" name="txtUserName" value="" tabindex="250" class="option"
                               maxlength="16" required='required' onfocus="this.select()" placeholder="Username here" style="width: 10em;"></label>
                </fieldset>
                <fieldset>
                    <label for="txtPassword" class="required">Password: 
                        <input type="password" id="txtPassword" name="txtPassword" value="" tabindex="255" class="option"
                               maxlength="16" onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
                </fieldset>
                <fieldset>
                    <label for="txtFirstName" class="required">First Name: 
                        <input type="text" id="txtFirstName" name="txtFirstName" value="" tabindex="260" class="option"
                               maxlength="12" onfocus="this.select()" placeholder="First Name here" style="width: 10em;"></label><br>
                </fieldset>
                <fieldset>
                    <label for="txtLastName" class="required">Last Name: 
                        <input type="text" id="txtLastName" name="txtLastName" value="" tabindex="261" class="option"
                               maxlength="12" onfocus="this.select()" placeholder="Last Name here" style="width: 10em;"></label><br>
                </fieldset>
                <fieldset>
                    <label><input type="radio" class="option" id="radDadYoung" name="radGender"  value="boy">Boy Dad</label><br>
                    <label><input type="radio"  class="option" id="radDadMed" name="radGender"  value="girl">Girl Dad</label><br>
                    <label><input type="radio"  class="option" id="radDadOld" name="radGender"  value="other">Outside-The-Gender-Binary Dad</label><br>

                </fieldset>
                <fieldset>
                    <label><input type="checkbox" id="chkHiking" name="chkHiking"  value="Hiking"  class="option" tabindex="262" style="float:left"> Hiking</label><br>
                    <label><input type="checkbox" id="chkSailing" name="chkSailing"  value="Sailing" class="option" tabindex="263"  style="float:left"> Sailing</label><br>
                    <label><input type="checkbox" id="chkGrilling" name="chkGrilling"  value="Grilling" class="option" tabindex="264"  style="float:left"> Grilling</label><br>
                    <label><input type="checkbox" id="chkReading" name="chkReading"  value="Reading" class="option" tabindex="265"  style="float:left"> Reading</label><br>
                    <label><input type="checkbox" id="chkTelevision" name="chkTelevision"  value="Television" class="option" tabindex="266"  style="float:left"> Watching the television</label><br>
                    <label><input type="checkbox" id="chkChillaxing" name="chkChillaxing"  value="Chillaxing" class="option" tabindex="267"  style="float:left"> Chillaxing</label><br>
                    <label><input type="checkbox" id="chkSkiing" name="chkSkiing" value="Skiing" class="option" tabindex="268"  style="float:left"> Skiing</label><br>                 
                    <label><input type="checkbox" id="chkPigskin" name="chkPigskin"  value="Pigskin" class="option" tabindex="269"  style="float:left"> Football</label><br>     


                </fieldset>
                <fieldset>
                    <label class="required">Choose one: 
                        <select id="lstAdjective" name="lstAdjective" tabindex="270" size="1">
                            <option value="neutral">Neutral</option>
                            <option value="calm" >Calm</option>
                            <option value="quirky">Quirky</option>
                            <option value="stubborn" >Stubborn</option>
                            <option value="athletic" >Athletic</option>
                            <option value="timely">Timely</option>
                            <option value="relaxed" >Relaxed</option>
                            <option value="naive" >Naive</option>
                            <option value="cool" >Cool</option>
                            <option value="maniacal">Maniacal</option>
                            <option value="mysterious" >Mysterious</option>
                        </select></label>

                </fieldset>
                <fieldset>
                    <textarea id="txtBio" name="txtBio" tabindex="300" 
                              onfocus="this.select()" style="width: 25em; height: 4em;" ></textarea>
                </fieldset>
                <fieldset>
                    <label for="txtCity" >City: 
                        <input type="text" id="txtCity" name="txtCity" value="" tabindex="310" class="option"
                               maxlength="12" onfocus="this.select()" placeholder="City here" style="width: 10em;"></label><br>
                </fieldset>
                <fieldset>
                    <label for="file">Filename: 
                        <input type="file" name="file" id="file"></label><br>
                </fieldset>
                <fieldset>
                    <label><input type="radio" id="radConfirmed" name="radConfirm" value="1">This user has a confirmed Email</label><br>
                    <label><input type="radio" id="radNotConfirmed" name="radConfirm"  value="0">This user does not have a confirmed Email</label><br>

                </fieldset>
                <fieldset>
                    <input name='add' value='add' type='hidden'>
                    <input type="reset" id="btnreset" name="btnReset" value="Reset" 
                           tabindex="990" class="btnDad" style="margin-left:30px" >					
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Insert Profile" 
                           tabindex="991" class="btnDad" style="margin-left:30px">
                </fieldset>
            </form>
            <?php
        }
    } elseif ($_POST['tbl'] == 'f') {
        
    } elseif ($_POST['tbl'] == 'p') {
        
    }



    //#####################################Deleting Stuff############################
} elseif (isset($_POST['delete'])) {
    if (isset($_POST['yes'])) {

        $delUser = ltrim($_POST['delete'], 'Delete ');
        if ($_POST['tbl'] == 'u') {
            $query = 'DELETE FROM tblUsers WHERE fldUserName LIKE "' . $delUser . '"';
            $results = $thisDatabase->delete($query);
            $query = 'DELETE FROM tblLikes WHERE fnkUserId LIKE "' . $delUser . '"';
            $results = $thisDatabase->delete($query);
        } elseif ($_POST['tbl'] == 'f') {
            
        } elseif ($_POST['tbl'] == 'p') {
            
        }
    } elseif (isset($_POST['no'])) {
        print '';
    } else {
        print '<p style="text-align:center;color:red;padding:20px;background-color:white">Are you sure you want to complete this action? You will not be able to undo it.</p>';
        print '<form method="post" style="margin:auto"><input type="submit" name="yes" value="Yes, this must be done for the betterment of Dadkind"><input type="submit" name="no" value="No! Every dad is sacred, and deserves to flourish and meet other neato dads!"><input type="hidden" value="' . $_POST['delete'] . '" name="delete"></form>';
    }
} elseif (isset($_POST['edit'])) {
    if (isset($_POST['btnSubmit'])) {
        //Uploads the file to the server, and sanitizes all the inputs
        include ('upload.php');
        //print "The uploaded file is ".$_SESSION['profilePic'];


        $ProfilePic = $_SESSION['profilePic'];
        $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
        $userName = htmlentities($_POST["txtUserName"], ENT_QUOTES, "UTF-8");
        $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
        $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
        $password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
        $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
        $adjective = htmlentities($_POST["lstAdjective"], ENT_QUOTES, "UTF-8");
        $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
        $city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
        $confirm = htmlentities($_POST["radConfirm"], ENT_QUOTES, "UTF-8");
        if (isset($_POST["chkHiking"])) {
            $hiking = true;
        } else {
            $hiking = false;
        }
        if (isset($_POST["chkSailing"])) {
            $sailing = true;
        } else {
            $sailing = false;
        }
        if (isset($_POST["chkGrilling"])) {
            $grilling = true;
        } else {
            $grilling = false;
        }
        if (isset($_POST["chkReading"])) {
            $reading = true;
        } else {
            $reading = false;
        }
        if (isset($_POST["chkTelevision"])) {
            $television = true;
        } else {
            $television = false;
        }
        if (isset($_POST["chkChillaxing"])) {
            $chillaxing = true;
        } else {
            $chillaxing = false;
        }
        if (isset($_POST["chkSkiing"])) {
            $skiing = true;
        } else {
            $skiing = false;
        }
        if (isset($_POST["chkPigskin"])) {
            $pigskin = true;
        } else {
            $pigskin = false;
        }

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();


            if ($ProfilePic == '') {
                $ProfilePic = $editInfo[0]['fldPicName'];
            }
            //print "The password is ".$password;
            if ($password == '') {
                $password = $editInfo[0]['fldPassword'];
            } else {
                $password = sha1($password);
            }


            $data = array($password, $firstName, $lastName, $bio, $city, $gender, $ProfilePic, $adjective, $confirm, $userName, $email);
            $query = 'UPDATE tblUsers SET fldPassword="' . $data[0] . '",fldFirstName="' . $data[1] . '",fldLastName="' . $data[2] . '",fldBio="' . $data[3] . '",fldCity="' . $data[4] . '",fldGender="' . $data[5] . '",fldPicName="' . $data[6] . '",fldAdjective="' . $data[7] . '",fldConfirmed="' . $data[8] . '",fldUserName="' . $data[9] . '",pmkEmail="' . $data[10] . '" WHERE fldUserName LIKE "' . $_POST['edit'] . '"';
            print $query;

            //print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
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

        //Second Query

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $data = array($userName, $hiking, $sailing, $grilling, $reading, $television, $chillaxing, $skiing, $pigskin);
            $query = 'UPDATE tblLikes SET fnkUserId="' . $data[0] . '",fldHiking="' . $data[1] . '",fldSailing="' . $data[2] . '",fldGrilling="' . $data[3] . '",fldReading="' . $data[4] . '",fldTelevision="' . $data[5] . '",fldChillaxing="' . $data[6] . '",fldSkiing="' . $data[7] . '",fldPigskin="' . $data[8] . '" WHERE fnkUserId LIKE "' . $_POST['edit'] . '"';
            //print $query;
            //print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
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

        //Query to update the friends table with changed name and profile picture


        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $data = array($firstName, $lastName, $ProfilePic);
            $query = 'UPDATE tblFriends SET fldTargetFirst="' . $data[0] . '", fldTargetLast="' . $data[1] . '", fldTargetPic="' . $data[2] . '" WHERE fnkTargetDad LIKE "' . $_POST['edit'] . '"';
            //print $query;
            //print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
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

//Query to update the post list
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $data = array($firstName, $lastName, $ProfilePic);
            $query = 'UPDATE tblPosts SET fldSubjectFirst="' . $data[0] . '", fldSubjectLast="' . $data[1] . '", fldSubjectPic="' . $data[2] . '" WHERE fnkSubjectDad LIKE "' . $_POST['edit'] . '"';
            //print $query;
            //print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
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
    } else {

//###############################Queries to show editing option#######################

        $queryID = ltrim($_POST['edit'], 'Edit');
        $queryID = ltrim($queryID, ' ');
//print $queryID;

        $query1 = "SELECT pmkUserId, pmkEmail, fldUserName, fldPassword, fldFirstName, fldLastName, fldSignUpDate, fldConfirmed, fldAdjective, fldBio, fldCity, fldGender, fldPicName FROM tblUsers WHERE fldUserName LIKE '" . $queryID . "'";
        $userList = $thisDatabase->select($query1);
//print $query1;
        $query2 = "SELECT pmkLikesId, fnkUserId,fldHiking, fldSailing, fldGrilling, fldReading, fldTelevision, fldChillaxing, fldSkiing, fldPigskin FROM tblLikes WHERE fnkUserId LIKE '" . $queryID . "'";
        $likeList = $thisDatabase->select($query2);


        $firstName = $userList[0]['fldFirstName'];
        $lastName = $userList[0]['fldLastName'];
        $userName = $userList[0]['fldUserName'];
        $password = $userList[0]['fldPassword'];
        $hiking = $likeList[0]['fldHiking'];
        $sailing = $likeList[0]['fldSailing'];
        $grilling = $likeList[0]['fldGrilling'];
        $reading = $likeList[0]['fldReading'];
        $television = $likeList[0]['fldTelevision'];
        $chillaxing = $likeList[0]['fldChillaxing'];
        $skiing = $likeList[0]['fldSkiing'];
        $pigskin = $likeList[0]['fldPigskin'];
        $gender = $userList[0]['fldGender'];
        $city = $userList[0]['fldCity'];
        $ProfilePic = $userList[0]['fldPicName'];
        $bio = $userList[0]['fldBio'];
        $adjective = $userList[0]['fldAdjective'];
        ?>

        <form action="<?php print $phpSelf ?>" 
              method="post"
              id="frmRegister"
              enctype="multipart/form-data" >
            <fieldset>
                <label for="txtUserName" class="required">Username: 
                    <input type="text" id="txtUserName" name="txtUserName" value="<?php print $userName; ?>" tabindex="250" class="option"
                           maxlength="16" required='required' onfocus="this.select()" placeholder="Username here" style="width: 10em;"></label>
            </fieldset>
            <fieldset>
                <label for="txtPassword" class="required">Password: 
                    <input type="password" id="txtPassword" name="txtPassword" value="<?php print $password; ?>" tabindex="255" class="option"
                           maxlength="16" onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
            </fieldset>
            <fieldset>
                <label for="txtFirstName" class="required">First Name: 
                    <input type="text" id="txtFirstName" name="txtFirstName" value="<?php print $firstName; ?>" tabindex="260" class="option"
                           maxlength="12" onfocus="this.select()" placeholder="First Name here" style="width: 10em;"></label><br>
            </fieldset>
            <fieldset>
                <label for="txtLastName" class="required">Last Name: 
                    <input type="text" id="txtLastName" name="txtLastName" value="<?php echo $lastName; ?>" tabindex="261" class="option"
                           maxlength="12" onfocus="this.select()" placeholder="Last Name here" style="width: 10em;"></label><br>
            </fieldset>
            <fieldset>
                <label><input type="radio" class="option" id="radDadYoung" name="radGender" <?php if ($gender == "boy") echo ' checked="checked" '; ?> value="boy">Boy Dad</label><br>
                <label><input type="radio"  class="option" id="radDadMed" name="radGender" <?php if ($gender == "girl") echo ' checked="checked" '; ?> value="girl">Girl Dad</label><br>
                <label><input type="radio"  class="option" id="radDadOld" name="radGender" <?php if ($gender == "other") echo ' checked="checked" '; ?> value="other">Outside-The-Gender-Binary Dad</label><br>

            </fieldset>
            <fieldset>
                <label><input type="checkbox" id="chkHiking" name="chkHiking" <?php if ($hiking == true) echo 'checked="checked"'; ?> value="Hiking"  class="option" tabindex="262" style="float:left"> Hiking</label><br>
                <label><input type="checkbox" id="chkSailing" name="chkSailing" <?php if ($sailing == true) echo 'checked="checked"'; ?> value="Sailing" class="option" tabindex="263"  style="float:left"> Sailing</label><br>
                <label><input type="checkbox" id="chkGrilling" name="chkGrilling" <?php if ($grilling == true) echo 'checked="checked"'; ?> value="Grilling" class="option" tabindex="264"  style="float:left"> Grilling</label><br>
                <label><input type="checkbox" id="chkReading" name="chkReading" <?php if ($reading == true) echo 'checked="checked"'; ?> value="Reading" class="option" tabindex="265"  style="float:left"> Reading</label><br>
                <label><input type="checkbox" id="chkTelevision" name="chkTelevision" <?php if ($television == true) echo 'checked="checked"'; ?> value="Television" class="option" tabindex="266"  style="float:left"> Watching the television</label><br>
                <label><input type="checkbox" id="chkChillaxing" name="chkChillaxing" <?php if ($chillaxing == true) echo 'checked="checked"'; ?> value="Chillaxing" class="option" tabindex="267"  style="float:left"> Chillaxing</label><br>
                <label><input type="checkbox" id="chkSkiing" name="chkSkiing" <?php if ($skiing == true) echo 'checked="checked"'; ?> value="Skiing" class="option" tabindex="268"  style="float:left"> Skiing</label><br>                 
                <label><input type="checkbox" id="chkPigskin" name="chkPigskin" <?php if ($pigskin == true) echo 'checked="checked"'; ?> value="Pigskin" class="option" tabindex="269"  style="float:left"> Football</label><br>     


            </fieldset>
            <fieldset>
                <label class="required">Choose one: 
                    <select id="lstAdjective" name="lstAdjective" tabindex="270" size="1">
                        <option <?php if ($adjective == "neutral") echo ' selected="selected" '; ?> value="neutral">Neutral</option>
                        <option <?php if ($adjective == "calm") echo ' selected="selected" '; ?> value="calm" >Calm</option>
                        <option <?php if ($adjective == "quirky") echo ' selected="selected" '; ?> value="quirky">Quirky</option>
                        <option <?php if ($adjective == "stubborn") echo ' selected="selected" '; ?> value="stubborn" >Stubborn</option>
                        <option <?php if ($adjective == "athletic") echo ' selected="selected" '; ?> value="athletic" >Athletic</option>
                        <option <?php if ($adjective == "timely") echo ' selected="selected" '; ?> value="timely">Timely</option>
                        <option <?php if ($adjective == "relaxed") echo ' selected="selected" '; ?> value="relaxed" >Relaxed</option>
                        <option <?php if ($adjective == "naive") echo ' selected="selected" '; ?> value="naive" >Naive</option>
                        <option <?php if ($adjective == "cool") echo ' selected="selected" '; ?> value="cool" >Cool</option>
                        <option <?php if ($adjective == "maniacal") echo ' selected="selected" '; ?> value="maniacal">Maniacal</option>
                        <option <?php if ($adjective == "mysterious") echo ' selected="selected" '; ?> value="mysterious" >Mysterious</option>
                    </select></label>

            </fieldset>
            <fieldset>
                <textarea id="txtBio" name="txtBio" tabindex="300" 
                          onfocus="this.select()" style="width: 25em; height: 4em;" ><?php echo $bio ?></textarea>
            </fieldset>
            <fieldset>
                <label for="txtCity" >City: 
                    <input type="text" id="txtCity" name="txtCity" value="<?php echo $city; ?>" tabindex="310" class="option"
                           maxlength="12" onfocus="this.select()" placeholder="City here" style="width: 10em;"></label><br>
            </fieldset>
            <fieldset>
                <label for="file">Filename: 
                    <input type="file" name="file" id="file"></label><br>
            </fieldset>
            <fieldset>
                <label for="txtEmail" class="required">Email: 
                    <input type="text" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>" tabindex="400" class="option"
                           maxlength="45" onfocus="this.select()" required='required' placeholder="Email here" style="width: 10em;"></label><br>
            </fieldset>
            <fieldset>
                <label><input type="radio" id="radConfirmed" name="radConfirm" <?php if ($gender == "boy") echo ' checked="checked" '; ?> value="1">This user has a confirmed Email</label><br>
                <label><input type="radio" id="radNotConfirmed" name="radConfirm" <?php if ($gender == "girl") echo ' checked="checked" '; ?> value="0">This user does not have a confirmed Email</label><br>

            </fieldset>
            <fieldset>
                <input type='hidden' name='edit' value='<?php $userName ?>'>
                <input type="reset" id="btnreset" name="btnReset" value="Reset" 
                       tabindex="990" class="btnDad" style="margin-left:30px" >					
                <input type="submit" id="btnSubmit" name="btnSubmit" value="Update Profile" 
                       tabindex="991" class="btnDad" style="margin-left:30px">
            </fieldset>
        </form>
        <?php
    }
} else {

//###############################Queries to show all tables#######################
    $query1 = "SELECT pmkUserId, pmkEmail, fldUserName, fldPassword, fldFirstName, fldLastName, fldSignUpDate, fldConfirmed, fldAdjective, fldBio, fldCity, fldGender, fldPicName FROM tblUsers";
    $userList = $thisDatabase->select($query1);

    $query2 = "SELECT pmkLikesId, fnkUserId,fldHiking, fldSailing, fldGrilling, fldReading, fldTelevision, fldChillaxing, fldSkiing, fldPigskin FROM tblLikes";
    $likeList = $thisDatabase->select($query2);

    $query3 = "SELECT pmkFriendId, fnkSubjectDad, fnkTargetDad, fldTargetFirst, fldTargetLast, fldTargetPic, fldNew FROM tblFriends";
    $friendList = $thisDatabase->select($query3);

    $query4 = "SELECT pmkPostId, fnkSubjectDad, fnkTargetDad, fldSubjectFirst, fldSubjectLast, fldSubjectPic, fldPostText, fldPostDate, fldNew FROM tblPosts";
    $postList = $thisDatabase->select($query4);


    print '<form method="post">';


    $nameIndex = 0;
    foreach ($userList as $oneUser) {
        print '<table class="admin">';
        print '<tr><th>User ID</th><th>Email</th><th>User Name</th><th>Encrypted Password</th><th>First Name</th><th>Last Name</th><th>Sign-Up Date</th><th>Adjective</th><th>Bio</th><th>Pic Name</th><th>Gender</th><th>City</th><th>Confirmed Email?</th></tr>';
        echo '<tr><td>' . $userList[$nameIndex]['pmkUserId'] . '</td><td>' . $userList[$nameIndex]['pmkEmail'] . '</td><td>' . $userList[$nameIndex]['fldUserName'] . '</td><td>' . $userList[$nameIndex]['fldPassword'] . '</td><td>' . $userList[$nameIndex]['fldFirstName'] . '</td><td>' . $userList[$nameIndex]['fldLastName'] . '</td><td>' . $userList[$nameIndex]['fldSignUpDate'] . '</td><td>' . $userList[$nameIndex]['fldAdjective'] . '</td><td>' . $userList[$nameIndex]['fldBio'] . '</td><td>' . $userList[$nameIndex]['fldPicName'] . '</td><td>' . $userList[$nameIndex]['fldGender'] . '</td><td>' . $userList[$nameIndex]['fldCity'] . '</td><td>' . $userList[$nameIndex]['fldConfirmed'] . '</td></tr>';
        print '</table>';
        echo '<div style="margin:auto"><input type="submit" name="edit" value="Edit ' . $userList[$nameIndex]['fldUserName'] . '"><input type="submit" name="delete" value="Delete ' . $userList[$nameIndex]['fldUserName'] . '"></div>';
        $nameIndex++;
    }
    print '<fieldset><input type="submit" name="add" value="Add a New User"><input type="hidden" name="tbl" value="u"></fieldset>';
    print '</form>';



    ;
    print '<table class="admin">';
    print '<th>Like ID</th><th>User Name</th><th>Hiking?</th><th>Sailing?</th><th>Grilling?</th><th>Reading?</th><th>Television?</th><th>Chillaxing?</th><th>Skiing?</th><th>Football?</th>';
    $likeIndex = 0;
    foreach ($likeList as $likeNum => $oneLike) {
        echo '<tr>';
        foreach ($oneLike as $key => $innerLike) {
            if (!is_int($key)) {
                echo '<td>' . $innerLike . '</td>';
            }
        }
        echo '<td style="margin:auto"><input type="submit" name="edit" value="Edit ' . $likeList[$likeIndex]['fnkUserId'] . '"></td><td><input type="submit" name="delete" value="Delete ' . $likeList[$likeIndex]['fnkUserId'] . '"></td>';
        echo '</tr>';
        $likeIndex++;
    }
    print '</table>';



    print '<form method="post">';
    print '<table class="admin">';
    print '<th>Friend ID</th><th>Subject User</th><th>Target User</th><th>Target First</th><th>Target Last</th><th>Target Profile Pic</th><th>New?</th>';
    $friendIndex = 0;
    foreach ($friendList as $oneFriend) {
        echo '<tr>';
        foreach ($oneFriend as $key => $innerFriend) {
            if (!is_int($key)) {
                echo '<td>' . $innerFriend . '</td>';
            }
        }
        echo '<td style="margin:auto"><input type="submit" name="edit" value="Edit ' . $friendList[$friendIndex]['pmkFriendId'] . '"></td><td><input type="submit" name="delete" value="Delete ' . $friendList[$friendIndex]['pmkFriendId'] . '"></td>';
        echo '</tr>';
        $friendIndex++;
    }
    print '</table>';
    print '<div><input type="submit" value="Add A New Friendship"><input type="hidden" value="tblFriends"><input type="hidden" name="tbl" value="f"></div>';
    print '</form>';





    print '<form method="post">';
    print '<table class="admin">';
    print '<th>Post ID</th><th>Subject User</th><th>Target User</th><th>Subject First</th><th>Subject Last</th><th>Subject Profile Pic</th><th>Post Date</th><th>New?</th>';
    $postIndex = 0;
    foreach ($postList as $onePost) {
        echo '<tr>';
        foreach ($onePost as $key => $innerPost) {
            if (!is_int($key)) {
                echo '<td>' . $innerPost . '</td>';
            }
        }
        echo '<td style="margin:auto"><input type="submit" name="edit" value="Edit ' . $postList[$postIndex]['pmkPostId'] . '"></td><td><input type="submit" name="delete" value="Delete ' . $postList[$postIndex]['pmkPostId'] . '"></td>';
        echo '</tr>';
        $postIndex++;
    }
    print '</table>';
    print '<div><input type="submit" value="Add a New Profile Post"><input type="hidden" value="tblPost"><input type="hidden" name="tbl" value="p"></div>';
    print '</form>';
}
include 'footer.php';
