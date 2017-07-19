<?php
/**
* class to create pagination in of results
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-10
*/
class Pagination {
	private static $instance;
	protected $_pagenum;
	protected $_lastpage;
	protected $_qtyperpage;
	protected $_totalrows;
	protected $_numstoshow;
	protected $_querystring;
	
	public static function getInstance() {
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setpagenum($pagenum){ $this->_pagenum = $pagenum; }
	public function getpagenum(){ return $this->_pagenum; }
	public function setlastpage($lastpage){ $this->_lastpage = $lastpage; }
	public function getlastpage(){ return $this->_lastpage; }
	public function setqtyperpage($qtyperpage){ $this->_qtyperpage = $qtyperpage; }
	public function getqtyperpage(){ return $this->_qtyperpage; }
	public function settotalrows($totalrows){ $this->_totalrows = $totalrows; }
	public function gettotalrows(){ return $this->_totalrows; }
	public function setnumstoshow($numstoshow){ $this->_numstoshow = $numstoshow; }
	public function getnumstoshow(){ return $this->_numstoshow; }
	public function setquerystring($querystring){ $this->_querystring = $querystring; }
	public function getquerystring(){ return $this->_querystring; }

	public function querystringConstruct() {
		$return = "";
		$querystring = $this->getquerystring();
		if(!empty($querystring)) {
				$a = explode("&", $querystring);
				for($i=0; $i < count($a); $i++) {
					if(!empty($a[$i])) {
						$b = explode("=", $a[$i]);
						if($b[0] <> "page") {
							if(!empty($b[1])) {
								if($i < count($a)) {
									$return .= $b[0] . "=" . $b[1] . "&";
								}
								else {
									$return .= $b[0] . "=" . $b[1];
								}
							}
						}
					}
				}
			}
		return $return;
	}
	
	public function create() {
		$pagenum = $this->getpagenum();
		$lastpage = $this->getlastpage();
		$qtyperpage = $this->getqtyperpage();
		$totalrows = $this->gettotalrows();
		$numstoshow = $this->getnumstoshow();
		$this->setquerystring($_SERVER['QUERY_STRING']);
		$qs = $this->querystringConstruct();
		$return = "";
		$return .= "    <div id='pagination'>\n";
		
		if($lastpage > 1) {
			if($pagenum > 1) {
				$link = "";
				if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=1"; }
				else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=1"; }

				$return .= "     <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='" . $link . "' title='Ir para primeira p&aacute;gina '>";
				$return .= "<span class='ui-icon ui-icon-arrowthickstop-1-w'></span>";
				$return .= "</a>&nbsp;\n";
			
				$link = "";
				if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=" . ($pagenum-1); }
				else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=" . ($pagenum-1); }
				
				$return .= "     <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='" . $link . "' title='P&aacute;gina Anterior'>";
				$return .= "<span class='ui-icon ui-icon-arrowthick-1-w'></span>";
				$return .= "</a>&nbsp;\n";
			}
			
			if($pagenum == 1 || $pagenum == $lastpage) {
				for($i=($pagenum-$numstoshow); $i <= ($pagenum+$numstoshow); $i++) {
					$link = "";
					if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=" . $i; }
					else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=" . $i; }

					if($i > 0 && $i <= $lastpage) {
						//if() {
							if($i == $pagenum){ $return .= "     <a style='padding: 0pt;' title='P&aacute;gina " . $i . "' href='javascript:void(0);' class='error ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
							else{ $return .= "     <a style='padding: 0pt;' title='P&aacute;gina " . $i . "' href='" . $link . "' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
						//}
					}
				}
			}
			else {
				for($i=($pagenum-($numstoshow%2)); $i <= ($pagenum+($numstoshow%2)); $i++) {
					$link = "";
					if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=" . $i; }
					else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=" . $i; }

					if($i > 0 && $i <= $lastpage) {
						//if() {
							if($i == $pagenum){ $return .= "     <a style='padding: 0pt;' title='P&aacute;gina " . $i . "' href='javascript:void(0);' class='error ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
							else{ $return .= "     <a style='padding: 0pt;' title='P&aacute;gina " . $i . "' href='" . $link . "' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
						//}
					}
				}
			}
			
			if($pagenum < $lastpage) {
				$link = "";
				if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=" . ($pagenum+1); }
				else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=" . ($pagenum+1); }

				$return .= "      <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='" . $link . "' title='Pr&oacute;xima p&aacute;gina'>";
				$return .= "<span class='ui-icon ui-icon-arrowthick-1-e'></span>";
				$return .= "</a>&nbsp;\n";
				
				$link = "";
				if(!empty($qs)){ $link = $_SESSION['SITE_IPATH'] . "/admin/?" . $qs . "page=" . $lastpage; }
				else{ $link = $_SESSION['SITE_IPATH'] . "/admin/?page=" . $lastpage; }
				
				$return .= "      <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='" . $link . "' title='Ir para &Uacute;ltima p&aacute;gina'>";
				$return .= "<span class='ui-icon ui-icon-arrowthickstop-1-e'></span>";
				$return .= "</a>&nbsp;\n";
			}
			
			$return .= "     <center><a style='margin:10px 0;' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b>P&aacute;gina " . $pagenum . " de " . $lastpage . " - " . $totalrows . " registro(s) encontrado(s) no total</b></span></a></center>\n";
		}
		else {
			$return .= "     <center><a style='margin:10px 0;' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b>" . $totalrows . " registro(s) encontrado(s) no total</b></span></a></center>\n";
		}
		$return .= "    </div>\n";
		
		return $return;
	}
}