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
    <p style="text-align:center;padding:30px;font-size:2em"><a href="index.php" style='padding:10px;text-decoration:none;background:linear-gradient(#94E9F7, #66DFF4);color:blue;border-radius:4px;'>Go back home</a></p>
</article>

<?php
include 'footer.php';
?>