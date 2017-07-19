<?php
/**
* class to resize the image
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
class Images_Crop {
	private static $instance;
	protected $_sourcefile;
	protected $_sourcefolder;
	protected $_height;
	protected $_width;
	protected $_x1;
	protected $_x2;
	protected $_y1;
	protected $_y2;
    
	public static function getInstance() {
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setsourcefile($sourcefile){ $this->_sourcefile = $sourcefile; }
	public function getsourcefile(){ return $this->_sourcefile; }
	public function setsourcefolder($sourcefolder){ $this->_sourcefolder = $sourcefolder; }
	public function getsourcefolder(){ return $this->_sourcefolder; }
	public function setheight($height){ $this->_height = $height; }
	public function getheight(){ return $this->_height; }
	public function setwidth($width){ $this->_width = $width; }
	public function getwidth(){ return $this->_width; }
	public function setx1($x1){ $this->_x1 = $x1; }
	public function getx1(){ return $this->_x1; }
	public function setx2($x2){ $this->_x2 = $x2; }
	public function getx2(){ return $this->_x2; }
	public function sety1($y1){ $this->_y1 = $y1; }
	public function gety1(){ return $this->_y1; }
	public function sety2($y2){ $this->_y2 = $y2; }
	public function gety2(){ return $this->_y2; }

	public function action() {
		$sourceFile = realpath(dirname(__FILE__)) . '/../../' . $this->getsourcefolder() . '/' . $this->getsourcefile();
		$sourceFile = str_replace("//", "/", $sourceFile);
		$height = $this->getheight();
		$width = $this->getwidth();
		$x1 = $this->getx1();
		$x2 = $this->getx2();
		$y1 = $this->gety1();
		$y2 = $this->gety2();
        
		if(file_exists($sourceFile) && !empty($width) && !empty($height)) {
			list($originalwidth, $originalheight, $imgType) = getimagesize($sourceFile);
			$imageType = image_type_to_mime_type($imgType);
			$croppedimage = imagecreatetruecolor($width, $height);

			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($sourceFile);
				break;
				case "image/pjpeg":
				case "image/jpeg":
					$source=imagecreatefromjpeg($sourceFile);
				break;
				case "image/jpg":
					$source=imagecreatefromjpeg($sourceFile);
				break;
				case "image/png":
					$source=imagecreatefrompng($sourceFile);
				break;
				case "image/x-png":
					$source=imagecreatefrompng($sourceFile);
				break;
				default:
					return false;
				break;
			}

			imagecopy($croppedimage, $source, 0, 0, $x1, $y1, $originalwidth, $originalheight);
			
			switch($imageType) {
				case "image/gif":
					if(imagegif($croppedimage, $sourceFile)) { return true; }
				break;
				case "image/pjpeg":
				case "image/jpeg":
					if(imagejpeg($croppedimage, $sourceFile, 100)) { return true; }
				break;
				case "image/jpg":
					if(imagejpeg($croppedimage, $sourceFile, 100)) { return true; }
				break;
				case "image/png":
					if(imagepng($croppedimage, $sourceFile)) { return true; }
				break;
				case "image/x-png":
					if(imagepng($croppedimage, $sourceFile)) { return true; }
				break;
				default:
					return false;
				break;
			}
		}
		else return false;
	}
}
