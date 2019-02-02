            <div id="nav-wrapper">
                <div id="navigation">
                
                    <?php
                        if (isset($_SESSION['username']) && $_SESSION['authenticate']) {
                            // user is logged in
                    ?>

                    <p class="nav"><a href="home.php" target="_self">Home</a></p>
                    <p class="nav"><a href="index.php" target="_self">Marketplace</a></p>
                    <p class="nav"><a href="pantry.php" target="_self">Your Pantry</a></p>
                    <p id="logout"><a href="logout.php" target="_self">Log Out</a></p>
                    <p class="contact"><a href="contact.php" target="_self">Contact Us</a></p>
                    <p class="forvendor"><a href="vendor.php" target="_self">For Vendors</a></p>
                    
                    <?php
                        } else { // user is not logged in
                    ?>
                    <p class="nav"><a href="home.php" target="_self">Home</a></p>
                    <p id="login"><a href="login.php" target="_self">Log In</a></p>
                    <p class="contact"><a href="contact.php" target="_self">Contact Us</a></p>
                    <p class="forvendor"><a href="vendor.php" target="_self">For Vendors</a></p>
                    
                    <?php
                        } 
                    ?>
                </div>
            </div>