<?php
/**
* class to store de db connection
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
//include_once "config.php";

class DB_Instance
{
	private static $instance;
    public static function getInstance()
    {
        if(!self::$instance)
		{
			try{ self::$instance = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS); }
			catch(PDOException $e){ die(print "Error!: <b>" . $e->getMessage() . "</b><br/>"); }
		}
        return self::$instance;
    }
}
?>
