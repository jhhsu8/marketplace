<?php
    // start and check for valid session
    require_once("./includes/redirecthome.inc.php");

    // database connection
    require_once "./includes/connectvars.inc.php";

    // signed in user's username
    $username = $_SESSION['username'];

    // get information from signed in user
    $query = "SELECT * FROM Users WHERE User_Username = '$username'"; 
    $user_data = mysqli_query($dbc, $query)
        or die ("Error querying database - $query");
    
    // get user name and id
    $row = mysqli_fetch_array($user_data);
    $name = $row['User_First_Name'];

    // destroy session, logout, redirect to login page
    $_SESSION = array();

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600);
    }

    session_destroy();
    header('Refresh: 3; home.php');

    // HTML document
    require_once("./includes/htmlhead.inc.php");
?>

    <body>
        <div id="outercircle"><div id="innercircle"></div></div>
        <div id="container">
            
<?php require_once("./includes/header.inc.php"); ?>
            
<?php require_once("./includes/navigation.inc.php"); ?>
            
            <div id="content">
                <h2>You have now logged out</h2>
                <div>
                    <p>Thank you for visiting, <?= $name ?>!</p>
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