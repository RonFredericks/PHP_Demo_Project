<?php
// File: HP_User.php
// Purpose: User class to manage undefined data members
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013
// References: 
//        http://forum.zonehacks.com/threads/10-PHP-User-Management-System-using-Object-Oriented-Programming-and-MySQL
//        Object-Oriented PHP by Peter Lavin, No Starch Press, 2006
class User 
{
    
    private $userdata = array();
    
    public function checkPassword($pass) 
    {
        if(isset($this->userdata['password']) && $this->userdata['password'] == md5($pass)) { 
            return true;
        }
        return false;
    }
    
    public function set($var, $value) 
    { 
        $this->userdata[$var] = $value; 
    }
    
    public function get($var) 
    {
        if(isset($this->userdata[$var])) {
            return $this->userdata[$var];
        }
        return NULL;
    }
}
