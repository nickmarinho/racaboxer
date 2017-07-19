<?php 
/**
 * Model blog
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-09-14
 * @version    1.0 
 * @name Blog.php
 */
class Model_Blog {
	protected $_table;
	protected $_order;
	protected $_limit;
	protected $_options;
	
	public function setorder($order) { $this->_order = $order; }
	public function getorder() { return $this->_order; }
	public function setlimit($limit) { $this->_limit = $limit; }
	public function getlimit() { return $this->_limit; }
	public function setoptions($options) { $this->_options = $options; }
	public function getoptions() { return $this->_options; }
	
	/**
	* incluir nova linha
	*
	* garante que um timestamp seja configurado para o campo created
	*
	* @param array $data
	* @return int
	*/
	public function getTable() {
		// this is singleton
		if(null === $this->_table) {
			/**
			* uma vez que a tabela do banco não é um item da biblioteca e sim da aplicacao, devemos forçar seu uso
			*/
			require_once APP_PATH . '/models/DbTable/Blog.php';
			$this->_table = new Model_DbTable_Blog();
		}
		
		return $this->_table;
	}
	
	/**
	* grava nova entrada
	*
	* @param array $data
	* @return int|string
	*/
	public function save(array $data) {
		$table = $this->getTable();
		$fields = $table->info(Zend_Db_Table_Abstract::COLS);
		
		foreach($data as $field => $value) {
			if(!in_array($field, $fields)) { unset($data[$field]); }
		}
		
		return $table->insert($data);
	}
	
	/**
	* atualiza entrada
	*
	* @param array $data
	*/
	public function update(array $data, $id) {
		$table = $this->getTable();
		$fields = $table->info(Zend_Db_Table_Abstract::COLS);
		
		foreach($data as $field => $value) {
			if(!in_array($field, $fields)) { unset($data[$field]); }
		}
		
		return $table->update($data, "id = $id");
	}
	
	/**
	* remove entrada
	*
	* @param $id
	* @return true|false
	*/
	public function delete($id) {
		$table = $this->getTable();
		return $table->delete("id = $id");
	}
	
	/**
	* traz todas entradas
	*
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchEntries() {
		$options = $this->getoptions();
		$table = $this->getTable();
		$select = $table
			->select()
			->setIntegrityCheck(false)
			->from($table, array('*'));
		if(count($options) > 0) {
			$where = "1 ";
			foreach($options as $key => $value) {
				$where .= " AND " . $key . " LIKE '%" . $value . "%' ";
			}
			$select->where($where);
		}
		
		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('cdate DESC');
		
		return $table->fetchAll($select)->toArray();
	}
	
	/**
	* traz todas entradas
	*
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchFrontEntries() {
		$options = $this->getoptions();
		$table = $this->getTable();
		$select = $table
			->select()
			->setIntegrityCheck(false)
			->from($table, array('*'));
		if(count($options) > 0) {
			$where = "1 ";
			foreach($options as $key => $value) {
				$where .= " AND " . $key . " LIKE '%" . $value . "%' ";
			}
			$select->where($where);
		}
		
		$select->where(" blog.display='1' ");
		
		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('cdate DESC');
		
		if($this->getlimit() <> '') $select->limit($this->getlimit());
		
		//die($select->__toString());
		return $table->fetchAll($select)->toArray();
	}
	
	public function fetchYears() {
		$table = $this->getTable();
		$select = $table
		    ->select()
		    ->distinct()
		    ->from($table, array("DATE_FORMAT(cdate, '%Y') as year"))
		    ->where('display = 1')
//			->rand()
//			->limit(1)
			;
			
		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('year');
		
		return $table->fetchAll($select)->toArray();
	}
	
	/**
	* traz entrada de acordo com o id passado
	*
	* @param int|string $id
	* @return null|Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchEntryByUrl($url) {
		$table = $this->getTable();
		$select = $table->select()->where('url = ?',$url);
		
		if(count($table->fetchRow($select)) > 0) return $table->fetchRow($select)->toArray();
		else return false;
	}
	
	/**
	* traz entrada de acordo com o id passado
	*
	* @param int|string $id
	* @return null|Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchEntry($id) {
		$table = $this->getTable();
		$select = $table->select()->where('id = ?',$id);
		
		return $table->fetchRow($select)->toArray();
	}
}
?>
