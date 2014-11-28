<?php
include 'top.php';


$firstName = "";
$lastName = "";
$userName = "";
$password = "";
$email = "";
$hiking = false;
$sailing = false;
$grilling = false;
$reading = false;
$television = false;
$chillaxing = false;
$skiing = false;
$pigskin = false;
$gender = 'other';
$city = '';
$confirm = 0;
$ProfilePic = '';
$bio = '';



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


    $mailed = false;
    $messageA = "";
    $messageB = "";
    $messageC = "";

//Uploads the file to the server, and sanitizes all the inputs
    include ('upload.php');
//print "The uploaded file is ".$_SESSION['profilePic'];
    $ProfilePic = $_SESSION['profilePic'];
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $userName = htmlentities($_POST["txtUserName"], ENT_QUOTES, "UTF-8");
    $password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $passwordCheck = htmlentities($_POST["txtPassword2"], ENT_QUOTES, "UTF-8");
    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    $adjective = htmlentities($_POST["lstAdjective"], ENT_QUOTES, "UTF-8");
    $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
    $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
    $city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
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


//Checks if the submitted email is on the server
    $query = "SELECT pmkEmail FROM tblUsers WHERE pmkEmail LIKE '" . $email . "'";
    $usedEmail = array();
    $usedEmail = $thisDatabase->select($query);

    $query = "SELECT fldUserName FROM tblUsers WHERE fldUserName LIKE '" . $userName . "'";
    $usedName = array();
    $usedName = $thisDatabase->select($query);

//Ensures that all of the information is valid
    if ($email == "") {
        $errorMsg[] = "Sorry, you need to enter a valid email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Sorry, but your email address appears to be incorrect.";
        $emailERROR = true;
    } elseif (!empty($usedEmail)) {
        $errorMsg[] = "This email is already in use. It looks like you're already a dad!";
        $emailERROR = true;
    }
    if ($userName == "") {
        $errorMsg[] = "Please enter a user name";
        $userNameERROR = true;
    } elseif (!verifyAlphaNum($userName)) {
        $errorMsg[] = "Your username address appears to be incorrect.";
        $userNameERROR = true;
    } elseif (!empty($usedName)) {
        $errorMsg[] = "This username is already in use. It looks like you're already a dad!";
        $emailERROR = true;
    }
    if ($password == "") {
        $errorMsg[] = "Please enter your password";
        $PasswordERROR = true;
    } elseif (!verifyAlphaNum($password)) {
        $errorMsg[] = "Your password appears to be incorrect.";
        $passwordERROR = true;
    } elseif ($password != $passwordCheck) {
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


//Consider removing all of this block

        $message = '<h2>Welcome to Dads Meet Dads! Here is what you submitted:</h2>';

        foreach ($_POST as $key => $value) {
            if ($key != "btnSubmit") {

                $message .= "<p>";

                $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

                foreach ($camelCase as $one) {
                    $message .= $one . " ";
                }
                $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
            }
        }



//Building queries to insert info into the database
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

//print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query);

            $primaryKey = $email;
            if ($debug) {
                print "<p>pmk= " . $primaryKey;
            }

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

    //print $query;
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query);

            $primaryKey = $email;
            if ($debug) {
                print "<p>pmk= " . $primaryKey;
            }

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


// If the transaction was successful, give success message
        if ($dataEntered) {
            if ($debug) {
                print "<p>data entered now prepare keys ";
            }
//#################################################################
// create a key value for confirmation

            $query = "SELECT fldSignUpDate FROM tblUsers WHERE pmkEmail='" . $primaryKey . "'";
            $results = $thisDatabase->select($query);

            $dateSubmitted = $results[0]["fldSignUpDate"];
            $key1 = sha1($dateSubmitted);
            $key2 = $primaryKey;

            if ($debug) {
                print "<p>key 1: " . $key1;
            }
            if ($debug) {
                print "<p>key 2: " . $key2;
            }


//Emails confirmation code so that the user can confirm their account

            $messageA = '<h2>Thank you for registering!</h2>';

            $messageB = "<p>All you have to do is click this link to confirm your registration: ";
            $messageB .= '<a href="' . $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . '">Confirm Registration</a></p>';
            $messageB .= "<p>or copy and paste this url into a web browser: ";
            $messageB .= $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . "</p>";

            $messageC .= "<p><b>Email Address:</b><i>   " . $email . "</i></p>";

            $to = $email; // the person who filled out the form
            $cc = "";
            $bcc = "";
            $from = "Dads Meet Dads <noreply@dadsmeetdads.com>";
            $subject = "Welcome to Dads Meet Dads!";

            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC);
        } //ends if data was entered
    } // ends form is valid
} // ends if form was submitted. We will be adding more information ABOVE this        
print "<article style='padding:30px'>";




