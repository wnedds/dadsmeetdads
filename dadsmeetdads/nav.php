<nav>

    <ol>

        <?php
        
        
            if (!isset($_SESSION['userName'])) {
            
            
                print '<form method="post" action="' . $phpSelf . '">';
                print '<li class="login"><input type="submit" id="btnLogIn" name="btnLogin" value="Log In" 
           tabindex="991" class="next"></li>';
                print '<li><input type="password" id="txtPasswordInput" name="txtPasswordInput"
                                   value=""
                                   tabindex="131" maxlength="50" placeholder="Enter your password"
                                   onfocus="this.select()"></li>';
                print '<li class="login"><input type="text" id="txtUserNameInput" name="txtUserNameInput"
                                   value=""
                                   tabindex="130" maxlength="45" placeholder="Enter your username"
                                   onfocus="this.select()"></li>';
                print '</form>';
            } else {
                if (basename($_SERVER['PHP_SELF']) == "logout.php") {
                    print '     <li class="activepage">Log Out</li>';
                } else {
                    print '     <li class="selection"><a href="logout.php">Log Out</a></li>
            ';
                }
                if (basename($_SERVER['PHP_SELF']) == "invite.php") {
                    print '     <li class="activepage">Invite</li>';
                } else {
                    print '     <li class="selection"><a href="invite.php">Invite</a></li>
            ';
                }

                if (basename($_SERVER['PHP_SELF']) == "profile.php") {
                    print '     <li class="activepage">Profile</li>';
                } else {
                    print '     <li class="selection"><a href="profile.php">Profile</a></li>
            ';
                }
            }
            ?>
        </ol>

        <?php include "header.php"; ?>
</nav>

