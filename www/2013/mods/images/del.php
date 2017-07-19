<?php
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);
	if(!empty($data['path']) && is_file(dirname(__FILE__) . '/../../' . $data['path'])) {
        createLog(("removendo imagem do " . $table . " id '" . $_GET['id'] . "' - comando: 'unlink(" . $data['path'] . ");' "));
        unlink(dirname(__FILE__) . '/../..' . $data['path']);
	}
    
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
