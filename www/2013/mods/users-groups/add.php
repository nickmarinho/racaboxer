<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="INSERT INTO " . $table . " SET
              roleName='" . $_POST['roleName'] . "',
              active='" . $_POST['active'] . "',
              cdate='" . CDATE . "';";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();

	$sqlid="SELECT id FROM " . $table . " ORDER BY id DESC LIMIT 1;";
	$connid = Db_Instance::getInstance();
	$qid = $connid->prepare($sqlid);
	$qid->execute();
	$dataid = $qid->fetch(PDO::FETCH_ASSOC);
	$id = $dataid['id'];

	if($id) {
		createLog(("cadastrou novo " . $table . " id '" . $id . "' - comando: '" . $sql . "' "));
		if($id) {
			foreach($_POST as $k => $v) {
				if(substr($k,0,5) == "perm_") {
					$permId = str_replace("perm_","",$k);

					if($v == 'X') {
						$sqlaup = sprintf("DELETE FROM `acl_roles_permissions` WHERE `roleId` = %u AND `permId` = %u",$id,$permId);
					} else {
						$sqlaup = sprintf("REPLACE INTO `acl_roles_permissions` SET `roleId` = %u, `permId` = %u, `value` = %u, `cdate` = '%s'",$id,$permId,$v,date ("Y-m-d H:i:s"));
					}

					$connaup = Db_Instance::getInstance();
					$qaup = $connaup->prepare($sqlaup);
					$qaup->execute();
				}
			}

			echo "alert('Cadastrado com sucesso');\n";
		
            $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
            if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
			echo "window.top.location.href=wl;\n";
		}
	}
	else {
		createLog(("tentou cadastrar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível cadastrar');\n";
		echo "alert('".$id."');\n";
	}

	echo "</script>\n";
	exit;
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
var saveusuariosgrupos = function (){
	var erro = 0;
	var msg = '';
	var roleName = $("#roleName");
	var active = $("input[name='active']:checked");

	if(roleName.val() == '') {
		erro++;
		msg += "Nome<br />";
		roleName.focus().select();
		roleName.addClass('ui-state-error');
	}
	else { roleName.removeClass('ui-state-error'); }

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
	else {
		$('#permissoes_chosen option').each(function(){ $(this).attr("selected", "selected"); });
		submitform('<?php echo $_GET['mod']; ?>');
	}
};

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');

var onetoright = function(){
	$('#permissoes_avaliable option:selected').remove().appendTo('#permissoes_chosen');
	$('#permissoes_chosen option').focus();
};

var alltoright = function(){
	$('#permissoes_avaliable option').remove().appendTo('#permissoes_chosen');
	$('#permissoes_chosen option').focus();
};

var onetoleft = function(){
	$('#permissoes_chosen option:selected').remove().appendTo('#permissoes_avaliable');
};

var alltoleft = function(){
	$('#permissoes_chosen option').remove().appendTo('#permissoes_avaliable');
};
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="add<?php echo $_GET['mod']; ?>" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "roleName", "Nome", "75", "", "");
echo insertField("radio", "active", "Ativo", "", array("S" => "Sim","N" => "N&atilde;o"), "S");
echo insertField("vazio", "", "", "2", "", "");
?>
	</table>

	<hr />

	<table id="perms_choices" class="sampleperms ui-widget ui-widget-content ui-corner-all ui-helper-reset">
		<tr><th colspan="4" class='ui-state-default'>Permissões</th></tr>
		<tr>
			<th colspan="4" class='ui-state-default'>
				<a class="button" onclick="javascript:permiteAll();" title="Clique para selecionar todos em 'Permite'">Permite Todos</a>
				<a class="button" onclick="javascript:negaAll();" title="Clique para selecionar todos em 'Nega'">Nega Todos</a>
			</th>
		</tr>
<?php
			$myAcl = new Acl();
			$aPerms = $myAcl->getAllPerms('full');

			foreach ($aPerms as $k => $v) {
				echo "		<tr>\n";
				echo "			<td class='label ui-state-default' style='width:260px;'><label>" . $v['permName'] . "</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_1'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_1\" value=\"1\" />Permite</label></td>\n";
				echo "			<td><label for='perm_" . $v['id'] . "_0'><input type=\"radio\" name=\"perm_" . $v['id'] . "\" id=\"perm_" . $v['id'] . "_0\" value=\"0\" />Nega</label></td>\n";
				echo "		</tr>\n";
			}

echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
echo insertField("vazio", "", "", "2", "");
?>
		<tr>
			<td colspan='4' style="text-align:center;">
				<input class="button" type="button" onclick="saveusuariosgrupos();" value="Salvar" title="Clique para Salvar" />
				<input class="button" type="button" onclick="canceladdpage('<?php echo $_GET['mod']; ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>

</form>
<?php
}