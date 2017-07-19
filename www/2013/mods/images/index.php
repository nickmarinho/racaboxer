<?php
$included = strtolower(realpath(__FILE__)) != strtolower(realpath($_SERVER['SCRIPT_FILENAME']));

if(!$included) {
	session_start();
}

if(!empty($_SESSION['userLogged']) && $_SESSION['userLogged'] <> true) {
	$_SESSION['mess'] = "Por favor, faça seu login para ver essa página";
	$_SESSION['mod'] = "images";

	echo "<script type='text/javascript'>";
	echo "location='/admin/?login&url=" . $url . "';";
	echo "</script>";
}

$_SESSION['mod'] = "images";
$table="`images`";
$querystring=querystringConstruct();
$mod = !empty($_GET['mod']) ? $_GET['mod'] : '';
$modaction = !empty($_GET['a']) ? $_GET['a'] : 'list';
$acl = new Acl($_SESSION['userId']);
$rPerms = $acl->perms;
$key="";
$errPage="../inc/errpermission.php";
$errPageAjax="../inc/errpermissionajax.php";
$errPageExport="../inc/errpermissionexport.php";

switch($modaction) {
	case 'a':
		$key="add";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;

	case 'changeactive':
		$key="changeactive";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPageAjax; }
	break;

	case 'd':
		$key="del";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPageAjax; }
	break;

	case 'e':
		$key="edit";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;

	case 'list':
		$key="list";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;

	case 'sendemail':
		$key="sendemail";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;

	case 'sendconfirmationemail':
		$key="sendconfirmationemail";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;

	case 'view':
		$key="view";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPageExport; }
	break;

	case 'x':
		$key="export";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPageExport; }
	break;

	default:
		$key="list";
		if($_SESSION['userId'] == '1' || $acl->hasPermission($_GET['mod'] . "_" . $key) || (array_key_exists($_GET['mod'] . "_" . $key, $rPerms) && $rPerms[$_GET['mod'] . "_" . $key]['value'] === true)) { include_once $key . ".php"; }
		else { include_once $errPage; }
	break;
}
