<?php 
/**
 * Model to manage images 
 * @copyright  2011 Raça Boxer
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-09-28
 * @version    4.0  
 * @name Images.php
 */
class Model_Images {
	protected $_table;
	protected $order;
	protected $options;
	
	public function setorder($order) { $this->order = $order; }
	public function getorder() { return $this->order; }
	public function setoptions($options) { $this->options = $options; }
	public function getoptions() { return $this->options; }
	
	/**
	* incluir nova linha
	*
	* garante que um timestamp seja configurado para o campo created
	*
	* @param array $data
	* @return int
	*/
	public function getTable() {
		if (null === $this->_table) {
			/**
			* uma vez que a tabela do banco não é um item da biblioteca e sim da aplicacao, devemos forçar seu uso
			*/
			require_once APP_PATH . '/models/DbTable/Images.php';
			$this->_table = new Model_DbTable_Images;
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
			foreach($options as $key => $value){ $where .= " AND " . $key . " LIKE '%" . $value . "%' "; }
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
			foreach($options as $key => $value){ $where .= " AND " . $key . " LIKE '%" . $value . "%' "; }
			$select->where($where);
		}
		
		$select->where(" images.display='1' ");
		
		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('cdate DESC');
		
		return $table->fetchAll($select)->toArray();
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
		$fetch = $table->fetchRow($select);
		
		if($fetch) return $fetch->toArray();
		else return 'error';
	}
	/**
	* traz entrada de acordo com o id passado
	*
	* @param int|string $id
	* @return null|Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchFotoAntes($id) {
		$table = $this->getTable();
		$select = $table->select();
		$select->where(" images.display='1' AND id < '" . $id . "' ");

		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('cdate DESC');
		
		$select->limit(1);
		$fetch = $table->fetchRow($select);
		
		if($fetch) return $fetch->toArray();
		else return '';
	}
	/**
	* traz entrada de acordo com o id passado
	*
	* @param int|string $id
	* @return null|Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchFotoDepois($id) {
		$table = $this->getTable();
		$select = $table->select();
		$select->where(" images.display='1' AND id > '" . $id . "' ");

		if($this->getorder() <> '') $select->order($this->getorder());
		else $select->order('cdate DESC');
		
		$select->limit(1);
		$fetch = $table->fetchRow($select);
		
		if($fetch) return $fetch->toArray();
		else return '';
	}
}
?>
