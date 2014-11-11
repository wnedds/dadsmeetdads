<?php

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 2000000000)
&& in_array($extension, $allowedExts))
  {
    $temp = explode(".",$_FILES["file"]["name"]);
    $newfilename = microtime() . '.' .end($temp);
    if (file_exists("images/" . $_FILES["file"]["name"]))
      {
      echo 'This file already exists and cannot be uploaded.';
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "images/" . $newfilename);
      //print "The file being uploaded is".$newfilename;
      $_SESSION['profilePic'] = $newfilename;
      //print "The session variable is ".$_SESSION['profilePic'];
      }
    }
else
  {
  echo "";
  }

?>