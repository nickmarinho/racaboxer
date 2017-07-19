<?php
include_once APP_FOLDER . '/class/Db.php';
/**
* class to registry the votes in fotos
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-16
*/
class Db_Votos extends Db
{
	private static $instance;
	protected $_id;
	protected $_cpf;
	protected $_nome;
	protected $_email;
	protected $_sql;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function __construct(){ $this->settable('votos'); }
	public function setid($id){ $this->_id = $id; }
	public function getid(){ return $this->_id; }
	public function setcpf($cpf){ $this->_cpf = $cpf; }
	public function getcpf(){ return $this->_cpf; }
	public function setnome($nome){ $this->_nome = $nome; }
	public function getnome(){ return $this->_nome; }
	public function setemail($email){ $this->_email = $email; }
	public function getemail(){ return $this->_email; }
	public function setsql($sql){ $this->_sql = $sql; }
	public function getsql(){ return $this->_sql; }

	public function fetchfrontentries()
	{
		$table = $this->gettable();
		$setsql = $this->getsql();
		if(!empty($setsql))
		{
			$sql = $setsql;
			//if($debug == '1'){ echo "<pre>"; die(print_r($sql)); }
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$q->execute();
			return $q->fetchAll();
		}
		return false;
	}	
	
	public function votar()
	{
		$id = $this->getid();
		if(!empty($id))
		{
			$formData = array();
			$table = $this->gettable();
			$sql = "SELECT * FROM fotos WHERE id='" . $id . "' ";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$q->execute();
			$row = $q->fetch(PDO::FETCH_ASSOC);
			
			if($row['foto'] <> '')
			{
				$id_foto = $row['id'];
				$nome = $this->getnome();
				$email = $this->getemail();
				$cpf = $this->getcpf();
				$ip = $_SERVER['REMOTE_ADDR'];
				
				//$sql = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1 AND id_foto='" . $id . "' AND email='" . $email . "' AND cpf='" . $cpf . "' ";
				$sql = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1 AND id_foto='" . $id_foto . "' AND email='" . $email . "' ";
				$conn = Db_Instance::getInstance();
				$q = $conn->prepare($sql);
				$q->execute();
				$row = $q->fetch(PDO::FETCH_ASSOC);
				
				if($row['total'] > 0){ return false; }
				else
				{
					$formData['id_foto'] = $id_foto;
					$formData['nome'] = $nome;
					$formData['email'] = $email;
					$formData['cpf'] = $cpf;
					$formData['codigo_validacao'] = md5($email . "_VALIDAR");
					$formData['ip'] = $ip;
					$formData['validado'] = '0';
					$cdate = date(date("Y-m-d H:i:s"),mktime((date("H")-3),date("i"),date("s"),date("m"),date("d"),date("Y")));
					$mdate = date(date("Y-m-d H:i:s"),mktime((date("H")-3),date("i"),date("s"),date("m"),date("d"),date("Y")));
					$formData['cdate'] = $cdate;
					$formData['mdate'] = $mdate;
					return $this->insert($formData);
				}
			}
		}
		
		return false;
	}
	
    public function deletevotos()
    {
        $where = $this->getwhere();
        $sql = " DELETE FROM votos ";
		if(is_array($where) && count($where) > 0)
		{
			$sql .= " WHERE 1 ";
			foreach($where as $key => $value){ $sql .= " AND " . $key . " LIKE '%" . $value . "%' "; }
		}
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		return $q->execute();
    }
}
