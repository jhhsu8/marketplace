// once entire page is loaded, loading circle is hidden and content is shown
$(window).on("load", function(){
    
    function showContent() {
        $("#outercircle").hide(); // hide loading circle
        $("#container").show(); // show content
    }
     // show content
    showContent();
});

$(document).ready(function(){
    
    //sticky navigation bar
    var pos = 150; 
    $(window).on("scroll", function() {
        if ($(window).scrollTop() > pos) { // if vertical scrollbar position greater than 150px
            $("#navigation").addClass("sticky"); // add sticky class to navigation bar
            $("#navbar").addClass("sticky");
        } else { // if vertical scrollbar position is equal to or less than 150px
            $("#navigation").removeClass("sticky"); // remove sticky class from navigation bar
            $("#navbar").removeClass("sticky");
        }
    });
    
    // tabs
    $("#sidebar p").click(function(){
        var tab_id = $(this).attr("data-tab"); // get name of data-tab attribute 
        $("#sidebar p").removeClass("active"); // remove active class from tabs
         $(this).addClass("active"); // add active class to clicked tab
        $(".section").hide(); // hide sections
        $("#"+tab_id).show(); // show section with id attribute name
	})
    
    // select a food item category 
    $("#selection").change(function(){
        var str = $(this).val();
        if (str == "") { // user has not selected
            $("#category").empty(); // remove #category element
            $("#allcategories").show(); // show #allcategories element
            
            } else { // user has selected
                $("#allcategories").hide(); // hide #allcategories element
                var request = $.ajax({ 
                    url: "selection.php", // server url
                    method: "POST", // send input data
                    data: {category: str}, // input data
                    dataType: "html" // HTML data type
                })
                request.done(function(result){ // ajax request succeeds
                $("#category").html(result);            
            });
                request.fail(function(jqXHR, textStatus){ // ajax request fails
                $("#category").html("<p>Request failed: " + textStatus + "</p>");
                });
            }
    });
    
    // search food item(s)
    $("#search").keyup(function(){
        var str = $(this).val();
        str = str.trim(); // remove whitespace from both sides of a string
        if (str == "") { // user has not entered input
            $("#items").empty(); // remove #items element
            } else { // user has entered input
                var request = $.ajax({ 
                    url: "search.php", // server url
                    method: "POST", // send input data
                    data: {item: str}, // input data
                    dataType: "html"  // HTML data type
                })
                request.done(function(result){ // ajax request succeeds
                $("#items").html(result);            
            });
                request.fail(function(jqXHR, textStatus){ // ajax request fails
                $("#items").html("<p>Request failed: " + textStatus + "</p>");
                });
            }
    });
    
    // delete click function
    $(".delete").click(function() { 
        
        var delete_item = confirm("Are you sure you want to delete?");
        if (delete_item) { // if confirmed, delete 
            return true;
        } else { // if not confirmed, do not delete
            return false;
        }
    });
    
    // check if vendor name is from 2 to 20 alphabetic characters. Only numbers
    $("#vendorname").blur(function() {
        var regexTest = /^[a-zA-Z0-9\s]{2,30}$/.test($(this).val());
        if (regexTest) {
                // valid input
               $("#vendornameprompt").html("");

            } else {

                // invalid input
                $("#vendornameprompt").html("Enter from 2 to 30 alphabets/numbers/spaces");
            }
    });
    
    // check if vendor id is a 1 to 10-digit number 
    $("#vendorid").blur(function() {
        var regexTest = /^[0-9]{1,10}$/.test($(this).val());
            if (regexTest) {
                // valid input
               $("#vendoridprompt").html("");

            } else {

                // invalid input
                $("#vendoridprompt").html("Enter 1 to 10 digits only");
            }
      });
    
    // check if item name is from 2 to 20 alphabetic characters 
    $("#itemname").blur(function() {
        var regexTest = /^[a-zA-Z\s]{2,30}$/.test($(this).val());
        if (regexTest) {
                // valid input
               $("#itemnameprompt").html("");

            } else {

                // invalid input
                $("#itemnameprompt").html("Enter from 2 to 30 alphabets/spaces");
            }
      });
    
    // check if item id is a 1 to 10-digit number
    $("#item_id").blur(function() {
        var regexTest = /^[0-9]{1,10}$/.test($(this).val());
        if (regexTest) {
                // valid input
               $("#item_idprompt").html("");

            } else {
                // invalid input
                $("#item_idprompt").html("Enter 1 to 10 digits only");
            }
    });
    
    // check if item quantity is a 1 to 5-digit number 
    $("#item_quantity").blur(function() {
        var regexTest = /^[0-9]{1,5}$/.test($(this).val());
        if (regexTest) {
                // valid input
               $("#item_quantityprompt").html("");

            } else {
                // invalid input
                $("#item_quantityprompt").html("Enter 1 to 5 digits only");
            }
    });
});

