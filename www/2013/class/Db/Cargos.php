<?php
include_once APP_FOLDER . '/class/Db.php';
/**
* class to manage cargos in curriculos
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-12-05
*/
class Db_Cargos extends Db
{
	private static $instance;
	protected $_id;
	protected $_cargo;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function __construct(){ $this->settable('cargos'); }
	public function setid($id){ $this->_id = $id; }
	public function getid(){ return $this->_id; }
	public function setcargo($cargo){ $this->_cargo = $cargo; }
	public function getcargo(){ return $this->_cargo; }
}
