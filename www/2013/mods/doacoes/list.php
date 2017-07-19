                <script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
$(document).ready(function(){
	<?php if(isset($_GET['new'])) { echo "addpage('" . $_GET['mod'] . "');\n"; } ?>
	<?php if(isset($_GET['edit']) && !empty($_GET['id'])) { echo "editpage('" . $_GET['mod'] . "', '" . $_GET['id'] . "');\n"; } ?>
});
				</script>
				<h1 id="pagetitle"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?></h1>
				<div id="addedit"></div>
				<table class='list ui-widget ui-widget-content ui-corner-all ui-helper-reset' id="grid">
					<thead class="ui-widget-header ui-corner-all">
						<tr>
							<th class="ui-state-default ui-corner-tl ui-corner-tr" style="text-align:left;" colspan="12">
								<a class="button" href="#" onclick="javascript:addpage('<?php echo $_GET['mod']; ?>');" title="Adicionar">Adicionar</a>
								<a class="button" href="#" onclick="javascript:exportpage('<?php echo $_GET['mod']; ?>','csv');" title="Exportar para CSV">Exportar (CSV)</a>
								<a class="button" href="#" onclick="javascript:exportpage('<?php echo $_GET['mod']; ?>','xls');" title="Exportar para XLS">Exportar (XLS)</a>
								<a class="button" href="#" onclick="javascript:exportpage('<?php echo $_GET['mod']; ?>','sql');" title="Exportar para SQL">Exportar (SQL)</a>
							</th>
						</tr>
						<tr>
<?php
$thfields = array(
	"id" => "35_ID",
	"title" => "_Título",
	"cdate" => "120_Cadastrado",
	"mdate" => "120_Alterado",
	"active" => "45_Ativo",
	"ver" => "25_Ver",
	"empty_0" => "19_",
	"empty_1" => "19_",
	"empty_2" => "19_",
);

include_once "settingsorder.php";
?>
						</tr>
					</thead>
					<tbody id="list">
<?php
	$where = " WHERE 1 ";

	$limitperpage = ''; $numtoshow = ''; $page = ""; $limitnum = ""; $sortname = ""; $sortorder = "";
	$limitperpage = '12';
	$numtoshow = '5';
	$initialname = !empty($_GET['initialname']) ? $_GET['initialname'] : '';
	$initialorder = !empty($_GET['initialorder']) ? $_GET['initialorder'] : '';
	if(!empty($initialname) && !empty($initialorder)) {
		$where .= " AND " . $initialname . " LIKE '" . $initialorder . "%' ";
	}
	$sortname = !empty($_GET['sortname']) ? $_GET['sortname'] : 'id';
	$sortorder = !empty($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';
	if(!empty($sortname) && !empty($sortorder)) {
		$order = " ORDER BY " . $sortname . " " . $sortorder . " ";
	}
	
	$sql="SELECT * FROM " . $table . $where . " ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	$last="";

	if($rows > 0) {
		$page = !empty($_GET['page']) ? $_GET['page'] : 1;
		$limitnum = !empty($_GET['c']) ? $_GET['c'] : $limitperpage;
		$last = ceil($rows/$limitnum);
	
		if($page < 1){ $page = 1; }
		elseif($page > $last){ $page = $last; }
	
		$limit = " LIMIT " . ($page - 1) * $limitnum . ',' . $limitnum . " ";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql . $order . $limit);
		$q->execute();
		$_SESSION['sqltoexport'] = $sql . $order . $limit;
		
		$c=0;
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			if(($c % 2) == 0){ $over = "overa"; }
			else{ $over = "overb"; }
?>
						<tr id='list_<?php echo $c; ?>' class="<?php echo $over; ?>">
							<td style='text-align:center;'><?php echo $data['id']; ?></td>
							<td style='cursor:pointer;' onclick="javascript:editpage('<?php echo $_GET['mod']; ?>', '<?php echo $data['id']; ?>');" title="Clique para editar"><?php echo $data['title']; ?></td>
							<td>
<?php
			if($data['cdate'] <> "0000-00-00 00:00:00"){ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); echo $cdatenow; }
			else{ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); echo $cdatenow; }
?>
							</td>
							<td>
<?php
			if($data['mdate'] <> "0000-00-00 00:00:00"){ $cdatenow = strtotime($data['mdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); echo $cdatenow; }
			else{ echo "Nunca"; }
?>
							</td>
							<td class='tdpag-icons' id="active_<?php echo $c; ?>" onclick="javascript:changeactive('<?php echo $_GET['mod']; ?>', '<?php echo $data['id']; ?>', '<?php echo $c; ?>');">
								<div class="ui-state-default ui-corner-all img-icons"><span class="ui-icon ui-icon-<?php
										if($data['active'] == "S") { echo "check"; }
										else if($data['active'] == "N") { echo "cancel"; }
									?>" title="Mudar Status (<?php echo $data['id']; ?>)"></span></div>
							</td>
							<td class='tdpag-icons'>
								<a href="http://<?php echo $_SERVER['SERVER_NAME'] . '/doacoes.php?id=' . $data['id']; ?>" target="_blank" title="Clique para ver no site">
									<div class="ui-state-default ui-corner-all img-icons"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span></div>
								</a>
							</td>
							<td class='tdpag-icons' onclick="javascript:viewpage('<?php echo $_GET['mod']; ?>', '<?php echo $data['id']; ?>');">
								<div class="ui-state-default ui-corner-all img-icons"><span class="ui-icon ui-icon-newwin" title="Visualizar Completo (<?php echo $data['id']; ?>)"></span></div>
							</td>
							<td class='tdpag-icons' onclick="javascript:editpage('<?php echo $_GET['mod']; ?>', '<?php echo $data['id']; ?>');">
								<div class="ui-state-default ui-corner-all img-icons"><span class="ui-icon ui-icon-pencil" title="Editar (<?php echo $data['id']; ?>)"></span></div>
							</td>
							<td class='tdpag-icons' onclick="javascript:removepage('<?php echo $_GET['mod']; ?>','<?php echo $data['id']; ?>','<?php echo $c; ?>');">
								<div class="ui-state-default ui-corner-all img-icons"><span class="ui-icon ui-icon-trash" title="Remover (<?php echo $data['id']; ?>)"></span></div>
							</td>
						</tr>
<?php
			$c++;
		}
	}
	else {
?>
						<tr id="list_empty">
							<td colspan='12' style='text-align:center;'><a class="button" onclick="javascript:addpage('<?php echo $_GET['mod']; ?>');">Nenhum Registro, Clique Aqui para Adicionar</a></td>
						</tr>
<?php
	}
?>
					</tbody>
				</table>
<?php
	echo "<!-- pagination & filters -->\n";
	echo "<div id='paginationdiv' class='ui-widget-header ui-corner-all'>\n";
	
	include_once "pagination-admin.php";
	$pagination = Pagination::getInstance();
	$pagination->settotalrows($rows);
	
	if($last > 1) {
		//$pagination = new Pagination();
		$pagination->setpagenum($page);
		$pagination->setlastpage($last);
		$pagination->setqtyperpage($limitnum);
		$pagination->setnumstoshow($numtoshow);
	}
	$pag = $pagination->create();
	if(!empty($pag)) { echo $pag; }
	
	$names = array(
		"author" => "Autor",
		"title" => "Título"
	);

	echo "	<center>\n";

	include_once "initialletters.php";
	include_once "qtyperpage.php";

	echo "	</center>\n";
	echo "</div>\n";
	echo "<!-- end pagination & filters -->\n";
?>
