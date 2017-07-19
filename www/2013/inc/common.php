<?php
if(isset($_GET['logout'])) {
	createLog("saiu do sistema");
	session_destroy();
	setcookie("jquery-ui-theme", "", time()-4200);
	header("location: " . $_SESSION['SITE_IPATH'] . "/admin/");
}

function debugVar(&$var, $scope=0) {
	if(!empty($var)) {
		$old = $var;
		if(($key = array_search($var = 'unique'.rand().'value', !$scope ? $GLOBALS : $scope)) && $var = $old) {
			echo "<b>$" . $key . "</b>";
		}

		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}

$cdate = getDateToDb();
$mdate = getDateToDb();
function getDateToDb() {
	return 
	// date(date("Y-m-d H:i:s"),mktime(date("H")-3,date("i"),date("s"),date("m"),date("d"),date("Y")));
	date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s");
}

function getUltimoDiaMes($month = '', $year = '') {
   if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));

   return date('d', $result);
}

function getDiaSemanaNome($d) {
	$dias = array(
		"Sun" => "Dom",
		"Mon" => "Seg",
		"Tue" => "Ter",
		"Wed" => "Qua",
		"Thu" => "Qui",
		"Fri" => "Sex",
		"Sat" => "Sáb"
	);

	return $dias[$d];
}

function getMesNome($m) {
	$meses = array(
		"01" => "Janeiro",
		"02" => "Fevereiro",
		"03" => "Março",
		"04" => "Abril",
		"05" => "Maio",
		"06" => "Junho",
		"07" => "Julho",
		"08" => "Agosto",
		"09" => "Setembro",
		"10" => "Outubro",
		"11" => "Novembro",
		"12" => "Dezembro"
	);

	return $meses[$m];
}

/**
 *
 * @param type $action
 */
function createLog($action) {
	if(!empty($action) && !empty($_SESSION['userId'])) {
		$cdate = date("Y") . "-" . date("m") . "-" . date("d") . " " . (date("H")-3) . ":" . date("i") . ":" . date("s");
		$id_user = $_SESSION['userId'];
		$sql="INSERT INTO log (id, id_user, action, cdate) VALUES ('', '" . $id_user . "', '" . addslashes($action) . "', '" . $cdate . "')";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
	}
}

function querystringConstruct() {
	$return = "";
	$querystring = $_SERVER['QUERY_STRING'];
	if(!empty($querystring)) {
		$a = explode("&", $querystring);
		for($i=0; $i < count($a); $i++) {
			if(!empty($a[$i])) {
				$b = explode("=", $a[$i]);
				if($b[0] <> "filtroestatusname" && $b[0] <> "filtroestatusname" &&
					$b[0] <> "filtrolojasname" && $b[0] <> "filtrolojasorder" &&
					$b[0] <> "filtrogruposname" && $b[0] <> "filtrogruposorder" && 
					$b[0] <> "ftp" &&
					$b[0] <> "sortname" && $b[0] <> "sortorder") {
					if(!empty($b[1])) {
						if($i < count($a)){ $return .= $b[0] . "=" . $b[1] . "&"; }
						else{ $return .= $b[0] . "=" . $b[1]; }
					}
				}
			}
		}
	}
	return $return;
}

function insertOptions($options, $selected = '') {
	foreach($options as $k => $v) {
		echo "<option value='" . $k . "'";
		if(!empty($selected) && $selected == $k) { echo " selected"; }
		echo ">" . $v . "</option>\n";
	}
}

/**
 *
 * @param type $type
 * @param type $name
 * @param type $displayname
 * @param type $size
 * @param type $value
 * @param type $default
 * @return string
 */
