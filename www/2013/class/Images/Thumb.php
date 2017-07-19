<?php
/**
* class to create the thumb setting the sizes
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-08
*/
class Images_Thumb {
	private static $instance;
	protected $_sourcefile;
	protected $_sourcefolder;
	protected $_targetfolder;
	protected $_thumbheight;
	protected $_thumbwidth;
	
	public static function getInstance() {
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setsourcefile($sourcefile){ $this->_sourcefile = $sourcefile; }
	public function getsourcefile(){ return $this->_sourcefile; }
	public function setsourcefolder($sourcefolder){ $this->_sourcefolder = $sourcefolder; }
	public function getsourcefolder(){ return $this->_sourcefolder; }
	public function settargetfolder($targetfolder){ $this->_targetfolder = $targetfolder; }
	public function gettargetfolder(){ return $this->_targetfolder; }
	public function setthumbheight($thumbheight){ $this->_thumbheight = $thumbheight; }
	public function getthumbheight(){ return $this->_thumbheight; }
	public function setthumbwidth($thumbwidth){ $this->_thumbwidth = $thumbwidth; }
	public function getthumbwidth(){ return $this->_thumbwidth; }

	public function action() {
		$sourceFile = realpath(dirname(__FILE__)) . '/../../' . $this->getsourcefolder() . $this->getsourcefile();
		$sourceFile = str_replace("//", "/", $sourceFile);
		$targetFile = realpath(dirname(__FILE__)) . '/../../' . $this->gettargetfolder() . $this->getsourcefile();
		$targetFile = str_replace("//", "/", $targetFile);
		$thumbheight = $this->getthumbheight();
		$thumbwidth = $this->getthumbwidth();
	
		if(file_exists($sourceFile) && !empty($thumbheight) && !empty($thumbwidth)) {
			list($width, $height, $imageType) = getimagesize($sourceFile);
			
			$imageType = image_type_to_mime_type($imageType);
            
            if($width > $height) { $thumbheight=($height/$width)*$thumbwidth; }
            else { $thumbwidth=($width/$height)*$thumbheight; }
            
			$croppedimage = imagecreatetruecolor( $thumbwidth, $thumbheight );

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

			imagecopyresampled($croppedimage, $source, 0, 0, 0, 0, $thumbwidth, $thumbheight, $width, $height);

			switch($imageType) {
				case "image/gif":
					imagegif($croppedimage, $targetFile);
				break;
				case "image/pjpeg":
				case "image/jpeg":
					imagejpeg($croppedimage, $targetFile, 100);
				break;
				case "image/jpg":
					imagejpeg($croppedimage, $targetFile, 100);
				break;
				case "image/png":
					imagepng($croppedimage, $targetFile, 9);
				break;
				case "image/x-png":
					imagepng($croppedimage, $targetFile, 9);
				break;
				default:
					return false;
				break;
			}
			
			return $targetFile;
		}
		else return false;
	}
}