<?php
// File: HP_miscFunctions.php
// Purpose: PHP Visual display and support functions
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/11/2013


//////////////////////////////////////////////////////////////////
// Login Support Functions                                      //
//////////////////////////////////////////////////////////////////

function LoginForm($url)
// Present a login form
{
    // Reference: http://designikx.wordpress.com/2010/04/07/pure-css-div-based-form-design-form-layout/
    $myArray[0] = array("test" => "", "message" => "Username must be entered");
    ?>
    
    <br/>
    <div id="myform">
    <form name="loginForm" action="<?php echo $url; ?>" onsubmit="return login_form(this)" method="post">
    <h1>Login Window</h1>
    <p>Welcome to the background image selector website</p>
    <br/>
    <label>Username
    <span>Its Your Name</span>
    </label>
    <input id="username" type="text" name="username">
 
    <label>Password
    <span>Enter Your Password</span>
    </label>
    <input id="password" type="text" name="password">
    <button  type="submit">Login</button>
    </form>
    </div>
    <?php
}

function SelectValidUser()
// Present list of valid users for login
{
?>
    <div id="myformsupport">
    <h1>Login List</h1>
    <p>Load one of these valid user credentials into login window</p>
    <ul>
    <li><button onclick="load_login('Ron Fredericks')">Load</button>Ron Fredericks</li>
    <li><button onclick="load_login('Tommy Tuba')">Load</button>Tommy Tuba</li>
    <li><button onclick="load_login('Admin')">Load</button>Admin</li>
    </ul>
    </div>
<?php
}

function ProcessLoginForm($um)
{
    if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
    {    
        // Attempt to login 
        $user = $um->login($_REQUEST['username'], $_REQUEST['password']);
        // Login failed, try again      
        if(!$user)
        {
            PutHeader(HEADING);
            PutErrorMessage ("Invalid login, try selecting a user from the list...");
            Redirect("HP_login.php", 5); 
            PutFooter(FOOTER);
        } else {
            // login was succesful
            return true;
        }
    }
    // login was either not successful or no login info was requested
    return false;
}


//////////////////////////////////////////////////////////////////
// Upload Image Support Functions                               //
//////////////////////////////////////////////////////////////////

function GetImageForm($url)
// Present a login form
{
    // Reference: http://designikx.wordpress.com/2010/04/07/pure-css-div-based-form-design-form-layout/
    ?>
    <br/>
    <div id="mygetimageform">
    <form name="form1" method="post" enctype="multipart/form-data" action="<?php echo $url; ?>"> 
    <h1>Load New Background Image Window</h1>
    <p>Welcome to the background image selector website</p>
    <br/>
    <input type="file" name="file" id="file" size="40"> 
    <button type="submit" name="Submit" value="Submit">Load Image</button>
    </form> 
    </div>
    <?php
}

function DisplayValidImageTypes()
// Present list of valid file types for image upload
{
    global $validext;

    echo '<div id="myformsupport">';
    echo '<h1>Valid File Types</h1>';
    echo '<p>Choose a file to load with one of these valid extensions:</p>';
    echo '<ul>';
    foreach ($validext as $key => $value) {
        echo "<li>$value [$key]</li>";
    }
    echo '</ul>';
    echo '</div>';

}

function UploadFile($origin, $dest, $tmp_name)
// reference: function GetImageForm($url)
{
    global $validext;
    $origin = strtolower(basename($origin));
    $fulldest = $dest.$origin;
    $filename = $origin;
    $fileext = (strpos($origin,'.')===false?'':'.'.substr(strrchr($origin, "."), 1));
    $validflag = false;
    foreach($validext as $ext=>$type) {
      if ($fileext === $ext) { 
          $validflag = true;
          break;
      }
    }
    if (!$validflag) {
      PutErrorMessage("Error: invalid file extension [$fileext]");
      return false;
      }
    
    for ($i=1; file_exists($fulldest); $i++) {
     $fileext = (strpos($origin,'.')===false?'':'.'.substr(strrchr($origin, "."), 1));
     $filename = substr($origin, 0, strlen($origin)-strlen($fileext)).'['.$i.']'.$fileext;
     $fulldest = $dest.$filename;
    }
    
    if (move_uploaded_file($tmp_name, $fulldest))  return $filename;
     
    return false;
}

