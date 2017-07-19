<?php
$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);

if(!empty($data['link']) && $_GET['removepermissions'] == 'SIM') {
	$link=str_replace("/admin/?mod=", "", $data['link']);
	$sql="DELETE FROM acl_permissions WHERE permKey LIKE '" . $link . "_%'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
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
