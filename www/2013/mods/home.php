<style type="text/css">
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:150px;}
</style>

<!-- END LASTLOGIN -->
<table class='sample ui-widget-header ui-corner-all' style="margin:5px auto;">
	<tbody>
		<tr>
			<td>
				<?php //echo '<a class="">Senha="' . geraSenha() . '"</a><br /><br />'; ?>
				<a class="button">Seu &uacute;ltimo login foi em: <?php echo $_SESSION['userLastLogin']; ?></a>&nbsp;<br />
				<br />
				Para mudar seus dados <a href="#" onclick="javascript:location='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=profile';" class="button">clique aqui</a>
			</td>
		</tr>
	</tbody>
</table>
<!-- END LASTLOGIN -->
