<?php
$included = strtolower(realpath(__FILE__)) != strtolower(realpath($_SERVER['SCRIPT_FILENAME']));

if(!$included) {
	session_start();
}

if(!empty($_SESSION['userLogged']) && $_SESSION['userLogged'] <> true) {
	$_SESSION['mess'] = "Por favor, faça seu login para ver essa página";
	$_SESSION['mod'] = "themes";

	echo "<script type='text/javascript'>";
	echo "location='/admin/?login&url=" . $url . "';";
	echo "</script>";
}

?>
				<script type='text/javascript' charset='utf-8'>
				$(document).ready(function(){
					//$('#link_themes').addClass('ui-tabs-selected ui-state-active');
					$('#link_themes').addClass('current');
				});
				</script>
				<!-- <script type='text/javascript' src='http://jqueryui.com/themeroller/themeswitchertool/'></script> -->
				<script type='text/javascript' src='/js/themeswitchertool.js'></script>
				<h1 id="pagetitle">Para mudar a aparência escolha um tema abaixo</h1>
				<div id='switcher'></div>
				<script type='text/javascript'>$('#switcher').themeswitcher();</script>
<?php
