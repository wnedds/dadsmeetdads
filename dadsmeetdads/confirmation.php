<?php
/* the purpose of this page is to accept the hashed date joined and primary key  
 * as passed into this page in the GET format.
 * 
 * I retrieve the date joined from the table for this person and verify that 
 * they are the same. After which i update the confirmed field and acknowlege 
 * to the user they were successful. Then i send an email to the system admin 
 * to approve their membership 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Last updated on: October 17, 2014
 * 
 * 
 */


include "top.php";

?><article><?php
print '<h1>Registration Confirmation</h1>';

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

$adminEmail = "wnedds@uvm.edu";
$message = "<p>There seems to have been an issue with your submission. Please wait a few minutes and try again.</p>";


//##############################################################
//
// SECTION: 2 
// 
// process request

if (isset($_GET["q"])) {
    $key1 = htmlentities($_GET["q"], ENT_QUOTES, "UTF-8");
    $key2 = htmlentities($_GET["w"], ENT_QUOTES, "UTF-8");

    $data = array($key2);
    //##############################################################
    // get the membership record 

    $query = "SELECT fldSignUpDate, pmkEmail FROM tblUsers WHERE pmkEmail = '" . $key2 . "' ";
    // print $query;
    $results = $thisDatabase->select($query);

    $dateSubmitted = $results[0]["fldSignUpDate"];
    $email = $results[0]["pmkEmail"];
    $k1 = sha1($dateSubmitted);

    if ($debug) {
        print "<p>Date: " . $dateSubmitted;
        print "<p>email: " . $email;
        print "<p><pre>";
        print_r($results);
        print "</pre></p>";
        print "<p>k1: " . $k1;
        print "<p>q : " . $key1;
    }
    //##############################################################
    // update confirmed
    if ($key1 == $k1) {
        if ($debug) {
            print "<h1>Confirmed</h1>";
        }
        $query = "UPDATE tblUsers SET fldConfirmed = 1 WHERE pmkEmail = '" . $email . "' ";
        //print $query;
        $results = $thisDatabase->insert($query);
        //print "The results are ".$results;


        if ($debug) {
            print "<p>Query: " . $query;
            print "<p><pre>";
            print_r($results);
            print_r($data);
            print "</pre></p>";
        }
        // notify admin
        $message = '<h2>Welcome!</h2>';

        $message = "<p>Click this link to approve this registration: ";
        $message .= '<a href="' . $domain . $path_parts["dirname"] . '/approve.php?q=' . $key2 . '">Approve Registration</a></p>';
        $message .= "<p>or copy and paste this url into a web browser: ";
        $message .= $path_parts["dirname"] . '/approve.php?q=' . $key2 . "</p>";

        if ($debug)
            print "<p>" . $message;

        $to = $adminEmail;
        $cc = "";
        $bcc = "";
        $from = "Dads Meet Dads <noreply@dadsmeetdads.com>";
        $subject = "A new Dad Approaches!";
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

        if ($debug) {
            print "<p>";
            if (!$mailed) {
                print "NOT ";
            }
            print "mailed to admin " . $to . ".</p>";
        }

        // notify user
        $to = $email;
        $cc = "";
        $bcc = "";
        $from = "Dads Meet Dads <noreply@dadsmeetdads.com>";
        $subject = "You have successfully signed up to Dads Meet Dads!";
        $message = "<h1>You did it, dad!</h1>";
        $message .= "<p>Hey dad!</p><br>";
        $message .= "<p>Welcome to Dads Meet Dads! You can now use your profile to do all sorts of cool dad things! Hang around and see what dads you meet! </p>";

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

        print $message;
        if ($debug) {
            print "<p>";
            if (!$mailed) {
                print "NOT ";
            }
            print "mailed to member: " . $to . ".</p>";
        }
    } else {
        print $message;
    }
} // ends isset get q
?>
</article>
<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>

</body>
</html>
