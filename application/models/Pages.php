<?php 
/**
 * Model to manage pages
 * @copyright  2011 Raça Boxer
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-01-10
 * @version    4.0  
 * @name Pages.php
 */
class Model_Pages
{
	protected $_table;
	protected $_order;
	protected $_options;
	
	public function setorder($order)
	{
		$this->_order = $order;
	}
	
	public function getorder()
	{
		return $this->_order;
	}
	
	public function setoptions($options)
	{
		$this->_options = $options;
	}
	
	public function getoptions()
	{
		return $this->_options;
	}
	
	/**
	* incluir nova linha
	*
	* garante que um timestamp seja configurado para o campo created
	*
	* @param array $data
	* @return int
	*/
	public function getTable()
	{
		if (null === $this->_table)
		{
			/**
			* uma vez que a tabela do banco não é um item da biblioteca e sim da aplicacao, devemos forçar seu uso
			*/
			require_once APP_PATH . '/models/DbTable/Pages.php';
			$this->_table = new Model_DbTable_Pages;
		}
		
		return $this->_table;
	}
	
	/**
	* grava nova entrada
	*
	* @param array $data
	* @return int|string
	*/
	public function save(array $data)
	{
		$table = $this->getTable();
		$fields = $table->info(Zend_Db_Table_Abstract::COLS);
		
		foreach($data as $field => $value)
		{
			if(!in_array($field, $fields))
			{
				unset($data[$field]);
			}
		}
		
		return $table->insert($data);
	}
	
	/**
	* atualiza entrada
	*
	* @param array $data
	*/
	public function update(array $data, $id)
	{
		$table = $this->getTable();
		$fields = $table->info(Zend_Db_Table_Abstract::COLS);
		
		foreach($data as $field => $value)
		{
			if(!in_array($field, $fields))
			{
				unset($data[$field]);
			}
		}
		
		return $table->update($data, "id = $id");
	}
	
	/**
	* remove entrada
	*
	* @param $id
	* @return true|false
	*/
	public function delete($id)
	{
		$table = $this->getTable();
		return $table->delete("id = $id");
	}
	
	/**
	* traz todas entradas
	*
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchEntries()
	{
		$options = $this->getoptions();
		$table = $this->getTable();
		$select = $table
			->select()
			->setIntegrityCheck(false)
			->from($table, array('*'));
		if(count($options) > 0)
		{
			$where = "1 ";
			foreach($options as $key => $value){ $where .= " AND " . $key . " LIKE '%" . $value . "%' "; }
			$select->where($where);
		}
		
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
	public function fetchEntry($id)
	{
		$table = $this->getTable();
		$select = $table->select()->where('id = ?',$id);
		
		return $table->fetchRow($select)->toArray();
	}
	
	/**
	* traz anos contendo posts no blog
	*
	* @return Zend_Db_Table_Rowset_Abstract
	*/
	public function fetchPageByUrl($url)
	{
		$table = $this->getTable();
		$select = $table
			->select()
			->where('url = ?', $url);
			
		return $table->fetchAll($select)->toArray();
	}
}
?>