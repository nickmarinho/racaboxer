<style type="text/css">
.preview {margin:0 auto;padding:0;width:99.9%;}
.preview tbody td:first-child{background:#DEDEDE;text-align:right;padding-right:5px;width:120px;}
.view {border:1px dotted #DEDEDE;margin:0 auto;padding:10px;width:95%;}
</style>
<div class="ui-widget ui-widget-content ui-corner-all">
	<br />
	<fieldset>
		<legend>Visualizando <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?></legend>
		<div id="printcontent">
			<table class="preview">
				<tbody>
<?php
if(!empty($_GET['id'])) {
	$sql = "SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "' ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);

	if($data['cdate'] <> "0000-00-00 00:00:00"){ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); }
	else{ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); }

	echo insertFieldView("data", "Data", $cdatenow);
    echo insertFieldView("name", "<b>Name</b>", $data['name']);
    echo insertFieldView("email", "<b>Email</b>", $data['email']);
}
?>
				</tbody>
			</table>
		</div>
		<center>
			<input type="button" class="button" onclick="printhis('Dados de <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?>');" value="Imprimir" />&nbsp;
			<input type="button" class="button" onclick="$('#viewpage_<?php echo $_GET['mod']; ?>').dialog('close');" value="Fechar" /><br /><br />
			<b>Você pode fechar a janela apertando ESC</b>
		</center>
	</fieldset>
	<br />
</div>