window.onload = function() {
    
    var page = window.location.pathname;
    
    if (page == "/final/register.php") { // register page
        
        var firstname = document.getElementById("firstname");

        // check if first name is from 2 to 20 alphabetic characters 
        firstname.onblur = function() {
    
            var regexTest = /^[a-zA-Z]{2,20}$/.test(firstname.value);

            if (regexTest) {
                // valid input
                document.getElementById("firstnameprompt").innerHTML = "";
        
            } else {
                // invalid input
                document.getElementById("firstnameprompt").innerHTML = "Enter from 2 to 20 alphabets";
            }
        }
    
        var lastname = document.getElementById("lastname");

        // check if last name is from 2 to 20 alphabetic characters 
        lastname.onblur = function() {
    
            var regexTest = /^[a-zA-Z]{2,20}$/.test(lastname.value);

            if (regexTest) {
                // valid input
                document.getElementById("lastnameprompt").innerHTML = "";
            
            } else {

                // invalid input
                document.getElementById("lastnameprompt").innerHTML = "Enter from 2 to 20 alphabets";
            }
        }
        
        var username = document.getElementById("username");

        /* check if username input is 4 to 25 characters. Acceptable characters are letters, numbers, underscore, and hypen. First character must be a letter. Last character must be a letter or a number */
        username.onblur = function() {
    
            var regexTest = /^[a-zA-Z][a-zA-Z0-9_\-]{2,23}[a-zA-Z0-9]$/.test(username.value);

            if (regexTest) {
                // valid input
                document.getElementById("usernameprompt").innerHTML = "";
            
            } else {

                // invalid input
                document.getElementById("usernameprompt").innerHTML = "Username is not acceptable";
            }
        }
            
        var password = document.getElementById("password");

        // check if password contains at least 5 characters. Password must have at least one letter and one number 
        password.onblur = function() {

            var regexTest = /(?=.*\d)(?=.*[a-zA-Z]).{5,}/.test(password.value);

            if (regexTest) {
                // valid input
                document.getElementById("passwordprompt").innerHTML = "";

            } else {

                // invalid input
                document.getElementById("passwordprompt").innerHTML = "Password is not acceptable";
            }
        }
    }
    
    if (page == "/final/contact.php") { // contact page
        
        var firstname = document.getElementById("firstname");
        
        // check if first name is from 2 to 20 alphabetic characters 
        firstname.onblur = function() {

            var regexTest = /^[a-zA-Z]{2,20}$/.test(firstname.value);

            if (regexTest) {
                // valid input
                document.getElementById("firstnameprompt").innerHTML = "";

            } else {
                // invalid input
                document.getElementById("firstnameprompt").innerHTML = "Enter from 2 to 20 alphabets";

            }
        }
        
        var lastname = document.getElementById("lastname");
        // check if last name input is 2 to 20 alphabetic characters
        lastname.onblur = function() {

            var regexTest = /^[a-zA-Z]{2,20}$/.test(lastname.value);    

            if (regexTest) {
                // valid input
                document.getElementById("lastnameprompt").innerHTML = "";

            } else {
                // invalid input
                document.getElementById("lastnameprompt").innerHTML = "Enter from 2 to 20 alphabets";

            }
        }
        
        var email = document.getElementById("email");

        // checks if input is an valid email address
        email.onblur = function() {

            var regexTest = /^[a-zA-Z0-9\_\.\-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(email.value);    

            if (regexTest) {
                // valid input
                document.getElementById("emailprompt").innerHTML = "";

            } else {
                // invalid input
                document.getElementById("emailprompt").innerHTML = "Email address is not acceptable";
     
            }
        }
        
        var subject = document.getElementById("subject");

        // check if subject is from 2 to 30 characters, only alphabets and spaces acceptable 
        subject.onblur = function() {
            
            var regexTest = /^[a-zA-Z\s]{2,30}$/.test(subject.value);    

            if (regexTest) {
                // valid input
                document.getElementById("subjectprompt").innerHTML = "";
            } else {
                // invalid input
                document.getElementById("subjectprompt").innerHTML = "Enter from 2 to 30 characters. Only alphabets and spaces are accepted";
            }
        }

        var message = document.getElementById("message");
        
        // checks if message is from 1 to 500 characters
        message.onblur = function() {

            if (message.value.length >= 1 && message.value.length <= 500) {
                // valid input
                document.getElementById("messageprompt").innerHTML = "";
      
            } else {
                // invalid input
                document.getElementById("messageprompt").innerHTML = "Character count must be from 1 to 500";
         
            }
        }           
    } 
    
    if (page == "/final/login.php") { // login page
        
        // photo slideshow
        var index = 0;
        var slides = document.getElementsByClassName("slide");
        
        carousel();
        
        function carousel() {
            var i;
            
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            index++;
            
            if (index > slides.length) {
                index = 1;
            }
            slides[index - 1].style.display = "block";
            setTimeout(carousel, 2000); // Change image every 2 seconds
        }
    }
};
  
        

    
    
    
    
    




 
    
 