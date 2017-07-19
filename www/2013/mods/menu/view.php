<style type="text/css">
.preview {margin:0 auto;padding:0;width:99.9%;}
.preview tbody td:first-child{background:#DEDEDE;text-align:right;padding-right:5px;width:120px;}
.view {border:1px dotted #DEDEDE;margin:0 auto;padding:10px;width:95%;}
</style>
<div class="ui-widget ui-widget-content ui-corner-all">
	<br />
	<fieldset>
		<legend>Visualizando Dados de Banner - ID: <?php echo $_GET['id']; ?></legend>
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

	if($data['mdate'] <> "0000-00-00 00:00:00"){ $mdatenow = strtotime($data['mdate']); $mdatenow = date('d/m/Y g:ia', $mdatenow); }
	else{ $mdatenow = "Nunca"; }

	$active = $data['active'] == 'S' ? 'Sim' : 'Não';

	echo insertFieldView("path", "<b>Imagem</b>", "<img style='border:1px dotted #DEDEDE;box-shadow:1px 1px #000000;cursor:pointer;height:16px;width:16px;' onclick='javascript:previewimage(\"" . $_SESSION['SITE_IPATH'] . '/' . $data['path'] . "\");' src='" . $_SESSION['SITE_IPATH'] . '/' . $data['path'] . "' title='Ver imagem em tamanho original' />");
	echo insertFieldView("title", "<b>Título</b>", $data['title']);
	echo insertFieldView("url", "<b>Url</b>", $data['url']);
	echo insertFieldView("btext", "<b>Texto</b>", "<div class='preview view'>" . $data['btext'] . "</div>");
	echo insertFieldView("width", "<b>Largura</b>", $data['width']);
	echo insertFieldView("height", "<b>Altura</b>", $data['height']);
	echo insertFieldView("position", "<b>Posição</b>", $data['position']);
	echo insertFieldView("active", "<b>Ativo</b>", $active);
	echo insertFieldView("cdate", "<b>Cadastrado</b>", $cdatenow);
	echo insertFieldView("mdate", "<b>Alterado</b>", $mdatenow);

}
?>
				</tbody>
			</table>
		</div>
		<center>
			<input type="button" class="button" onclick="printhis('Dados de Banner - ID: <?php echo $_GET['id']; ?>');" value="Imprimir" />&nbsp;
			<input type="button" class="button" onclick="$('#viewpage_<?php echo $_GET['mod']; ?>').dialog('close');" value="Fechar" /><br /><br />
			<b>Você pode fechar a janela apertando ESC</b>
		</center>
	</fieldset>
	<br />
</div>
