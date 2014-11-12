<?php
include 'top.php';

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

?>
<article>
    <p style='text-align: center'>You have been successfully logged out. Keep in touch, dad!</p>
    <br>
    <p style="text-align:center;padding:30px;font-size:2em"><a href="index.php" style='padding:10px;text-decoration:none;background-color:#66DFF4;color:black;border: 1px solid #709fcf;'>Go back home</a></p>
</article>

<?php
include 'footer.php';
?>