<?php
class DB_Connect
{
    static function isLoggedIn()
    {
        if (isset($_SESSION['IS_LOGIN_VALID']) && $_SESSION['IS_LOGIN_VALID'])
            return true;
        else
            return false;
    }
    
    static function connectDB() 
    {
        $root = $_SERVER['DOCUMENT_ROOT']; //   var/www/html
        $db = get_db();
        $ini_array = parse_ini_file($root. '/m4j/db.ini');
        //var_dump($ini_array);
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
