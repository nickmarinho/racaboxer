<?php
$filename = !empty($_GET['filepath']) ? $_GET['filename'] : '';
$filepath = !empty($_GET['filepath']) ? $_GET['filepath'] : '';

if(!empty($filename) && !empty($filepath)) {
	if($filepath == 'session' && $filename == 'session') {
		$filepath = $_SESSION['fileuploaded'];
		if(is_file($filepath)) {
			if(unlink($filepath)) { echo "1"; }
			else echo "error";
		}
	}
	else if(is_file(dirname(__FILE__) . "/../" . $filepath)) {
		if(unlink(dirname(__FILE__) . "/../" . $filepath)) { echo "1"; }
		else echo "error";
	}
	else if(is_file(dirname(__FILE__) . "/../" . $filepath . $filename)) {
		if(unlink(dirname(__FILE__) . "/../" . $filepath . $filename)) { echo "1"; }
		else echo "error";
	}
}
else { echo "no file"; }
?>
