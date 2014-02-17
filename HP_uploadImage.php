<?php
// File: HP_uploadImage.php
// Purpose: Initialize and start PHP session
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013

    require_once './HP_assets/HP_initialize.php';
?>    
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload Image</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="./HP_assets/HP_functions.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./HP_assets/HP_main.css">
</head>
<body>
<?php
    // Attempt to process user login form $_POST  
    if (ProcessLoginForm($um)) {
        // update user info on successful new login
        $user = $um->getSession();
    }
    PutHeader(HEADING);   
    // Test for valid user, return to login page if user not valid
    if (!isset($user)) {
        PutErrorMessage("You must log in to view this page: ".basename($_SERVER['PHP_SELF']));
        Redirect('HP_login.php', 5);
        die();
    }    
    ?>
    
    <script>
    // Insert user name into heading
    newText = "<?php echo 'Welcome: ' . $user->get('username'); ?>";
    update_id_text("header_lft", newText); 
    </script>
    
    <?php 
    // Initiate image upload form    
    GetImageForm("HP_landingPage.php");
    DisplayValidImageTypes();
    PutFooter(FOOTER); 
?>
</body>
</html>
