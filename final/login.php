<?php
    // start and check for valid session
    require_once("./includes/redirectindex.inc.php");
    // database connection
    require_once "./includes/connectvars.inc.php";
    // custom functions
    require_once("./includes/functions.inc.php");

    // declare variables
    $display_form = true;
    $error_msg = '';
    $username = '';
    $password = '';

    // check if submit button has submitted form data
    if (isset($_POST['submit'])) {
        
        // get submitted form data
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
        
        if (strlen($username) == 0 || strlen($password) == 0) { // username or password field is blank

            $error_msg = '<p class="error">Enter both password and username</p>';
            $display_form = true;
            
        } else {
            
            if (authenticate($username, $password, $dbc)) {
                
                // valid username and password
                $_SESSION['username'] = $username;
                $_SESSION['authenticate'] = true;
                $display_form = false;
                header('Location: index.php');
        
            } else {
                // username or password is invalid
                $display_form = true;
                $_SESSION['authenticate'] = false;
                $error_msg = 'Invalid username and/or password';
            }
        }
    }

    // HTML document
    require_once("./includes/htmlhead.inc.php");
?>

    <body>
        <div id="outercircle"><div id="innercircle"></div></div>
        <div id="container">
            
<?php require_once("./includes/header.inc.php"); ?>
            
<?php require_once("./includes/loginnav.inc.php"); ?>
          
        <?php if ($display_form) { // display entry form  ?>
            
                <div id="content">
                    <div id="loginblock">
                        <h2 id="userlogin">Login</h2>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="loginform">

                            <p class="error"><?= $error_msg ?></p>

                            <table>
                                <tr>
                                    <td><label for="username">Username:</label></td>
                                    <td><input type="text" name="username" id="username" size="18" value="<?= $username ?>"></td>
                                </tr>
                                <tr>
                                    <td><label for="password">Password:</label></td>
                                    <td><input type="password" name="password" id="password" size="18" value="<?= $password ?>"></td>
                                </tr>
                            </table>
                            <p><input type="submit" name="submit" id="submit" value="Submit"></p>
                        </form>

                <?php  } ?>
                        <hr>
                        <p><a href="register.php">New User Account Registration</a></p>
                        
                    </div>
                    <div id="introblock">
                        <p>Customers: You may procure food items from various vendors in the marketplace to add to your pantry and keep track of the food item quantities available in the marketplace. You may also consume the foods by decreasing the quantities in the pantry.</p>
                    </div>
                    <div id="slideshowblock">
                        <div class="slide">
                            <div>1 / 4</div>
                            <img class="vendorimage" src="./images/safeway.jpg" alt="Safeway">
                        </div>

                        <div class="slide">
                            <div>2 / 4</div>
                            <img class="vendorimage" src="./images/costco.jpg" alt="Costco">
                        </div>

                        <div class="slide">
                            <div>3 / 4</div>
                            <img class="vendorimage" src="./images/ranch99.jpg" alt="Ranch99">
                        </div>
                        <div class="slide">
                            <div>4 / 4</div>
                            <img class="vendorimage" src="./images/albertsons.jpg" alt="Albertsons">
                        </div>
                    </div>
                </div>
            
<?php require_once("./includes/footer.inc.php"); ?>
            
        </div>
    </body>
</html>

<?php
    // close database connection
    mysqli_close($dbc);
?>