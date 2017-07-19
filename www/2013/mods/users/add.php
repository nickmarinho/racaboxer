<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . $_SESSION['SITE_IPATH'] . '/' . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$passwd=""; $logpasswd="";
	if(!empty($_POST['passwd'])){ $passwd="passwd='" . md5($_POST['passwd']) . "', "; $logpasswd="' - senha: '" . $_POST['passwd'] . "', "; }

	$_POST['born'] = implode("-", array_reverse(explode("/", $_POST['born'])));

	$sql="INSERT INTO " . $table . " SET
              name='" . $_POST['name'] . "',
              email='" . $_POST['email'] . "',
              login='" . $_POST['login'] . "',
              $passwd
              born='" . $_POST['born'] . "',
              active='" . $_POST['active'] . "',
              cdate='" . CDATE . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$id = $conn->lastInsertId();
	
	if($id) {
		foreach($_POST as $k => $v) {
			if(substr($k,0,5) == "perm_") {
				$permId = str_replace("perm_","",$k);

				if($v == 'X') {
					$sqlaup = sprintf("DELETE FROM `acl_users_permissions` WHERE `userId` = %u AND `permId` = %u",$id,$permId);
				} else {
					$sqlaup = sprintf("REPLACE INTO `acl_users_permissions` SET `userId` = %u, `permId` = %u, `value` = %u, `cdate` = '%s'",$id,$permId,$v,date ("Y-m-d H:i:s"));
				}

				$connaup = Db_Instance::getInstance();
				$qaup = $connaup->prepare($sqlaup);
				$qaup->execute();
			}
		}

		foreach($_POST as $k => $v) {
			if(substr($k, 0, 5) == "role_") {
				$roleId = str_replace("role_", "", $k);

				if($v == '0' || $v == 'X') {
					$sql = sprintf("DELETE FROM `acl_users_roles` WHERE `userId` = %u AND `roleId` = %u", $id, $roleId);
				} else {
					$sql = sprintf("REPLACE INTO `acl_users_roles` SET `userId` = %u, `roleId` = %u, `cdate` = '%s'", $id, $roleId, date("Y-m-d H:i:s"));
				}

				$conn = Db_Instance::getInstance();
				$q = $conn->prepare($sql);
				$q->execute();
			}
		}

		createLog(("cadastrou novo " . $table . " id '" . $id . "' - comando: '" . $sql . "' "));
		echo "alert('Cadastrado com sucesso');\n";
		
        $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
        if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
		echo "window.top.location.href=wl;\n";
	}
	else {
		createLog(("tentou cadastrar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível cadastrar');\n";
		echo "alert('".urlencode($sql)."')";
	}
	echo "</script>\n";
}
else {
?>
<style type="text/css">
#content .sampleedit, #content .sampleperms{margin:0 auto;width:700px;}
.sampleedit, .sampleperms{padding:5px 0;}
.sampleedit .label, .sampleperms .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;}
.sampleedit .label{width:140px;}
.sampleperms .label{width:275px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var podesalvar = 'S';
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
	else if(podesalvare == 'N') {
		alert("Por favor verifique o campo Email \n\n Talvez já exista um deles em algum Morador já cadastrado anteriormente");
	}
	else if(podesalvarl == 'N') {
		alert("Por favor verifique os campos Login \n\n Talvez já exista um deles em algum Morador já cadastrado anteriormente");
	}
	else { submitform('<?php echo $_GET['mod']; ?>'); }
};

$("#born").mask("99/99/9999");
$("#passwd").pstrength();

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');

$("#email").change(function(){
	if($("#chkexistse").length) { $("#chkexistse").remove(); }
	$.get('?mod=<?php echo $_GET['mod']; ?>', { a : 'checkifexists', ajax : '1', campo : 'email', valor : $("#email").val() }, function(data){
		if(data == 'S') { $("#email").after("<strong id='chkexistse'><br />Email já cadastrado por favor escolha outro</strong>"); podesalvare='N'; }
		else { if($("#chkexistse").length) { $("#chkexistse").remove(); } podesalvare='S'; }
	});
});

$("#login").change(function(){
	if($("#chkexistsl").length) { $("#chkexistsl").remove(); }
	$.get('?mod=<?php echo $_GET['mod']; ?>', { a : 'checkifexists', ajax : '1', campo : 'login', valor : $("#login").val() }, function(data){
		if(data == 'S') { $("#login").after("<strong id='chkexistsl'><br />Login já cadastrado por favor escolha outro</strong>"); podesalvarl='N'; }
		else { if($("#chkexistsl").length) { $("#chkexistsl").remove(); } podesalvarl='S'; }
	});
});
</script>
<link rel="stylesheet" href="<?php echo $_SESSION["SITE_IPATH"]; ?>/css/fileuploader.css" type="text/css" />
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="add<?php echo $_GET['mod']; ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "name", "Nome", "75", "", "");
echo insertField("text", "email", "Email", "75", "", "");
echo insertField("text", "login", "Login", "75", "", "");
echo insertField("text", "passwd", "Senha", "75", "", "");
echo insertField("text", "born", "Nascimento", "15", "", "");
echo insertField("radio", "active", "Ativo", "", array("S" => "Sim","N" => "N&atilde;o"), "S");
echo insertField("vazio", "", "", "2", "", "");
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
			$roleAcl = new Acl();
			$roles = $roleAcl->getAllRoles('full');

			foreach($roles as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default'><label>" . $v['roleName'] . "</label></td>\n";
				echo "			<td><label for='role_" . $v['id'] . "_1'><input type=\"radio\" name=\"role_" . $v['id'] . "\" id=\"role_" . $v['id'] . "_1\" value=\"1\" /><small>SIM</small></label></td>\n";
				echo "			<td><label for='role_" . $v['id'] . "_0'><input type=\"radio\" name=\"role_" . $v['id'] . "\" id=\"role_" . $v['id'] . "_0\" value=\"0\" checked /><small>NÃO</small></label></td>\n";
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
			$userAcl = new Acl();
			$aPerms = $userAcl->getAllPerms('full');

			foreach ($aPerms as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default' style='width:260px;'><label>" . $v['permName'] . "</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_1'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_1\" value=\"1\" />Permite</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_0'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_0\" value=\"0\" />Nega</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_X'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_X\" value=\"X\" />Herda</label></td>\n";
				echo "		</tr>\n";
			}

echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
?>		<tr>
			<td colspan='4' style="text-align:center;">
				<input class="button" type="button" onclick="save<?php echo $_GET['mod']; ?>();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceladdpage('<?php echo $_GET['mod']; ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
	<script charset='<?php echo JSCHARSET; ?>' type="text/javascript">
		$(document).ready(function(){
			$("#passwd").after("<br />- Toda senha deve respeitar as maiúsculas e minúsculas.");
		});
	</script>
</form>
<?php
}