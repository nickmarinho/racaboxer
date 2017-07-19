<?php
include_once APP_FOLDER . '/class/Dir.php';
/**
* class to create directories in file based name
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-09
*/
class Dir_PathFileBased extends Dir
{
	private static $instance;
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	/**
	 * this function is to create the structure of directories to save images based on md5 of the name of files
	 * ex. for id ehtyuiyje56321655142222.png
	 * will create the 'e' dir 
	 * and 'eh' 
	 * and 'eht' 
	 * and 'ehty' 
	 * and 'ehtyu' 
	 * after create this 5 recursive folders finally will save the file into it.
	 *
	 * @param string $path Path initial of the files
	 * @param int $id Id generated by database
	 * @return int $path The directory to save the files
	 */
	public function create(){ return $this->mkfiledir(); }
}