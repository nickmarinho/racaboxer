<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . $_SESSION['SITE_IPATH'] . '/' . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$passwd=""; $logpasswd="";
	if(!empty($_POST['passwd'])){ $passwd="passwd='" . md5($_POST['passwd']) . "', "; $logpasswd="' - senha: '" . $_POST['passwd'] . "', "; }

	$_POST['born'] = implode("-", array_reverse(explode("/", $_POST['born'])));

	$sql="UPDATE " . $table . " SET
              name='" . $_POST['name'] . "',
              email='" . $_POST['email'] . "',
              login='" . $_POST['login'] . "',
              $passwd
              born='" . $_POST['born'] . "',
              active='" . $_POST['active'] . "',
              mdate='" . MDATE . "'
          WHERE id='" . $_POST['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	
	if($query) {
		foreach($_POST as $k => $v) {
			if(substr($k,0,5) == "perm_") {
				$permId = str_replace("perm_","",$k);

				if($v == 'X') {
					$sql = sprintf("DELETE FROM `acl_users_permissions` WHERE `userId` = %u AND `permId` = %u",$_POST['id'],$permId);
				} else {
					$sql = sprintf("REPLACE INTO `acl_users_permissions` SET `userId` = %u, `permId` = %u, `value` = %u, `cdate` = '%s'",$_POST['id'],$permId,$v,date ("Y-m-d H:i:s"));
				}

				$conn = Db_Instance::getInstance();
				$q = $conn->prepare($sql);
				$q->execute();
			}
		}

		foreach($_POST as $k => $v) {
			if(substr($k, 0, 5) == "role_") {
				$roleId = str_replace("role_", "", $k);

				if($v == '0' || $v == 'X') {
					$sql = sprintf("DELETE FROM `acl_users_roles` WHERE `userId` = %u AND `roleId` = %u", $_POST['id'], $roleId);
				} else {
					$sql = sprintf("REPLACE INTO `acl_users_roles` SET `userId` = %u, `roleId` = %u, `cdate` = '%s'", $_POST['id'], $roleId, date("Y-m-d H:i:s"));
				}

				$conn = Db_Instance::getInstance();
				$q = $conn->prepare($sql);
				$q->execute();
			}
		}

		createLog(("atualizou " . $table . " id '" . $_POST['id'] . $logpasswd . "' - comando: '" . $sql . "' "));
		echo "alert('Atualizado com sucesso');\n";
		
        $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
        if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
		echo "window.top.location.href=wl;\n";
	}
	else {
		createLog(("tentou atualizar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível atualizar');\n";
	}
	echo "</script>\n";
}
else {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";
	
	if(!empty($_GET['id'])) {
		$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
		createLog(("editando " . $table . " id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$query = $q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if(!empty($data['id'])) {
?>
	var passwdf = '<div id="editpasswddiv"><input id="passwd" class="ui-state-default ui-corner-all" name="passwd" size="75" type="text" value="" /></div>';

	$("#editpasswd").toggle(
		function(){
			$("#editpasswd").html("<span class='ui-button-text'>Esconder</span>");
			$("#editpasswd").after(passwdf);
			$("#editpasswddiv").show();
			$("#passwd").focus(function(){ $(this).addClass("ui-state-focus"); });
			$("#passwd").blur(function(){ $(this).removeClass("ui-state-focus"); });
			$("#passwd").after("<br />- Toda senha deve respeitar as maiúsculas e minúsculas.");
			$("#passwd").pstrength();
		},
		function(){
			$("#editpasswd").html("<span class='ui-button-text'>Editar</span>");
			$("#passwd").removeAttr("class");

			var uewdisplay = $(".ui-effects-wrapper").css("display");
			$(".ui-effects-wrapper").css("display", "none");

			$("#editpasswddiv").hide("slide", { direction: "left" }, 1000);
			setTimeout('$("#editpasswddiv").remove()', 1500);
			setTimeout('$(".ui-effects-wrapper").remove(); $(".ui-effects-wrapper").css("display", "' + uewdisplay + '");', 2000);
		}
	);
</script>
<style type="text/css">
#content .sampleedit, #content .sampleperms{margin:0 auto;width:700px;}
.sampleedit, .sampleperms{padding:5px 0;}
.sampleedit .label, .sampleperms .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;}
.sampleedit .label{width:140px;}
.sampleperms .label{width:275px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var save<?php echo $_GET['mod']; ?> = function (){
	var erro = 0;
	var msg = '';
	var name = $("#name");
	var email = $("#email");
	var active = $("input[name='active']:checked");

	if(name.length && name.val() == '') {
		erro++;
		msg += "Nome<br />";
		name.focus().select();
		name.addClass('ui-state-error');
	}
	else { name.removeClass('ui-state-error'); }

	if(email.length && email.val() == '') {
		erro++;
		msg += "Email<br />";
		email.focus().select();
		email.addClass('ui-state-error');
	}
	else if(IsValidEmail(email.val()) == false) {
		erro++;
		msg += "<b class='error'>Email Inválido</b><br />";
		email.focus().select();
		email.addClass('ui-state-error');
	}
	else { email.removeClass('ui-state-error'); }

<?php
$campos = array("login" => "Login","passwd" => "Senha","born" => "Data de Nascimento","born" => "Data de Nascimento");
echo gerajQueryCheckForm($campos);
?>

	if(active.val() == '' || active.val() == undefined) {
		erro++;
		msg += "Ativo<br />";
		active.focus().select();
		active.addClass('ui-state-error');
	}
	else { active.removeClass('ui-state-error'); }

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else { submitform('users'); }
};

$("#born").mask("99/99/9999");
$("html, body").animate({scrollTop:0}, '5000');
</script>
<link rel="stylesheet" href="<?php echo $_SESSION["SITE_IPATH"]; ?>/css/fileuploader.css" type="text/css" />
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="edit<?php echo $_GET['mod']; ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("vazio", "", "", "2", "Editando id: '" . $_GET['id'] . "'", "");
echo insertField("text", "name", "Nome", "75", $data['name'], "");
echo insertField("text", "email", "Email", "75", $data['email'], "");
echo insertField("text", "login", "Login", "75", $data['login'], "");
?>
		<tr>
			<td class='label ui-state-default'><label for="passwd">Senha: </label></td>
			<td><a class="button" id="editpasswd">Editar</a></td>
		</tr>
<?php
$born = !empty($data['born']) ? implode("/", array_reverse(explode("-", $data['born']))) : ''; 
echo insertField("text", "born", "Nascimento", "15", $born, "");
echo insertField("radio", "active", "Ativo", "", array("S" => "Sim","N" => "N&atilde;o"), $data['active']);
echo insertField("vazio", "", "", "2", "", "");
echo insertField("hidden", "id", "", "", $data['id'], "");
?>
	</table>

	<hr />

	<table id="groups_choices" class="sampleperms ui-widget ui-widget-content ui-corner-all ui-helper-reset">
		<tr><th colspan="3" class='ui-state-default'>Grupos</th></tr>
		<tr>
			<th>
				<a class="button" onclick="javascript:simAll();" title="Clique para selecionar todos em 'SIM'">Sim Todos</a>
				<a class="button" onclick="javascript:naoAll();" title="Clique para selecionar todos em 'NÃO'">Não Todos</a>
			</th>
			<th colspan="2" class='ui-state-default'>Membro ?</th>
		</tr>
<?php
			$roleAcl = new Acl($data['id']);
			$roles = $roleAcl->getAllRoles('full');

			foreach($roles as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default'><label>" . $v['roleName'] . "</label></td>\n";
				echo "			<td><label for='role_" . $v['id'] . "_1'><input type=\"radio\" name=\"role_" . $v['id'] . "\" id=\"role_" . $v['id'] . "_1\" value=\"1\"";

				if ($roleAcl->userHasRole($v['id'])) { echo " checked=\"checked\""; }

				echo " /><small>SIM</small></label></td>\n";
				echo "			<td><label for='role_" . $v['id'] . "_0'><input type=\"radio\" name=\"role_" . $v['id'] . "\" id=\"role_" . $v['id'] . "_0\" value=\"0\"";

				if (!$roleAcl->userHasRole($v['id'])) { echo " checked=\"checked\""; }

				echo " /><small>NÃO</small></label></td>\n";
				echo "		</tr>\n";
			}
echo insertField("vazio", "", "", "2", "");
?>
	</table>

	<hr />

	<table id="perms_choices" class="sampleperms ui-widget ui-widget-content ui-corner-all ui-helper-reset">
		<tr><th colspan="4" class='ui-state-default'>Permissões</th></tr>
		<tr>
			<th colspan="4" class='ui-state-default'>
				<a class="button" onclick="javascript:permiteAll();" title="Clique para selecionar todos em 'Permite'">Permite Todos</a>
				<a class="button" onclick="javascript:negaAll();" title="Clique para selecionar todos em 'Nega'">Nega Todos</a>
				<a class="button" onclick="javascript:herdaAll();" title="Clique para selecionar todos em 'Herdado'">Herda Todos</a>
			</th>
		</tr>
<?php
			$userAcl = new Acl($data['id']);
			$rPerms = $userAcl->perms;
			$aPerms = $userAcl->getAllPerms('full');
			$iVal="";

			foreach ($aPerms as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default' style='width:260px;'><label>" . $v['permName'] . "</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_1'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_1\" value=\"1\"";

				if(array_key_exists($v['permKey'], $rPerms) && $userAcl->hasPermission($v['permKey']) && $rPerms[$v['permKey']]['inheritted'] != true) { echo " checked=\"checked\""; }

				echo " />Permite</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_0'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_0\" value=\"0\"";

				if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['value'] === false && $rPerms[$v['permKey']]['inheritted'] != true) { echo " checked=\"checked\""; }

				echo " />Nega</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_X'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_X\" value=\"X\"";

				if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['inheritted'] == true || !array_key_exists($v['permKey'], $rPerms)) { echo " checked=\"checked\""; }

				if(array_key_exists($v['permKey'], $rPerms) && $rPerms[$v['permKey']]['value'] === true) {
					$iVal = '(Permitido)';
				} else {
					$iVal = '(Negado)';
				}

				echo " />Herdado $iVal</label></td>\n";
				echo "		</tr>\n";
			}

echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
?>
		<tr>
			<td colspan='4' style="text-align:center;">
				<input class="button" type="button" onclick="save<?php echo $_GET['mod']; ?>();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceleditpage('<?php echo $_GET['mod']; ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
</form>
<?php
		}
		else {
?>
	<h1 style="text-align:center;"><?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> não encontrado na nossa base de dados</h1>
<?php
		}
	}
}