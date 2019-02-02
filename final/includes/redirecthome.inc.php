<?php
    session_start();
    //check to see if session variables are set for valid user session
    if (!isset($_SESSION['username']) && !$_SESSION['authenticate']) {
        header('Location: home.php');
    }  
?>