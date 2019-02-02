<?php
    // start and check for valid session
    require_once("./includes/redirecthome.inc.php");
    // database connection
    require_once "./includes/connectvars.inc.php";

    // signed in user's username
    $username = $_SESSION['username'];

    // declare variables
    $itemid = '';
    $error = '';

    $user_quantity = '';
    $valid_quantity = false;
    $quantity_regex = '/^[0-9]{1,3}$/';
    
    // get information from signed in user
    $query = "SELECT * FROM Users WHERE User_Username = '$username'"; 
    $user_data = mysqli_query($dbc, $query)
        or die ("Error querying database - $query");
    
    // get user name and id
    $row = mysqli_fetch_array($user_data);
    $userid = $row['User_Number'];
    $name = $row['User_First_Name']; 
    
    // get information from user's pantry
    $query = "SELECT * FROM Pantry WHERE User_Number = '$userid'";
    $pantry_data = mysqli_query($dbc, $query)
        or die ("Error querying database - $query");

    // get pantry id
    $row = mysqli_fetch_array($pantry_data);
    $pantryid = $row['Pantry_Number'];
    
    // get information from both pantry foods and pantry tables under a pantry number
    $query = "SELECT * FROM Foods
    INNER JOIN Vendors ON Foods.Vendor_Number = Vendors.Vendor_Number
    INNER JOIN Pantry_Foods ON Pantry_Foods.Food_Number = Foods.Food_Number WHERE Pantry_Foods.Pantry_Number = '$pantryid' ORDER BY Pantry_Foods.Date_updated DESC";
 
    $foodsdata = mysqli_query($dbc, $query)
        or die("Error querying database - $query");

    // check if submit button has submitted form data
    if(isset($_POST['minus'])) {

        // get submitted form data
        $user_quantity = mysqli_real_escape_string($dbc, trim($_POST['quantity']));
        $itemid = mysqli_real_escape_string($dbc, trim($_POST['substractq']));

        // check if quantity is valid
        if (preg_match($quantity_regex, $user_quantity)) {
             $valid_quantity = true;
         }

        // get information from pantry's food item
        $query = "SELECT * FROM Pantry_Foods WHERE Pantry_Number = '$pantryid' AND Food_Number='$itemid'";
        $pantry_food_data = mysqli_query($dbc, $query)
            or die ("Error querying database - $query");

        // get pantry's food item quantity
        $row = mysqli_fetch_array($pantry_food_data);
        $pantry_quantity = $row['Quantity'];

        // pantry's total food item quantity
        $total_quantity = (int)$pantry_quantity - (int)$user_quantity;

        if (($total_quantity) > 0 && $valid_quantity) { // valid input quantity and total pantry food item quantity is equal to or greater than zero

            $query = "UPDATE Pantry_Foods SET Quantity = '$total_quantity' WHERE Pantry_Number = '$pantryid' AND Food_Number = '$itemid'";

            mysqli_query($dbc, $query)
                or die ("Error querying database - $query");
            
            header("Location: pantry.php");
        
        } else if ($total_quantity == 0) { 
        
            $query = "DELETE FROM Pantry_Foods WHERE Pantry_Number = '$pantryid' AND Food_Number = '$itemid'";
            mysqli_query($dbc, $query)
                or die ("Error querying database - $query");
            
            header("Location: pantry.php");
            
        } else { // invalid input quantity or total pantry food item quantity is less than zero
                
            $error .= 'Error: The input is invalid.';
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
                <h2><?= $name ?>'s Pantry</h2>
                
                <?php
                    
                    $row_count = 1;
                    
                    if (mysqli_num_rows($foodsdata) > 0) { // pantry is not empty
                        
                        echo "<p class='instruction'>Please enter a number (from 1 to 999) to decrease a food item quantity.</p>
                                <p class='error'>$error</p>
                                <table id='pantry'><tr>
                                <th class='cell'>Vendor</th>
                                <th class='cell'>Food Item</th>
                                <th class='cell'>Picture</th>
                                <th class='cell'>Quantity Stored</th>
                                <th class='cell'>Quantity Decreased</th>
                                <th class='cell'>Delete</th>
                            </tr>";
                        
                        // loop through each data row
                        while ($row = mysqli_fetch_array($foodsdata)) {
                            $vendor = $row['Vendor_Name'];
                            $itemid = $row['Food_Number'];
                            $name = $row['Food_Name'];
                            $image = $row['Food_Image'];
                            $quantity = $row['Quantity'];

                            $row_count++; // count row number

                            if ($row_count % 2 == 0) { // even row
                                echo "<tr class='even-row-color'>
                                        <td class='cell'>$vendor</td>
                                        <td class='cell'>$name</td>  
                                        <td class='cell'><img class='foodimage' src='./uploads/$image' alt='$name'>
                                        <td class='cell'>$quantity</td><td class='cell'><form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data' name='substractqlist'><input type='hidden' name='substractq' value='$itemid'><input type='text' class='quantity' name='quantity' size='3'> <input type='submit' class='minus' name='minus' value='Submit'></form></td><td class='cell'><a href='delete.php?delid=$itemid' class='delete'><img src='./images/delete.png' alt='Delete'></a></td>
                                    </tr>";  
                            } else { // odd row
                                echo "<tr class='odd-row-color'>
                                        <td class='cell'>$vendor</td>
                                        <td class='cell'>$name</td>
                                        <td class='cell'><img class='foodimage' src='./uploads/$image' alt='$name'>
                                        <td class='cell'>$quantity</td><td class='cell'><form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data' name='substractqlist'><input type='hidden' name='substractq' value='$itemid'><input type='text' class='quantity' name='quantity' size='3'> <input type='submit' class='minus' name='minus' value='Submit'></form></td><td class='cell'><a href='delete.php?delid=$itemid' class='delete'><img src='./images/delete.png' alt='Delete'></a></td>
                                    </tr>";
                            }
                        } 
                        
                        echo "</table>"; 
                    
                    } else {
                        
                        echo "<p>Your pantry is empty. To stock food in the pantry, go to Marketplace and select the items to purchase.</p>";    
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