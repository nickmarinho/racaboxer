<style type='text/css'>
#addmenu .sampleedit{width:100%;}
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

echo insertField("text", "mod", "Módulo", "75", "", "");
echo insertField("text", "action", "Ação", "75", "list", "");
echo insertField("text", "label", "Título", "75", "", "");
echo insertField("text", "link", "Url", "75", "/admin/?mod=menu", "");
echo insertField("select", "parent", "Pai", "", $parent, "");
echo insertField("vazio", "", "", "2", "", "");
?>
    <tr>
	<td style='text-align:center;' colspan='2'>
	    <input type='button' title='Clique para Salvar' value='Salvar' onclick='savemenu();' class='button'>
	    <input type='button' title='Clique para Cancelar' value='Cancelar' onclick='canceladdmenu();' class='button'>
	</td>
    </tr>
<?php
echo insertField("vazio", "", "", "2", "", "");
?>
</table>
</form>