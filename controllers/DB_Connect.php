<?php
/**
 * Class with static methods for connecting to the database
 */
class DB_Connect
{
    /**
     * Checks to see if the cookie is still valid. If it is, the user is logged in, false otherwise
     * @return boolean
     */
    static function isLoggedIn()
    {
        if (isset($_SESSION['IS_LOGIN_VALID']) && $_SESSION['IS_LOGIN_VALID'])
            return true;
        else
            return false;
    }
    /**
     * Creates a database object using the db.ini file. This is currently configured in m4j/db.ini
     * @return mysqli object
     */
    static function connectDB() 
    {
        $root = $_SERVER['DOCUMENT_ROOT']; // 
        $db = get_db();
        $ini_array = parse_ini_file($root. getOmekaPath(). '/db.ini');
        $dbhost = $ini_array["host"];
        $dbuser = $ini_array["username"];
        $dbpass = $ini_array["password"];
        $dbname = $ini_array["dbname"];
        $db_conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if (mysqli_connect_errno()) 
        {
            printf("Connection to database has failed: %s\n", $db_conn->connect_error);
            exit();
        }
        return $db_conn;
    }
    
}
?>
