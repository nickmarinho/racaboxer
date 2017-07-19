<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="UPDATE " . $table . " SET
                author='" . $_POST['author'] . "',
                title='" . $_POST['title'] . "',
                meta_keywords='" . $_POST['meta_keywords'] . "',
                meta_description='" . $_POST['meta_description'] . "',
                url='" . $_POST['url'] . "',
                post='" . $_POST['post'] . "',
                active='" . $_POST['active'] . "',
                mdate='" . MDATE . "'
        WHERE id='" . $_POST['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();

	if($query) {
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
$campos = array("author" => "Autor",
                "title" => "Título",
                "meta_keywords" => "Keywords",
                "meta_description" => "Description",
                "url" => "URL");
echo gerajQueryCheckForm($campos);

$campos = array("post" => "Post");
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
if(CKEDITOR.instances['post']) { CKEDITOR.instances['post'].destroy(true); };
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="edit<?php echo str_replace("-", "", $_GET['mod']); ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
$sqlaut="SELECT * FROM `users` WHERE active='S'; ";
$connaut = Db_Instance::getInstance();
$qaut = $connaut->prepare($sqlaut);
$qaut->execute();
$author = array();
while($dataaut = $qaut->fetch(PDO::FETCH_ASSOC)) {
	if(!in_array($dataaut['id'], $author)) {
		$author[$dataaut['id']] = $dataaut['name'];
	}
}

echo insertField("select", "author", "Autor", "", $author, $data['author']);
echo insertField("text", "title", "Título", "75", $data['title'], "");
echo insertField("text", "meta_keywords", "Keywords", "75", $data['meta_keywords'], "");
echo insertField("text", "meta_description", "Description", "75", $data['meta_description'], "");
echo insertField("text", "url", "Url", "75", $data['url'], "");
echo insertField("textarea", "post", "Postagem", "cols='85' rows='24' ", stripslashes($data['post']), "");
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
?>
	</table>
</form>
<?php
        include_once '../js/ckeditor/ckeditor.php';
        $ckeditor = new CKEditor();
        $ckeditor->basePath = $_SESSION['SITE_IPATH'] . '/js/ckeditor/';
        $ckeditor->config = array('language' => "pt-br");

        $_SESSION['ckfinder_baseUrl'] = $_SESSION['SITE_IPATH'] . "/img/doacoes/";
        include_once '../js/ckfinder/ckfinder.php';

        $ckfinder = new CKFinder();
        $ckfinder->BasePath = $_SESSION['SITE_IPATH'] . '/js/ckfinder/'; // Note: the BasePath property in the CKFinder class starts with a capital letter.
        $ckfinder->config = array(
            'CheckDoubleExtension' => true,
            'language' => 'pt-br',
            'SecureImageUploads' => true
        );

        $ckfinder->SetupCKEditorObject($ckeditor);
        $ckeditor->replace('post');
	}
	else {
?>
	<h1 style="text-align:center;"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> não encontrado na nossa base de dados</h1>
<?php
	}
}