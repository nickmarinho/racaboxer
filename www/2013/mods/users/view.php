<style type="text/css">
.preview {margin:0 auto;padding:0;width:99.9%;}
.preview tbody td:first-child{background:#DEDEDE;text-align:right;padding-right:5px;width:120px;}
.view {border:1px dotted #DEDEDE;margin:0 auto;padding:10px;width:95%;}
</style>
<div class="ui-widget ui-widget-content ui-corner-all">
	<br />
	<fieldset>
		<legend>Visualizando Dados de <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?></legend>
		<div id="printcontent">
			<table class="preview">
				<tbody>
<?php
if(!empty($_GET['id'])) {
	$sql = "SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "' ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$data = $q->fetch(PDO::FETCH_ASSOC);

	echo insertFieldView("active", "<b>Ativo</b>", $data['active'] == 'S' ? 'Sim' : 'Não');
	echo insertFieldView("nome", "<b>Nome</b>", $data['name']);
	echo insertFieldView("email", "<b>Email</b>", $data['email']);
	echo insertFieldView("login", "<b>Login</b>", $data['login']);

	if(empty($data['foto']))  $foto="não enviada";
	else $foto="<img style='border:1px dotted #DEDEDE;box-shadow:1px 1px #000000;cursor:pointer;height:16px;width:16px;' onclick='javascript:previewimage(\"" . $data['foto'] . "\");' src='" . $data['foto'] . "' title='Ver imagem em tamanho original' />";

	$nascimento = $data['born'] <> '0000-00-00' ? implode("/", array_reverse(explode("-", $data['born']))) : 'não informado';
	$cadastrado = $data['cdate'] <> '0000-00-00 00:00:00' ? date("d/m/Y", strtotime($data['cdate'])) : '';
	$alterado = $data['mdate'] <> '0000-00-00 00:00:00' ? date("d/m/Y", strtotime($data['mdate'])) : 'nunca';
	$lastlogin = $data['lastlogin'] <> '0000-00-00 00:00:00' ? date("d/m/Y g:ia", strtotime($data['lastlogin'])) : 'nunca';
    
	echo insertFieldView("foto", "<b>Foto</b>", $foto);
	echo insertFieldView("born", "<b>Nascimento</b>", $nascimento);
	echo insertFieldView("cdate", "<b>Cadastrado</b>", $cadastrado);
	echo insertFieldView("mdate", "<b>Alterado</b>", $alterado);
	echo insertFieldView("mdate", "<b>Último Login</b>", $lastlogin);
}
?>
				</tbody>
			</table>
			<hr />
			<table class="preview">
				<tr><th colspan="2" class='ui-state-default'>Grupos - Membro ?</th></tr>
<?php
			$roleAcl = new Acl($data['id']);
			$roles = $roleAcl->getAllRoles('full');

			foreach($roles as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default'><label>" . $v['roleName'] . "</label></td>\n";
				echo "			<td>";

				if ($roleAcl->userHasRole($v['id'])) { echo "SIM"; }
				else if (!$roleAcl->userHasRole($v['id'])) { echo "NÃO"; }

				echo "</td>\n";
				echo "		</tr>\n";
			}
?>
			</table>
			<hr />
			<table class="preview">
				<tr><th colspan="2" class='ui-state-default'>Permissões</th></tr>
<?php
			$userAcl = new Acl($data['id']);
			$rPerms = $userAcl->perms;
			$aPerms = $userAcl->getAllPerms('full');

			foreach ($aPerms as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default' style='width:260px;'><label>" . $v['permName'] . "</label></td>\n";
				echo "			<td>";

				if(array_key_exists($v['permKey'], $rPerms) && $userAcl->hasPermission($v['permKey']) && $rPerms[$v['permKey']]['inheritted'] != true) { echo "SIM"; }
				else if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['value'] === false && $rPerms[$v['permKey']]['inheritted'] != true) { echo "NÃO"; }
				else if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['inheritted'] == true || !array_key_exists($v['permKey'], $rPerms)) {
					if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['value'] === true) {
						echo 'Herdado (Permitido)';
					} else {
						echo 'Herdado (Negado)';
					}
				}

				echo "</td>\n";
				echo "		</tr>\n";
			}
?>
			</table>


		</div>
		<br />
		<br />
		<br />
		<center>
			<input type="button" class="button" onclick="printhis('Dados de <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?>');" value="Imprimir" />&nbsp;
			<input type="button" class="button" onclick="$('#viewpage_<?php echo $_GET['mod']; ?>').dialog('close');" value="Fechar" /><br /><br />
			<b>Você pode fechar a janela apertando ESC</b>
		</center>
	</fieldset>
	<br />
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.button').button();
});
</script>
