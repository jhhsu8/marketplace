-- Marketplace Database

--
-- Create Users Table
--

CREATE TABLE IF NOT EXISTS Users (
    User_Number int(10) unsigned NOT NULL auto_increment COMMENT 'A unique number to identify a single user.',
    User_First_Name varchar(40) NOT NULL COMMENT 'User''s first name.',
    User_Last_Name varchar(40) NOT NULL COMMENT 'User''s last name.',
    User_Username varchar(40) NOT NULL COMMENT 'User''s username.',
    User_Password varchar(32) NOT NULL COMMENT 'User''s password.',
    CONSTRAINT UsersPK PRIMARY KEY (User_Number)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 
-- --------------------------------------------------------

--
-- Create Pantry Table
--

CREATE TABLE IF NOT EXISTS Pantry (
    Pantry_Number int(10) unsigned NOT NULL auto_increment COMMENT 'A unique number to identify a single pantry.',
    User_Number int(10) unsigned NOT NULL COMMENT 'A reference to the user that owns the pantry.',
    CONSTRAINT PantryPK PRIMARY KEY (Pantry_Number),
    CONSTRAINT PantryFK1 FOREIGN KEY PantryIX1 (User_Number)
    REFERENCES Users (User_Number) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Create Vendors Table
--

CREATE TABLE IF NOT EXISTS Vendors (
    Vendor_Number int(10) unsigned NOT NULL auto_increment COMMENT 'A unique number to identify a single vendor.',
    Vendor_Name varchar(40) NOT NULL COMMENT 'Vendor''s name.',
    CONSTRAINT VendorsPK PRIMARY KEY (Vendor_Number)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for Vendors Table
--

INSERT INTO Vendors (Vendor_Number, Vendor_Name) VALUES 
(1, 'Safeway'),
(2, 'Costco'),
(3, 'Ranch99'),
(4, 'Albertson'),
(5, 'QFC');

-- --------------------------------------------------------

--
-- Create Categories Table
--

CREATE TABLE IF NOT EXISTS Categories (
    Category_Number int(10) unsigned NOT NULL auto_increment COMMENT 'A unique number to identify a single category.',
    Category_Name varchar(40) NOT NULL COMMENT 'Category''s name.',
    CONSTRAINT CategoriesPK PRIMARY KEY (Category_Number)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for Categories Table
--

INSERT INTO Categories (Category_Number, Category_Name) VALUES 
(1, 'Fruits'),
(2, 'Vegetables'),
(3, 'Meat'),
(4, 'Dairy'),
(5, 'Grains');

-- --------------------------------------------------------

--
-- Create Foods Table
--

CREATE TABLE IF NOT EXISTS Foods (
    Food_Number int(10) unsigned NOT NULL auto_increment COMMENT 'A unique number to identify a single food.',
    Vendor_Number int(10) unsigned NOT NULL COMMENT 'A reference to the vendor that owns the food',
    Category_Number int(10) unsigned NOT NULL COMMENT 'A reference to the category that contains the food.',
    Food_Name varchar(40) NOT NULL COMMENT 'Food name.',
    Date_Updated timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Date updated.',
    Food_Quantity smallint(5) unsigned NOT NULL COMMENT 'Food quantity.',
    Food_Image VARCHAR(50) NOT NULL COMMENT 'Food image',
    CONSTRAINT FoodsPK PRIMARY KEY (Food_Number),
    CONSTRAINT FoodsFK1 FOREIGN KEY FoodsIX1 (Vendor_Number)
    REFERENCES Vendors (Vendor_Number) ON DELETE CASCADE,
    CONSTRAINT FoodsFK2 FOREIGN KEY FoodsIX2 (Category_Number)
    REFERENCES Categories (Category_Number) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for Food Tables
--
 
INSERT INTO Foods (Vendor_Number, Category_Number, Food_Name, Date_Updated, Food_Quantity, Food_Image) VALUES
(1, 1, 'Granny Smith Apple', '2018-12-15 00:00:00', 100, 'granny_smith_apple.jpg'),
(2, 1, 'Fuji Apple', '2018-12-14 00:00:00', 100, 'fuji_apple.jpg'),
(3, 1, 'Red Delicious Apple', '2018-12-13 00:00:00', 100, 'red_apple.jpg'),
(4, 1, 'Gala Apple', '2018-12-12 00:00:00', 100, 'gala_apple.png'),
(1, 2, 'Romaine Lettuce', '2018-12-11 00:00:00', 100, 'lettuce.jpg'),
(1, 3, 'Roasted Beef', '2018-12-10 00:00:00', 100, 'beef.jpeg'),
(3, 3, 'Baked Chicken', '2018-12-09 00:00:00', 100, 'chicken.jpg'),
(1, 4, 'Feta Cheese', '2018-12-08 00:00:00', 100, 'feta_cheese.jpg'),
(2, 4, 'Brie Cheese', '2018-12-07 00:00:00', 100, 'brie_cheese.jpg'),
(3, 4, 'Cheddar Cheese', '2018-12-06 00:00:00', 100, 'cheddar_cheese.jpg'),
(4, 4, 'Swiss Cheese', '2018-12-05 00:00:00', 100, 'swiss_cheese.jpeg'),
(1, 5, 'French Bread', '2018-12-04 00:00:00', 100, 'french_bread.jpg'),
(3, 5, 'White Bread', '2018-12-03 00:00:00', 100, 'white_bread.jpg'),
(4, 5, 'Rye Bread', '2018-12-02 00:00:00', 100, 'rye_bread.jpg'),
(5, 5, 'Wheat Bread', '2018-12-01 00:00:00', 100, 'wheat_bread.jpeg');

-- --------------------------------------------------------

--
-- Create Pantry_Foods Table
--

CREATE TABLE IF NOT EXISTS Pantry_Foods (
    Pantry_Number int(10) unsigned NOT NULL COMMENT 'A reference to the pantry that contains the food.',
    Food_Number int(10) unsigned NOT NULL COMMENT 'A reference to the food that is contained in the pantry.',
    Quantity smallint(5) unsigned NOT NULL COMMENT 'Quantity of the food in the pantry.',
    Date_Updated timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'Date updated.',
    CONSTRAINT P_FFK1 FOREIGN KEY P_FIX1 (Pantry_Number)
    REFERENCES Pantry (Pantry_Number) ON DELETE CASCADE,
    CONSTRAINT P_FFK2 FOREIGN KEY P_FIX2 (Food_Number)
    REFERENCES Foods (Food_Number) ON DELETE CASCADE,
    CONSTRAINT P_FPK PRIMARY KEY (Pantry_Number, Food_Number)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



