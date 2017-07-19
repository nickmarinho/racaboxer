				<h1 id="pagetitle"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?></h1>
				<div id="addedit"></div>
				<table class='list ui-widget ui-widget-content ui-corner-all ui-helper-reset' id="grid">
					<thead class="ui-widget-header ui-corner-all">
						<tr>
							<th class="ui-state-default ui-corner-tl ui-corner-tr" style="text-align:left;" colspan="12">
								<a class="button" href="#" onclick="javascript:exportpage('log','csv');" title="Exportar para CSV">Exportar (CSV)</a>
								<a class="button" href="#" onclick="javascript:exportpage('log','xls');" title="Exportar para XLS">Exportar (XLS)</a>
							</th>
						</tr>
						<tr>
<?php
$thfields = array(
	"id" => "35_ID",
	"name" => "_Nome",
	"empty_0" => "_Ação",
	"cdate" => "120_Cadastrado",
	"empty_1" => "19_",
);

include_once "settingsorder.php";
?>
						</tr>
					</thead>
					<tbody id="list">
<?php
	$limitperpage = ''; $numtoshow = ''; $page = ""; $limitnum = ""; $sortname = ""; $sortorder = ""; $where = "";
	$limitperpage = '12';
	$numtoshow = '5';
	$initialname = !empty($_GET['initialname']) ? $_GET['initialname'] : '';
	$initialorder = !empty($_GET['initialorder']) ? $_GET['initialorder'] : '';
	if(!empty($initialname) && !empty($initialorder)) {
		$where = " where " . $initialname . " LIKE '" . $initialorder . "%' ";
	}
	$sortname = !empty($_GET['sortname']) ? $_GET['sortname'] : 'id';
	$sortorder = !empty($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';
	if(!empty($sortname) && !empty($sortorder)) {
		$order = " ORDER BY " . $sortname . " " . $sortorder . " ";
	}
	
	$sql="SELECT * FROM " . $table . $where . " ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	$result = $q->fetchAll();
	$rows = count($result);
	$last = "";

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

			$sqlus = "SELECT name FROM users WHERE id='" . $data['id_user'] . "'; ";
			$connus = Db_Instance::getInstance();
			$qus = $connus->prepare($sqlus);
			$qus->execute();
			$dataus = $qus->fetch(PDO::FETCH_ASSOC);
			$data['name'] = $dataus['name'];
?>
						<tr id='list_<?php echo $c; ?>' class="<?php echo $over; ?>">
							<td style='text-align:center;'><?php echo $data['id']; ?></td>
							<td><?php echo $data['name']; ?></td>
							<td><?php echo $data['action']; ?></td>
							<td>
<?php
			if($data['cdate'] <> "0000-00-00 00:00:00"){ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); echo $cdatenow; }
			else{ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); echo $cdatenow; }
?>
							</td>
							<td class='tdpag-icons' onclick="javascript:removepage('log','<?php echo $data['id']; ?>','<?php echo $c; ?>');">
								<div class="ui-state-default ui-corner-all pag-icons"><span class="ui-icon ui-icon-trash" title="Remover (<?php echo $data['id']; ?>)"></span></div>
							</td>
						</tr>
<?php
			$c++;
		}
	}
	else {
?>
						<tr id="list_empty">
							<td colspan='12' style='text-align:center;'><a class="button">nada encontrado</a></td>
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
	
	echo "	<center>\n";
	
	include_once "qtyperpage.php";

	echo "	</center>\n";
	echo "</div>\n";
	echo "<!-- end pagination & filters -->\n";
?>
