<?php
    session_start();
    // custom functions
    require_once("./includes/functions.inc.php");

    // declare variables
    $display_form = true;
    $error_msg = '';

    $firstname = '';
    $firstname_error_msg = '';
    $valid_firstname = false;
    $firstname_regex = '/^[a-zA-Z]{2,20}$/';
    
    $lastname = '';
    $lastname_error_msg = '';
    $valid_lastname = false;
    $lastname_regex = '/^[a-zA-Z]{2,20}$/';
    
    $email = '';
    $email_error_msg = '';
    $valid_email = false;
    $email_regex = '/^[a-zA-Z0-9\_\.\-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/';

    $subject = '';
    $subject_error_msg = '';
    $valid_subject = false;
    $subject_regex = '/^[a-zA-Z\s]{2,30}$/';

    $valid_characters = false;
    $characters_error_msg = '';

    $to = '';
    $from = '';
    $message = '';
    $pst_date_time = '';
    $sender_msg = '';
 
    // check if submit button has submitted form data
    if (isset($_POST['contactsubmit'])) {
        
        // get submitted form data
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        
        $to = 'jhhsu11@gmail.com'; // recipient email
        $from = "From: $firstname $lastname <$email>"; // sender full name and email
        $pst_date_time = pst_datetime(); // PST date and time
        $sender_msg = "On $pst_date_time PST, $firstname $lastname sent this message:\r\n$message"; // sender's message
        
        // validate first name
        if (preg_match($firstname_regex, $firstname)) {
            $valid_firstname = true;
        } else {
            $error_msg .= 'First name must be from 2 to 20 alphabets<br>';
        }
        
        // validate last name
        if (preg_match($lastname_regex, $lastname)) {
            $valid_lastname = true;
        } else {
            $error_msg .= 'Last name must be from 2 to 20 alphabets<br>';
        }
        
        // validate email
        if (preg_match($email_regex, $email)) {
            $valid_email = true;
        } else {
            $error_msg .= 'Email address is not acceptable<br>';
        }
        
        // validate subject
        if (preg_match($subject_regex, $subject)) {
            $valid_subject = true;
        } else {
            $error_msg .= 'Subject must be from 2 to 30 characters. Only alphabets and spaces are accepted<br>';
        }
        
        // validate number of input characters
        if (characters($message)) {
            $valid_characters = true;
        } else {
            $error_msg .= 'Character count in the comment box must be from 1 to 500';
        }
         
        if (!$valid_firstname || !$valid_lastname || !$valid_email || !$valid_subject || !$valid_characters) {
            // one or more inputs are invalid
            $display_form = true;
        
        } else {
            // all inputs are valid
            $display_form = false;
            
            mail($to, $subject, $sender_msg, $from); // send an email
        }
    }

    require_once("./includes/htmlhead.inc.php");

?>

    <body>
        <div id="outercircle"><div id="innercircle"></div></div>
        <div id="container">
            
<?php require_once("./includes/header.inc.php"); ?>
            
<?php require_once("./includes/navigation.inc.php"); ?>

            <div id="content">
                            
            <?php
                if ($display_form) { // display form
            ?>
                <h2>Send Your Comments or Questions</h2>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="userform">
                    <p class="error"><?= $error_msg ?></p>
                    <table>
                        <tr>
                            <td><label for="firstname">Your First Name:</label></td>
                            <td><input type="text" name="firstname" id="firstname" size="20" value="<?= $firstname ?>"><span class="error" id="firstnameprompt"></span></td>
                        </tr>
                        <tr>
                            <td><label for="lastname">Your Last Name:</label></td>
                            <td><input type="text" name="lastname" id="lastname" size="20" value="<?= $lastname ?>"><span class="error" id="lastnameprompt"></span></td>
                        </tr>
                        <tr>
                            <td><label for="email">Your Email:</label></td>
                            <td><input type="text" name="email" id="email" size="20" value="<?= $email ?>"><span class="error" id="emailprompt"></span></td>
                        </tr>
                        <tr>
                            <td><label for="subject">Subject:</label></td>
                            <td><input type="text" name="subject" id="subject" size="30" value="<?= $subject ?>"><span class="error" id="subjectprompt"></span></td>
                        </tr>
                        <tr>
                            <td><label for="message">Comments or<br>Questions:</label></td>
                            <td><textarea name="message" id="message" cols="50" rows="10"><?= $message ?></textarea><span class="error" id="messageprompt"></span></td>
                        </tr>
                    </table>
                    <p><input type="submit" name="contactsubmit" id="contactsubmit" value="Submit"></p>
                </form>

                <?php
                    } else { // display form processing page
                ?>

                <h2>Your message has been successfully submitted</h2>
                <p>Subject: <?= $subject ?></p>
                <p><?= $sender_msg ?></p>

                <?php
                    }
                ?>
                
            </div>
            
<?php require_once("./includes/footer.inc.php"); ?>
            
        </div>
    </body>
</html>