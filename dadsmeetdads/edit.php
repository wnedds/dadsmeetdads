<?php
include 'top.php';


$query = "SELECT pmkEmail, fldPassword, fldGender, fldCity, fldPicName,fldBio FROM tblUsers WHERE flduserName LIKE '" . $_SESSION['userName'] . "'";
//print $query;
$editInfo = $thisDatabase->select($query);
$query = "SELECT fldHiking,fldSailing,fldGrilling,fldReading,fldTelevision,fldChillaxing,fldSkiing,fldPigskin FROM tblLikes WHERE fnkUserId LIKE '" . $_SESSION['userName'] . "'";
//print $query;
$editLikes = $thisDatabase->select($query);

$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$userName = $_SESSION['userName'];
$password = '';
$passwordCheck = '';
$hiking = $editLikes[0]['fldHiking'];
$sailing = $editLikes[0]['fldSailing'];
$grilling = $editLikes[0]['fldGrilling'];
$reading = $editLikes[0]['fldReading'];
$television = $editLikes[0]['fldTelevision'];
$chillaxing = $editLikes[0]['fldChillaxing'];
$skiing = $editLikes[0]['fldSkiing'];
$pigskin = $editLikes[0]['fldPigskin'];
$gender = $editInfo[0]['fldGender'];
$city = $editInfo[0]['fldCity'];
$ProfilePic = $editInfo[0]['fldPicName'];
$bio = $editInfo[0]['fldBio'];
$adjective = $editInfo[0]['fldAdjective'];


// this would be the full url of your form
// See top.php for variable declartions
$yourURL = $domain . $phpSelf;

$emailERROR = false;
$errorMsg = array();
// This if statement is how we can check to see if the form has been submitted
//#####################MAJOR IF STATEMENT 1############################
if (isset($_POST["btnSubmit"])) {

    //******************************************************************
    // is the referring web page the one we want or is someone trying 
    // to hack in. this is not 100% reliable but ok for our purposes   */
    //
    // Security check block one, no changes needed
    if (!securityCheck()) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }


//Uploads the file to the server, and sanitizes all the inputs
    include ('upload.php');
    //print "The uploaded file is ".$_SESSION['profilePic'];


    $ProfilePic = $_SESSION['profilePic'];
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $passwordCheck = htmlentities($_POST["txtPassword2"], ENT_QUOTES, "UTF-8");
    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    $adjective = htmlentities($_POST["lstAdjective"], ENT_QUOTES, "UTF-8");
    $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
    $city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");

    //$checkArray = array($ProfilePic, $firstName,$lastName,$password,$passwordCheck,$gender,$adjective,$bio,$city);
    //foreach ($checkArray as $oneCheck) {
    //if ($oneCheck[] == '') {
    //}
    //}


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


    if ($password != $passwordCheck) {
        $errorMsg[] = "The passwords you entered do not match";
        $passwordERROR = true;
    }

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to be incorrect.";
        $firstNameERROR = true;
    }

    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to be incorrect.";
        $lastNameERROR = true;
    }
    if ($city == "") {
        $errorMsg[] = "Please enter a city";
        $cityERROR = true;
    } elseif (!verifyAlphaNum($city)) {
        $errorMsg[] = "Your city appears to be incorrect.";
        $cityERROR = true;
    }


