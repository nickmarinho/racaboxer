<?php
include_once "../config.php";
$qs = querystringConstruct();
$cfg['templatetype'] = 'admin';
$tpl = Template::getInstance();
$headers = $tpl->getheaders();
$mod = !empty($_GET['mod']) ? $_GET['mod'] : '';
$ajax = !empty($_GET['ajax']) ? $_GET['ajax'] : '';
$st = !empty($_GET['st']) ? $_GET['st'] : '';
$url = !empty($_GET['url']) ? $_GET['url'] : '';
$userLogged = !empty($_SESSION['userLogged']) ? $_SESSION['userLogged'] : '';
$tpl->setmodule = $mod;

if(!empty($ajax) && !empty($mod)) {
	if($userLogged <> true) {
		echo "<script charset='" . JSCHARSET . "' type='text/javascript'>";
		echo "alert('Sessão expirada, por favor faça seu login');";
		echo "location='" . $cfg['SITE_IPATH'] . "/admin/?login&url=" . $cfg['SITE_IPATH'] . "/admin/?mod=" . $mod . "';";
		echo "</script>";
	}
	else { echo $tpl->getmodulecontent(); }
}
else {
	if(empty($st)) { echo $headers; }

	echo "	<body>\n";
	/* if is not ajax template show full template otherwise show SIMPLE TEMPLATE */
	if(!empty($st)) {
		if(isset($_GET['login']) || $userLogged <> 'true') {
			echo "<script charset='" . JSCHARSET . "' type='text/javascript'>";
			echo "alert('Por favor, faça seu login !!!');";
			echo "location='" . $cfg['SITE_IPATH'] . "/admin/?login&url=" . $cfg['SITE_IPATH'] . "/admin/?mod=" . $mod . '&' . $qs . "';";
			echo "</script>";
		}
		elseif(!empty($mod)) { echo $tpl->getmodulecontent(); }
	}
	else {
		echo "		<!-- header (html5) -->\n";
		echo "		<header>\n";
		echo "			<!-- div top -->\n";
		echo "			<div id='top'><div class='ui-state-default ui-corner-all'><span class='ui-text'>";

		if(!empty($_SESSION['userLogged']) && $_SESSION['userLogged'] == true && !empty($_SESSION['userId']) && $_SESSION['userId'] > 1 && !empty($_SESSION['userGroups'])) {
			foreach($_SESSION['userGroups'] as $groups) {
				$acllogin = new Acl($_SESSION['userId']);
				$group = $acllogin->getRoleNameFromID($groups);
				
				if(strtolower($group) == 'moradores') {
					$sqlmor="SELECT u.name, e.logo
							FROM users u
							INNER JOIN empreendimentos e ON e.id=u.id_empreendimento
							WHERE u.id='" . $_SESSION['userId'] . "'";
					$connmor = Db_Instance::getInstance();
					$qmor=$connmor->prepare($sqlmor);
					$qmor->execute();
					$rowsmor=$qmor->rowCount();
					$datamor=$qmor->fetch(PDO::FETCH_ASSOC);

					if($rowsmor) {
						if(!empty($datamor['logo'])) { echo "<img src='/" . $datamor["logo"] . "' style='max-height:100px;' />"; }
						else { echo $datamor["nome"]; }
					}
					else {
						if(!empty($cfg["SITE_LOGO"])) { echo "<img src='" . $cfg["SITE_LOGO"] . "' />"; }
						else if(!empty($cfg["SITE_TITLE"])) { echo $cfg["SITE_TITLE"]; }
					}
					continue;
				}
			}
		}
		else {
			if(!empty($cfg["SITE_LOGO"])) { echo "<img src='" . $cfg["SITE_LOGO"] . "' />"; }
			else if(!empty($cfg["SITE_TITLE"])) { echo $cfg["SITE_TITLE"]; }
		}

		echo "</span></div></div>\n";
		echo "		<!-- /div top -->\n";
		echo "		</header>\n";
		echo "		<!-- /header (html5) -->\n";

		if($userLogged == 'true') {
			echo "		<!-- nav (html5) -->\n";
			echo "		<nav>\n";
			echo "			<!-- div menu -->\n";
			echo "			<div id='menu' class='ui-widget-content ui-corner-all'>\n";

			include_once "menu.php";

			echo "			</div>\n";
			echo "		</nav>\n";
			echo "		<!-- /nav (html5) -->\n";
		}

		echo "		<!-- content (html5) -->\n";
		echo "		<content>\n";
		echo "			<!-- div total -->\n";
		echo "			<div id='total'>\n";
		echo "				<!-- div content -->\n";
		echo "				<div id='content'>\n";
		echo "					<div class='ui-widget-content ui-corner-all'>\n";

		if(defined("NOSCRIPT")) {
			echo "						" . NOSCRIPT . "\n";
		}

		$url = "/admin/";
		if(!empty($qs)) { $url .= "?" . $qs; }

		if(empty($userLogged)) {
			include_once "login.php";
		}
		else if(isset($_GET['login']) && empty($userLogged)) {
			include_once "login.php";
		}
		else if(!empty($mod)) {
			echo $tpl->getmodulecontent();
		}
		else { include_once MODULES_PATH . "/home.php"; }

		echo "					</div>\n";
		echo "				</div>\n";
		echo "				<!-- end div content -->\n";
		echo "			</div>\n";
		echo "			<!-- end div total -->\n";
		echo "		</content>\n";
		echo "		<!-- /content (html5) -->\n";


		echo "		<!-- footer (html5) -->\n";
		echo "		<footer>\n";

		if(!empty($cfg["SITE_TITLE"])){ echo "			<div id='copyright'>" . $cfg["SITE_TITLE"] . " &nbsp; &copy; &reg; &trade; " . date("Y") . "</div>\n"; }

		echo "			<div id='html5'></div>\n";
		echo "			<div id='css3'></div>\n";
		echo "			<div id='bottoml'></div>\n";
		echo "			<div id='bottomr'></div>\n";
		echo "		</footer>\n";
		echo "		<!-- /footer (html5) -->\n";
	}

	echo "		<iframe id='addeditifrm' name='addeditifrm'></iframe>\n";

	// efeito do menu
	echo "		<script charset='" . JSCHARSET . "' type='text/javascript'>\n";
	echo "			$(document).ready(function(){\n";

	$SITEIPATH = !empty($_SESSION['SITE_IPATH']) ? $_SESSION['SITE_IPATH'] : '';
	$userName = !empty($_SESSION['userName']) ? $_SESSION['userName'] : '';

	echo "				var newitem  = \"					<li class='nodrop right' id='userlogged'><a href='" . $SITEIPATH . "/admin/?mod=profile' title='Clique para acessar o seu perfil' class='first'>Olá: <b>" . $userName . "</b></a>\";\n";
	echo "					newitem += \"						<div class='dropdown_1column'>\";\n";
	echo "					newitem += \"							<div class='col_1 firstcolumn'>\";\n";
	echo "					newitem += \"								<ul class='levels'>\";\n";
	echo "					newitem += \"									<li><a href='#' onclick='javascript:location=\";\n";
	echo "					newitem += '\"';\n";
	echo "					newitem += \"" . $_SESSION['SITE_IPATH'] . "/admin/?mod=profile\";\n";
	echo "					newitem += '\"';\n";
	echo "					newitem += \";' title='Meu Perfil'>Meu Perfil</a></li>\";\n";
	
	/*echo "					newitem += \"									<li><a href='#' onclick='javascript:location=\";\n";
	echo "					newitem += '\"';\n";
	echo "					newitem += \"" . $_SESSION['SITE_IPATH'] . "/admin/?mod=themes\";\n";
	echo "					newitem += '\"';\n";
	echo "					newitem += \";' title='Mudar Tema'>Mudar Tema</a></li>\";\n";*/
	/**
	 * desabilitado, pois parece nao existir mais
	 */
	echo "					newitem += \"								</ul>\";\n";
	echo "					newitem += \"							</div>\";\n";
	echo "					newitem += \"						</div>\";\n";
	echo "					newitem += \"</li>\";\n";
	echo "				$('#logout').after(newitem);\n";
	echo "			});\n";

	echo "			$(document).ready(function(){\n";

	if(empty($mod)) {
		echo '				$("#home .first").addClass("firsta ui-state-default ui-corner-all");' . "\n";

		if(!empty($_SESSION['jqmenu']) && is_array($_SESSION['jqmenu'])) {
			$jqmenu = $_SESSION['jqmenu'];
			for($i=0; $i < count($jqmenu); $i++) {
				echo '				$("#'.$jqmenu[$i].'").find("a").hover( function() { $("#'.$jqmenu[$i].' .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#'.$jqmenu[$i].' .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";
			}
		}

		echo '				$("#sistema").find("a").hover( function() { $("#sistema .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#sistema .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";
	}
	else {
		echo '				$("#home").find("a").hover( function() { $("#home .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#home .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";

		if(!empty($_SESSION['jqmenu']) && is_array($_SESSION['jqmenu'])) {
			$jqmenu = $_SESSION['jqmenu'];
			for($i=0; $i < count($jqmenu); $i++) {
				if($mod <> $jqmenu[$i]) {
					echo '				$("#'.$jqmenu[$i].'").find("a").hover( function() { $("#'.$jqmenu[$i].' .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#'.$jqmenu[$i].' .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";
				}
			}

			$sqlpai="SELECT a.`mod` as fathername FROM menu a, menu b WHERE a.id=b.parent AND b.`mod`='".$mod."';";
			$connpai = Db_Instance::getInstance();
			$qpai = $connpai->prepare($sqlpai);
			$qpai->execute();
			$rowspai = $qpai->rowCount();
			if($rowspai) {
				$datapai = $qpai->fetch(PDO::FETCH_ASSOC);
				echo '				$("#'.$datapai["fathername"].' .first").addClass("firsta ui-state-default ui-corner-all");' . "\n";
			}
			else echo '				$("#'.$mod.' .first").addClass("firsta ui-state-default ui-corner-all");' . "\n";
		}

		if($mod == "profile" || $mod == "themes") {
			echo '				$("#userlogged .first").addClass("firsta ui-state-default ui-corner-all");' . "\n";
		}
		else {
			echo '				$("#userlogged").find("a").hover( function() { $("#userlogged .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#userlogged .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";
		}
	}

	echo '				$("#logout").find("a").hover( function() { $("#logout .first").addClass("firsta ui-state-default ui-corner-all"); }, function() { $("#logout .first").removeClass("firsta ui-state-default ui-corner-all"); } );' . "\n";
	echo "			});\n";
	echo "		</script>\n";
	// end efeito do menu

	echo "	</body>\n";
	echo "</html>\n";
}