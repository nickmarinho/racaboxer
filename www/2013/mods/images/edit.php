<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="UPDATE " . $table . " SET
                name='" . $_POST['name'] . "',
                email='" . $_POST['email'] . "',
                path='" . $_POST['foldername'] . $_POST['filename'] . "',
                title='" . $_POST['title'] . "',
                obs='" . $_POST['obs'] . "',
                active='" . $_POST['active'] . "',
                mdate='" . MDATE . "'
        WHERE id='" . $_POST['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();

	if($query) {
        $imgcrop = Images_Crop::getInstance();
        $imgcrop->setsourcefile($_POST['filename']);
        $imgcrop->setsourcefolder($_POST['foldername']);
        $imgcrop->setheight($_POST['h']);
        $imgcrop->setwidth($_POST['w']);
        $imgcrop->setx1($_POST['x1']);
        $imgcrop->setx2($_POST['x2']);
        $imgcrop->sety1($_POST['y1']);
        $imgcrop->sety2($_POST['y2']);
        $imgcrop->action();

        $imgresize = Images_Resize::getInstance();
        $imgresize->setsourcefile($_POST['filename']);
        $imgresize->setsourcefolder($_POST['foldername']);
        $imgresize->settargetfolder($_POST['foldername']);
        $imgresize->setthumbheight('200');
        $imgresize->setthumbwidth('200');
        $imgresize->action();
		
		createLog(("atualizou " . $table . " id '" . $_POST['id'] . "' - comando: '" . $sql . "' "));
		echo "alert('Atualizado com sucesso');\n";
		
        $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
        if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
		echo "window.top.location.href=wl;\n";
	}
	else {
		createLog(("tentou atualizar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível atualizar');\n";
	}
	echo "</script>\n";
}
else {
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	createLog(("editando " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);

	if(!empty($data['id'])) {
?>
<style type="text/css">
.sampleedit{padding:5px 0;}
.sampleedit .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var save<?php echo str_replace("-", "", $_GET['mod']); ?> = function (){
	var erro = 0;
	var msg = '';
<?php
$campos = array("name" => "Nome",
                "email" => "Email",
                //"path" => "Caminho",
                "title" => "Título");
echo gerajQueryCheckForm($campos);

$campos = array("obs" => "OBS");
echo gerajQueryCkCheckForm($campos);
?>
	var active = $("input[name='active']:checked");
	if(active.val() == '' || active.val() == undefined) {
		erro++;
		msg += "Ativo<br />";
		active.focus().select();
		active.addClass('ui-state-error');
	}
	else { active.removeClass('ui-state-error'); }

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else { submitform('<?php echo $_GET['mod']; ?>'); }
};

$("html, body").animate({scrollTop:0}, '5000');
if(CKEDITOR.instances['obs']) { CKEDITOR.instances['obs'].destroy(true); };
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="edit<?php echo str_replace("-", "", $_GET['mod']); ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "name", "Nome", "75", $data['name'], "");
echo insertField("text", "email", "Email", "75", $data['email'], "");
echo insertFieldViewAddEdit("path", "Caminho", "<div id='path'><noscript><p>Por favor habilite o suporte a JavaScript.</p></noscript></div>");
echo insertField("text", "title", "Título", "75", $data['title'], "");
echo insertField("textarea", "obs", "OBS", "cols='85' rows='24' ", $data['obs'], "");
echo insertField("radio", "active", "Ativo", "", array("S" => "Sim","N" => "N&atilde;o"), $data['active']);
echo insertField("vazio", "", "", "2", "", "");
echo insertField("hidden", "id", "", "", $data['id'], "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="save<?php echo str_replace("-", "", $_GET['mod']); ?>();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceladdpage('<?php echo str_replace("-", "", $_GET['mod']); ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");

$imgfolder="img/dogs/";
$inifolder=dirname(__FILE__) . '/../../' . $imgfolder;
if(!is_dir($inifolder)){ @mkdir($inifolder, 0777, true); }
@chmod($inifolder, 0777);

$fullfolder="";
$foldertocreate="";

if(!empty($data['path'])) {
	$dirnameimg = explode("/". $imgfolder, dirname($data['path']));
	$dirname = explode("/", $dirnameimg[1]);
	$tdirname = count($dirname);
	
	for($i=0; $i < $tdirname; $i++) {
		$fullfolder .= $dirname[$i] . "/";
		$foldertocreate = $inifolder . $fullfolder;
		
		if(!is_dir($foldertocreate)){ @mkdir($foldertocreate, 0777, true); }
		@chmod($foldertocreate, 0777);
	}
}
else {
	/*------------------------------------------------------------*/
	$tempFile = time(); // temp file name
	$filename = md5($tempFile.uniqid("IMAGE")); // unique name to the file
	$numfolders=5;
	for($i=1; $i<=$numfolders; $i++) {
		$fullfolder .= substr($filename, 0, $i) . "/";
		$foldertocreate = $inifolder . $fullfolder;

		if(!is_dir($foldertocreate)){ @mkdir($foldertocreate, 0777, true); }
		@chmod($foldertocreate, 0777);
	}
	/*------------------------------------------------------------*/
}
?>
	</table>
	<script charset='<?php echo JSCHARSET; ?>' type="text/javascript">
		$(document).ready(function(){
			addCssToHead('<?php echo $_SESSION["SITE_IPATH"]; ?>/css/fileuploader.css');
			addJsToHead('<?php echo $_SESSION["SITE_IPATH"]; ?>/js/fileuploader.js');
            addCssToHead('<?php echo $_SESSION["SITE_IPATH"]; ?>/css/jquery.Jcrop.min.css');
            addJsToHead('<?php echo $_SESSION["SITE_IPATH"]; ?>/js/jquery.Jcrop.min.js');
            
			var createUploader = function(inputname, dirname){
				var uploader = new qq.FileUploader({
					element: document.getElementById('path'),
					action: '<?php echo $_SESSION["SITE_IPATH"]; ?>/inc/upload.php',
					allowedExtensions: ['jpg','jpeg','png'],
					multiple: false,
					params: {
						inputname : inputname,
						uploadpath : "<?php echo $imgfolder; ?>",
						fullfolder : "<?php echo $fullfolder; ?>"
					},
					onSubmit: function(id, fileName){
						running++;
						$("#"+inputname).append(loading('aguarde'));
					},
					onComplete : function(id, fileName){
						running--;
						$("#"+inputname+" .qq-upload-button").hide(); // when is not multiple files 'hide'
						$("#"+inputname+" .qq-upload-drop-area").remove(); // when is not multiple files 'hide'

						if(running==0) {
							if($("#"+inputname+" .qq-upload-list").length) { $("#"+inputname+" .qq-upload-list").remove(); }
							$.get('../inc/load-image-crop.php', {dir:dirname}, function(data){
								$("#"+inputname).append(data);
								$("#"+inputname+" .qq-upload-list").slideDown('5000').fadeIn('3000');
								if($("#loading").length) { $("#loading").slideUp('5000').fadeOut('3000'); }

                                callJCrop(inputname);

                                $("#" + inputname + " .button").button();
							});
						}
					},
					debug: false
				});
			};
			var running = 0;
			var inputname = 'path';
			var dirname = "<?php echo '/' . $imgfolder . $fullfolder; ?>";
			createUploader(inputname, dirname);
			
<?php
if(!empty($data['path'])) {
	$ccifolder = dirname($data['path']) . '/';
	$ccifile = array_reverse(explode("/", $data['path']));
?>
            $("#"+inputname+" .qq-upload-button").hide();
			var imgpreview  = "<div id='imgpreview'>";
				imgpreview += "<img style='border:1px dotted #DEDEDE;box-shadow:1px 1px #000000;cursor:pointer;height:160px;width:160px;' onclick='javascript:previewimage";
				imgpreview += '("<?php echo $_SESSION['SITE_IPATH'] . $data['path']; ?>");\' ';
				imgpreview += "src='<?php echo $_SESSION['SITE_IPATH'] . $data['path']; ?>' title='Ver imagem em tamanho original' />";
				imgpreview += "<br /><br /><br />";
				imgpreview += "<span onclick='changeCropImage(\"<?php echo $ccifolder; ?>\", \"<?php echo $ccifile[0]; ?>\");' class='qq-upload-change'>Mudar Foto</span>&nbsp;";
				imgpreview += "<span onclick='removeUploadedFile(this);' class='qq-upload-remove'>remover</span><br /><br />";
				imgpreview += "</div>";
			$("#path").after(imgpreview);
<?php
}
?>
		});
	</script>
</form>
<?php
        include_once '../js/ckeditor/ckeditor.php';
        $ckeditor = new CKEditor();
        $ckeditor->basePath = $_SESSION['SITE_IPATH'] . '/js/ckeditor/';
        $ckeditor->config = array('language' => "pt-br");

        $_SESSION['ckfinder_baseUrl'] = $_SESSION['SITE_IPATH'] . "/img/blog/";
        include_once '../js/ckfinder/ckfinder.php';

        $ckfinder = new CKFinder();
        $ckfinder->BasePath = $_SESSION['SITE_IPATH'] . '/js/ckfinder/'; // Note: the BasePath property in the CKFinder class starts with a capital letter.
        $ckfinder->config = array(
            'CheckDoubleExtension' => true,
            'language' => 'pt-br',
            'SecureImageUploads' => true
        );

        $ckfinder->SetupCKEditorObject($ckeditor);
        $ckeditor->replace('obs');
	}
	else {
?>
	<h1 style="text-align:center;"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> não encontrado na nossa base de dados</h1>
<?php
	}
}