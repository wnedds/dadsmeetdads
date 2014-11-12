<?php
include 'top.php';

if ($_SESSION['userName'] != 'wnedds') {
    header('Location: profile.php?block=true');
}

$email = '';

if (isset($_POST['btnInvite'])) {
    $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
    
    $message = "<h1>You're a dad. We're dads. Let's make this work.<h1>";
    $message .= "<p>You've been invited to join the greatest meeting of dads in history.</p>";
    $message .= "<p>Not only that, but you've been invited to take part in the early beta test of Dads Meet Dads!</p>";
    $message .= "<p>Do yourself a favor, and click on the link below. Follow the steps you see, make yourself a profile and start meeting dads right away! You'll be lighting up the barbecue and cracking open a couple cold ones (responsibly) with your fellow dads in no time! Why are you still reading this? You should be signing up and hanging out with all the other dads on Dads Meet Dads! Sign up now and see for yourself!</p>";
    $message .= '<br>';
    $message .= "<p>Okay, but seriously. This is an early test copy of the (relatively) final Dads Meet Dads website. Sign up and have fun, but please keep an eye out for any weird bugs that might pop up. You're not just dads, you're the dads of dads. Granddads, I dare say. If something breaks, let the admins know right away and they can get it fixed. Post any bugs you find on the profile of Will Nedds to get them fixed. Have fun, dad!</p>";
    $message .= "<p>Sign up <a href='https://wnedds.w3.uvm.edu/cs148/dadsmeetdads/'>here.</a></p>";
    
    $to = $email; // the person who filled out the form
    $cc = "";
    $bcc = "wnedds@uvm.edu";
    $from = "Dads Meet Dads <noreply@dadsmeetdads.com>";
    $subject = "Get out your Garfield tie and old baseball mitt";

    $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    if ($mailed) {
    print "<article>The invite has been sent!</article>";
    }
} else {
?>
<article>
    <form method="post" action="<?php print $phpSelf ?>">
        <h1>Invite other dads to Dads Meet Dads!</h1>

        <fieldset>
            <legend>Add the dad's email:</legend>
            <label for="txtEmail" class="required">Email:</label>
            <input type="text" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>" tabindex="400" class="option"
                   maxlength="45" onfocus="this.select()" required='required' placeholder="Email here" style="width: 10em;"><br>
        </fieldset>
        <br>           
        <input type="submit" id="btnInvite" name="btnInvite" value="Invite another Dad" 
               tabindex="991" class="next">

    </form>
</article>
<?php
}
include 'footer.php';
