					<center>
						<!-- ANUNCIO DO MERCADO LIVRE -->
						<iframe
							frameborder="0"
<?php
$h=0;
if($browser['name'] == 'Google Chrome')  $h=71;
else if($browser['name'] == 'Internet Explorer' || $browser['name'] == 'Mozilla Firefox')  $h=60;
else $h=59;
?>
						height="<?php echo $h; ?>"
							marginheight="0"
							marginwidth="0"
							scrolling="no"
							src="http://smartad.mercadolivre.com.br/jm/SmartAd?tool=6208091&creativity=37803&new=N&ovr=N&bgcol=FFE3D2&brdcol=c1560d&txtcol=c1560d&lnkcol=000000&hvrcol=FF0000&prccol=FF0000&word=cachorro&word=cães&word=cão&site=MLB"
							width="468"
							>
						</iframe>
						<!-- /ANUNCIO DO MERCADO LIVRE -->
					</center>

					<!-- FOTOS -->
					<div class="title-bar">CONFIRA AS FOTOS ENVIADAS PELOS VISITANTES DO NOSSO SITE</div>
					<h3>Clique nas fotos para ver mais detalhes, comentar e/ou compartilhar nas suas redes sociais</h3>
<?php
$limitperpage = ''; $numtoshow = ''; $p = ""; $limitnum = ""; $sortname = ""; $sortorder = "";
$limitperpage = '10';
$numtoshow = '5';

$sql="SELECT * FROM `images` WHERE active='S' ORDER BY id ASC ";
$conn = Db_Instance::getInstance();
$q = $conn->prepare($sql);
$q->execute();
$rows = $q->rowCount();
$last="";

if($rows > 0) {
	$p = !empty($_GET['p']) ? $_GET['p'] : 1;
	$limitnum = !empty($_GET['c']) ? $_GET['c'] : $limitperpage;
	$last = ceil($rows/$limitnum);

	if($p < 1){ $p = 1; }
	elseif($p > $last){ $p = $last; }

	$limit = " LIMIT " . ($p - 1) * $limitnum . ',' . $limitnum . " ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql . $limit);
	$q->execute();
	$rowsi = $q->rowCount();
	
	$c=0;
	while($data = $q->fetch(PDO::FETCH_ASSOC)) {
		if($c == 0) { echo "<div class='image-block'>\n"; }
		
		if(($c % 2) == 0 && $c > 0 && $c < $rowsi){
			echo "</div>\n";
			echo "<div class='image-block'>\n";
		}
		
		echo "<div class='image-box'><span class='image-container'><a href='/fotos/foto_" . $data['id'] . ".html' title='Veja mais detalhes da foto de: " . $data['title'] . "'><img alt='' src='" . $data['path'] . "' title='" . $data['title'] . "' /></a></span></div>\n";
		
		$c++;
		if($c == $rowsi) { echo "</div>\n"; }
	}
}

include_once "pagination-fotos.php";
$pagination = Pagination::getInstance();
$pagination->settotalrows($rows);

if($last > 1) {
	//$pagination = new Pagination();
	$pagination->setpagenum($p);
	$pagination->setlastpage($last);
	$pagination->setqtyperpage($limitnum);
	$pagination->setnumstoshow($numtoshow);
}
$pag = $pagination->create();
if(!empty($pag)) { echo $pag; }
?>					
					<div class="main-space"></div>
					<!-- /FOTOS -->