function insertField($type, $name=null, $displayname=null, $size=null, $value=null, $default=null) {
	$return="";
	if(!empty($type)) {
		switch($type) {
			case 'file':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td><input id='" . $name . "' name='" . $name . "' size='" . $size . "' type='file' /></td>\n";
					$return .= "		</tr>\n";
				}
			break;
		
			case 'hidden':
				if(!empty($name) && !empty($value)) {
					$return .= "			<input id='" . $name . "' name='" . $name . "' type='hidden' value='";

					if(!empty($value)) $return .= $value;

					$return .= "' />\n";
				}
			break;

			case 'passwd':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td><input id='" . $name . "' name='" . $name . "' size='" . $size . "' type='password' value='";

					if(!empty($value)) $return .= $value;

					$return .= "' />";
					$return .= "</td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'radio':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td>\n";

					if(!empty($value)) {
						if(is_array($value)) {
							foreach($value as $k => $v) {
								$return .= "				<label for='" . $name . "_" . $k . "'>" . $v . ": </label><input id='" . $name . "_" . $k . "' name='" . $name . "' type='radio' value='" . $k . "'";

								if($k == $default) { $return .= " checked"; }

								$return .= " />\n";
							}
						}
					}

					$return .= "			</td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'checkbox':
				if(!empty($name) && !empty($displayname) && !empty($value)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td><input id='" . $name . "' name='" . $name . "' type='checkbox' value='" . $value . "' ";

					if(!empty($default) && $default == $value) { $return .= " checked"; }

					$return .= "			/></td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'select':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td>\n";
					$return .= "				<select id='" . str_replace('[]','',$name) . "' name='" . $name . "' size=" . $size . ">\n";
					$return .= "					<option value='' selected>- escolha -</option>\n";

					if(!empty($value)) {
						if(is_array($value)) {
							foreach($value as $k => $v) {
								$return .= "					<option value='" . $k . "'";

								$defaults = explode(",", $default);
								if(count($defaults) > 0) {
									for($i=0; $i<count($defaults); $i++) {
										if($defaults[$i] <> '' && $k == $defaults[$i]) { $return .= " selected"; }
										//else {
										//	$return .= " k='".$k."' defaults_i='".$defaults[$i]."' igualnao ";
										//	$return .= " defaults_i='".$default."' igualnao ";
										//}
									}
								}
								else {
									if($k == $default) { $return .= " selected "; }
									//else { $return .= " k='".$k."' default='".$default."' igualnao "; }
								}

								$return .= ">" . $v . "</option>\n";
							}
						}
					}

					$return .= "				</select>\n";
					$return .= "			</td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'text':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td><input id='" . $name . "' name='" . $name . "' size='" . $size . "' type='text' value='";

					if(!empty($value)) $return .= $value;

					$return .= "' /></td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'textarea':
				if(!empty($name) && !empty($displayname)) {
					$return .= "		<tr>\n";
					$return .= "			<td class='label ui-state-default'><label for='" . $name . "'>" . ucwords($displayname) . ": </label></td>\n";
					$return .= "			<td><textarea id='" . $name . "' name='" . $name . "' " . $size . ">";

					if(!empty($value)) $return .= $value;

					$return .= "</textarea></td>\n";
					$return .= "		</tr>\n";
				}
			break;

			case 'vazio':
				$isize = !empty($size) ? $size : '';
				$ivalue = !empty($value) ? $value : '';
				$return .= "<tr><td style='text-align:center;' colspan='" . $isize . "'>" . $ivalue . "</td></tr>\n";
			break;

			default:
				$return .= "";
			break;
		}
	}
	return $return;
}

/**
 *
 * @param type $name
 * @param type $displayname
 * @param type $value
 * @param type $class
 * @return string
 */
function insertFieldViewAddEdit($name=null, $displayname=null, $value=null, $class=null) {
	$return  = "";

	if(!empty($name) && !empty($displayname) && !empty($value)) {
		$return .= "				<tr>\n";
		$return .= "					<td class='label ui-state-default'><label for='" . $name . "'>" . $displayname . ": </label></td>\n";
		$return .= "					<td>" . $value . "</td>\n";
		$return .= "				</tr>\n";
	}

	return $return;
}

