<?php
    session_start();
    // HTML document
    require_once("./includes/htmlhead.inc.php");
?>

    <body>
        <div id="outercircle"><div id="innercircle"></div></div>
        <div id="container">
            
<?php require_once("./includes/header.inc.php"); ?>
            
<?php require_once("./includes/navigation.inc.php"); ?>
          
                <div id="content">
                    <div id="intro">
                        <h2>Introduction</h2>
                        <p>This website presents a simplified market system that trades food items through a marketplace. Vendors supply various food items to the marketplace, and customers procure food items from the marketplace and store them in their own pantries.</p>
                        <p>The transactions involves the following database tables: Customers, vendors, food categories, marketplace food inventory, and pantry food content of individual customers. </p>
                    </div>
                    <p id="foods"><img src="./images/foods.jpg" alt="Foods"></p>
                </div>
            
<?php require_once("./includes/footer.inc.php"); ?>
            
        </div>
    </body>
</html>