        <h3 class='Profile'>Bio:</h3>
        <p id="bio"><?php
            if ($bio != '') {
                print $bio;
            } else {
                print "This dad hasn't added a bio yet.";
            }
            ?></p> 
        <h3 class='Profile'>Interests:</h3>
        <p><?php
            print $firstName . " enjoys:";
            print "<ul>";
            if ($hiking === "1") {
                print "<li>going hiking through the rugged wilderness</li>";
            }
            if ($sailing === "1") {
                print "<li>going sailing on the open ocean</li>";
            }
            if ($grilling === "1") {
                print "<li>grilling with the neighborhood dads</li>";
            }
            if ($reading === "1") {
                print "<li>reading some nice books on woodworking and classic cars</li>";
            }
            if ($television === "1") {
                print "<li>watching TV shows like sports or NCIS</li>";
            }
            if ($chillaxing === "1") {
                print "<li>chillaxing with his fellow dads</li>";
            }
            if ($skiing === "1") {
                print "<li>going skiing in a onesie snowsuit</li>";
            }
            if ($pigskin === "1") {
                print "<li>throwing around the ol' pigskin</li>";
            }
            print "<li>other cool dad things, too</li>";
            print "</ul>";
            print "<br>";
            ?></p>
        <h3 class='Profile'>About:</h3>
        <p><?php
            print $firstName . " is a " . $gender . ' dad who considers himself to be ' . $adjective . '.';
            ?></p>

        <h3 class='Profile'><?php print $firstName ?>'s Pals:</h3>
        <ol class="friends" style="width: 100%;height: 120px;padding:10px">


            <?php
            //print_r($friends);
            //print count($friends);
            if (empty($friends)) {
                print 'This dad has not met any other dads!';
            } else {
                $friendIndex = 0;
                //print $friends[0]["fnkTargetDad"];
                while ($friendIndex <= 4 and $friendIndex <= ( count($friends) - 1)) {
                    //print $friends[$friendIndex]["fnkTargetDad"];
                    print '<li style="float:left;height:130px;"><a href="../dadsmeetdads/profile.php?user=' . $friends[$friendIndex]["fnkTargetDad"] . '"><div><img src="images/' . $friends[$friendIndex]["fldTargetPic"] . '" alt="" height="90" width="70"></div><p>' . $friends[$friendIndex]['fldTargetFirst'] . " " . $friends[$friendIndex] ["fldTargetLast"] . "</p><br></a></li>";

                    $friendIndex++;
                }
            }
            ?>


        </ol>
        <br>
        <h3 class='Profile'>Contact:</h3>
        <p>Location: <?php print " " . $city;?></p>
        <br>
        <br>
        <p>Email: <?php print " " . $email;?></p>
