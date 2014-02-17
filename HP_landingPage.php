<?php
// File: HP_landingPage.php
// Purpose: Initialize and start PHP session
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013

    require_once './HP_assets/HP_initialize.php';
?>    
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Landing Page</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="./HP_assets/HP_functions.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./HP_assets/HP_main.css">
</head>
<style>
body 
{
background-image:url('./HP_assets/HP_paper.gif');
}
</style>
<body>
<?php

    PutHeader(HEADING);
    
    if (!isset($user)) {
        PutErrorMessage("You must log in to view this page: ".basename($_SERVER['PHP_SELF']));
        Redirect('HP_login.php', 5);
        exit();
    }
?>
<script>
    newText = "<?php echo 'Welcome: ' . $user->get('username'); ?>";
    update_id_text("header_lft", newText); 
    </script>
    <?php   
    PutErrorMessage(ProcessUploadForm());
    PutFooter(FOOTER);
    ?>
</body>
</html>