/**
 *
 * @param type $name
 * @param type $displayname
 * @param type $value
 * @param type $class
 * @return string
 */
function insertFieldView($name=null, $displayname=null, $value=null, $class=null) {
	$return  = "";

	if(!empty($name) && !empty($displayname) && !empty($value)) {
		$return .= "				<tr><td class='label ui-state-default'><label for='" . $name . "'>" . $displayname . ": </label></td><td";

		if(!empty($class)) $return .= " class='" . $class . "'";

		$return .= ">" . $value . "</td></tr>\n";
	}

	return $return;
}

function gerajQueryCheckForm($campos = array()) {
	$return="";
	if(count($campos)) {
		foreach($campos as $k => $v) {
			$return .= "	var " . $k . " = $('#" . $k . "');\n";
			$return .= "	if(" . $k . ".length && (" . $k . ".val() == '' || " . $k . ".val() == undefined)) {\n";
			$return .= "		erro++;\n";
			$return .= "		msg += '" . $v . "<br />';\n";
			$return .= "		" . $k . ".focus().select();\n";
			$return .= "		" . $k . ".addClass('ui-state-error');\n";
			$return .= "	}\n";
			$return .= "	else { " . $k . ".removeClass('ui-state-error'); }\n";
		}
	}
	return $return;
}

function gerajQueryCkCheckForm($campos = array()) {
	$return="";
	if(count($campos)) {
		foreach($campos as $k => $v) {
			$return .= "	var CKI" . $k . " = CKEDITOR.instances['" . $k . "'];\n";
			$return .= "	if(CKI" . $k . ") {\n";
			$return .= "		var " . $k . " = CKEDITOR.instances['" . $k . "'].getData();\n";
			$return .= "		if(" . $k . " == '') {\n";
			$return .= "			erro++;\n";
			$return .= "			msg += \"" . $v . "<br />\";\n";
			$return .= "		}\n";
			$return .= "		else { $(\"#" . $k . "\").val(" . $k . "); }\n";
			$return .= "	}\n";
			$return .= "	else {\n";
			$return .= "		var " . $k . " = $(\"#" . $k . "\");\n";
			$return .= "		if(" . $k . ".val() == '') {\n";
			$return .= "			erro++;\n";
			$return .= "			msg += \"" . $v . "<br />\";\n";
			$return .= "			" . $k . ".focus().select();\n";
			$return .= "			" . $k . ".addClass('ui-state-error');\n";
			$return .= "		}\n";
			$return .= "		else { " . $k . ".removeClass('ui-state-error'); }\n";
			$return .= "	}\n";
		}
	}
	return $return;
}

/* MENUS ADMIN */
function generateChildMenusAdmin($idparent, $c) {
	$return="";
	$sql="SELECT * FROM menu WHERE parent='" . $idparent . "' ORDER BY sort, label, parent";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();

	if($rows) {
		$return .= "\n						<ul id='sortable_child" . $c . "' class='all-sub-menu'>\n";

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$labeljquery = strtolower(str_replace(" ","-",$data['label']));

			$return .= "							<li class='s-menu'>\n";
			$return .= "							    <div class='s-menu-title'>\n";
			$return .= "								" . $data['label'] . "\n";
			$return .= "								<div id='editmenu_".$data['id']."' class='tdpag-icons editmenu'>\n";
			$return .= "								    <div class='ui-state-default ui-corner-all pag-icons'>\n";
			$return .= "									<span class='ui-icon ui-icon-pencil' title='Editar'></span>\n";
			$return .= "								    </div>\n";
			$return .= "								</div>\n";
			$return .= "								<div id='delmenu_".$data['id']."' class='tdpag-icons delmenu'>\n";
			$return .= "								    <div class='ui-state-default ui-corner-all pag-icons'>\n";
			$return .= "									<span class='ui-icon ui-icon-minusthick' title='Remover'></span>\n";
			$return .= "								    </div>\n";
			$return .= "								</div>\n";
			$return .= "							    </div>\n";
			$return .= "							</li>\n";

			$idparent = $data['id'];
			$return .= generateChildMenusAdmin($idparent, $c);

			$return .= "</li>\n";
		}

		$return .= "						</ul>\n";
	}

	$return .= "					";

	return $return;
}

