<?php 
/**
 * Model to manage "images" table  
 * @copyright  2011 Raça Boxer
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-09-28
 * @version    4.0  
 * @name Images.php
 */
class Model_DbTable_Images extends Zend_Db_Table_Abstract
{
	protected $_name = 'images';
	
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
