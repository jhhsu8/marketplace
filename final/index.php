<?php
    // start and check for valid session
    require_once("./includes/redirecthome.inc.php");
    // database connection
    require_once "./includes/connectvars.inc.php";
 
    // query to get all categories  
    $query = "SELECT * FROM Categories ORDER BY Category_Name ASC";
    $data = mysqli_query($dbc, $query)
        or die ("Error querying database - $query");

    // HTML document
    require_once("./includes/htmlhead.inc.php");
?>

    <body>
        <div id="outercircle"><div id="innercircle"></div></div>
        <div id="container">
            
<?php require_once("./includes/header.inc.php"); ?>
            
<?php require_once("./includes/navigation.inc.php"); ?>
            
            <div id="content">
                <h2>Marketplace</h2>
                <div id="sidebar">
                    <p class="active" data-tab="tab-1">Search Food Items by Category</p>
                    <p data-tab="tab-2">Search Food Items by Name</p>
                </div>
                <div id="forms">
                    <div id="tab-1" class="section">
                        <h3>Search Food Items by Category</h3>
                        <p class="instruction">Please enter a number (from 1 to 99) in the field to add item(s) to your pantry. You will be directed to the pantry page once the item(s) have been successfully added.</p>
                        <form>
                            <table>
                                <tr>
                                    <td><label for="selection">Select a Food Category:&nbsp;&nbsp;</label></td>
                                    <td><select name="selection" id="selection">
                                           <option value="">Select a Category</option>

                                            <?php
                                                // get category ID and name
                                                while ($row = mysqli_fetch_array($data)) {
                                                    $id = $row['Category_Number'];
                                                    $name= $row['Category_Name'];
                                            ?>

                                            <option value="<?= $id ?>"><?= $name ?></option>

                                            <?php
                                                }
                                            ?>

                                         </select>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div id="category"></div>
                        <div id="allcategories">
                            
<?php require_once("./includes/table.inc.php"); ?>
                            
                        </div>
                    </div>
                    <div id="tab-2" class="section">
                        <h3>Search Food Items by Name</h3>
                        <p class="instruction">Please enter letter(s) in the field to search food item(s).</p>
                        <form>
                            <table>
                                <tr>
                                    <td><label for="search">Search Food Items by Name:&nbsp;&nbsp;</label></td>
                                    <td><input type="text" id="search" name="search"></td>
                                </tr>
                            </table>
                        </form>
                        <p>Suggestions:</p>
                        <div id="items"></div>
                    </div>

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