function generateMenusAdmin() {
	$return="";
	$sql="SELECT * FROM menu WHERE parent='0' ORDER BY sort, label, parent";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	$c=1;
	if($rows) {
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$labeljquery = strtolower(str_replace(" ","-",$data['label']));
			$return .= "					<li id='page_".$data['id']."' class='p-menu'>";
			$return .= "	<div class='p-menu-title'>\n";
			$return .= "	".$data['label']."\n";
			$return .= "	    <div id='editmenu_".$data['id']."' class='tdpag-icons editmenu'>\n";
			$return .= "		<div class='ui-state-default ui-corner-all pag-icons'>\n";
			$return .= "		    <span class='ui-icon ui-icon-pencil' title='Editar'></span>\n";
			$return .= "		</div>\n";
			$return .= "	    </div>\n";
			$return .= "	    <div id='delmenu_".$data['id']."' class='tdpag-icons delmenu'>\n";
			$return .= "		<div class='ui-state-default ui-corner-all pag-icons'>\n";
			$return .= "		    <span class='ui-icon ui-icon-minusthick' title='Remover'></span>\n";
			$return .= "		</div>\n";
			$return .= "	    </div>\n";
			$return .= "	</div>\n";

			$idparent = $data['id'];
			$return .= generateChildMenusAdmin($idparent, $c);

			$return .= "</li>\n";

			$c++;
		}
	}

	return $return;
}
/* END MENUS ADMIN */

function generateChildMenus($idparent) {
	$return="";
	$sql="SELECT * FROM menu WHERE parent='" . $idparent . "' ORDER BY sort, label, parent";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();

	if($rows) {
		$idcm="mc_" . geraSenha();
		$return .= "\n						<div id='" . $idcm . "' class='dropdown_1column'>\n";
		$return .= "							<div class='col_1 firstcolumn'>\n";
		$return .= "								<ul class='levels'>\n";

		$aclmenu = new Acl($_SESSION['userId']);
		$rPerms = $aclmenu->perms;

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$labeljquery = strtolower(str_replace(" ","-",$data['mod']));
			if(
				$aclmenu->hasPermission($data['mod'] . "_" . $data['action']) ||
				(array_key_exists($data['mod'] . "_" . $data['action'], $rPerms) && $rPerms[$data['mod'] . "_" . $data['action']]['value'] === true) ||
				$_SESSION['userId'] == '1'
				) {
				//$return .= "									<!-- BEGIN '".strtoupper($labeljquery)."' -->\n";
				$return .= "									<li id='".$labeljquery."'><a href='" . $_SESSION['SITE_IPATH'] . "" . $data['link'] . "' title='" . $data['label'] . "'>" . $data['label'] . "</a>";

				$idparent = $data['id'];
				$return .= generateChildMenus($idparent);

				$return .= "</li>\n";
				//$return .= "									<!-- END '".strtoupper($labeljquery)."' -->\n";
			}
			else { echo "<script type='text/javascript'>$('#".$idcm."').remove();</script>\n"; }
		}

		$return .= "								</ul>\n";
		$return .= "							</div>\n";
		$return .= "						</div>\n";
		$return .= "					";
	}

	return $return;
}

