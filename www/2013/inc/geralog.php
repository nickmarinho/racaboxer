<?php
include_once "../config.php";

if(!empty($_GET['log'])) {
	createLog(stripslashes($_GET['log']));
}
?>
