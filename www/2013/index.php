<?php
include_once "config.php";
$page = !empty($_GET['page']) ? $_GET['page'] : 'home';
$errmsg=""; $title = ""; $meta_keywords = ""; $meta_description = ""; $content = "";
$browser=getBrowser();

if($page == 'blog') {
	$title = "Blog";
	if(!empty($_GET['id']) || !empty($_GET['url'])) {
		if(!empty($_GET['id'])) {
			$where=" AND id='" . $_GET['id'] . "' ";
		}
		if(!empty($_GET['url'])) {
			$where=" AND url='" . $_GET['url'] . "' ";
		}

		$sql="SELECT * FROM `blog` WHERE active='S' " . $where ." ; ";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$rows = $q->rowCount();

		if($rows) {
			$data = $q->fetch(PDO::FETCH_ASSOC);

			if(!empty($data['title'])) { $title = $data['title']; }
			if(!empty($data['meta_keywords'])) { $meta_keywords = $data['meta_keywords']; }
			if(!empty($data['meta_description'])) { $meta_description = $data['meta_description']; }
			if(!empty($data['content'])) { $content = $data['post']; }
		}
		else $errmsg="ESSA POSTAGEM NÃO EXISTE MAIS NO BLOG OU ESTÁ INATIVA";
	}
	else if(!empty($_GET['ano']) && !empty($_GET['mes']) && !empty($_GET['dia'])) {
		$title .= " - " . $_GET['dia'] . '/' . $_GET['mes'] . '/' . $_GET['ano'];
		$meta_keywords = "raca,boxer,blog,postagens,dia," . $_GET['dia'] . '/' . $_GET['mes'] . '/' . $_GET['ano'];
		$meta_description = "Esse é o site raça boxer, confira as postagens do dia: "  . $_GET['dia'] . '/' . $_GET['mes'] . '/' . $_GET['ano'];
	}
	else if(!empty($_GET['ano']) && !empty($_GET['mes'])) {
		$title .= " - " . $_GET['mes'] . '/' . $_GET['ano'];
		$meta_keywords = "raca,boxer,blog,postagens,mes," . $_GET['mes'] . '/' . $_GET['ano'];
		$meta_description = "Esse é o site raça boxer, confira as postagens do mes: " . $_GET['mes'] . '/' . $_GET['ano'];
	}
	else if(!empty($_GET['ano'])) {
		$title .= " - " . $_GET['ano'];
		$meta_keywords = "raca,boxer,blog,postagens,ano," . $_GET['ano'];
		$meta_description = "Esse é o site raça boxer, confira as postagens do ano de " . $_GET['ano'];
	}
}
else {
	$sql=" SELECT * FROM `pages` WHERE url='" . $page . "'";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);
	if($data['id'] > 0) {
		if(!empty($data['title'])) { $title = $data['title']; }
		if(!empty($data['meta_keywords'])) { $meta_keywords = $data['meta_keywords']; }
		if(!empty($data['meta_description'])) { $meta_description = $data['meta_description']; }
		if(!empty($data['content'])) { $content = $data['content']; }
	}
	
	if($page == 'fotos') {
		$p = !empty($_GET['p']) ? $_GET['p'] : '1';
		
		$title .= ' - Página ' . $p;
		$meta_keywords .= ',página,' . $p;
		$meta_description .= ' - Página ' . $p;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Raça Boxer - <?php echo $title; ?></title>
		<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
		<meta name="description" content="<?php echo $meta_description; ?>" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="/favicon.png" rel="shortcut icon" />
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="shortcut icon" />
		<link href="/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="icon" />
		<link href="/favicon.ico" rel="apple-touch-icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="apple-touch-icon" />
		<link type="text/css" href="/css/frontend.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo JQUERYUI_FOLDER . DS . JQUERYUI_THEME . DS . JQUERYUI_FILE; ?>" rel="stylesheet" />
		<script type="text/javascript" charset="UTF-8"  src="<?php echo JQUERY_FILE; ?>"></script>
		<script type="text/javascript" charset="UTF-8"  src="<?php echo JQUERYUI_JS; ?>"></script>
		<script type="text/javascript" charset="UTF-8"  src="/js/common.js"></script>
		
		<script type="text/javascript">var switchTo5x=true;</script>
		<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
		<script type="text/javascript">stLight.options({publisher: "685bd827-cfa6-4fd9-beff-5b2b2e47f7c7", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	</head>
	<body>
		<noscript>
			<center>
				<h1 style='color:#FF0000;'>Por favor habilite ou use um navegador com suporte a javascript e atualize a página.</h1>
				<h1 style='color:#FF0000;'>Please enable or use a browser that supports javascript and reload the page.</h1>
			</center>
		</noscript>
		<!-- header (html5) -->
		<header>
<?php
include_once "inc/layout/frontend/header.php";
?>
		</header>
		<!-- /header (html5) -->
		<!-- content (html5) -->
		<content>
			<div id="content-center-box">
				<div id="menu-left">
<?php
include_once "inc/layout/frontend/left-bar.php";
?>
				</div>
				<div id="content-box">
<?php
if(is_file("inc/layout/frontend/$page.php")) {
	include_once "inc/layout/frontend/$page.php";
}
else if(!empty($content)) {
	echo $content;
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
					var disqus_url = 'http://<?php echo $_SERVER['SERVER_NAME'] . "/" . $_GET['page'] . ".html"; ?>';
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
<?php
}
?>
					<!-- PREVIEW FACE -->
					<div class="main-space"></div>
					<div class="title-bar"><a name="previewface" href="http://www.facebook.com/racaboxer" target="_blank">VEJA UM PREVIEW DO NOSSA FÃ PAGE NO FACEBOOK</a></div>
					<div id="fb-root"></div>
					<script>
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=162744593790875";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like-box" data-href="http://www.facebook.com/racaboxer" data-width="610" data-show-faces="true" data-stream="true" data-header="false" style="background:#FFFFFF;margin:20px 0 0 0 !important;"></div>
					
				</div>
				<div id="ads-right">
<?php
include_once "inc/layout/frontend/right-bar.php";
?>
				</div>
			</div>
		</content>
		<!-- /content (html5) -->
		<!-- footer (html5) -->
		<footer>
<?php
include_once "inc/layout/frontend/footer.php";
?>
		</footer>
		<!-- /footer (html5) -->
	</body>
</html>