//#######################SECOND MAJOR IF STATEMENT#################    
    if (!$errorMsg) {

        //Building queries to insert info into the database
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


            $data = array($password, $firstName, $lastName, $bio, $city, $gender, $ProfilePic, $adjective);
            $query = 'UPDATE tblUsers SET fldPassword="' . $data[0] . '",fldFirstName="' . $data[1] . '",fldLastName="' . $data[2] . '",fldBio="' . $data[3] . '",fldCity="' . $data[4] . '",fldGender="' . $data[5] . '",fldPicName="' . $data[6] . '",fldAdjective="' . $data[7] . '" WHERE fldUserName LIKE "'.$_SESSION['userName'].'"';
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

        //Second Query

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $data = array($userName, $hiking, $sailing, $grilling, $reading, $television, $chillaxing, $skiing, $pigskin);
            $query = 'UPDATE tblLikes SET fnkUserId="' . $data[0] . '",fldHiking="' . $data[1] . '",fldSailing="' . $data[2] . '",fldGrilling="' . $data[3] . '",fldReading="' . $data[4] . '",fldTelevision="' . $data[5] . '",fldChillaxing="' . $data[6] . '",fldSkiing="' . $data[7] . '",fldPigskin="' . $data[8] . '" WHERE fnkUserId LIKE "'.$_SESSION['userName'].'"';
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
            $data = array($firstName,$lastName,$ProfilePic);
            $query = 'UPDATE tblFriends SET fldTargetFirst="' . $data[0] . '", fldTargetLast="' . $data[1] . '", fldTargetPic="' . $data[2] . '" WHERE fnkTargetDad LIKE "'.$_SESSION['userName'].'"';
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
        
//Query to update the post list
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $data = array($firstName,$lastName,$ProfilePic);
            $query = 'UPDATE tblPosts SET fldSubjectFirst="' . $data[0] . '", fldSubjectLast="' . $data[1] . '", fldSubjectPic="' . $data[2] . '" WHERE fnkSubjectDad LIKE "'.$_SESSION['userName'].'"';
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


    } // ends form is valid
} // ends if form was submitted. We will be adding more information ABOVE this        
print "<article style='padding:30px'>";




