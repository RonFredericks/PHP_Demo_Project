<?php
// File: HP_Database.php
// Purpose: Define a class to MySQL database connection class
// Author: Ron Fredericks, LectureMaker LLC
// Last Updated: 7/10/2013
// References: 
//        http://forum.zonehacks.com/threads/10-PHP-User-Management-System-using-Object-Oriented-Programming-and-MySQL
//        Object-Oriented PHP by Peter Lavin, No Starch Press, 2006
class Database {
    
    private static $instances = 0;  // make sure db connection is made only once, RDF
    /**
     * =============================================================
     * Change these values to work with your mysql database settings
     * =============================================================
     */
    private $db_host = 'localhost';
    private $db_user = '***';
    private $db_pass = '***';
    private $db_name = 'ronf3_myDB';
    

    private $where = array();
    private $variables = array();
    private $link;

    // see __call method below for implementaiton, RDF
    private $functions = array( 
    'affected_rows' => 'mysql_affected_rows',
    'client_encoding' => 'mysql_client_encoding',
    'close'    =>    'mysql_close',
    'connect' => 'mysql_connect',
    'create_db' => 'mysql_create_db',
    'data_seek' => 'mysql_data_seek',
    'db_name'    =>    'mysql_db_name',
    'db_query'    =>    'mysql_db_query',
    'drop_db'    =>    'mysql_drop_db',
    'errno'        =>    'mysql_errno',
    'error'        =>    'mysql_error',
    'escape_string'    =>    'mysql_escape_string',
    'fetch_array'    =>    'mysql_fetch_array',
    'fetch_assoc'    =>    'mysql_fetch_assoc',
    'fetch_field'    =>    'mysql_fetch_field',
    'fetch_lengths'    =>    'mysql_fetch_lengths',
    'fetch_object'    =>    'mysql_fetch_object',
    'fetch_row'        =>    'mysql_fetch_row',
    'field_flags'    =>    'mysql_field_flags',
    'field_len'        =>    'mysql_field_len',
    'field_name'    =>    'mysql_field_name',
    'field_seek'    =>    'mysql_field_seek',
    'field_table'    =>    'mysql_field_table',
    'field_type'    =>    'mysql_field_type',
    'free_result'    =>    'mysql_free_result',
    'get_client_info'    =>    'mysql_get_client_info',
    'get_host_info'        =>    'mysql_get_host_info',
    'get_proto_info'    =>    'mysql_get_proto_info',
    'get_server_info'    =>    'mysql_get_server_info',
    'info'                =>    'mysql_info',
    'insert_id'            =>    'mysql_insert_id',
    'list_dbs'            =>    'mysql_list_dbs',
    'list_fields'        =>    'mysql_list_fields',
    'list_processes'    =>    'mysql_list_processes',
    'list_tables'        =>    'mysql_list_tables',
    'num_fields'        =>    'mysql_num_fields',
    'num_rows'            =>    'mysql_num_rows',
    'pconnect'            =>    'mysql_pconnect',
    'ping'                =>    'mysql_ping',
    'query'                =>    'mysql_query',
    'real_escape_string'    =>    'mysql_real_escape_string',
    'result'            =>    'mysql_result',
    'select_db'            =>     'mysql_select_db',
    'set_charset'        =>    'mysql_set_charset',
    'stat'                =>    'mysql_stat',
    'tablename'            =>    'mysql_tablename',
    'thread_id'            =>    'mysql_thread_id',
    'unbuffered_query'    =>    'mysql_unbuffered_query'
    );

    function __construct() 
    {
        if (Database::$instances == 0) {
            $this->link = @$this->connect($this->db_host, $this->db_user, $this->db_pass);  // catch and hide error messages using "@", RDF
            if(!$this->link) {
                die("Database class: ".mysql_error(). " Error no: ".mysql_errno());  // Include all possible error messages in display, RDF
            }
            
            $this->select_db($this->db_name);
            Database::$instances = 1;
        }
    }
    
    function __destruct() 
    {
        if($this->link) {
            Database::$instances == 0;
            mysql_close($this->link);
            unset($this->link); // Clear from memory, RDF
        }
    }
    
    // Manage undeclared functions
    public function __call($name, $arguments) {
        if(isset($this->functions[$name])) {
            return call_user_func_array($this->functions[$name], $arguments);
        }
        return FALSE;
    }
    
} 
