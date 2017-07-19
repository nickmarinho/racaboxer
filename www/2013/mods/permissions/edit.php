<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="UPDATE " . $table . " SET
              permKey='" . $_POST['permKey'] . "',
              permName='" . $_POST['permName'] . "'
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
	exit;
}
else {
	if(!empty($_GET['id'])) {
		$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
		createLog(("editando " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$query = $q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if(!empty($data['id'])) {
?>
<style type="text/css">
#content .sampleedit{width:700px;}
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var saveusuariosgrupos = function (){
	var erro = 0;
	var msg = '';
	var permKey = $("#permKey");
	var permName = $("#permName");

	if(permKey.val() == '') {
		erro++;
		msg += "Chave<br />";
		permKey.focus().select();
		permKey.addClass('ui-state-error');
	}
	else { permKey.removeClass('ui-state-error'); }

	if(permName.val() == '') {
		erro++;
		msg += "Nome<br />";
		permName.focus().select();
		permName.addClass('ui-state-error');
	}
	else { permName.removeClass('ui-state-error'); }

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else { submitform('permissions'); }
};

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="editpermissions" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "permKey", "Chave", "75", $data['permKey'], "");
echo insertField("text", "permName", "Nome", "75", $data['permName'], "");
echo insertField("vazio", "", "", "2", "", "");
echo insertField("hidden", "id", "", "", $data['id'], "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="savepermissions();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceleditpage('permissions');" value="Cancelar" title="Clique para Cancelar" />
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
	<h1 style="text-align:center;">permissions não encontrado na nossa base de dados</h1>
<?php
		}
	}
}