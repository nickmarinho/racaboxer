<?php
include_once APP_FOLDER . '/class/Db.php';
/**
* class to manage curriculos
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-16
*/
class Db_Curriculos extends Db
{
	private static $instance;
	protected $_id;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function __construct(){ $this->settable('cv'); }
	public function setid($id){ $this->_id = $id; }
	public function getid(){ return $this->_id; }
}
