<?php
/**
* class to create pagination in of results
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-10
*/
class Db_Pagination
{
	private static $instance;
	protected $_pagenum;
	protected $_lastpage;
	protected $_qtyperpage;
	protected $_totalrows;
	
	public static function getInstance()
	{
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
	
	public function create()
	{
		$pagenum = $this->getpagenum();
		$lastpage = $this->getlastpage();
		$qtyperpage = $this->getqtyperpage();
		$totalrows = $this->gettotalrows();
		$return = "";
		
		if($lastpage > 1)
		{
			$return .= "    <div id='pagination'>\n";
			if($pagenum > 1)
			{
				$return .= "     <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='?p=1' title='Ir para primeira página '>";
				$return .= "<span class='ui-icon ui-icon-arrowthickstop-1-w'></span>";
				$return .= "</a>&nbsp;\n";
			
				$return .= "     <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='?p=" . ($pagenum-1) . "' title='Página Anterior'>";
				$return .= "<span class='ui-icon ui-icon-arrowthick-1-w'></span>";
				$return .= "</a>&nbsp;\n";
			}
			
			for($i=($pagenum-1); $i <= (($pagenum-1)+$qtyperpage); $i++)
			{
				if($i <= $lastpage)
				{
					if($pagenum == 1 && $i == 3)
					{
						if($i == $pagenum){ $return .= "     <a style='padding: 0pt;' title='Página " . $i . "' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
						else{ $return .= "     <a style='padding: 0pt;' title='Página 1' href='?p=" . $i . "' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
					}
					else if($pagenum == ($totalrows-1) && $i == 0)
					{
						if($i == $pagenum){ $return .= "     <a style='padding: 0pt;' title='Página " . $i . "' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
						else{ $return .= "     <a style='padding: 0pt;' title='Página 1' href='?p=" . $i . "' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
					}
					else if($i > 0 && $i < ($pagenum+2))
					{
						if($i == $pagenum){ $return .= "     <a style='padding: 0pt;' title='Página " . $i . "' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
						else{ $return .= "     <a style='padding: 0pt;' title='Página 1' href='?p=" . $i . "' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text' style='padding:0.8px 6px !important;'><b id='actual_" . $i . "'>" . $i . "</b></span></a>\n"; }
					}
				}
			}
			
			
			if($pagenum < $lastpage)
			{
				$return .= "      <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='?p=" . ($pagenum+1) . "' title='Próxima página'>";
				$return .= "<span class='ui-icon ui-icon-arrowthick-1-e'></span>";
				$return .= "</a>&nbsp;\n";
				
				$return .= "      <a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' href='?p=" . $lastpage . "' title='Ir para última página'>";
				$return .= "<span class='ui-icon ui-icon-arrowthickstop-1-e'></span>";
				$return .= "</a>&nbsp;\n";
			}
			
			$return .= "    </div>\n";
		}
		
		return $return;
	}
	
}