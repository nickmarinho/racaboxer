<?php
	$sql="DELETE FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	
	if($query) {
		createLog(("deletou " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
		echo "1";
	}
	else {
		createLog(("tentou remover " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo $query;
	}