//#########################THIRD MAJOR IF STATEMENT###########################
if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {  // closing of if marked with: end body submit
    //Final info after everything is said and done
    print "<aside>";
    print "<h1 style='text-align:center'>Your profile has ";

    if (!$mailed) {
        echo "not ";
    }

    echo "been created! Welcome to Dads Meet Dads!</h1>";

    
    print "<p>Hold your horses there, sport! You have to sign up before you can be a part of the Dads Meet Dads community. Why, if anyone could just go around and visit whatever page they like, the internet would be even more dangerous than it is now! You'll need to confirm your account first so that we can be sure you're really one of us dads, at which point you can start building ships-n-a-bottle and filing your tax returns with other equally cool dads! Check your email for updates and confirm your email to begin!</p>";
    print "<p>You will ";
    if (!$mailed) {
        echo "not ";
    }
    print "receive a message at ". $email . "</p>";
    print "</aside>";




    //Adding in the User's info for when they are on another page
    //$_SESSION['userName'] = $userName;
    //$_SESSION['firstName'] = $firstName;
    //$_SESSION['lastName'] = $lastName;
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

    <h1 style='text-align:center'>Ready to meet other cool dads?</h1>

    <form action="<?php print $phpSelf ?>" 
          method="post"
          id="frmRegister"
          enctype="multipart/form-data" >

        <fieldset>
            <legend>Choose a username associated with your dadsona</legend>
            <label for="txtUserName" class="required">Username: 
            <input type="text" id="txtUserName" name="txtUserName" value="<?php print $userName; ?>" tabindex="250" class="option"
                   maxlength="16" required='required' onfocus="this.select()" placeholder="Username here" style="width: 10em;"></label><br>
        </fieldset>
        <br> 

        <fieldset>
            <legend>Add a password to hide from non-dads</legend>
            <label for="txtPassword" class="required">Password: 
            <input type="password" id="txtPassword" name="txtPassword" value="<?php print $password; ?>" tabindex="255" class="option"
                   maxlength="16" required='required' onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
        </fieldset>
        <br>    
        <fieldset>
            <legend>Confirm your password</legend>
            <label for="txtPassword2" class="required">Password: 
            <input type="password" id="txtPassword2" name="txtPassword2" value="" tabindex="260" class="option"
                   maxlength="16" required='required' onfocus="this.select()" placeholder="Password here" style="width: 10em;"></label><br>
        </fieldset>
        <br>  

        <fieldset>
            <legend>What is your first name?</legend>
            <label for="txtFirstName" class="required">First Name: 
            <input type="text" id="txtFirstName" name="txtFirstName" value="<?php print $firstName; ?>" tabindex="270" class="option"
                   maxlength="12" required='required' onfocus="this.select()" placeholder="First Name here" style="width: 10em;"></label><br>
        </fieldset>
        <br>

        <fieldset>
            <legend>What is your last name?</legend>
            <label for="txtLastName" class="required">Last Name: 
            <input type="text" id="txtLastName" name="txtLastName" value="<?php echo $lastName; ?>" tabindex="280" class="option"
                   maxlength="12" required='required' onfocus="this.select()" placeholder="Last Name here" style="width: 10em;"></label><br>
        </fieldset>
        <br>
        <fieldset>
            <legend class="required">What gender dad are you?</legend>
            <label><input type="radio" class="option" id="radDadYoung" name="radGender" <?php if ($gender == "boy") echo ' checked="checked" '; ?> value="boy">Boy Dad</label><br>
            <label><input type="radio"  class="option" id="radDadMed" name="radGender" <?php if ($gender == "girl") echo ' checked="checked" '; ?> value="girl">Girl Dad</label><br>
            <label><input type="radio"  class="option" id="radDadOld" name="radGender" <?php if ($gender == "other") echo ' checked="checked" '; ?> value="">Outside-The-Gender-Binary Dad</label><br>


        </fieldset>
        <br>
        <fieldset>
            <legend class="required">What activities do you enjoy?</legend>
            <label><input type="checkbox" id="chkHiking" name="chkHiking" <?php if ($hiking) echo 'checked="checked"'; ?> value="Hiking"  class="option" tabindex="290" > Hiking</label><br>
            <label><input type="checkbox" id="chkSailing" name="chkSailing" <?php if ($sailing) echo 'checked="checked"'; ?> value="Sailing" class="option" tabindex="291" > Sailing</label><br>
            <label><input type="checkbox" id="chkGrilling" name="chkGrilling" <?php if ($grilling) echo 'checked="checked"'; ?> value="Grilling" class="option" tabindex="292" > Grilling</label><br>
            <label><input type="checkbox" id="chkReading" name="chkReading" <?php if ($reading) echo 'checked="checked"'; ?> value="Reading" class="option" tabindex="293" > Reading</label><br>
            <label><input type="checkbox" id="chkTelevision" name="chkTelevision" <?php if ($television) echo 'checked="checked"'; ?> value="Television" class="option" tabindex="294" > Watching the television</label><br>
            <label><input type="checkbox" id="chkChillaxing" name="chkChillaxing" <?php if ($chillaxing) echo 'checked="checked"'; ?> value="Chillaxing" class="option" tabindex="295" > Chillaxing</label><br>
            <label><input type="checkbox" id="chkSkiing" name="chkSkiing" <?php if ($skiing) echo 'checked="checked"'; ?> value="Skiing" class="option" tabindex="296" > Skiing</label><br>                 
            <label><input type="checkbox" id="chkPigskin" name="chkPigskin" <?php if ($pigskin) echo 'checked="checked"'; ?> value="Pigskin" class="option" tabindex="297" > Football</label><br>     


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
            <legend>Personalize your profile with a small biography:</legend>
            <textarea id="txtBio" name="txtBio" tabindex="300" 
                      onfocus="this.select()" style="width: 25em; height: 4em;" placeholder="Enter a bio here!" ><?php echo $bio; ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Where are you?</legend>
            <label for="txtCity">City: 
            <input type="text" id="txtCity" name="txtCity" value="<?php echo $city; ?>" tabindex="350" class="option"
                   maxlength="16" onfocus="this.select()" placeholder="City here" style="width: 10em;"></label><br>
        </fieldset>
        <br>

        <fieldset>
            <legend>Almost done! We just need your email.</legend>
            <label for="txtEmail" class="required">Email: 
            <input type="text" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>" tabindex="400" class="option"
                   maxlength="45" onfocus="this.select()" required='required' placeholder="Email here" style="width: 10em;"></label><br>
        </fieldset>
        <br>
        <fieldset>
            <legend>Would you like to upload a profile picture?</legend>
            <label for="file">Filename: 
            <input type="file" name="file" id="file"></label><br>
        </fieldset>
        <br>

        <fieldset>
            <legend>You must agree to the Privacy Policy and Terms of Service to sign up.</legend>
            <label><input type="checkbox" id="chkAgreement" name="chkAgreement" value="Agreement" class="option" required="required" tabindex="900" > I agree to the Dads Meet Dads <a href="license.php">Privacy Policy and Terms of Service</a>.</label><br>
        </fieldset>

        <fieldset>
            <legend>Click to continue</legend>


            <input type="reset" id="btnreset" name="btnReset" value="Reset" 
                   tabindex="990" class="btnDad" style="margin-left:40px" >					
            <input type="submit" id="btnSubmit" name="btnSubmit" value="Create Profile" 
                   tabindex="991" class="btnDad" style="margin-left:40px">
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
