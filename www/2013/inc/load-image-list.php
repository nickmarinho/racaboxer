<?php
$vo = !empty($_GET['viewonly']) ? '1' : '0';

if(!empty($_GET['dir'])) {
	$dir = dirname(__FILE__) . '/../' . $_GET['dir'];

	if ($handle = opendir($dir)) {
?>
<ul class="qq-upload-list">
<?php
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") { $files[]=$entry; }
		}
		closedir($handle);

		sort($files);

		foreach($files as $entry) {
?>
	<li class=" qq-upload-success">
		<span class="qq-upload-image"><img class="qq-upload-src" src="<?php echo $_GET['dir'] . $entry; ?>" onclick="javascript:previewimage('<?php echo $_GET['dir'] . $entry; ?>');"></span>
		<span class="qq-upload-completefilepath"><?php echo $_GET['dir'] . $entry; ?></span>
		<span class="qq-upload-file"><?php echo $_GET['dir'] . $entry; ?></span>
		<span class="qq-upload-size" style="display: inline;"><?php echo format_bytes(filesize($dir . $entry)); ?></span>
		<?php if(!$vo) { ?><span onclick="removeUploadedFile(this);" class="qq-upload-remove">remover</span><?php } ?>
	</li>
<?php
		}
?>
</ul>
<?php
	}
}

function format_bytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}
