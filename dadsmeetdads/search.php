<?php
include 'top.php';

    if (!isset($_GET['txtDadSearch'])) {
        header('Location: profile.php');
    }   
print '<article>';
    
    $query = "SELECT fldFirstName, fldLastName, fldUserName, fldPicName FROM (SELECT fldFirstName, fldLastName, fldUserName, fldPicName, concat(fldFirstName, fldLastName) as fldName FROM tblUsers) as tblNames WHERE fldName LIKE '%" . $_GET['txtDadSearch'] . "%'";
    //print $query;
$searchResults = $thisDatabase->select($query);
//print_r($searchResults);
print '<h1>Dads with names like "'.$_GET['txtDadSearch'].'":</h1><br>';
if (empty($searchResults)) {
    print "<p style='text-align: center;'>It looks like there are no dads with that name.</p>";
} else {
    foreach ($searchResults as $oneResult) {
    print '<div style="height:70px">';
    print '<a href="../dadsmeetdads/profile.php?user='.$oneResult['fldUserName'].'" style="text-decoration: none"><img src="images/'.$oneResult['fldPicName'].'" alt="" style="height:70px;float:left">';
    print '<h1 style="padding: 20px;margin-left: 50px;">'.$oneResult['fldFirstName'].' ';
    print $oneResult['fldLastName'].'</h1></a>';
    print '</div><br>';
}
}
print '</article>';
include 'footer.php';
