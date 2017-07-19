<?php
if(!empty($_GET['id'])) {
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	createLog(("editando " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);

	if(!empty($data['id'])) {
?>
<style type='text/css'>
#editmenu .sampleedit{width:100%;}
.sampleedit{padding:5px 0;}
.sampleedit .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<form>
<table class='sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset'>
<?php
$sqlm="SELECT * FROM menu WHERE parent='0' ORDER BY sort, label, parent";
$connm = Db_Instance::getInstance();
$qm = $connm->prepare($sqlm);
$qm->execute();
$rowsm = $qm->rowCount();
$parent=array();
if($rowsm) {
    while($datam = $qm->fetch(PDO::FETCH_ASSOC)) { $parent[$datam['id']] = $datam['label']; }
}

echo insertField("text", "mod", "Módulo", "75", $data['mod'], "");
echo insertField("text", "action", "Ação", "75", $data['action'], "");
echo insertField("text", "label", "Título", "75", $data['label'], "");
echo insertField("text", "link", "Url", "75", $data['link'], "");
echo insertField("select", "parent", "Pai", "", $parent, $data['parent']);
echo insertField("vazio", "", "", "2", "", "");
echo insertField("hidden", "id", "", "", $data['id'], "");
?>
    <tr>
	<td style='text-align:center;' colspan='2'>
		<input type='button' title='Clique para Salvar' value='Salvar' onclick='saveemenu();' class='button'>
		<input type='button' title='Clique para Cancelar' value='Cancelar' onclick='canceleditmenu();' class='button'>
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
	<h1 style="text-align:center;">menu não encontrado na nossa base de dados</h1>
<?php
	}
}