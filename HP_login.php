<?php
// File: HP_login.php
// Purpose: Initialize and start PHP session
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013

    require_once './HP_assets/HP_initialize.php';
?>    
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Page</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="./HP_assets/HP_functions.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./HP_assets/HP_main.css">
</head>
<body>
<?php
    // Insure that prior users are logged out of this web service
    $um->logout();
    // Initiate page div layout and display heading
    PutHeader(HEADING);
    // Initiate login
    LoginForm("HP_uploadImage.php");
    SelectValidUser();
    // Complete page div layout and display footer
    PutFooter(FOOTER);
    exit();     
?>
</body>
</html>
