<?php 
/**
 * Model to manage "pages" table  
 * @copyright  2011 Raça Boxer
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-01-10
 * @version    4.0  
 * @name Pages.php
 */
class Model_DbTable_Pages extends Zend_Db_Table_Abstract
{
	protected $_name = 'pages';
	
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
