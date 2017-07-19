<?php
session_start();

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()) { return false; }

		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);
		@chmod($path, 0777);

		return true;
	}

	function getName() { return $_GET['qqfile']; }
	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])) { return (int) $_SERVER["CONTENT_LENGTH"]; }
		else { throw new Exception('Getting content length is not supported.'); }
	}
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) { return false; }
		@chmod($path, 0777);
		return true;
	}
	function getName() { return $_FILES['qqfile']['name']; }
	function getSize() { return $_FILES['qqfile']['size']; }
}

class qqFileUploader {
	private $allowedExtensions = array();
	private $sizeLimit = 10485760;
	private $file;

	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		$this->checkServerSettings();

		if (isset($_GET['qqfile'])) { $this->file = new qqUploadedFileXhr(); }
		elseif (isset($_FILES['qqfile'])) { $this->file = new qqUploadedFileForm(); }
		else { $this->file = false; }
	}

	private function checkServerSettings() {
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
		}
	}

	private function toBytes($str) {
		$val = trim($str);
		$last = strtolower($str[strlen($str) - 1]);
		switch ($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}

	/**
	 * Returns array('success'=>true) or array('error'=>'error message')
	 */
	function handleUpload($uploadDirectory, $multiplefiles, $inputname, $replaceOldFile = FALSE) {
		/* faz upload da imagem aqui primeiro para depois mover para pasta destino */
		$uploadIniDirectory = $uploadDirectory;
		$imgfolderexplode = explode("/", $uploadDirectory);
		$totalimgfolderexplode = count($imgfolderexplode);
		$foldertocreate = '../';
		for ($i = 0; $i < $totalimgfolderexplode; $i++) {
			if (!is_dir($foldertocreate . $imgfolderexplode[$i])) { @mkdir($foldertocreate . $imgfolderexplode[$i], 0777, true); }
			@chmod($foldertocreate . $imgfolderexplode[$i], 0777);

			if (is_dir($foldertocreate . $imgfolderexplode[$i]) && $i == $totalimgfolderexplode) {
				@mkdir($foldertocreate . $imgfolderexplode[$i] . '__', 0777, true);
				@chmod($foldertocreate . $imgfolderexplode[$i], 0777);
				$foldertocreate .= $imgfolderexplode[$i] . "__/";
			}
			else $foldertocreate .= $imgfolderexplode[$i] . "/";
		}
		$uploadDirectory = $foldertocreate;
		/* end */

		if (!is_writable($uploadDirectory)) { return array('error' => "Server error. Upload directory isn't writable."); }
		if (!$this->file) { return array('error' => 'No files were uploaded.'); }

		$size = $this->file->getSize();

		if ($size == 0) { return array('error' => 'File is empty'); }
		if ($size > $this->sizeLimit) { return array('error' => 'File is too large'); }

		$pathinfo = pathinfo($this->file->getName());
		$filename = $pathinfo['filename'];
		$ext = $pathinfo['extension'];

		if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
		}

		if (!$replaceOldFile) {
			/// don't overwrite previous files that were uploaded
			while (file_exists($uploadDirectory . $filename . '.' . $ext)) { $filename .= rand(10, 99); }
		}

		if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
			$uploadDirectory = str_replace("//", "/", $uploadIniDirectory);
			//if(empty($_SESSION['gallery'][$inputname])) { $_SESSION['gallery'][$inputname] = str_replace("img/" . $inputname . "/", "", $uploadDirectory); }
			$_SESSION['gallery'][$inputname] = str_replace("img/" . $inputname . "/", "", $uploadDirectory);
			@chmod($uploadDirectory . $filename . '.' . $ext, 0777);

			return array (
				'success' => true,
				'name' => $inputname,
				'dir' => $uploadDirectory
			);
		}
		else { return array('error' => 'Could not save uploaded file.' . 'The upload was cancelled, or server error encountered'); }
	}
}

$inputname = !empty($_GET['inputname']) ? $_GET['inputname'] : '';
$fullfolder = !empty($_GET['fullfolder']) ? $_GET['fullfolder'] : '';
$uploadpath = !empty($_GET['uploadpath']) ? $_GET['uploadpath'] : '';
$multiplefiles = !empty($_GET['multiplefiles']) ? $_GET['multiplefiles'] : '';

$result = "";
// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

if (!$fullfolder || !$uploadpath) { return array('error' => 'No files were uploaded.'); }

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($uploadpath . $fullfolder, $multiplefiles, $inputname);
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
