                <?php
                    // database connection
                    require_once "connectvars.inc.php";

                    // signed in user's username
                    $username = $_SESSION['username'];
                    
                    // declare variables
                    $itemid = '';
                    $user_quantity = '';
    
                    $valid_quantity = false;
                    $quantity_regex = '/^[0-9]{1,2}$/';
                    $error = '';

                    // get information from signed in user
                    $query = "SELECT * FROM Users WHERE User_Username = '$username'";
                    $user_data = mysqli_query($dbc, $query)
                        or die ("Error querying database - $query");

                    // get user id
                    $row = mysqli_fetch_array($user_data);
                    $userid = $row['User_Number'];

                    // get information from user's pantry
                    $query = "SELECT * FROM Pantry WHERE User_Number = '$userid'";
                    $pantry_data = mysqli_query($dbc, $query)
                        or die ("Error querying database - $query");

                    // get pantry id
                    $row = mysqli_fetch_array($pantry_data);
                    $pantryid = $row['Pantry_Number'];

                    // check if submit button has submitted form data
                    if(isset($_POST['addsubmit'])) {

                        // get submitted form data
                        $user_quantity = mysqli_real_escape_string($dbc, trim($_POST['quantity']));
                        $itemid = mysqli_real_escape_string($dbc, trim($_POST['itemid']));

                        // check if quantity is valid
                        if (preg_match($quantity_regex, $user_quantity) && $user_quantity != 0) {
                             $valid_quantity = true;
                         }

                        // get information from vendor's food item
                        $query = "SELECT * FROM Foods WHERE Food_Number = '$itemid'";
                        $vendor_food_data = mysqli_query($dbc, $query)
                                or die("Error querying database - $query");

                        // get vendor's food item quantity
                        $row = mysqli_fetch_array($vendor_food_data);
                        $vendor_quantity = $row['Food_Quantity'];
                        
                        // get information from user's pantry food item
                        $query = "SELECT * FROM Pantry_Foods WHERE Pantry_Number = '$pantryid' AND Food_Number = '$itemid'";
                        $pantry_food_data = mysqli_query($dbc, $query)
                                or die("Error querying database - $query");

                        // get pantry's food item quantity
                        $row = mysqli_fetch_array($pantry_food_data);
                        $pantry_quantity = $row['Quantity'];
                            
                        // total pantry quantity
                        $total_pantry_quantity = (int)$pantry_quantity + (int)$user_quantity;
                        
                        // total vendor quantity
                        $total_vendor_quantity = (int)$vendor_quantity - (int)$user_quantity;

                        if ($total_vendor_quantity >= 0 && $valid_quantity) {  // valid input quantity and total vendor food quantity is equal to or greater than zero

                            if (mysqli_num_rows($pantry_food_data) == 0){ // if food item does not already exist in user's pantry

                                // add food item information into user's pantry
                                $query = "INSERT INTO Pantry_Foods (Pantry_Number, Food_Number, Quantity, Date_Updated) VALUES ('$pantryid', '$itemid', '$user_quantity', NOW())";
                                mysqli_query($dbc, $query)
                                    or die ("Error querying database - $query");

                                // update vendor's food item quantity
                                $query = "UPDATE Foods SET Food_Quantity = '$total_vendor_quantity' WHERE Food_Number = '$itemid'";
                                mysqli_query($dbc, $query)
                                    or die ("Error querying database - $query");

                                // direct to pantry page
                                header('Location: pantry.php');

                            } else { // if food item already exists in user's pantry

                                // direct to index page
                                $query = "UPDATE Pantry_Foods SET Quantity = '$total_pantry_quantity', Date_Updated = NOW() WHERE Pantry_Number = '$pantryid' AND Food_Number = '$itemid'";
                                mysqli_query($dbc, $query)
                                    or die ("Error querying database - $query");

                                // update vendor's food item quantity
                                $query = "UPDATE Foods SET Food_Quantity = '$total_vendor_quantity' WHERE Food_Number = '$itemid'";
                                mysqli_query($dbc, $query)
                                    or die ("Error querying database - $query");

                                // direct to pantry page
                                header('Location: pantry.php');
                            }
                        } else { //invalid input or total food quantity is less than zero

                            $error .= 'Error: Either the input is invalid or vendor inventory is too low.';
                        }
                    }
                    
                    // get information from both vendors and foodss tables
                    $query = "SELECT * FROM Vendors INNER JOIN Foods ON Foods.Vendor_Number = Vendors.Vendor_Number ORDER BY Foods.Date_Updated DESC";
                    $data = mysqli_query($dbc, $query)
                        or die ("Error querying database - $query");

                ?>

                <p class="error"><?= $error ?></p>

                <p>All Food Items:</p>                

                <?php

                    $row_count = 1;
                    
                    if (mysqli_num_rows($data) > 0) { 
                        
                        echo "<table>
                            <tr>
                                <th class='cell'>Vendor</th>
                                <th class='cell'>Food Item</th>
                                <th class='cell'>Picture</th>
                                <th class='cell'>Quantity Available</th>
                                <th class='cell'>Date Last Updated</th>
                                <th class='cell'>For Pantry</th>
                            </tr>";
                        
                        // loop through each data row
                        while ($row = mysqli_fetch_array($data)) {
                            $vendor = $row['Vendor_Name'];
                            $itemid = $row['Food_Number'];
                            $name = $row['Food_Name'];
                            $image = $row['Food_Image'];
                            $quantity = $row['Food_Quantity'];
                            $date = date_create($row['Date_Updated']);
                            $formatted_date = date_format($date,"F jS, Y");
              
                            $row_count++; // count row number
                    
                            if ($row_count % 2 == 0) { // even row
                                echo "<tr class='even-row-color'>
                                        <td class='cell'>$vendor</td>
                                        <td class='cell'>$name</td>
                                        <td class='cell'><img class='foodimage' src='./uploads/$image' alt='$name'></td>
                                        <td class='cell'>$quantity</td>
                                        <td class='cell'>$formatted_date</td>
                                        <td class='cell'><form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data' name='addlist'>
                                        <input type='hidden' name='itemid' value='$itemid'>
                                        <input type='text' class='quantity' name='quantity' size='3'>&nbsp;<input type='submit' class='addsubmit' name='addsubmit' value='Purchase'></form></td>
                                    </tr>
                                    ";
                            } else { // odd row
                                echo "<tr class='odd-row-color'>
                                        <td class='cell'>$vendor</td>
                                        <td class='cell'>$name</td>
                                        <td class='cell'><img class='foodimage' src='./uploads/$image' alt='$name'></td>
                                        <td class='cell'>$quantity</td>
                                        <td class='cell'>$formatted_date</td>
                                        <td class='cell'><form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data' name='addlist'>
                                        <input type='hidden' name='itemid' value='$itemid'>
                                        <input type='text' class='quantity' name='quantity' size='3'>&nbsp;<input type='submit' class='addsubmit' name='addsubmit' value='Purchase'></form></td>
                                    </tr>
                                    ";
                            }
                        } echo "</table>"; 
                    } else {
                        echo "<p>Sorry, no results</p>";
                    }
                ?>