<?php
	$sql="DELETE FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	
	if($query) {
		$sqlaur = sprintf("DELETE FROM `acl_users_roles` WHERE `roleId` = %u",$_GET['id']);
		$connaur = Db_Instance::getInstance();
		$qaur = $connaur->prepare($sqlaur);
		$qaur->execute();

		$sqlarp = sprintf("DELETE FROM `acl_roles_permissions` WHERE `roleId` = %u",$_GET['id']);
		$connarp = Db_Instance::getInstance();
		$qarp = $connarp->prepare($sqlarp);
		$qarp->execute();

		createLog(("deletou " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
		echo "1";
	}
	else {
		createLog(("tentou remover " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo $query;
	}
