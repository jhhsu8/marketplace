<?php
    function authenticate($uname, $pwd, $dbc) {
        // look up username and password in database
        $select_query = "SELECT * FROM Users WHERE User_Username = '$uname' AND User_Password = md5('$pwd')";
        $data = mysqli_query($dbc, $select_query)
            or die("Error querying database - $select_query");

        if (mysqli_num_rows($data) == 1){

            return true;

        } else {

            return false;
        }
    }
    // function that returns PST date and time
    function pst_datetime() {
        date_default_timezone_set('America/Los_Angeles');
        return date("F jS Y h:i:sa");
    };
    
    // function that checks if input contains from 1 to 500 characters
    function characters($input) {
        
        if (strlen($input) >= 1 && strlen($input) <= 500) {
            return true;
        } else {
            return false;
        }
    }

?>