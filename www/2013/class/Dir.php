<?php
/**
* class to create directories
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
class Dir
{
	private static $instance;
	protected $_path;
	protected $_file;
	protected $_numfolders;
	protected $_data;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setpath($path){ $this->_path = $path; }
	public function getpath(){ return $this->_path; }
	public function setfile($file){ $this->_file = $file; }
	public function getfile(){ return $this->_file; }
	public function setnumfolders($numfolders){ $this->_numfolders = $numfolders; }
	public function getnumfolders(){ return $this->_numfolders; }
	public function setdata($data){ $this->_data = $data; }
	public function getdata(){ return $this->_data; }
	
	public function mkdir()
	{
		$path = $this->getpath();
		if(!empty($path))
		{
			$folder = explode("/", $path);
			for($i=0; $i < count($folder); $i++)
			{
				if(!is_dir($folder[$i])){ @mkdir($folder[$i], '0777', true); }
				@chmod($folder[$i], 0777);
			}
			
			return count($folder);
		}
		else return false;
	}
	
	public function mkfiledir()
	{
		$path = $this->getpath() <> '' ? $this->getpath() : '';
		$file = $this->getfile() <> '' ? $this->getfile() : '';
		$numfolders = $this->getnumfolders() <> '' ? $this->getnumfolders() : '';
		if($path <> '' && $file <> '' && $numfolders <> '' && strlen($file) >= 5)
		{
			$curdir="/";
			for($i=1; $i<=$numfolders; $i++)
			{
				$curdir .= substr($file, 0, $i) . "/";
				$folder = $path . $curdir;
				
				if(!is_dir($folder))
				{
					@mkdir($folder, '0777', true);
				}
				@chmod($folder, 0777);
			}
			
			return $curdir;
		}
		else return false;
	}
	
	public function write()
	{
		$data = $this->getdata() <> '' ? $this->getdata() : '';
		$path = $this->getpath() <> '' ? $this->getpath() : '';
		$file = $this->getfile() <> '' ? $this->getfile() : '';
		$fh = fopen($path . '/' . $file, 'w') or die("Erro ao abrir o arquivo da galeria, por favor informe o administrador do sistema.");
		fwrite($fh, $data);
		fclose($fh);
		return true;
	}
	
	
}