<?php
/* if is a new item to menu */
if(isset($_GET['new'])) {
	/* is have a parent or not */
	$parent =  !empty($_GET['parent']) ? $_GET['parent'] : '0';
	$sql="INSERT INTO " . $table . " SET `mod`='" . $_GET['modn'] . "', `action`='" . $_GET['action'] . "', label='" . $_GET['label'] . "', link='" . $_GET['link'] . "', parent='" . $parent . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();

	createPermissions();

	echo "1";
}
else if(isset($_GET['edit'])) {
	/* if is to edit a existent */
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "';";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);

	if($data['mod'] <> $_GET['modn']) {
		/* if the mod is diferent of that stored in db before, then delete it to create the new one */
		$sql="DELETE FROM acl_permissions WHERE permKey LIKE '" . $data['mod'] . "_%'; ";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
	}

	createPermissions();

	$parent =  !empty($_GET['parent']) ? $_GET['parent'] : '0';
	$sql="UPDATE " . $table . " SET `mod`='" . $_GET['modn'] . "', `action`='" . $_GET['action'] . "', label='" . $_GET['label'] . "', link='" . $_GET['link'] . "', parent='" . $parent . "' WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();

	if($query) { echo "1"; }
	else { echo urlencode($sql); }
}
else {
    parse_str($_POST['page'], $pageOrder);

    foreach ($pageOrder['page'] as $key => $value) {
	    $sql="UPDATE " . $table . " SET sort='".$key."' WHERE id='".$value."'";
	    $conn = Db_Instance::getInstance();
	    $q = $conn->prepare($sql);
	    $q->execute();
    }
}

/**
 * This function create the permissions to the mod informed to be created
 * 
 * @global array $items
 */
function createPermissions() {
	/* checking if the var link is null or not :P */
	if(!empty($_GET['link'])) {
		$link=str_replace("/admin/?mod=", "", $_GET['link']);
		$items = array(
			$link . "_add" => ucfirst($link) . " - Adicionar",
			$link . "_changeactive" => ucfirst($link) . " - Mudar Status",
			$link . "_del" => ucfirst($link) . " - Remover",
			$link . "_edit" => ucfirst($link) . " - Editar",
			$link . "_export" => ucfirst($link) . " - Exportar",
			$link . "_list" => ucfirst($link) . " - Listar",
			$link . "_view" => ucfirst($link) . " - Visualizar",
		);
	}

	foreach ($items as $k => $v) {
		$sql="SELECT id FROM acl_permissions WHERE permKey='" . $k . "'; ";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$rows = $q->rowCount();

		if($rows <= 0) {
			$sql="INSERT INTO acl_permissions SET permKey='" . $k . "', permName='" . $v . "'; ";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$q->execute();
		}
	}
}