<?php
	/*------------------------------------------------------------*/
	$numfolders=5;
	$imgfolder="img/".$_GET['mod']."/";

	if(!empty($imgafolder)) { $imgfolder.=$imgafolder.'/'; }
	$inifolder=dirname(__FILE__) . '/../' . $imgfolder;
	if(!is_dir($inifolder)){ @mkdir($inifolder, 0777, true); }
	@chmod($inifolder, 0777);

	$tempFile = time(); // temp file name
	$filename = md5($tempFile.uniqid("IMAGE")); // unique name to the file
	$fullfolder="";
	$foldertocreate="";
	for($i=1; $i<=$numfolders; $i++) {

		$fullfolder .= substr($filename, 0, $i) . "/";
		$foldertocreate = $inifolder . $fullfolder;

		if(!is_dir($foldertocreate)){ @mkdir($foldertocreate, 0777, true); }
		@chmod($foldertocreate, 0777);
	}
	/*------------------------------------------------------------*/