function generateMenus() {
	$return="";
	$sql="SELECT * FROM menu WHERE parent='0' ORDER BY sort, label, parent";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	if($rows) {
		$aclmenu = new Acl($_SESSION['userId']);
		$rPerms = $aclmenu->perms;
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			if(
				$aclmenu->hasPermission($data['mod'] . "_" . $data['action']) ||
				(array_key_exists($data['mod'] . "_" . $data['action'], $rPerms) && $rPerms[$data['mod'] . "_" . $data['action']]['value'] === true) ||
				$_SESSION['userId'] == 1
				) {
				$labeljquery = strtolower(str_replace(" ","-",$data['mod']));

				//$return .= "					<!-- BEGIN '".strtoupper($labeljquery)."' -->\n";
				$return .= "					<li id='".$labeljquery."'><a class='first' href='" . $_SESSION['SITE_IPATH'] . "" . $data['link'] . "' title='" . $data['label'] . "'>" . $data['label'] . "</a>";

				$idparent = $data['id'];
				$return .= generateChildMenus($idparent);

				$return .= "</li>\n";
				//$return .= "					<!-- END '".strtoupper($labeljquery)."' -->\n";

				if(empty($_SESSION['jqmenu'])) { $_SESSION['jqmenu'][] = $labeljquery; }
				else if(is_array($_SESSION['jqmenu']) && !in_array($labeljquery, $_SESSION['jqmenu'])) { $_SESSION['jqmenu'][] = $labeljquery; }
			}
		}
	}

	return $return;
}

function generateMenusBkp() {
	$return="";
	$basemenu = realpath(dirname(__FILE__));
	//$basemenu = "../mods";
	$dirmenu = $basemenu . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "mods" . DIRECTORY_SEPARATOR;
	
	if(is_dir($dirmenu)) {
		$modmenus = "";
		$dh = opendir($dirmenu);
		while (false !== ($modmenu = readdir($dh))) {
			$modmenus[] = $modmenu;
		}
		closedir($dh);
		sort($modmenus);

		foreach($modmenus as $modmenu) {
			if(is_dir($dirmenu . $modmenu)) {
				if(is_file($dirmenu . $modmenu . DIRECTORY_SEPARATOR . "config.php")) {
					include_once $dirmenu . $modmenu . DIRECTORY_SEPARATOR . "config.php";

					if(!empty($_SESSION['menufull'][$modmenu]) && is_array($_SESSION['menufull'][$modmenu]) && in_array($_SESSION['userType'], $_SESSION['acl'])) {
						$cmenu=0;
						$totalcmenu = count($_SESSION['menufull'][$modmenu]);
						foreach($_SESSION['menufull'][$modmenu] as $k => $v) {
							if($cmenu == 0) {
								$return .= "					<!-- BEGIN '".strtoupper($modmenu)."' -->\n";
								$return .= "					<li id='".$modmenu."'><a class='first' href='#' onclick='javascript:location=\"" . $_SESSION['SITE_IPATH'] . "/admin/?mod=" . $modmenu . "&amp;" . $k . "\";' title='" . $v . "'>" . $v . "</a>";
								
								if($totalcmenu > 1) {
									$return .= "\n						<div class='dropdown_1column'>\n";
									$return .= "							<div class='col_1 firstcolumn'>\n";
									$return .= "								<ul class='levels'>\n";
								}
							}

							if($cmenu > 0) {
								$return .= "									<li><a href='#' onclick='javascript:location=\"" . $_SESSION['SITE_IPATH'] . "/admin/?mod=" . $modmenu . "&amp;" . $k . "\";' title='" . $v . "'>" . $v . "</a></li>\n";
							}

							$cmenu++;

							if($cmenu == $totalcmenu) {
								if($totalcmenu > 1) {
									$return .= "								</ul>\n";
									$return .= "							</div>\n";
									$return .= "						</div>\n";
								}

								if($totalcmenu > 1) { $return .= "					"; }

								$return .= "</li>\n";
								$return .= "					<!-- END '".strtoupper($modmenu)."' -->\n";
							}

							if(empty($_SESSION['jqmenu'])) { $_SESSION['jqmenu'][] = $modmenu; }
							else if(is_array($_SESSION['jqmenu']) && !in_array($modmenu, $_SESSION['jqmenu'])) { $_SESSION['jqmenu'][] = $modmenu; }
						}
					}
				}
			}
		}
	}

	return $return;
}

