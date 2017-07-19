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

	$sqlaut="SELECT name FROM `users` WHERE id='" . $data['author'] . "'; ";
	$connaut = Db_Instance::getInstance();
	$qaut = $connaut->prepare($sqlaut);
	$qaut->execute();
	$dataaut = $qaut->fetch(PDO::FETCH_ASSOC);
	$author = $dataaut['name'];
	
    echo insertFieldView("author", "<b>Autor</b>", $author);
    echo insertFieldView("title", "<b>Título</b>", $data['title']);
    echo insertFieldView("meta_keywords", "<b>Meta Keywords</b>", $data['meta_keywords']);
    echo insertFieldView("meta_description", "<b>Meta Description</b>", $data['meta_description']);
    echo insertFieldView("url", "<b>URL</b>", $data['url']);
	echo insertFieldView("post", "<b>Postagem</b>", "<div class='preview view'>" . stripslashes($data['post']) . "</div>");
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