//#########################THIRD MAJOR IF STATEMENT###########################
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {  // closing of if marked with: end body submit
    //Final info after everything is said and done
    print "<aside>";
    print "<h1 style='text-align:center'>Your profile has ";
    echo "been updated! Thanks for using Dads Meet Dads!</h1>";
    print "</aside>";




    //Adding in the User's info for when they are on another page
    $_SESSION['userName'] = $userName;
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
} else {

    //Displaying errors   
    print '<div id="errors">';

    if ($errorMsg) {
        echo "<ol>\n";
        foreach ($errorMsg as $err) {
            echo "<li>" . $err . "</li>\n";
        }
        echo "</ol>\n";
    }

    print '</div>';
    ?> 

    <h1 style='text-align:center'>Need to change your profile?</h1>

    <form action="<?php print $phpSelf ?>" 
          method="post"
          id="frmRegister"
          enctype="multipart/form-data" >

        <fieldset>
            <legend>Add a new password</legend>
            <label for="txtPassword" class="required">Password: 
            <input type="password" id="txtPassword" name="txtPassword" value="" tabindex="255" class="option"
                   maxlength="16" onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
        </fieldset>
        <br>    
        <fieldset>
            <legend>Confirm your password</legend>
            <label for="txtPassword2" class="required">Password: 
            <input type="password" id="txtPassword2" name="txtPassword2" value="" tabindex="255" class="option"
                   maxlength="16" onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
        </fieldset>
        <br>  

        <fieldset>
            <legend>Change your first name:</legend>
            <label for="txtFirstName" class="required">First Name: 
            <input type="text" id="txtFirstName" name="txtFirstName" value="<?php print $firstName; ?>" tabindex="260" class="option"
                   maxlength="12" onfocus="this.select()" placeholder="First Name here" style="width: 10em;"></label><br>
        </fieldset>
        <br>

        <fieldset>
            <legend>Change your last name:</legend>
            <label for="txtLastName" class="required">Last Name: 
            <input type="text" id="txtLastName" name="txtLastName" value="<?php echo $lastName; ?>" tabindex="261" class="option"
                   maxlength="12" onfocus="this.select()" placeholder="Last Name here" style="width: 10em;"></label><br>
        </fieldset>
        <br>
        <fieldset>
            <legend class="required">What gender dad are you?</legend>
            <label><input type="radio" class="option" id="radDadYoung" name="radGender" <?php if ($gender == "boy") echo ' checked="checked" '; ?> value="boy">Boy Dad</label><br>
            <label><input type="radio"  class="option" id="radDadMed" name="radGender" <?php if ($gender == "girl") echo ' checked="checked" '; ?> value="girl">Girl Dad</label><br>
            <label><input type="radio"  class="option" id="radDadOld" name="radGender" <?php if ($gender == "other") echo ' checked="checked" '; ?> value="other">Outside-The-Gender-Binary Dad</label><br>

        </fieldset>
        <br>
        <fieldset>
            <legend class="required">What activities do you enjoy?</legend>
            <label><input type="checkbox" id="chkHiking" name="chkHiking" <?php if ($hiking == true) echo 'checked="checked"'; ?> value="Hiking"  class="option" tabindex="221" style="float:left"> Hiking</label><br>
            <label><input type="checkbox" id="chkSailing" name="chkSailing" <?php if ($sailing == true) echo 'checked="checked"'; ?> value="Sailing" class="option" tabindex="222"  style="float:left"> Sailing</label><br>
            <label><input type="checkbox" id="chkGrilling" name="chkGrilling" <?php if ($grilling == true) echo 'checked="checked"'; ?> value="Grilling" class="option" tabindex="223"  style="float:left"> Grilling</label><br>
            <label><input type="checkbox" id="chkReading" name="chkReading" <?php if ($reading == true) echo 'checked="checked"'; ?> value="Reading" class="option" tabindex="224"  style="float:left"> Reading</label><br>
            <label><input type="checkbox" id="chkTelevision" name="chkTelevision" <?php if ($television == true) echo 'checked="checked"'; ?> value="Television" class="option" tabindex="225"  style="float:left"> Watching the television</label><br>
            <label><input type="checkbox" id="chkChillaxing" name="chkChillaxing" <?php if ($chillaxing == true) echo 'checked="checked"'; ?> value="Chillaxing" class="option" tabindex="226"  style="float:left"> Chillaxing</label><br>
            <label><input type="checkbox" id="chkSkiing" name="chkSkiing" <?php if ($skiing == true) echo 'checked="checked"'; ?> value="Skiing" class="option" tabindex="227"  style="float:left"> Skiing</label><br>                 
            <label><input type="checkbox" id="chkPigskin" name="chkPigskin" <?php if ($pigskin == true) echo 'checked="checked"'; ?> value="Pigskin" class="option" tabindex="227"  style="float:left"> Football</label><br>     


        </fieldset>
        <br>
        <fieldset>
            <legend>What adjective best describes you?</legend>
            <label class="required">Choose one: 
            <br>
            <select id="lstAdjective" name="lstAdjective" tabindex="283" size="1">
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
            </select></label><br>

        </fieldset>
        <br>
        <fieldset>					
            <legend>Spruce up your biography:</legend>
            <textarea id="txtBio" name="txtBio" tabindex="300" 
                      onfocus="this.select()" style="width: 25em; height: 4em;" ><?php echo $bio ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Did you move somewhere new?</legend>
            <label for="txtCity" >City: 
            <input type="text" id="txtCity" name="txtCity" value="<?php echo $city; ?>" tabindex="261" class="option"
                   maxlength="12" onfocus="this.select()" placeholder="City here" style="width: 10em;"></label><br>
        </fieldset>
        <br>
        <fieldset>
            <legend>Would you like to update your profile picture?</legend>
            <label for="file">Filename: 
            <input type="file" name="file" id="file"></label><br>
        </fieldset>
        <br>

        <fieldset>
            <legend>Click to continue:</legend>


            <input type="reset" id="btnreset" name="btnReset" value="Reset" 
                   tabindex="993" class="btnDad" style="margin-left:30px" >					
            <input type="submit" id="btnSubmit" name="btnSubmit" value="Update Profile" 
                   tabindex="991" class="btnDad" style="margin-left:30px">
        </fieldset>

        <br>


    </form>

    <?php
} // end body submit NO CHANGE NEEDED
if ($debug) {
    print "<p>END OF PROCESSING. THAT WASN'T TOO BAD.</p>";
}

print "</article>";
?>
