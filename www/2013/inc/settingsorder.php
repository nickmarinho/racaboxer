<?php
$sortorder="ASC";
$qs = $_SERVER['QUERY_STRING'];
$cthfields=0;
if(!empty($thfields)) {
	foreach($thfields as $thk => $thv) {
		$zeroname = explode("_", $thk);
		$thtw = explode("_", $thv);

		if($zeroname[0] == "empty") {
			echo "							<th class='ui-state-default' style='width:" . $thtw[0] . "px;'>";
			echo "&nbsp;";
		}
		else {
			if(empty($thtw[0])) { echo "							<th class='ui-state-default'>"; }
			else {
				echo "							<th class='ui-state-default' style='width:" . $thtw[0] . "px;'>";
			}

			echo "<a href='?" . $querystring . "sortname=" . $thk . "&sortorder=";

			if(strstr($qs, $thk . "&sortorder=ASC")){ $sortorder="DESC"; }

			echo $sortorder . "' title='Clique para ordenar por esse campo'>";
			echo $thtw[1];
			echo "</a>";
		}

		echo "</th>\n";

		$cthfields++;
	}
}
