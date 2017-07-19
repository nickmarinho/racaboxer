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

<?php
	$sql="SELECT * FROM `blog` WHERE active='S' ORDER BY cdate DESC LIMIT 10; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	if($rows) {
?>
					<!-- BLOG -->
					<div class="title-bar">CONFIRA AS 10 ÚLTIMAS POSTAGENS DO BLOG</div>
<?php
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			echo "						<span class='posts-links'><a href=\"/blog/" . date("Y-m-d", strtotime($data['cdate'])) . "/" . $data['id'] . "-" . $data['url'] . ".html\" title=\"" . date("d/m/Y", strtotime($data['cdate'])) . ' - ' . $data['title'] . "\">" . date("d/m/Y", strtotime($data['cdate'])) . ' - ' . $data['title'] . "</a></span><br />\n";
		}
?>
					<div class="title-button"><a href="/blog" title="ACESSE O BLOG">ACESSE O BLOG</a></div>
					<div class="main-space"></div>
					<!-- /BLOG -->
<?php
	}
?>

					<!-- ADOÇÃO -->
					<div class="title-bar">ELES PRECISAM DE VOCÊ</div>
					<center>
						Esse lindo cão é mais um dos que precisa de sua ajuda.<br />Para saber como adotá-lo clique aqui.<br /><br />Adote um cãozinho, não compre!!!<br />
						<img alt="Adote um cão" src="/img/layout/frontend/adote-um-cao.png" /><br />
					</center>
					<div class="title-button"><a href="/doacoes" title="VEJA A LISTA PARA ADOÇÃO">LISTA DE ADOÇÃO</a></div>

					<div class="main-space"></div>

					<!-- MATERIAS -->
					<div class="title-bar">LEIA AS MATÉRIAS DE QUEM ENTENDE</div>
					<center>
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
						<a href="/lorem-ipsilom-lorem-ipsilom" title="lorem ipsilom lorem ipsilom">lorem ipsilom lorem ipsilom lorem ipsilom</a><br />
					</center>
					<div class="title-button"><a href="/materias" title="LEIA AS MATÉRIAS DE QUEM ENTENDE">VEJA MAIS</a></div>

					<div class="main-space"></div>

					<!-- DESTAQUE -->
					<div class="title-bar">FOTO DESTAQUE DO MÊS <?php echo date("m/Y"); ?></div>
					<center>
							<img alt="" src="/img/layout/frontend/foto-destaque-mes.png" title="Destaque do mês: <?php echo date("m/Y"); ?>" /><br />
					</center>
					<div id="destaque-box">
						<span id="destaque-box-titles">
							Dono:<br />
							Nome:<br />
							Data:
						</span>
						<span id="destaque-box-values">
							Luciano Marinho<br />
							Nick<br />
							17/02/2013
						</span>
					</div>
					<div class="main-space"></div>
					<div class="title-button"><a href="/fotos" title="ACESSE AS FOTOS">ACESSE AS FOTOS</a></div>
