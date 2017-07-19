<?php
class Template{
	protected $_headers;
	protected $_module;
	private static $instance;
	
	public static function getInstance() {
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}

	public function setheaders($headers){ $this->_headers = $headers; }
	public function getheaders(){ return $this->_headers; }
	public function setmodule($module) {
		if(defined("MODULES_PATH")) { $this->_module = $module; }
		else { die("caminho para pasta de módulos não configurada, reveja o config.php em MODULES_PATH"); }
	}
	public function getmodule(){ return $this->_module; }
	
	public function __construct() {
		global $cfg;
		$headers  = "";
		$headers .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
		$headers .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
		$headers .= '	<head>' . "\n";
		if(defined("CHARSET")){ $headers .= '		<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '" />' . "\n"; }

		if(is_file("../favicon.ico")) {
			$headers .= '		<link href="/favicon.png" rel="shortcut icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="shortcut icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="icon" type="image/x-icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="apple-touch-icon" type="image/x-icon" />' . "\n";
			$headers .= '		<link href="/favicon.ico" rel="apple-touch-icon" />' . "\n";
		}

		if(defined("JQUERYUI_FOLDER") && defined("JQUERYUI_FILE")) {
			if(isset($_COOKIE['jquery-ui-theme'])) { $headers .= '		<link type="text/css" href="' . $_SESSION['SITE_IPATH'] . JQUERYUI_FOLDER . '/' . strtolower(str_replace(' ','-',$_COOKIE['jquery-ui-theme'])) . '/' . JQUERYUI_FILE . '" rel="stylesheet" />' . "\n"; }
			else { $headers .= '		<link type="text/css" href="' . $_SESSION['SITE_IPATH'] . JQUERYUI_FOLDER . '/' . JQUERYUI_THEME . '/' . JQUERYUI_FILE . '" rel="stylesheet" />' . "\n"; }
			
			$headers .= '		<style type="text/css">' . "\n";
			$headers .= '		#content .sample{' . "\n";
			$headers .= '			margin:10px 0;' . "\n";
			$headers .= '			width:100%;' . "\n";
			$headers .= '		}' . "\n";
			$headers .= '		#content #grid th{' . "\n";
			$headers .= '			padding:3px;' . "\n";
			$headers .= '		}' . "\n";
			$headers .= '		#content .list{' . "\n";
			$headers .= '			margin:10px 0;' . "\n";
			$headers .= '			width:100%;' . "\n";
			$headers .= '		}' . "\n";
			$headers .= '		.ui-tabs .ui-tabs-panel{' . "\n";
			$headers .= '			padding:0;' . "\n";
			$headers .= '		}' . "\n";
			$headers .= '		</style>' . "\n";
		}
		
		if(!empty($cfg['CSS_THEME'])) {
			$cssfiles = explode("##", $cfg['CSS_THEME']);
			for($i=0; $i < count($cssfiles); $i++) {
				$headers .= '		<link href="' . $_SESSION['SITE_IPATH'] . $cssfiles[$i] . '" rel="stylesheet" type="text/css" />' . "\n";
			}
		}
		
		if(defined("JQUERY_FILE") && defined("JSCHARSET")){ $headers .= '		<script type="text/javascript" charset="' . JSCHARSET . '"  src="' . $_SESSION['SITE_IPATH'] . JQUERY_FILE . '"></script>' . "\n"; }
		if(defined("JQUERYUI_JS") && defined("JSCHARSET")){ $headers .= '		<script type="text/javascript" charset="' . JSCHARSET . '"  src="' . $_SESSION['SITE_IPATH'] . JQUERYUI_JS . '"></script>' . "\n"; }

		if(defined("JQUERY_ADDITIONAL_FILES") && defined("JSCHARSET")) {
			$jqueryfiles = explode("##", JQUERY_ADDITIONAL_FILES);
			for($i=0; $i < count($jqueryfiles); $i++) {
				$headers .= '		<script type="text/javascript" charset="' . JSCHARSET . '"  src="' . $_SESSION['SITE_IPATH'] . $jqueryfiles[$i] . '"></script>' . "\n";
			}
		}
		
		if(!empty($cfg["ADMIN_SCRIPT"]) && defined("JSCHARSET")) {
			$jsfiles = explode("##", $cfg["ADMIN_SCRIPT"]);
			for($i=0; $i < count($jsfiles); $i++) {
				$headers .= '		<script type="text/javascript" charset="' . JSCHARSET . '"  src="' . $_SESSION['SITE_IPATH'] . $jsfiles[$i] . '"></script>' . "\n";
			}
		}
		
		if(defined("IE_HACK")) {
			$headers .= "		<!--[if IE]>" . IE_HACK . "<![endif]-->\n";
		}

		if(!empty($cfg["SITE_TITLE"])) { $headers .= "		<title>" . $cfg["SITE_TITLE"]; }
		
		if(!empty($_GET['mod'])) {
			$this->setmodule($_GET['mod']);
			$module = $this->getmodule();

			if(is_file(MODULES_PATH . DS . $module . DS . "config.php")){ include_once MODULES_PATH . DS . $module . DS . "config.php"; }
			else {
				die("nao foi possível incluir o arquivo de configuração do módulo: '" . $module . "'. Por favor reveja o config.php do modulo");
			}

			//if(defined("SITE_TITLE_SEPARATOR") && defined("MODULE_TITLE")){ $headers .= SITE_TITLE_SEPARATOR . MODULE_TITLE; }
			if(!empty($cfg["SITE_TITLE_SEPARATOR"]) && !empty($_SESSION[$_GET['mod']]['MODULE_TITLE'])){ $headers .= $cfg["SITE_TITLE_SEPARATOR"] . $_SESSION[$_GET['mod']]['MODULE_TITLE']; }
		}

		$headers .= "</title>\n";
		$headers .= "	</head>\n";
		$this->setheaders($headers);
	}
	
	public function getmodulecontent() {
		$module = $this->getmodule();
		if(!empty($module)) {
			if(is_file(MODULES_PATH . DS . $module . DS . "index.php")){ include_once MODULES_PATH . DS . $module . DS . "index.php"; }
		}
		else {
			die("nao foi possível incluir o módulo: '" . $module . "'. Por favor reveja o index.php do modulo");
		}
	}
}
