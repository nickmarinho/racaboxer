				<ul class="menu"><!-- BEGIN MENU -->
					<li id="home"><a class="first" href="#" onclick="location='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/';" title="P&aacute;gina Inicial">Home</a></li><!-- End Home Item -->
<?php
if(!empty($_SESSION['userLogged'])) { echo generateMenus(); }
?>
					<li id="logout" class="nodrop right">
						<a class="first" href="#" onclick="if(confirm('Deseja realmente sair  ?')) { location='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?logout'; }; " style="color: rgb(255, 0, 0) ! important;">Sair</a>
					</li>
				</ul>
<?php
