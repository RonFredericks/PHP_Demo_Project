<?php
// File: HP_initialize.php
// Initialize PHP environment
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013

    define("HEADING", "Select Background Image Project");
    define("FOOTER", "<a href=\"http://www.lecturemaker.com/about-ron-fredericks/\">About Ron Fredericks</a>");
    define("IMAGE_DIR", "./HP_images/");
    define("DEBUG", false);    // set to true for more display messages
    error_reporting(-1);  // set to (-1) to display all errors, (0) for no errors and (E_ALL ^ E_NOTICE) for default production
    global $validext;   // define valid background image types as global
    $validext = array(".gif"=>"GIF image", ".jpeg"=>"JPEG image", ".jpg"=>"JPG image",  ".png"=>"PNG image");
    // include the mySQL data management system
    require_once './HP_assets/HP_UserManager.php';
    require_once './HP_assets/HP_miscFunctions.php';
    // 
    //session_start();
    $um = new UserManager();
    $user = $um->getSession();
