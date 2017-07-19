<?php
/**
* class to native functions
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
class Db
{
	private static $instance;
	protected $_table;
	protected $_debug;
	protected $_fields;
	protected $_inner;
	protected $_where;
	protected $_sortname;
	protected $_sortorder;
	protected $_sortrand;
	protected $_limit;
	protected $_pagenum;
	protected $_lastpage;
	protected $_totalrows;
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function settable($table){ $this->_table = $table; }
	public function gettable(){ return $this->_table; }
	public function setdebug($debug){ $this->_debug = $debug; }
	public function getdebug(){ return $this->_debug; }
	public function setfields($fields){ $this->_fields = $fields; }
	public function getfields(){ return $this->_fields; }
	public function setinner($inner){ $this->_inner = $inner; }
	public function getinner(){ return $this->_inner; }
	public function setwhere($where){ $this->_where = $where; }
	public function getwhere(){ return $this->_where; }
	public function setsortname($sortname){ $this->_sortname = $sortname; }
	public function getsortname(){ return $this->_sortname; }
	public function setsortorder($sortorder){ $this->_sortorder = $sortorder; }
	public function getsortorder(){ return $this->_sortorder; }
	public function setsortrand($sortrand){ $this->_sortrand = $sortrand; }
	public function getsortrand(){ return $this->_sortrand; }
	public function setlimit($limit){ $this->_limit = $limit; }
	public function getlimit(){ return $this->_limit; }
	public function setpagenum($pagenum){ $this->_pagenum = $pagenum; }
	public function getpagenum(){ return $this->_pagenum; }
	public function setlastpage($lastpage){ $this->_lastpage = $lastpage; }
	public function getlastpage(){ return $this->_lastpage; }
	public function settotalrows($totalrows){ $this->_totalrows = $totalrows; }
	public function gettotalrows(){ return $this->_totalrows; }
	
	public function fetchentries()
	{
		$table = $this->gettable();
		$debug = $this->getdebug();
		$fields = $this->getfields();
		$inner = $this->getinner();
		$where = $this->getwhere();
		$sortname = $this->getsortname();
		$sortorder = $this->getsortorder();
		$sortrand = $this->getsortrand();
		$id = $this->getid();
		$limit = $this->getlimit();
		$pagenum = $this->getpagenum();
		$sql = "SELECT DISTINCT ";
		if(is_array($fields) && count($fields) > 0)
		{
			for($i=0; $i < count($fields); $i++)
			{
				$sql .= " a." . $fields[$i];
				if($i < (count($fields)) -1){ $sql .= " , "; }
			}
		}
		else{ $sql .= " DISTINCT a.* "; }
		$sql .= " FROM " . $table . " a ";
		if(!empty($inner))
		{
			$sql .= " INNER JOIN " . $inner['table'] . " AS b ON b." . $inner['campob'] . " " . $inner['tipofiltro'] . " " . $inner['word'] . "";
		}
		if(is_array($where) && count($where) > 0)
		{
			$sql .= " WHERE 1 ";
			foreach($where as $key => $value){
				if($key == 'id'){ $sql .= " AND a." . $key . "='" . $value . "' "; }
				elseif($key == 'id_foto'){ $sql .= " AND a." . $key . "='" . $value . "' "; }
				else{ $sql .= " AND a." . $key . " LIKE '%" . $value . "%' "; }
			}
		}
		else if(!empty($id))
		{
			$sql .= " WHERE a.id='" . $id . "' ";
		}
		if(!empty($sortname) && !empty($sortorder))
		{
			if($sortrand == '1'){ $sql .= " ORDER BY RAND(), a." . $sortname . " " . $sortorder . " "; }
			else{ $sql .= " ORDER BY a." . $sortname . " " . $sortorder . " "; }
		}
		else
		{
			if($sortrand == '1'){ $sql .= " ORDER BY RAND() "; }
			else{ $sql .= " ORDER BY a.id DESC "; }
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
		
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		
		if($debug == '1'){ echo "<pre>"; die(print_r($sql)); }

		if(!empty($id)){ return $q->fetch(PDO::FETCH_ASSOC); }
		else{ return $q->fetchAll(); }
		
		return false;
	}
	
	public function fetchentry($id=null)
	{
		if(!empty($id)){ $this->setid($id); }
		
		$id = $this->getid($id);
		
		if(!empty($id))
		{
			$table = $this->gettable();
			$debug = $this->getdebug();
			$fields = $this->getfields();
			$where = $this->getwhere();
			$sortname = $this->getsortname();
			$sortorder = $this->getsortorder();
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
			$sql .= " FROM " . $table . " ";
			
			if(is_array($where) && count($where) > 0)
			{
				$sql .= " WHERE 1 ";
				foreach($where as $key => $value)
				{
					if($key == 'id'){ $sql .= " AND id='" . $value . "' "; }
					elseif($key == 'id_foto'){ $sql .= " AND id_foto='" . $value . "' "; }
					else{ $sql .= " AND " . $key . " LIKE '%" . $value . "%' "; }
				}
			}
			else if(!empty($id))
			{
				$sql .= " WHERE id='" . $id . "' ";
			}

			if(!empty($sortname) && !empty($sortorder))
			{
				$sql .= " ORDER BY " . $sortname . " " . $sortorder . " ";
			}
			if(!empty($limit)){ $sql .= " LIMIT " . $limit . " "; }
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			
			if($debug == '1'){ echo "<pre>"; print_r($sql); }
			
			$q->execute();

			return $q->fetch(PDO::FETCH_ASSOC);
		}
		else return false;
	}
	
	public function insert(array $data)
	{
		if(count($data) > 0)
		{
			$table = $this->gettable();
			$total = count($data);
			$c=1;
			$f="";
			$v="";
			foreach($data as $field => $value)
			{
				if($c == $total){ $f .= $field; $v .= "'" . addslashes($value) . "'"; }
				else{ $f .= $field . ", "; $v .= "'" . addslashes($value) . "', "; }
				$c++;
			}
			$sql = "INSERT INTO " . $table . " (" . $f . ") VALUES (" . $v . "); ";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$q->execute();
			$id = $conn->lastInsertId();
			return $id;
		}
		
		return false;
	}
	
	public function update(array $data, $id)
	{
		if(count($data) > 0)
		{
			$table = $this->gettable();
			$total = count($data);
			$c=1;
			$f="";
			$v="";
			$sql = "UPDATE " . $table . " SET ";
			foreach($data as $field => $value)
			{
				if($c == $total){ $sql .= $field . "='" . utf8_decode($value) . "'"; }
				else{ $sql .= $field . "='" . utf8_decode(addslashes($value)) . "', "; }
				$c++;
			}
			$sql .= " WHERE id='" . $id . "' ";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);

			if($q->execute()) {
				return true;
			}
			else {
				echo "<pre>";
				print_r($sql);
				echo "</pre>";
				exit;
			}
		}
		else return false;
	}
	
	public function delete($id)
	{
		$table = $this->gettable();
		$conn = Db_Instance::getInstance();
		$sql = "DELETE FROM " . $table . " WHERE id='" . $id . "' ";
		$q = $conn->prepare($sql);
		return $q->execute();
	}
}