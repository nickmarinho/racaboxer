			<div id="header_top">
				<a href="/" title="Voltar para página inicial"><div id="logo"></div></a>
				<div id="header_box">
					<div id="header_ads">
						<!--
						<div id="header_ads_ml">
							<iframe
								frameborder="0"
								height="90"
								marginheight="0"
								marginwidth="0"
								scrolling="no"
								src="http://smartad.mercadolivre.com.br/jm/SmartAd?tool=6109427&creativity=39401&new=Y&ovr=N&bgcol=EDC485&brdcol=000000&txtcol=006600&lnkcol=0000FF&hvrcol=FF0000&prccol=FF0000&word=cão&word=boxer&word=cachorro&site=MLB"
								width="728"
								>
							</iframe>
						</div>
						-->
						<div id="header_ads_google">
							<script type="text/javascript">
								<!--
									google_ad_client = "ca-pub-6309972780716096";
									google_ad_slot = "6718004074";
									google_ad_width = 728;
									google_ad_height = 90;
								//-->
							</script>
							<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
							
						</div>
					</div>
					<div id="header_bottom">
						<div id="header_rss">
							<a href="/rss.html" title="Assine nosso canal Atom Feed"><img alt="" src="/img/layout/frontend/header-rss.png" /></a>
						</div>
						<div id="header_facebook">
							<a href="#previewface" title="Acompanhe novidades no Facebook"><img alt="" src="/img/layout/frontend/header-facebook.png" /></a>
						</div>
						<div id="header_clock-bar"><?php
							echo "								" . date("d") . " de " . getMesNome(date("m")) . " de " . date("Y");
						?></div>
						<div id="header_search-bar">
							<form action="" id="busca_frm" method="" name="busca_frm">
								<input id="w" name="w" type="text" value="faça sua busca aqui no site..." />
								<input id="busca_btn" name="busca_btn" type="button" />
							</form>
						</div>
					</div>
				</div>
			</div>
			<div id="menu">
				<div id="menu-container">
<?php
$menuclasshom=""; $menuclassblo=""; $menuclassdoa=""; $menuclassfot=""; $menuclassloj=""; $menuclassmat=""; $menuclasscon="";
switch($page) {
	case 'home':
		$menuclasshom="home-active";
	break;
	case 'blog':
		$menuclassblo="blog-active";
	break;
	case 'doacoes':
		$menuclassdoa="doacoes-active";
	break;
	case 'fotos':
		$menuclassfot="fotos-active";
	break;
	case 'loja':
		$menuclassloj="loja-active";
	break;
	case 'materias':
		$menuclassmat="materias-active";
	break;
	case 'contato':
		$menuclasscon="contato-active";
	break;
}
?>
					<a href="/"><div id="home" class="<?php echo $menuclasshom; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/blog"><div id="blog" class="<?php echo $menuclassblo; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/doacoes.html"><div id="doacoes" class="<?php echo $menuclassdoa; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/fotos.html"><div id="fotos" class="<?php echo $menuclassfot; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/loja.html"><div id="loja" class="<?php echo $menuclassloj; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/materias.html"><div id="materias" class="<?php echo $menuclassmat; ?>"></div></a>
					<div class="menu-separator"></div>
					<a href="/contato.html"><div id="contato" class="<?php echo $menuclasscon; ?>"></div></a>
				</div>
			</div>
