<?php
    // database connection
    require_once "./includes/connectvars.inc.php"; 
    
    $item = '';
    
    // get input data
    if(isset($_POST['item'])) {
        $item = trim($_POST['item']);
    }

    // query to get food items containing value entered by user
    if ($item != '') {
        
        // if input value is not empty
        $query = "SELECT * FROM Foods INNER JOIN Vendors ON Foods.Vendor_Number = Vendors.Vendor_Number WHERE Food_Name like '%$item%' ORDER BY Date_Updated DESC";
        
    } else {
        
        // if input value is empty
        $query = "SELECT * FROM Foods INNER JOIN Vendors ON Foods.Vendor_Number = Vendors.Vendor_Number WHERE Food_Number = ''";
    }

        $data = mysqli_query($dbc, $query)
                or die ("Error querying database - $query");

                $row_count = 1;

                    if (mysqli_num_rows($data) > 0) { 

                        echo "<table>
                            <tr>
                                <th class='cell'>Vendor</th>
                                <th class='cell'>Food Item</th>
                                <th class='cell'>Picture</th>
                                <th class='cell'>Quantity Available</th>
                                <th class='cell'>Date Last Updated</th>
                                <th class='cell'>Add to Pantry</th>
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
                                        <td class='cell'><form action='index.php' method='post' enctype='multipart/form-data' name='addlist'>
                                        <input type='hidden' name='itemid' value='$itemid'>
                                        <input type='text' class='quantity' name='quantity' size='3'>&nbsp;<input type='submit' class='addsubmit' name='addsubmit' value='Purchase'></form></td>
                                    </tr>";
                            } else { // odd row
                                echo "<tr class='odd-row-color'>
                                        <td class='cell'>$vendor</td>
                                        <td class='cell'>$name</td>
                                        <td class='cell'><img class='foodimage' src='./uploads/$image' alt='$name'></td>
                                        <td class='cell'>$quantity</td>
                                        <td class='cell'>$formatted_date</td>
                                        <td class='cell'><form action='index.php' method='post' enctype='multipart/form-data' name='addlist'>
                                        <input type='hidden' name='itemid' value='$itemid'>
                                        <input type='text' class='quantity' name='quantity' size='3'>&nbsp;<input type='submit' class='addsubmit' name='addsubmit' value='Purchase'></form></td>
                                    </tr>
                                    ";
                            }
                        } 

                        echo "</table>"; 

                    } else {

                        echo "<p>Sorry, no results</p>";
                    }
                   
    // close database connection
mysqli_close($dbc);

?>