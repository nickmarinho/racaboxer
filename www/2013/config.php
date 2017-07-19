<?php
session_start();
$cfg = array();
date_default_timezone_set('America/Sao_Paulo');

/* charset settings */
define("JSCHARSET", "UTF-8");
define("CHARSET", "UTF-8"); // com iso-8859-1 os acentos bugam
//define("JSCHARSET", "ISO-8859-1");
//define("CHARSET", "ISO-8859-1"); // com iso-8859-1 os acentos bugam
if(defined("CHARSET")) { header("Content-Type: text/html; charset=" . CHARSET); }
/* end charset settings */

/* db settings */

/*
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "gmail");
define("DB_NAME", "racaboxer");
*/
define("DB_HOST", "mysql.racaboxer.com.br");
define("DB_USER", "racaboxer01");
define("DB_PASS", "nick1978");
define("DB_NAME", "racaboxer01");

/* end db settings */

/* site settings */
define("NOSCRIPT", "<noscript><center><h1 style='color:#FF0000;'>Por favor habilite ou use um navegador com suporte a javascript e atualize a página.</h1><h1 style='color:#FF0000;'>Please enable or use a browser that supports javascript and reload the page.</h1></center><div style='margin:0 0 9999px 0;'></div></noscript>");
define("CDATE", date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s"));
define("MDATE", date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s"));

//$cfg["ADMIN_THEME"] = "/css/admin.css##/css/megamenu.css##/css/farbtastic.css";
//$cfg["CSS_THEME"] = "/css/colorpicker.css";
define("JQUERYUI_FOLDER", "/css/themes");
define("JQUERYUI_THEME", "smoothness");
define("JQUERYUI_FILE", "jquery-ui-1.8.17.custom.css");
define("JQUERY_FILE", "/js/jquery-1.7.1.min.js");
define("JQUERYUI_JS", "/js/jquery-ui-1.8.17.custom.min.js");

/* this you may separate the files by ## or get the error of: "Warning: Constants may only evaluate to scalar value" */
//define("JQUERY_ADDITIONAL_FILES", "/js/jquery.blockUI.js##/js/jquery.maskedinput-1.3.js##/js/jquery.qtip-1.0.0-rc3.js##/js/jquery.ui.form.js##/js/ckeditor/ckeditor.js##/js/ckeditor/adapters/jquery.js##/js/farbtastic.js");
define("JQUERY_ADDITIONAL_FILES", "/js/jquery.blockUI.js##/js/jquery.maskMoney.js##/js/jquery.maskedinput-1.3.js##/js/jquery.qtip-1.0.0-rc3.js##/js/jquery.ui.form.js##/js/ckeditor/ckeditor.js##/js/ckeditor/adapters/jquery.js##/js/colorpicker.js##/js/jquery.pstrength-min.1.2.js##/js/jquery.pstrengthc-min.1.2.js##/js/admin-common.js");
/* this mean that if you have any js files that you maked and want in the system, then you put it here */
$cfg["ADMIN_SCRIPT"] = "/js/admin.js";
$cfg["JS_SCRIPT"] = "/js/default.js";

define("DS", DIRECTORY_SEPARATOR);
define("APP_FOLDER", $_SERVER['DOCUMENT_ROOT']);
//define("APP_FOLDER", getcwd());
defined("APP_PATH") || define("APP_PATH", realpath(dirname(__FILE__)));
	define("CLASS_PATH", APP_PATH . DS . "class");
	define("INC_PATH", APP_PATH . DS . "inc");
	define("MODULES_PATH", APP_PATH . DS . "mods");
define("EXPORT_FOLDER", APP_PATH . DS . "export");
define("EXPORT_URL", "/export/");
define("IMG_FOLDER", "img");
	define("UPLOAD_FOLDER", IMG_FOLDER . DS . "upload");
	define("UPLOAD_HEIGHT", "300");
	define("UPLOAD_WIDTH", "300");
	define("THUMB_FOLDER", IMG_FOLDER . DS . "thumbs");
	define("THUMB_HEIGHT", "30");
	define("THUMB_WIDTH", "30");
/* end site settings */

/* include path & autoload to all work nicelly */
if(defined("APP_PATH")) {
	set_include_path(implode(PATH_SEPARATOR, array(
		get_include_path(),
		realpath(APP_PATH . DS . "class"),
		realpath(APP_PATH . DS . "inc"),
	)));

	function __autoload($filename) {
		$requiredpath = str_replace("_", DS, $filename);

		if(is_file(CLASS_PATH . DS . $requiredpath . ".php")) {
		    include_once CLASS_PATH . DS . $requiredpath . ".php";
		}
		elseif(is_file(INC_PATH . DS . $requiredpath . ".php")) {
		    include_once INC_PATH . DS . $requiredpath . ".php";
		}
	}

	spl_autoload_register('__autoload');
}
else die("Por favor reveja o config.php. Você deve setar o APP_PATH");

/* configuracao do sistema */
$sql  = "SET NAMES 'utf8';";
//$sql .= "SET global character_set_server=utf8;";
//$sql .= "SET session character_set_server=utf8;";
//$sql .= "SET global character_set_database=utf8;";
//$sql .= "SET session character_set_database=utf8;";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$q->execute();

/*
$sql="SELECT * FROM sistema WHERE id='1'; ";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$q->execute();
$data = $q->fetch(PDO::FETCH_ASSOC);
$cfg = array();

if(!empty($data['nome'])) { $cfg["SITE_NOME"] = $data['nome']; }
if(!empty($data['nome_fantasia'])) { $cfg["SITE_NOMEFANTASIA"] = $data['nome_fantasia']; }
if(!empty($data['email'])) { $cfg["SITE_EMAIL"] = $data['email']; }
if(!empty($data['url'])) { $cfg["SITE_URL"] = $data['url']; }
if(!empty($data['titulo'])) { $cfg["SITE_TITLE"] = $data['titulo']; }
if(!empty($data['logo'])) { $cfg["SITE_LOGO"] = $data['logo']; }
if(!empty($data['cpfcnpj'])) { $cfg["SITE_CPFCNPJ"] = $data['cpfcnpj']; }
 */
$cfg["SITE_NOME"] = "Raça Boxer - Tudo sobre o mundo dos cães";
$cfg["SITE_NOMEFANTASIA"] = "Raça Boxer - Tudo sobre o mundo dos cães";
$cfg["SITE_EMAIL"] = "contato@racaboxer.com.br";
//$cfg["SITE_URL"] = "http://www.racaboxer.com.br";
$cfg["SITE_IPATH"] = "";
//$_SESSION["SITE_IPATH"] = "/";
$_SESSION["SITE_IPATH"] = "";
$cfg["SITE_URL"] = "http://racaboxer.localhost";
$cfg["SITE_TITLE"] = "Raça Boxer - Tudo sobre o mundo dos cães";
$cfg["SITE_LOGO"] = "/img/logo/logo.png";
$cfg["SITE_TITLE_SEPARATOR"] = " - ";
/* end configuracao do sistema */

include_once "common.php";
