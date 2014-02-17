<?php
// File: HP_UserManager.php
// Purpose: Create a class to manage user functions using a MySQL database
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013
// References: 
//         http://forum.zonehacks.com/threads/10-PHP-User-Management-System-using-Object-Oriented-Programming-and-MySQL
//        Object-Oriented PHP by Peter Lavin, No Starch Press, 2006

// Include the User and Database class files
require_once "./HP_assets/HP_User.php";
require_once "./HP_assets/HP_Database.php";

class UserManager 
{ 
    private $db;
    
    // Create an instance of the database class and store it into a private variable
    public function UserManager() 
    {
        $this->db = new Database();
    } 

     public  function createUser($username, $password, $email, $is_admin =  FALSE) 
     {
         if (isset($username) && isset($password) && isset($email)) { // Check for invalid function call, RDF
             $stmt = sprintf("INSERT INTO users (`id`, `username`,  `password`,  `email`, `is_admin`) VALUES (NULL, '%s', '%s', '%s',  '%d')",
                    $this->db->real_escape_string($username),
                     md5($this->db->real_escape_string($password)),     // A  md5 hash of the user's password will be stored in the database.
                     $this->db->real_escape_string($email),             //  always escape data from public before storing in database
                    $this->db->real_escape_string($is_admin));
            $result = $this->db->query($stmt);
    
            if ($result) return true;
         }
        return false;
    }  

    public function updateUser($user)
    {
        // Normally I wouldn't store session data in the database, but 
        // it can be changed to track cookies if you plan to go that
        // route.
        $session = $user->get('session');
        if (!$session) $session = 0;
         $stmt = sprintf("UPDATE users SET `username` = '%s', `password`  =  '%s', `email` = '%s', `is_admin` = '%d', `session` = '%s' WHERE `id`  =  '%d'",
                $this->db->real_escape_string($user->get('username')),
                $this->db->real_escape_string($user->get('password')), 
                $this->db->real_escape_string($user->get('email')), 
                $this->db->real_escape_string($user->get('is_admin')),
                $this->db->real_escape_string($session),
                $this->db->real_escape_string($user->get('id')));
        return $this->db->query($stmt);                
    }  

    public function deleteUser($user)
    {
        $userID = $this->db->real_escape_string($user->get('id'));
        return $this->db->query("DELETE FROM users WHERE `id` = '$userID'");
     }  

    // Get users from the database and return a user object by id or username
    public function getUserByID($id)
    {
        if (isset($id)) {     // Check for invalid function call, RDF
        
            // get the user by id from database
            $stmt = sprintf("SELECT * FROM users WHERE id = '%s'", $this->db->real_escape_string($id));
            $result = $this->db->query($stmt);
            if($result) {
                $user = new User();                            // create a new user object
                $row = $this->db->fetch_assoc($result);
                foreach($row as $key => $value) {              // loop through user table values
                    $user->set($key, $value);                // store them in the user object
                }
                return $user;                                // and return it
            }
        }
        return NULL;
    }
    
    public function getUserByName($name) 
    {

        if (isset($name)) {    // Check for invalid function call, RDF
            $stmt = sprintf("SELECT * FROM users WHERE username = '%s'", $this->db->real_escape_string($name));
            $result = $this->db->query($stmt) or trigger_error(mysql_error()." ".$stmt);
            if ($result && $this->db->num_rows($result) > 0) {
                $user = new User();
                $row = $this->db->fetch_assoc($result);
                foreach($row as $key => $value) {
                    $user->set($key, $value);
                }
                return $user;
            }
        }
        return NULL;
    } 

    // Get user by name, check the password, updates session info in the database, and return the user object
    public function login($username,  $password)
    {
        if (isset($username) && isset($password)) {    // Check for invalid function call, RDF
            $user = $this->getUserByName($username);
            if (isset($user) && $user->checkPassword($password)) {                
                // start PHP session, RDF
                if(!isset($_SESSION)) session_start();
                $_SESSION['zhuser'] = $user->get('username');            // I normally use these for cookies
                $_SESSION['zhsess'] = md5($username.microtime());        // calculate md5 of username + current unix time
                $user->set('session', $_SESSION['zhsess']);              // set the session in user object
                $this->updateUser($user);                                // update the user
                return $user;                                            // and return the user object if we're good
            }
        }
        return NULL;
    }
        
    public function logout() 
    {
        if (isset($_SESSION)) {
            unset($_SESSION);
            session_destroy();
        }
    }  

    //  Check if a session exists and against what we have stored in the database, if they match we're good
    public  function getSession()  
    {
        // start PHP session, RDF
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION['zhuser']) && isset($_SESSION['zhsess'])) {
            $user = $this->getUserByName($_SESSION['zhuser']);
            if (!$user) $this->logout();
            if ($user->get('session') == $_SESSION['zhsess']) {
                return $user;
            }
        }
        return NULL;
    }
}
