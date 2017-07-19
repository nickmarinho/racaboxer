<?php
$campo = !empty($_GET['campo']) ? $_GET['campo'] : '';
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$mod = !empty($_GET['mod']) ? $_GET['mod'] : '';

if(!empty($campo) && !empty($id) && !empty($mod)) {
	$vo = !empty($_GET['viewonly']) ? '1' : '0';

	if(!empty($_GET['dir'])) {
		$dir = $_GET['dir'];

		if ($handle = opendir(dirname(__FILE__) .'/../'. $dir)) {
?>
	<ul class="qq-upload-list">
<?php
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") { $files[]=$entry; }
			}
			closedir($handle);

			if(!empty($files) && is_array($files)) {
				sort($files);
				foreach($files as $entry) {
?>
		<li class="qq-upload-success">
			<span class="qq-upload-image"><img class="qq-upload-src" src="<?php echo $dir . $entry; ?>" onclick="javascript:previewimage('<?php echo $dir . $entry; ?>');" title="Clique para ampliar"></span>
			<span class="qq-upload-completefilepath"><?php echo $dir . $entry; ?></span>
			<span class="qq-upload-file"><?php echo $entry; ?></span>
			<span class="qq-upload-size" style="display: inline;"><?php echo format_bytes(filesize(dirname(__FILE__) .'/../'. $dir . $entry)); ?></span>
			<?php if(!$vo) { ?><span onclick="removeUploadedFileById(this, '<?php echo $campo; ?>', '<?php echo $id; ?>', '<?php echo $mod; ?>');" class="qq-upload-remove">remover</span><?php } ?>
		</li>
<?php
				}
			}
?>
	</ul>
<?php
		}
	}
}

function format_bytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}
