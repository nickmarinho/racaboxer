<?php
	$sql="SELECT url, title FROM `pages` WHERE active='S' ORDER BY title ASC; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	if($rows) {
?>
					<div id="menu-left-links-top"></div>
					<div id="menu-left-links">
<?php
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			echo "						<a href=\"/" . $data['url'] . ".html\" title=\"" . $data['title'] . "\">" . $data['title'] . "</a>\n";
		}
?>
					</div>
					<div id="menu-left-bottom"></div>
					<div id="menu-left-separator"></div>
<?php
	}
?>
					<div id="menu-left-parceiros-top"></div>
					<div id="menu-left-parceiros">
						<a href="http://www.tirinhasdocao.com.br" target="_blank" title="Tirinhas do Cão"><img alt="" src="/img/parceiros/tirinhas-do-cao.png" /></a>
						<a href="http://www.seubichosumiu.com.br" target="_blank" title="Seu Bicho Sumiu"><img alt="" src="/img/parceiros/seu-bicho-sumiu.png" /></a>
					</div>
					<div id="menu-left-bottom"></div>