function geraSenha() {
	/**
	 * @desc Função utilizada para gerar senhas dinamicamente
	 * @param Void
	 * @return String senha
	 */
	$trecho[1] = "a";
	$trecho[2] = "e";
	$trecho[3] = "i";
	$trecho[4] = "o";
	$trecho[5] = "u";
	$trecho[6] = "ba";
	$trecho[7] = "be";
	$trecho[8] = "bi";
	$trecho[9] = "bo";
	$trecho[10] = "bu";
	$trecho[11] = "ca";
	$trecho[12] = "ce";
	$trecho[13] = "ci";
	$trecho[14] = "co";
	$trecho[15] = "cu";
	$trecho[16] = "da";
	$trecho[17] = "de";
	$trecho[18] = "di";
	$trecho[19] = "do";
	$trecho[20] = "du";
	$trecho[21] = "fa";
	$trecho[22] = "fe";
	$trecho[23] = "fi";
	$trecho[24] = "fo";
	$trecho[25] = "fu";
	$trecho[26] = "ga";
	$trecho[27] = "ge";
	$trecho[28] = "gi";
	$trecho[29] = "go";
	$trecho[30] = "gu";
	$trecho[31] = "ja";
	$trecho[32] = "je";
	$trecho[33] = "ji";
	$trecho[34] = "jo";
	$trecho[35] = "ju";
	$trecho[36] = "ka";
	$trecho[37] = "ke";
	$trecho[38] = "ki";
	$trecho[39] = "ko";
	$trecho[40] = "ku";
	$trecho[41] = "la";
	$trecho[42] = "le";
	$trecho[43] = "li";
	$trecho[44] = "lo";
	$trecho[45] = "lu";
	$trecho[46] = "ma";
	$trecho[47] = "me";
	$trecho[48] = "mi";
	$trecho[49] = "mo";
	$trecho[50] = "mu";
	$trecho[51] = "na";
	$trecho[52] = "ne";
	$trecho[53] = "ni";
	$trecho[54] = "no";
	$trecho[55] = "nu";
	$trecho[56] = "pa";
	$trecho[57] = "pe";
	$trecho[58] = "pi";
	$trecho[59] = "po";
	$trecho[60] = "pu";
	$trecho[61] = "ra";
	$trecho[62] = "re";
	$trecho[63] = "ri";
	$trecho[64] = "ro";
	$trecho[65] = "ru";
	$trecho[66] = "sa";
	$trecho[67] = "se";
	$trecho[68] = "si";
	$trecho[69] = "so";
	$trecho[70] = "su";
	$trecho[71] = "ta";
	$trecho[72] = "te";
	$trecho[73] = "ti";
	$trecho[74] = "to";
	$trecho[75] = "tu";
	$trecho[76] = "va";
	$trecho[77] = "ve";
	$trecho[78] = "vi";
	$trecho[79] = "vo";
	$trecho[80] = "vu";
	$trecho[81] = "xa";
	$trecho[82] = "xe";
	$trecho[83] = "xi";
	$trecho[84] = "xo";
	$trecho[85] = "xu";
	$trecho[86] = "wa";
	$trecho[87] = "we";
	$trecho[88] = "wi";
	$trecho[89] = "wo";
	$trecho[90] = "wu";
	$trecho[91] = "za";
	$trecho[92] = "ze";
	$trecho[93] = "zi";
	$trecho[94] = "zo";
	$trecho[95] = "zu";

	$senha = $trecho[rand(1, 95)] . ucfirst($trecho[rand(1, 95)]) . rand(10, 99);

	return $senha;
}

function echoBr($qty) {
	$return='';
	for($i=0; $i<$qty; $i++) { $return .= "<br >\n"; }
	return $return;
}

function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

