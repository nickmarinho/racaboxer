<?php
$campo = !empty($_GET['campo']) ? $_GET['campo'] : '';
$valor = !empty($_GET['valor']) ? $_GET['valor'] : '';

if(!empty($campo) && !empty($valor)){
	$sql="SELECT id FROM " . $table . " WHERE ".$campo."='" . $valor . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	if($rows) { echo "S"; } // existe usuario
	else { echo "N"; } // não existe usuario
}
