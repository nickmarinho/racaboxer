<?php
include_once APP_FOLDER . '/class/Db.php';
/**
* class to upload images to server
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
class Db_Images extends Db
{
	private static $instance;
	protected $_id;
	protected $_nome;
	protected $_sexo;
	protected $_email;
	protected $_telefone;
	protected $_cep;
	protected $_nascimento;
	protected $_titulo;
	protected $_foto;
	protected $_sql;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function __construct(){ $this->settable('fotos'); }
	public function setid($id){ $this->_id = $id; }
	public function getid(){ return $this->_id; }
	public function setnome($nome){ $this->_nome = $nome; }
	public function getnome(){ return $this->_nome; }
	public function setsexo($sexo){ $this->_sexo = $sexo; }
	public function getsexo(){ return $this->_sexo; }
	public function setemail($email){ $this->_email = $email; }
	public function getemail(){ return $this->_email; }
	public function settelefone($telefone){ $this->_telefone = $telefone; }
	public function gettelefone(){ return $this->_telefone; }
	public function setcep($cep){ $this->_cep = $cep; }
	public function getcep(){ return $this->_cep; }
	public function setnascimento($nascimento){ $this->_nascimento = $nascimento; }
	public function getnascimento(){ return $this->_nascimento; }
	public function settitulo($titulo){ $this->_titulo = $titulo; }
	public function gettitulo(){ return $this->_titulo; }
	public function setfoto($foto){ $this->_foto = $foto; }
	public function getfoto(){ return $this->_foto; }
	public function setsql($sql){ $this->_sql = $sql; }
	public function getsql(){ return $this->_sql; }
	
	public function fetchfrontentries()
	{
		$table = $this->gettable();
		$fields = $this->getfields();
		$debug = $this->_debug;
		$where = $this->getwhere();
		$sortname = $this->getsortname();
		$sortorder = $this->getsortorder();
		$sortrand = $this->getsortrand();
		$id = $this->getid();
		$limit = $this->getlimit();
		$pagenum = $this->getpagenum();
		$setsql = $this->getsql();
		
		if(!empty($setsql)){ $sql = $setsql; }
		else
		{
			$sql = "SELECT ";
			if(is_array($fields) && count($fields) > 0)
			{
				for($i=0; $i < count($fields); $i++)
				{
					$sql .= $fields[$i];
					if($i < (count($fields)) -1){ $sql .= ","; }
				}
			}
			else{ $sql .= " * "; }
			
			$sql .= " FROM " . $table . " AS a ";
			
			if(!empty($innerjoin))
			{
				$sql .= $innerjoin;
			}
			
			if(is_array($where) && count($where) > 0)
			{
				$sql .= " WHERE 1 ";
				foreach($where as $key => $value){
					if($key == 'id'){ $sql .= " AND a." . $key . "='" . $value . "' "; }
					else{ $sql .= " AND a." . $key . " LIKE '%" . $value . "%' "; }
				}
			}
			else if(!empty($id))
			{
				$sql .= " WHERE a.id='" . $id . "' ";
			}
			if(!empty($sortname) && !empty($sortorder))
			{
				if(!empty($sortrand)){ $sql .= " ORDER BY RAND(), a." . $sortname . " " . $sortorder . " "; }
				else{ $sql .= " ORDER BY a." . $sortname . " " . $sortorder . " "; }
			}

			if(!empty($limit))
			{
				if(!empty($pagenum))
				{
					$conn = Db_Instance::getInstance();
					$q = $conn->prepare($sql);
					$q->execute();
					$rows = $q->fetchAll();
					$last = ceil(count($rows)/$limit);
					$this->setlastpage($last);
					$this->settotalrows(count($rows));
					
					if($pagenum < 1){ $pagenum = 1; }
					elseif($pagenum > $last){ $pagenum = $last; }
					
					$sql .= " LIMIT " . ($pagenum - 1) * $limit . ',' . $limit;
				}
				else{ $sql .= " LIMIT " . $limit; }
			}
		}
		
		if($debug == '1'){ echo "<pre>"; die(print_r($sql)); }

		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		
		if(!empty($id)){ return $q->fetch(PDO::FETCH_ASSOC); }
		else{ return $q->fetchAll(); }
		
		return false;
	}

	public function save()
	{
		$id = $this->getid();
		$nome = $this->getnome();
		$sexo = $this->getsexo();
		$email = $this->getemail();
		$telefone = $this->gettelefone();
		$cep = $this->getcep();
		$nascimento = $this->getnascimento();
		$titulo = $this->gettitulo();
		$foto = $this->getfoto();
		$cdate = date(date("Y-m-d H:i:s"),mktime((date("H")-3),date("i"),date("s"),date("m"),date("d"),date("Y")));
		
		if(!empty($nome) && !empty($sexo) && !empty($email) && !empty($telefone) && !empty($cep) && !empty($nascimento) && !empty($titulo) && !empty($foto))
		{
			$formData['ativa'] = '0';
			$formData['nome'] = $nome;
			$formData['sexo'] = $sexo;
			$formData['email'] = $email;
			$formData['telefone'] = $telefone;
			$formData['cep'] = $cep;
			$formData['nascimento'] = $nascimento;
			$formData['foto'] = $foto;
			$formData['titulo'] = $titulo;
			$formData['ip'] = $_SERVER['REMOTE_ADDR'];
			$formData['cdate'] = $cdate;
			
			if(!empty($id))
			{
				$mdate = date(date("Y-m-d H:i:s"),mktime((date("H")-3),date("i"),date("s"),date("m"),date("d"),date("Y")));
				$formData['mdate'] = $mdate;
				return $this->update($formData, $id);
			}
			else return $this->insert($formData); // return the id inserted
		}
		else return false;
	}
}
