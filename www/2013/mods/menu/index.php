<?php
$included = strtolower(realpath(__FILE__)) != strtolower(realpath($_SERVER['SCRIPT_FILENAME']));

if(!$included) {
	session_start();
}

if(!empty($_SESSION['userLogged']) && $_SESSION['userLogged'] <> true) {
	$_SESSION['mess'] = "Por favor, faça seu login para ver essa página";
	$_SESSION['mod'] = "menu";

	echo "<script type='text/javascript'>";
	echo "location='/admin/?login&url=" . $url . "';";
	echo "</script>";
	exit;
}

$_SESSION['mod'] = "menu";
$table="`menu`";
$querystring=querystringConstruct();
$modaction = !empty($_GET['a']) ? $_GET['a'] : 'list';
$acl = new Acl($_SESSION['userId']);
$rPerms = $acl->perms;
$key="";
$errPage="../inc/errpermission.php";
$errPageAjax="../inc/errpermissionajax.php";

if($_SESSION['userId'] == '1'){ // só o administrador geral vai poder ver esse menu
	switch($modaction) {
		case 'a':
			$key="add";
			include_once $key . ".php";
		break;

		case 'd':
			$key="del";
			include_once $key . ".php";
		break;

		case 'e':
			$key="edit";
			include_once $key . ".php";
		break;

		case 'list':
			$key="list";
			include_once $key . ".php";
		break;

		case 's':
			$key="save";
			include_once $key . ".php";
		break;

		default:
			$key="list";
			include_once $key . ".php";
		break;
	}
}
else { include_once $errPage; }
/*
else {
	switch($modaction) {
		case 'a':
			$key="add";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPage; }
		break;

		case 'd':
			$key="del";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPageAjax; }
		break;

		case 'e':
			$key="edit";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPage; }
		break;

		case 'list':
			$key="list";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPage; }
		break;

		case 's':
			$key="save";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPage; }
		break;

		default:
			$key="list";
			if($acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
			else { include_once $errPage; }
		break;
	}
}
*/