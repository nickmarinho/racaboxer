<?php 
/**
 * Model contact
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-08-23
 * @version    1.0 
 * @name Contact.php
 */
class Model_Contact
{
	protected $_table;
	
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
			require_once APP_PATH . '/models/DbTable/Contact.php';
			$this->_table = new Model_DbTable_Contact();
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
		return $this->getTable()->fetchAll('1')->toArray();
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
	
}
?>
