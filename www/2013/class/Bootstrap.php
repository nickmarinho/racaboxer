<?php
class Bootstrap{
	private static $instance;
	
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}

	public function __construct() {
		defined("APP_PATH") || die("Por favor reveja as configurações no config.php");
	}

	public function go() {
		global $cfg;
		$tpl = Template::getInstance();
		$headers = $tpl->getheaders();
		
		if(!empty($_GET['ajax']) && !empty($_GET['mod'])) {
			if($_SESSION['userLogged'] <> true) {
				
			}
			else {
				$tpl->setmodule = $_GET['mod'];
				echo $tpl->getmodulecontent();
			}
		}
		else {
			if(empty($_GET['st'])) {
				echo $headers;
			}
		
			echo "	<body>\n";
		
			/* if is not ajax template show full template otherwise show SIMPLE TEMPLATE */
			if(!empty($_GET['st'])) {
				if(isset($_GET['login']) || $_SESSION['userLogged'] <> 'true') {
					echo "<script charset='utf-8' type='text/javascript'>";
					echo "alert('Por favor, faça seu login !!!');";
					echo "location='/';";
					echo "</script>";
				}
				elseif(!empty($_GET['mod'])) {
					$tpl->setmodule = $_GET['mod'];
					echo $tpl->getmodulecontent();
				}
			}
			else {
				echo "		<!-- header (html5) -->\n";
				echo "		<header>\n";
				echo "			<!-- div top -->\n";
				echo "			<div id='top'><div class='ui-state-default ui-corner-all'><span class='ui-text'>";

				if(!empty($cfg["SITE_LOGO"])) {
					echo "<img src='" . $cfg["SITE_LOGO"] . "' />";
				}
				else if(!empty($cfg["SITE_TITLE"])) { echo $cfg["SITE_TITLE"]; }
				//include_once "top.php";

				echo "</span></div></div>\n";
				echo "		<!-- /div top -->\n";
				echo "		</header>\n";
				echo "		<!-- /header (html5) -->\n";

				//if(!empty($_GET['mod']) && $_GET['mod'] <> 'login') {
				if(!empty($_SESSION['userLogged']) && $_SESSION['userLogged'] == 'true') {
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

				/*
				echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";
				*/

				if(empty($_SESSION['userLogged']) || isset($_GET['login'])) {
					include_once "login.php";
				}
				elseif(!empty($_GET['mod'])) {
					$tpl->setmodule = $_GET['mod'];
					echo $tpl->getmodulecontent();
				}
				else {
?>
		    <style type="text/css">
		    .sample{padding:5px 0;}
		    .sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:150px;}
		    </style>
		<script type='text/javascript' charset="utf-8">
		$(document).ready(function(){
			//$("#link_home").addClass("ui-tabs-selected ui-state-active");	
			//$("#link_home").addClass("current");
			$("#link_home").addClass("ui-state-default ui-corner-all");
		});
		</script>
		    <table class='sample ui-widget-header ui-corner-all' style="margin:5px auto;">
			    <tbody>
				    <tr>
					    <td style="text-align:center;">
						<a class="button">Seu &uacute;ltimo login foi em: <?php echo $_SESSION['userLastLogin']; ?></a><br /><br />
						Caso queira mudar seu email e/ou senha, por favor, clique em <a class="button">Meu Perfil</a> no menu acima.<br /><br />
						Para mudar os dados do sistema clique no botão acima <a class="button">Sistema</a><br /><br />
					    </td>
				    </tr>
			    </tbody>
		    </table>
		    <hr />
		    <table class='sample ui-widget-header ui-corner-all' style="margin:5px auto;">
			    <tbody>
				    <tr>
					    <td style="text-align:left;">
						<a class="button">Em breve aqui:</a><br /><br />
						- calend&aacute;rio<br />
						- mensagens<br />
						    <br />
						    <br />
						    <br />
						- ter mais id&eacute;ias<br />
						<br /><br />
					    </td>
				    </tr>
			    </tbody>
		    </table>
<?php
				}

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
			echo "	</body>\n";
			echo "</html>\n";
		}
	}
	
}
