<?php 
/**
 * Model users  
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-08-23
 * @version    1.0  
 * @name Users.php
 */
class Model_DbTable_Users extends Zend_Db_Table_Abstract
{
	protected $_name = 'users';
	
	/**
	* incluir nova linha
	*
	* garante que um timestamp seja configurado para o campo created
	*
	* @param array $data
	* @return int
	*/
	public function insert( array $data)
	{
		$data['cdate'] = date('Y-m-d H:i:s');
		return parent::insert($data);
	}	
	
}
?>
