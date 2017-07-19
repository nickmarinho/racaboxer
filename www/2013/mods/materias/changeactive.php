<?php
$sql="SELECT active FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$query = $q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);
$newactive = "";

if($data['active'] == 'S') { $newactive = "N"; }
else { $newactive = "S"; }

$sql="UPDATE " . $table . " SET active='" . $newactive . "', mdate='" . MDATE . "' WHERE id='" . $_GET['id'] . "'; ";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$query = $q->execute();

if($query) {
	createLog(("alterou status do " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
	echo $data['active'];
}
else {
	createLog(("tentou alterar o status do " . $table . " id '" . $_GET['id'] . "' sem sucesso - comando: '" . $sql . "' "));
	echo $query;
}
