<?php
    // start and check for valid session
    require_once("./includes/redirectindex.inc.php");
    // database connection
    require_once "./includes/connectvars.inc.php";
    
    // declare variables
    $display_form = true;
    $error_msg = '';

    $firstname = '';
    $valid_firstname = false;
    $firstname_regex = '/^[a-zA-Z]{2,20}$/';

    $lastname = '';
    $valid_lastname = false;
    $lastname_regex = '/^[a-zA-Z]{2,20}$/';

    $username = '';
    $valid_username = false;
    $username_regex = '/^[a-zA-Z][a-zA-Z0-9_\-]{2,23}[a-zA-Z0-9]$/';
    
    $password = '';
    $valid_password = false;
    $password_regex = '/(?=.*\d)(?=.*[a-zA-Z]).{5,}/';
    
    // check if submit button has submitted form data
    if (isset($_POST['registersubmit'])) {
        
        // get submitted form data
        $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
        $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
        $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
        
        // validate first name
        if (preg_match($firstname_regex, $firstname)) {
            $valid_firstname = true;
        } else {
            $error_msg .= 'First name is not acceptable<br>';
        }
        
        // validate last name
        if (preg_match($lastname_regex, $lastname)) {
            $valid_lastname = true;
        } else {
            $error_msg .= 'Last name is not acceptable<br>';
        }
        
        // validate username
        if (preg_match($username_regex, $username)) {
            $valid_username = true;
        } else {
            $error_msg .= 'Username is not acceptable<br>';
        }
        
        // validate password
        if (preg_match($password_regex, $password)) {
            $valid_password = true;
        } else {
            $error_msg .= 'Password is not acceptable';
        }

        if (!$valid_firstname || !$valid_lastname || !$valid_username || !$valid_password) {
            // one or more inputs are invalid
            $display_form = true;
         
        } else {
            
            $display_form = false;
            
            // look up username in database
            $query = "SELECT * FROM Users WHERE User_Username = '$username'";
            
            $data = mysqli_query($dbc, $query) 
                or die("Error querying database - $query");
            
            if (mysqli_num_rows($data) == 0) { // username is avaliable for registration
                
                $display_form = false;
                
                // insert user information into database
                $query = "INSERT INTO Users (User_First_Name, User_Last_Name, User_Username, User_Password) VALUES ('$firstname', '$lastname', '$username', md5('$password'))";
                mysqli_query($dbc, $query)
                    or die("Error querying database - $query");
                
                // insert user id into pantry
                $userid = mysqli_insert_id($dbc);
                
                $query = "INSERT INTO Pantry (User_Number) VALUES ('$userid')";
                mysqli_query($dbc, $query)
                    or die("Error querying database - $query");
                
                // redirect to login page
                header('Location: login.php');
            
            } else { // username already taken
                
                $display_form = true;
                $error_msg .= 'Username is already taken';
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
            
<?php require_once("./includes/navigation.inc.php"); ?>
            
                <?php
                    if ($display_form) { // display entry form  
                ?>
            
                <div id="content">
                    <h2>New User Account Registration</h2>

                    <p>Username: From 4 to 25 characters. Letters, numbers, underscore, and hypen are acceptable.<br>First character must be a letter. Last character must be a letter or a number</p>

                    <p>Password: At least 5 characters. It must have at least one letter and one number</p>

                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="registerform">
                        <p class="error"><?= $error_msg ?></p>    
                        <table>
                            <tr>
                                <td><label for="firstname">First Name:</label></td>
                                <td><input type="text" name="firstname" id="firstname" size="20" value="<?= $firstname ?>"><span class="error" id="firstnameprompt"></span></td>
                            </tr>
                            <tr>
                                <td><label for="lastname">Last Name:</label></td>
                                <td><input type="text" name="lastname" id="lastname" size="20" value="<?= $lastname ?>"><span class="error" id="lastnameprompt"></span></td>
                            </tr>
                            <tr>
                                <td><label for="username">Username:</label></td>
                                <td><input type="text" name="username" id="username" size="20" value="<?= $username ?>"><span class="error" id="usernameprompt"></span></td>
                            </tr>
                            <tr>
                                <td><label for="password">Password:</label></td>
                                <td><input type="password" name="password" id="password" size="20" value="<?= $password ?>"><span class="error" id="passwordprompt"></span></td>
                            </tr>
                        </table>
                        <p><input type="submit" name="registersubmit" id="registersubmit" value="Submit"></p>
                    </form>

                    <?php
                        }   
                    ?>
                    
            </div>
<?php require_once("./includes/footer.inc.php"); ?>
            
        </div>
    </body>
</html>

<?php
    // close database connection
    mysqli_close($dbc);
?>