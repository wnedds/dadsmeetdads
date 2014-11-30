<?php

include 'top.php';

$userName = '';
$password = '';

$query = "SELECT fldConfirmed FROM tblUsers WHERE fldUserName LIKE '" . $_POST['txtUserNameInput'] . "'";

$numDad = $thisDatabase->select($query);



if (isset($_POST['btnLogin'])) {
    if ($numDad[0]['fldConfirmed'] == 1) {
    
    
    //logic related to checking the login

    $userName = htmlentities($_POST["txtUserNameInput"],ENT_QUOTES,"UTF-8");
    $password = htmlentities($_POST["txtPasswordInput"],ENT_QUOTES,"UTF-8");
    $password = sha1($password);
    
    $query = "SELECT fldUserName, fldPassword, fldFirstName, fldLastName, fldPicName FROM tblUsers WHERE fldUserName LIKE '".$userName."'";
    //print($query);
    $results = $thisDatabase->select($query);

    if ($results[0]['fldPassword'] == $password) {
        $_SESSION['userName'] = $userName;
        $_SESSION['firstName'] = $results[0]['fldFirstName'];
        $_SESSION['lastName'] = $results[0]['fldLastName'];
        $_SESSION['profilePic'] = $results[0]['fldPicName'];
        if ($_SESSION['userName'] == 'kzieba' or $_SESSION['userName'] == 'wnedds') {
            $_SESSION['userAdmin'] = true;
        }
        header('Location:profile.php');
    } else {
        print '<p style="text-align:center;color:red;padding:20px;background-color:white">It looks like you entered the wrong username or password.</p>';
    include 'home.php';
    }

    

} elseif ($numDad[0]['fldConfirmed'] != 1) {
    print '<p style="text-align:center;color:red;padding:20px;background-color:white">Sorry, dad, but you need to confirm your email before you can use Dads Meet Dads.</p>';
    include 'home.php';

}
} elseif (isset($_SESSION['userName']) and $numDad[0]['fldConfirmed'] == 1) {
    header("Location: profile.php");
} else {
    include 'home.php';
}

if (isset($_SESSION['userName'])) {
    header("Location: profile.php");
}


include 'footer.php';
?>