<?php
    session_start();
    require_once "./includes/connectvars.inc.php";

    // signed in user's username
    $username = $_SESSION['username'];

    // declare variable
    $itemid = '';

    // check if $_GET variable is set
    if(isset($_GET['delid'])) {

        // get food item id
        $itemid = trim($_GET['delid']);

        // get information from signed in user
        $query = "SELECT * FROM Users WHERE User_Username = '$username'";
        $result = mysqli_query($dbc, $query)
            or die ("Error querying database - $query");

        // get user id
        $row = mysqli_fetch_array($result);
        $userid = $row['User_Number'];

        // get information from user's pantry
        $query = "SELECT * FROM Pantry WHERE User_Number = '$userid'";
        $result = mysqli_query($dbc, $query)
            or die ("Error querying database - $query");

        // get pantry id
        $row = mysqli_fetch_array($result);
        $pantryid = $row['Pantry_Number'];

        // delete food item in user's pantry
        $query = "DELETE FROM Pantry_Foods WHERE Pantry_Number = '$pantryid' AND Food_Number = '$itemid'";
        mysqli_query($dbc, $query)
            or die ("Error querying database - $query");

        header('Location: pantry.php');
    }

        mysqli_close($dbc);
?>

