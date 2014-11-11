        <h3 class='Profile'><?php print $firstName ?>'s Pals:</h3>
        <ol class="friends" style="width: 100%;padding:10px">
<?php
            if (empty($friends)) {
                print 'This dad has not met any other dads!';
            } else {
                $friendIndex = 0;
                //print $friends[0]["fnkTargetDad"];
                while ($friendIndex <= ( count($friends) - 1)) {
                    //print $friends[$friendIndex]["fnkTargetDad"];
                    print '<li style="float:left;height:130px;"><a href="../dadsmeetdads/profile.php?user=' . $friends[$friendIndex]["fnkTargetDad"] . '"><div><img src="images/' . $friends[$friendIndex]["fldTargetPic"] . '" alt="" height="90" width="70"></div><p style="font-size:.9em">' . $friends[$friendIndex]['fldTargetFirst'] . " " . $friends[$friendIndex] ["fldTargetLast"] . "</p><br></a></li>";
                    if ($friendIndex % 5 == 0) {
                        $lenIndex++;
                    }
                    $friendIndex++;
                }
            }
?>
        </ol>
        <div style='height:<?php echo ($lenIndex++ * 137) ?>px'></div>
        <br>
        
