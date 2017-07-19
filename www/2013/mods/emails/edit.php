<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="UPDATE " . $table . " SET
                name='" . $_POST['name'] . "',
                email='" . $_POST['email'] . "',
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
$campos = array("name" => "Name",
                "email" => "Email");
echo gerajQueryCheckForm($campos);
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
if(CKEDITOR.instances['rss']) { CKEDITOR.instances['rss'].destroy(true); };
if(CKEDITOR.instances['post']) { CKEDITOR.instances['post'].destroy(true); };
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="edit<?php echo str_replace("-", "", $_GET['mod']); ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "name", "Name", "75", $data['name'], "");
echo insertField("text", "email", "Email", "75", $data['email'], "");
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
	}
	else {
?>
	<h1 style="text-align:center;"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> não encontrado na nossa base de dados</h1>
<?php
	}
}