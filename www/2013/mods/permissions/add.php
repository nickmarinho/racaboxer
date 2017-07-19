<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$permKey = $_POST['permKey'];
	$permName = $_POST['permName'];
	$totalKeys = count($permKey);

	for($i=0; $i<$totalKeys; $i++) {
		if(!empty($permKey[$i]) && !empty($permName[$i])) {
			$sql="INSERT INTO " . $table . " SET
					permKey='" . $permKey[$i] . "',
					permName='" . $permName[$i] . "';";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$id = $q->execute();
			if($id) { createLog(("cadastrou novo " . $table . " id '" . $id . "' - comando: '" . $sql . "' ")); }
			else { createLog(("tentou cadastrar " . $table . " sem sucesso - comando: '" . $sql . "' ")); }
		}
	}

	echo "alert('Dados salvos com sucesso');\n";
		
    $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
    if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
	echo "window.top.location.href=wl;\n";
	echo "</script>\n";

	exit;
}
else {
?>
<style type="text/css">
#content .sampleedit{width:700px;}
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var pos = $(".sampleedit:last-child tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="addpermissions" method="post" target="addeditifrm">
	<table id="perm_0" class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
		<tr>
			<td class="label ui-state-default"><label for="permKey">Chave: </label></td>
			<td><label><input type="text" value="" size="75" name="permKey[]" id="permKey" class="ui-state-default ui-corner-all"></label></td>
		</tr>
		<tr>
			<td class="label ui-state-default"><label for="permName">Nome: </label></td>
			<td><label><input type="text" value="" size="75" name="permName[]" id="permName" class="ui-state-default ui-corner-all"></label></td>
		</tr>
	</table>
</form>

	<br />
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset" style="border:0;">
<?php
echo insertField("vazio", "", "", "2", "", "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="savepermissions();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceladdpage('permissions');" value="Cancelar" title="Clique para Cancelar" />
				<input class="button" type="button" onclick="morepermissions();" value="Mais &raquo;" title="Clique para Adicionar mais campos" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
<?php
}