function DeleteImageFiles($directory, $days)
// delete files from a diectory, 
// return number of files deleted,
// $directory must be: 1) a relative address and 2) not the same directory as the php file, for safety
{
        $count = 0;
        if (($days >= 0) && ($directory == IMAGE_DIR) && (dirname(__FILE__) != $directory) ) {
            $files = glob($directory."*");
            $seconds = $days * 24 * 60 * 60;
            foreach($files as $file) {
                $filemtime=filemtime ($file);
                if(is_file($file) && time()-$filemtime>= $seconds) {
                    $count++;
                    unlink($file);
                }
            }    
        }
        return $count;
}

function ProcessUploadForm()
// reference: function GetImageForm($url)
{
    $msg= "";
    if (isset($_POST['Submit']) && isset($_FILES["file"]["name"])) { 
        if (empty($_FILES["file"]["name"])) {
            PutErrorMessage("You did not select an image for landing page, please try again...");
            Redirect('HP_uploadImage.php', 5);
            die();
        }
    
        // process file name requested on client's computer as a result of client's use of the 'browse' button
        if ($_FILES["file"]["error"] > 0) {
            $msg .= "Error: " . $_FILES["file"]["error"] . "<br />";
        } 
        else {
            // file found
            $fileCnt = DeleteImageFiles(IMAGE_DIR, 0);  // remove old images
            if (DEBUG) {
            $msg .= "$fileCnt prior images removed from " . IMAGE_DIR . " image directory<br/>";
            $msg .=  "Upload: " . $_FILES["file"]["name"] . "<br />";
            $msg .=  "Type: " . $_FILES["file"]["type"] . "<br />";
            $msg .=  "temp: "  . $_FILES["file"]["tmp_name"] . "<br />";
            $msg .=  "Size: " . round($_FILES["file"]["size"] / 1024, 2) . " Kb<br />";
            }
            $result = UploadFile($_FILES["file"]["name"], IMAGE_DIR, $_FILES["file"]["tmp_name"]);
            if ($result) {
                if (DEBUG) {
                    $msg .=  "Result: " . IMAGE_DIR . $result."<br/>";
                }
                ?>
                <script>
                imgLink = "<?php echo IMAGE_DIR . $result ; ?>";
                update_background_image(imgLink);
                </script>
        <?php
            } 
            else {
                $msg .=  "Upload was not successful<br/>";
            }
        }
    } 
    return $msg;
}


//////////////////////////////////////////////////////////////////
// Page Display Functions                                      //
//////////////////////////////////////////////////////////////////

function PutHeader($heading, $left="", $right="") 
// Initialize web page structure and display heading 
{
    echo "<div id='container'>";
    echo "<div id='header_ctr'><span id='header_lft'></span>$heading<span id='header_rt'>".GetButtons()."</span></div>";
    echo "<div id='body'>";
}

function PutFooter($footer) 
// Complete web page structure and display footer
{
    echo "</div>";
    echo "<div id=\"footer\">$footer</div>";
    echo "</div>";
    ?>
    <script type="text/javascript">
    // place google analytics code to the bottom of each page
    // UA-8108355-1 is for LectureMaker
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-8108355-1']);
      _gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>  
    <?php  
}
    
function PutErrorMessage($msg)
// Manage display format for error messages
{
        if (!empty($msg)) {
            echo "<p class='myerror'>$msg</p>";
        }
}

function Redirect($url, $waitSeconds = 0) 
// Redirect to another web page
{
    //header("Location: $url"); // Rewrite the header
    echo "<meta http-equiv='refresh' content='$waitSeconds;url=$url'>";
    echo "<div id=\"timeMsg\">Click here if website does not load in <span>$waitSeconds</span> seconds<br/><a href=\"$url\">$url</a></div>";
    ?>    
    <script>
    count_down('#timeMsg', 'span');
    </script>
    
    <?php
    PutFooter(FOOTER);
    die ();
}

function GetButtons()
// Dislplay user options as buttons when logged in
{
    $msg="";
    if (isset($_SESSION["zhuser"]) && !empty($_SESSION["zhuser"])) {
        $msg .= "<button onclick=\"javascript:open_window('HP_login.php', '_self');\">Log Out</button>";
        $msg .= "<button onclick=\"open_window('HP_landingPage.php', '_self');\">Landing Page</button>";
        $msg .= "<button onclick=\"open_window('HP_uploadImage.php', '_self');\">New Image</button>";
    }
    //var_dump($_SESSION);
    return $msg;

}

function GetCurrentPage()
// Return current web page
{
    return basename($_SERVER['PHP_SELF']);
}

?>
