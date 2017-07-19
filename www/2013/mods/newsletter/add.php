<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . $_SESSION['SITE_IPATH'] . '/' . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$emails="";
	if(!empty($_POST['emails'])) {
		$emails=" emails='" . serialize($_POST['emails']) . "', ";
	}

	$sql="INSERT INTO " . $table . " SET
			$emails
			title='" . $_POST['title'] . "',
			content='" . $_POST['tcontent'] . "',
			cdate='" . CDATE . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$id = $q->execute();
	
	if($id) {
		createLog(("cadastrou novo " . $table . " id '" . $id . "' - comando: '" . $sql . "' "));
		echo "alert('Cadastrado com sucesso');\n";
		echo "var wl='" . $_SESSION['SITE_IPATH'] . "/admin/?mod=" . $_GET['mod'] . "&done=1';\n";
		echo "window.top.location.href=wl;\n";
	}
	else {
		createLog(("tentou cadastrar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível cadastrar');\n";
		echo "alert('".urlencode($sql)."')";
	}
	echo "</script>\n";
}
else {
?>
<style type="text/css">
.sampleedit{padding:5px 0;}
.sampleedit .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var save<?php echo $_GET['mod']; ?> = function (){
	var erro = 0;
	var msg = '';

<?php
$campos = array("title" => "Título");
echo gerajQueryCheckForm($campos);

$campos = array("tcontent" => "Conteúdo");
echo gerajQueryCkCheckForm($campos);
?>

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else { submitform('<?php echo $_GET['mod']; ?>'); }
};

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');

if(CKEDITOR.instances['tcontent']) { CKEDITOR.instances['tcontent'].destroy(true); };
</script>
<link rel="stylesheet" href="<?php echo $_SESSION["SITE_IPATH"]; ?>/css/fileuploader.css" type="text/css" />
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="add<?php echo $_GET['mod']; ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "title", "Título", "75", "", "");
echo insertField("textarea", "tcontent", "Conteúdo", "cols='85' rows='24' ", "", "");
echo insertFieldViewAddEdit("emails", "Emails", "<div id='emails'><noscript><p>Por favor habilite o suporte a JavaScript.</p></noscript></div>");
echo insertField("vazio", "", "", "2", "", "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="save<?php echo $_GET['mod']; ?>();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceladdpage('<?php echo $_GET['mod']; ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
</form>
<?php
	include_once '../js/ckeditor/ckeditor.php';
	$ckeditor = new CKEditor();
	$ckeditor->basePath = $_SESSION['SITE_IPATH'] . '/js/ckeditor/';
	$ckeditor->config = array('language' => "pt-br");

	$_SESSION['ckfinder_baseUrl'] = $_SESSION['SITE_IPATH'] . "/img/newsletter/";
	include_once '../js/ckfinder/ckfinder.php';

	$ckfinder = new CKFinder();
	$ckfinder->BasePath = $_SESSION['SITE_IPATH'] . '/js/ckfinder/'; // Note: the BasePath property in the CKFinder class starts with a capital letter.
	$ckfinder->config = array(
		'CheckDoubleExtension' => true,
		'language' => 'pt-br',
		'SecureImageUploads' => true
	);

	$ckfinder->SetupCKEditorObject($ckeditor);
	$ckeditor->replace('tcontent');
}