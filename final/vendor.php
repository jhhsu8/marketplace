<?php
    session_start();
    // database connection
    require_once "./includes/connectvars.inc.php";
    
    // declare variables
    $display_info = false;
    
    $name_added = false;
    $item_added = false;
    $quantity_added = false;
    $vendor_exist = false;
    $item_name_exist = true;
    $item_id_exist = false;
    $correct_image_type = false;
    $file_upload_size = false;

    $user_file = '';
    $file_type = '';
    $file_size = '';
    $file_tmp_name = '';
    $upload_error = '';
    
    $categoryid = '';
    $valid_categoryid = false;

    $vendorname = '';
    $valid_vendorname = false;
    $vendorname_regex = '/^[a-zA-Z0-9\s]{2,30}$/';

    $vendorid = '';
    $valid_vendorid = false;
    $vendorid_regex = '/^[0-9]{1,10}$/';

    $itemname = '';
    $valid_itemname = false;
    $itemname_regex = '/^[a-zA-Z\s]{2,30}$/';
    
    $item_id = '';
    $valid_itemid = false;
    $itemid_regex = '/^[0-9]{1,10}$/';
    
    $itemquantity = 0;
    $item_quantity = '';
    $valid_item_quantity = false;
    $item_quantity_regex = '/^[0-9]{1,5}$/';

    $name_error_msg = '';
    $additem_error_msg = '';
    $addquantity_error_msg = '';

    $vendorinfo = '';
    $iteminfo = '';
    $quantityinfo = '';

    $total_quantity = '';
    
    //allowed mime types for image upload
    $mime_types = array("image/png", "image/jpg", "image/jpeg", "image/pjpeg", "image/gif");

        // check if submit button has submitted form data
    if (isset($_POST['addname'])) {
        
        // get submitted form data
        $vendorname = mysqli_real_escape_string($dbc, trim($_POST['vendorname']));
        
        // validate vendor's name
        if (preg_match($vendorname_regex, $vendorname)) {
            $valid_vendorname = true;
        } else {
            $name_error_msg .= 'Vendor name is not acceptable<br>';
        }
        
        if (!$valid_vendorname) { // vendor's name is invalid
            
            $display_info = false;
            
        } else {// vendor's name is valid
            
            // look up vendor name in database
            $query = "SELECT * FROM Vendors WHERE Vendor_Name = '$vendorname'";
            
            $data = mysqli_query($dbc, $query) 
                or die("Error querying database - $query");
            
            if (mysqli_num_rows($data) == 0) { // vendor's name does not exist
                
                $display_info = true;
                $name_added = true;
                
                // insert vendor's name into database
                $query = "INSERT INTO Vendors (Vendor_Name) VALUES ('$vendorname')";
            
                mysqli_query($dbc, $query)
                    or die("Error querying database - $query");
            
            } else { // vendor's name already exists
                
                $name_error_msg .= 'Vendor name already exists';
            }
        }
    }
    
    // check if submit button has submitted form data
    if (isset($_POST['additem'])) {
        
        // get submitted form data
        $vendorid = mysqli_real_escape_string($dbc, trim($_POST['vendorid']));
        $categoryid = mysqli_real_escape_string($dbc, trim($_POST['categoryid']));
        $itemname = mysqli_real_escape_string($dbc, trim($_POST['itemname']));
        $file_type = $_FILES['itemimage']['type'];
        $file_size = $_FILES['itemimage']['size'];
        $file_tmp_name = $_FILES['itemimage']['tmp_name'];
        $upload_error = $_FILES['itemimage']['error'];
        $user_file = $_FILES['itemimage']['name'];
        
        if ($categoryid != '') {
            $valid_categoryid = true;
        } else {
            $additem_error_msg .= 'Please select a category<br>';        
        }
        
        // validate vendor id
        if (preg_match($vendorid_regex, $vendorid)) {
            $valid_vendorid = true;
        } else {
            $additem_error_msg .= 'Vendor ID is 1 to 10 digits only<br>';
        }
        
        // validate item name
        if (preg_match($itemname_regex, $itemname)) {
            $valid_itemname = true;
        } else {
            $additem_error_msg .= 'Item name is not acceptable<br>';
        }
        
        // look up vendor id in database
        $query = "SELECT * FROM Vendors WHERE Vendor_Number = '$vendorid'";
        $data = mysqli_query($dbc, $query) 
            or die("Error querying database - $query");

        // check if vendor exists in database
        if (mysqli_num_rows($data) == 0) {
            
            $additem_error_msg .= 'Vendor ID does not exist in database<br>';
          
        } else {
            $vendor_exist = true;
        }
            
        // look up vendor's item in database
        $query = "SELECT * FROM Foods WHERE Food_Name = '$itemname' AND Vendor_Number = '$vendorid'";
            
        $data = mysqli_query($dbc, $query) 
            or die("Error querying database - $query");

        // check if vendor's item exists in database
        if (mysqli_num_rows($data) == 1 ) {
            
            $additem_error_msg .= 'You have already added this food item<br>';
          
        } else {
            $item_name_exist = false;
        }
        
        //check if file size is valid
        if ($file_size == 0 || $file_size > MAX_FILE_SIZE) {
            
            $file_upload_size = false;
            $additem_error_msg .= "Invalid file size - maximum size is ".(MAX_FILE_SIZE / 1024)."kb<br>";
            
        } else { 
            
            $file_upload_size = true;
        }
        
        //check if file type is valid
        if (!in_array($file_type, $mime_types)) {
        
            $correct_image_type = false;
            $additem_error_msg .= "Invalid image format - it must be .png, .jpg, .jpeg, or .gif<br>";
    
        } else {
            
            $correct_image_type = true;
        }

        //check if file upload information is valid
        if ($upload_error != 0 || !$file_upload_size || !$correct_image_type || !$valid_categoryid || !$valid_vendorid || !$valid_itemname || !$vendor_exist || $item_name_exist) {
            
            // one or more inputs are invalid
            $display_info = false;
            
        } else {
        
            $display_info = true;
            $item_added = true;
            
            // add number of seconds to file name
            $pathinfo = pathinfo($user_file);
            $filename = $pathinfo['filename'];
            $ext = $pathinfo['extension'];
            $user_file = $filename.time().".".$ext; 

            $target_file = SITE_ROOT_PATH.USER_UPLOAD_DIR.$user_file;
            
            //move file to the uploads folder
            move_uploaded_file($file_tmp_name, $target_file)
                or die("File move failed");
           
            // insert item information into database
            $query = "INSERT INTO Foods (Vendor_Number, Category_Number, Food_Name, Food_Quantity, Food_Image, Date_Updated) VALUES ('$vendorid','$categoryid','$itemname', '$itemquantity','$user_file', NOW())";
            
            mysqli_query($dbc, $query)
                or die("Error querying database - $query");
        }
    }

    // check if submit button has submitted form data
    if (isset($_POST['addquantity'])) {
        
        // get submitted form data
        $item_id = mysqli_real_escape_string($dbc, trim($_POST['item_id']));
        $item_quantity = mysqli_real_escape_string($dbc, trim($_POST['item_quantity']));
         
        // validate item id
        if (preg_match($itemid_regex, $item_id)) {
            $valid_itemid = true;
        } else {
            $addquantity_error_msg .= 'Item ID is 1 to 10 digits only<br>';
        }
        
        // validate item quantity
        if (preg_match($item_quantity_regex, $item_quantity)) {
            $valid_item_quantity = true;
        } else {
            $addquantity_error_msg .= 'Item quantity is 1 to 5 digits only<br>';
        } 

        // look up item id in database
        $query = "SELECT * FROM Foods WHERE Food_Number = '$item_id'";
        $data = mysqli_query($dbc, $query) 
            or die("Error querying database - $query");
        
        $row = mysqli_fetch_array($data);
        $vendor_quantity = $row['Food_Quantity'];
        
               // check if vendor exists in database
        if (mysqli_num_rows($data) == 0) {
            
            $addquantity_error_msg .= 'Item ID does not exist in database<br>';
          
        } else {
            $item_id_exist = true;
        }
        
        if (!$valid_itemid || !$valid_item_quantity || !$item_id_exist) {
            
            // one or more inputs are invalid
            $display_info = false;
            
        } else {
            // calculate total item quantity
            $total_quantity = (int)$vendor_quantity + (int)$item_quantity;
 
                
            $display_info = true;
            $quantity_added = true;

            // update item quantity and date last updated
            $query = "UPDATE Foods SET Food_Quantity = '$total_quantity', Date_Updated = NOW() WHERE Food_Number = '$item_id'";

            mysqli_query($dbc, $query)
                or die("Error querying database - $query");
        }
    }
 
    if ($display_info) { // display information

        // get information from vendor
        $select_query = "SELECT * FROM Vendors WHERE Vendor_Name = '$vendorname'";
        $data = mysqli_query($dbc, $select_query) 
            or die("Error querying database - $select_query");

        // get information from vendor's food item
        $query = "SELECT * FROM Foods WHERE Food_Name = '$itemname' AND Vendor_Number = '$vendorid'";
        $result = mysqli_query($dbc, $query) 
            or die("Error querying database - $query");

        if ($name_added) { // vendor name is added

            $row = mysqli_fetch_array($data);
            $number = $row['Vendor_Number'];
            $vendorinfo =  "Your Vendor ID is $number.";


        } else if ($item_added) { // food item is added by the vendor              

            $row = mysqli_fetch_array($result);
            $itemid = $row['Food_Number'];
            $iteminfo = "The Item ID is $itemid.";


        } else { // food item quantity is added

            $quantityinfo = "The quantity for the Item ID $item_id is $total_quantity.";
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
            
            <div id="content">
                <p class="instruction">Vendors: Please set up your Vendor and Food Item IDs for stocking your food items in Marketplace.</p>
                <div id="block">
                    <div id="vendorform">
                        <h2>Vendor ID Setup</h2>
                        <p>Do not attempt to create more than one Vendor ID for the same vendor.</p>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="vendoridform">
                            <p class="error"><?= $name_error_msg ?></p>  
                            <table>
                                <tr>
                                    <td><label for="vendorname">Vendor Name:</label></td>
                                    <td><input type="text" name="vendorname" id="vendorname" size="20" value="<?= $vendorname ?>"><span class="error" id="vendornameprompt"></span></td>
                                </tr>
                            </table>
                            <p><input type="submit" name="addname" id="addname" value="Create Your Vendor ID"></p>
                        </form>
                        <p class="instruction">Vendor ID: <?= $vendorinfo ?></p>
                        
                    </div>
                    <div id="itemform">
                        <h2>New Food Item ID Setup</h2>
                        <p>Do not attempt to create more than one Item ID for the same food item.</p>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="itemidform">
                            <p class="error"><?= $additem_error_msg ?></p>
                            <table>
                                <tr>
                                    <td><label for="categoryid">Category:</label></td>
                                    <td>
                                        <select name="categoryid" id="categoryid">
                                            <option value="">Select a Category</option>
                                            <?php
                                                $query_category = "SELECT * FROM Categories ORDER BY Category_Name ASC";
                                                // category selection options
                                                $category = mysqli_query($dbc, $query_category)
                                                        or die ("Error querying database - $query_category");
                                                
                                                // get category ID and name
                                                while ($row = mysqli_fetch_array($category)) {
                                                    $cat_id = $row['Category_Number'];
                                                    $cat_name = $row['Category_Name'];
                                                    if ($categoryid == $cat_id) {
                                                        $selected = "selected";
                                                    } else {
                                                        $selected = "";
                                                    }
                                            ?>

                                            <option value="<?= $cat_id ?>" <?= $selected ?>><?= $cat_name ?></option>

                                            <?php
                                                }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="vendorid">Vendor ID:</label></td>
                                    <td><input type="text" name="vendorid" id="vendorid" size="20" value="<?= $vendorid ?>"><span class="error" id="vendoridprompt"></span></td>
                                </tr>
                                <tr>
                                    <td><label for="itemname">Item Name:</label></td>
                                    <td><input type="text" name="itemname" id="itemname" size="20" value="<?= $itemname ?>"><span class="error" id="itemnameprompt"></span></td>
                                </tr>
                                <tr>
                                    <td><label for="itemimage">Item Image:</label></td>
                                    <td><input type="file" name="itemimage" id="itemimage" size="20"></td>
                                </tr>
                            </table>
                            <p><input type="submit" name="additem" id="additem" value="Create Food Item ID"></p>
                        </form>
                        <p class="instruction">Food Item ID: <?= $iteminfo ?></p>
                        
                    </div>
                    <div id="addform">
                        <h2>Stocking Inventory in Marketplace</h2>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="addquantityform">
                            <p class="error"><?= $addquantity_error_msg ?></p>    
                            <table>
                                <tr>
                                    <td><label for="item_id">Item ID:</label></td>
                                    <td><input type="text" name="item_id" id="item_id" size="20" value="<?= $item_id ?>"><span class="error" id="item_idprompt"></span></td>
                                </tr>
                                <tr>
                                    <td><label for="item_quantity">Quantity to Add:</label></td>
                                    <td><input type="text" name="item_quantity" id="item_quantity" size="20" value="<?= $item_quantity ?>"><span class="error" id="item_quantityprompt"></span></td>
                                </tr>
                            </table>
                            <p><input type="submit" name="addquantity" id="addquantity" value="Add Quantity"></p>
                            <p class="instruction">Inventory Count: <?= $quantityinfo ?></p>
                        </form>
                    </div>
                    <p id="vendors">Please <a href="register.php" target="_self">Register</a> or log onto <a href="login.php" target="_self">Marketplace</a> to see the list of food items.</p>
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