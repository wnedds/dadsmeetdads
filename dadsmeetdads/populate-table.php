<?php

/* This page grabs data from the UVM web site and redisplays it here.
 *
 * this page displays a list of courses from the registrars web site with the
 * status highlighted, going to be canceled, full or ok. the web interface is
 * lousy but i made the example short so it gives you something to play with :)
 *
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Last updated on: August 21, 2014
 *
 *
 * creates tables each time (dropping table if it exists)
 * Generates about nine warnings (which I have not traced):
 * Warning: PDOStatement::execute(): SQLSTATE[HY093]: Invalid parameter number: 
 * number of bound variables does not match number of tokens in myDatabase.php 
 * on line 139 
 */
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
//
//-----------------------------------------------------------------------------
//
// Initialize variables
//
// SQL to create tables, drop if they exist
// any other error checking may be good, parsing html entities etc
//choose which semester data you want to scrape
$url = "http://giraffe.uvm.edu/~rgweb/batch/curr_enroll_fall.csv";
//$url="http://giraffe.uvm.edu/~rgweb/batch/curr_enroll_spring.csv";
//$url="http://giraffe.uvm.edu/~rgweb/batch/curr_enroll_summer.csv";

$outputBuffer[] = "";

$debug = false;
if (isset($_GET["debug"])) {
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

// include libraries
require_once('myDatabase.php');

// set up variables for database
$dbUserName = get_current_user() . '_admin';

$whichPass = "a"; //flag for which one to use.

$dbName = strtoupper(get_current_user()) . '_DADabase';

$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);


// intialize variables
$pmkCourseId = 0;
$subj = "";
$num = 0;
$title = "";

// put records into database tables
foreach ($records as $oneClass) {
// course table
    $query = "INSERT INTO tblCourses(fldCourseNumber, fldCourseName, fldDepartment, fldCredits) ";
    $query .= "VALUES (?, ?, ?, ?)";
    $data = array($oneClass[1], $oneClass[2], $oneClass[0], $oneClass[12]);
    if ($debug) {
        print "<p>sql " . $query . "</p><p><pre> ";
        print_r($data);
        print "</pre></p>";
    }
    $style = "background-color: lightblue;";
    if (!($subj == $oneClass[0] and
            $num == $oneClass[1] and
            $title == $oneClass[2])) {
        $results = $thisDatabase->insert($query, $data);
        $pmkCourseId = $thisDatabase->lastInsert();
        if ($results) {
            $style = "background-color: lightgreen;";
        } else {
            $style = "background-color: lightred;";
        }
    }
    $outputBuffer[] = "\t<tr></th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[1] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[2] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[0] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[12] . "</th>";

//avoid duplicates
    $subj = $oneClass[0];
    $num = $oneClass[1];
    $title = $oneClass[2];

// teacher table:
    $query = "INSERT IGNORE INTO tblTeachers(fldLastName, fldFirstName, pmkNetId) ";
    $query .= "VALUES (?, ?, ?)";
    $data = explode(', ', $oneClass[15]);

    $data[] = $oneClass[16];
    if ($debug) {
        print "<p>sql " . $query . "</p><p><pre> ";
        print_r($data);
        print "</pre></p>";
    }
    $results = $thisDatabase->insert($query, $data);
    if ($results) {
        $style = "background-color: green;";
    } else {
        $style = "background-color: red;";
    }
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[16] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $data[1] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $data[0] . "</th>";

// section table
    $query = "INSERT INTO tblSections(fnkCourseId, fldCRN, fnkTeacherId, fldMaxStudents, fldNumStudents, fldSection, fldType, fldStart, fldStop, fldDays, fldBuilding, fldRoom) ";
    
    $query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $data = array($pmkCourseId, $oneClass[3], $oneClass[16], $oneClass[7], $oneClass[8], $oneClass[4], $oneClass[5], $oneClass[9], $oneClass[10], $oneClass[11], $oneClass[13], $oneClass[14]);
    if ($debug) {
        print "<p>sql " . $query . "</p><p><pre> ";
        print_r($data);
        print "</pre></p>";
    }
    $results = $thisDatabase->insert($query, $data);
    if ($results) {
        $style = "background-color: green;";
    } else {
        $style = "background-color: red;";
    }
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $pmkCourseId . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[3] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[16] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[7] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[8] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[4] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[5] . "</th>";
        $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[9] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[10] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[11] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[13] . "</th>";
    $outputBuffer[] = "\t\t<th style='" . $style . "'>" . $oneClass[14] . "</th>";
    $outputBuffer[] = "\n\n\t</tr>";
} // ends looping through all records

$outputBuffer[] = "</table>";
$outputBuffer = join("\n", $outputBuffer);
echo $outputBuffer;
?>
