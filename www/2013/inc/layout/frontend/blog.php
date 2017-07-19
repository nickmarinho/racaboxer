					<!-- BLOG -->
<?php
$where=""; $errmsg="BLOG VAZIO, NÃO HÁ POSTAGENS PARA EXIBIR";

if(!empty($_GET['id']) || !empty($_GET['url'])) {
	$sql="SELECT * FROM `blog` WHERE active='S' AND id='" . $_GET['id'] . "' AND url='" . $_GET['url'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();

	if($rows) {
		$data = $q->fetch(PDO::FETCH_ASSOC);
	
		echo "				<div id='breadcrumbs'>\n";
		echo "					<span class='blog-link'><a href='/blog' title='Blog'>Blog</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "' title='" . $_GET['ano'] . "'>" . $_GET['ano'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "-" . $_GET['mes'] . "' title='" . $_GET['mes'] . "/" . $_GET['ano'] . "'>" . $_GET['mes'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "-" . $_GET['mes'] . "-" . $_GET['dia'] . "' title='" . $_GET['dia'] . "/" . $_GET['mes'] . "/" . $_GET['ano'] . "'>" . $_GET['dia'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-title' title='" . $data['title'] . "'>" . $data['title'] . "</span>\n";
		echo "				</div>\n";
		echo "				<div class='title-bar' id='blog-main-title' title='" . $data['title'] . "'><h1>" . $data['title'] . "</h1></div>\n";
		echo "				<div id='blog-post'>\n";
		echo stripslashes($data['post']);
		echo "				</div>\n";
?>
				<div id="blog-espalhe"></div>
				<div id="blog-espalhe-buttons">
					<span class='st_blogger_vcount' displayText='Blogger'></span>
					<span class='st_delicious_vcount' displayText='Delicious'></span>
					<span class='st_digg_vcount' displayText='Digg'></span>
					<span class='st_email_vcount' displayText='Email'></span>
					<span class='st_facebook_vcount' displayText='Facebook'></span>
					<span class='st_formspring_vcount' displayText='Formspring'></span><br />
					<span class='st_googleplus_vcount' displayText='Google +'></span>
					<span class='st_instapaper_vcount' displayText='Instapaper'></span>
					<span class='st_linkedin_vcount' displayText='LinkedIn'></span>
					<span class='st_myspace_vcount' displayText='MySpace'></span>
					<span class='st_orkut_vcount' displayText='Orkut'></span>
					<span class='st_reddit_vcount' displayText='Reddit'></span><br />
					<span class='st_sonico_vcount' displayText='Sonico'></span>
					<span class='st_stumbleupon_vcount' displayText='StumbleUpon'></span>
					<span class='st_tumblr_vcount' displayText='Tumblr'></span>
					<span class='st_twitter_vcount' displayText='Tweet'></span>
					<span class='st_wordpress_vcount' displayText='WordPress'></span>
				</div>
				
				<div class="main-space"></div>
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
				
				<style>#disqus_thread{margin:10px 55px 10px 35px !important;}</style>
				<div id="disqus_thread"></div>
				<script type="text/javascript">
				var disqus_shortname = 'racaboxer';
				var disqus_identifier = '3959aaaf999ad35b6c4057afd91d9c96';
				var disqus_url = 'http://<?php echo $_SERVER['SERVER_NAME'] . "/blog/" . $_GET['ano'] . "-" . $_GET['mes'] . "-" . $_GET['dia'] . "/" . $_GET['id'] . "-" . $_GET['url'] . ".html"; ?>';
				var disqus_title = 'Raça Boxer Blog - <?php echo $data['title']; ?>'; 
				(function(){
					var dsq = document.createElement('script');
					dsq.type = 'text/javascript';
					dsq.async = true;
					dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				})();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<?php
	}
	else $errmsg="ESSA POSTAGEM NÃO EXISTE MAIS NO BLOG OU ESTÁ INATIVA";
}
else {
	echo "				<div id='breadcrumbs'>\n";
	echo "					<span class='blog-link'><a href='/blog' title='Blog'>Blog</a></span>\n";
	echo "					<span class='blog-seta'>&raquo;</span>\n";
	
	if(!empty($_GET['ano']) && !empty($_GET['mes']) && !empty($_GET['dia'])) {
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "' title='" . $_GET['ano'] . "'>" . $_GET['ano'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "-" . $_GET['mes'] . "' title='" . $_GET['mes'] . "/" . $_GET['ano'] . "'>" . $_GET['mes'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-title' title='" . $_GET['dia'] . "/" . $_GET['mes'] . "/" . $_GET['ano'] . "'>" . $_GET['dia'] . "</span>\n";
		
		$where=" AND DATE_FORMAT(cdate, '%Y-%m-%d')='" . $_GET['ano'] . '-' . $_GET['mes'] . '-' . $_GET['dia'] . "' ";
		$errmsg="BLOG VAZIO, NÃO HÁ POSTAGENS PARA EXIBIR";
	}
	else if(!empty($_GET['ano']) && !empty($_GET['mes'])) {
		echo "					<span class='blog-link'><a href='/blog/" . $_GET['ano'] . "' title='" . $_GET['ano'] . "'>" . $_GET['ano'] . "</a></span>\n";
		echo "					<span class='blog-seta'>&raquo;</span>\n";
		echo "					<span class='blog-title' title='" . $_GET['mes'] . "/" . $_GET['ano'] . "'>" . $_GET['mes'] . "</span>\n";

		$where=" AND DATE_FORMAT(cdate, '%Y-%m')='" . $_GET['ano'] . '-' . $_GET['mes'] . "' ";
	}
	else if(!empty($_GET['ano'])) {
		echo "					<span class='blog-title' title='" . $_GET['ano'] . "'>" . $_GET['ano'] . "</span>\n";

		$where=" AND DATE_FORMAT(cdate, '%Y')='" . $_GET['ano'] . "' ";
	}
	
	echo "				</div>\n";

	$sql="SELECT * FROM `blog` WHERE active='S' " . $where ." ORDER BY cdate DESC; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();
	
	$ano = ""; 
	$anomes = "";
	$anomesdia = "";
	$diamesano = ""; 
	$mes = ""; 
	$mesano = ""; 
	$mesini = ""; 

	if($rows) {
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$anomes = date("Y-m", strtotime($data['cdate']));
			$anomesdia = date("Y-m-d", strtotime($data['cdate']));
			$diamesano = date("d/m/Y", strtotime($data['cdate']));
			$mesano = date("m/Y", strtotime($data['cdate']));
			
			if(!empty($_GET['ano']) && !empty($_GET['mes']) && !empty($_GET['dia']) && $ano <> $_GET['ano']) {
				echo "					<div class=\"main-space\"></div>\n";
				echo "					<div class=\"title-bar\">CONFIRA TODAS AS POSTAGENS DE <a href=\"/blog/" . $_GET['ano'] . '-' . $_GET['mes'] . '-' . $_GET['dia'] . "\" title=\"CONFIRA TODAS AS POSTAGENS DE " . $_GET['dia'] . '/' . $_GET['mes'] . '/' . $_GET['ano'] . "\">" . $_GET['dia'] . '/' . $_GET['mes'] . '/' . $_GET['ano'] . "</a></div>\n";
				$ano = $_GET['ano'];
			}
			else if(!empty($_GET['ano']) && !empty($_GET['mes']) && $ano <> $_GET['ano']) {
				echo "					<div class=\"main-space\"></div>\n";
				echo "					<div class=\"title-bar\">CONFIRA TODAS AS POSTAGENS DE <a href=\"/blog/" . $_GET['ano'] . '-' . $_GET['mes'] . "\" title=\"CONFIRA TODAS AS POSTAGENS DE " . $_GET['mes'] . '/' . $_GET['ano'] . "\">" . $_GET['mes'] . '/' . $_GET['ano'] . "</a></div>\n";
				$ano = $_GET['ano'];
			}
			else if(!empty($_GET['ano']) && $ano <> $_GET['ano']) {
				echo "					<div class=\"main-space\"></div>\n";
				echo "					<div class=\"title-bar\">CONFIRA TODAS AS POSTAGENS DE <a href=\"/blog/" . $_GET['ano'] . "\" title=\"CONFIRA TODAS AS POSTAGENS DE " . $_GET['ano'] . "\">" . $_GET['ano'] . "</a></div>\n";
				$ano = $_GET['ano'];
			}
			else if($ano <> date("Y", strtotime($data['cdate']))) {
				echo "					<div class=\"main-space\"></div>\n";
				echo "					<div class=\"title-bar\">CONFIRA TODAS AS POSTAGENS DE <a href=\"/blog/" . date("Y", strtotime($data['cdate'])) . "\" title=\"CONFIRA TODAS AS POSTAGENS DE " . date("Y", strtotime($data['cdate'])) . "\">" . date("Y", strtotime($data['cdate'])) . "</a></div>\n";
				$ano = date("Y", strtotime($data['cdate']));
			}
			
			$mesini = date("m", strtotime($data['cdate']));
			if($mes <> $mesini && empty($_GET['mes']) && empty($_GET['dia'])) {
				echo "					<div class=\"title-button\"><a href=\"/blog/" . $anomes . "/\" title=\"" . $mesano . "\">" . $mesano . "</a></div>\n";
			}
			$mes = date("m", strtotime($data['cdate']));

			echo "						<span class='posts-links'><a href=\"/blog/" . $anomesdia . "/" . $data['id'] . "-" . $data['url'] . ".html\" title=\"" . $diamesano . ' - ' . $data['title'] . "\">" . $diamesano . ' - ' . $data['title'] . "</a></span><br />\n";
		}
	}
	else  echo "					<div class=\"title-bar\">" . $errmsg . "</div>\n";
}
?>
					<div class="main-space"></div>
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
