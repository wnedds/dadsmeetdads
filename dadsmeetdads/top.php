<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
    <head>
        <title>Dads Meet Dads</title>
        <meta charset="utf-8">
        <meta name="author" content="Will Nedds">
        <meta name="description" content="The hippest social network on the internet network!">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->
        <script src='jquery.js'></script>
        <link rel="stylesheet" href="style.css" type="text/css" media="screen">
        <link rel="icon" href="dadsicon.ico">
        <?php
        include 'ascii.php';
        $debug = false;
ini_set('display_errors',1);  error_reporting(E_ALL);
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// PATH SETUP
//
//  $domain = "https://www.uvm.edu" or http://www.uvm.edu;

        $domain = "http://";
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS']) {
                $domain = "https://";
            }
        }

        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");

        $domain .= $server;

        $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

        $path_parts = pathinfo($phpSelf);

        if ($debug) {
            print "<p>Domain" . $domain;
            print "<p>php Self" . $phpSelf;
            print "<p>Path Parts<pre>";
            print_r($path_parts);
            print "</pre>";
        }

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// inlcude all libraries
//

        require_once('lib/security.php');
        include "lib/validation-functions.php";
        include "lib/mail-message.php";
        
        
        if (!isset($_SESSION['userName']) and ($path_parts['filename'] != 'form') and ($path_parts['filename'] != 'index')  and ($path_parts['filename'] != 'about')  and ($path_parts['filename'] != 'license') and ($path_parts['filename'] != 'confirmation')) {
            header('Location: index.php');
        }
        ?>	

    </head>
    <!-- ################ body section ######################### -->
    <body id="<?php print $path_parts['filename']?>" >
    <?php
    include "nav.php";
    // include libraries
require_once('../bin/myDatabase.php');

// set up variables for database
$dbUserName = get_current_user() . '_admin';

$whichPass = "a"; //flag for which one to use.

$dbName = strtoupper(get_current_user()) . '_DADabase';

$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);

if ($_SESSION['userName'] == 'kzieba') {
    $_SESSION['userAdmin'] = true;
}
?>