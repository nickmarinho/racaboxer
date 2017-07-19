<script type='text/javascript' charset="utf-8">
$(document).ready(function(){
	//$("#link_profile").addClass("ui-tabs-selected ui-state-active");
	$("#link_profile").addClass("current");
});
<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	$passwd=""; $logpasswd="";
	if(!empty($_POST['passwd'])) { $passwd="passwd='" . md5($_POST['passwd']) . "', "; $logpasswd="' senha: '" . $_POST['passwd'] . "' "; }
	
	$_POST['born'] = implode("-", array_reverse(explode("/", $_POST['born'])));

	$sql="UPDATE users SET
			name='" . $_POST['name'] . "',
			email='" . $_POST['email'] . "',
			born='" . $_POST['born'] . "',
			$passwd
			mdate='" . MDATE . "'
		WHERE id='" . $_SESSION['userId'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	
	if($query) {
		createLog(("modificou seu email e/ou senha '" . $logpasswd . "' - comando: '" . $sql . "' "));

		echo "alert('Atualizado com sucesso";
		if(!empty($passwd)) {
			echo ", por favor, faça login novamente.');\n";
			$_SESSION['mess'] = "Você trocou a senha, por favor faça login novamente";
			$_SESSION['mod'] = "profile";
			echo "window.parent.location='" . $_SESSION['SITE_IPATH'] . "/admin/?logout';\n";
		}
		else {
			echo "');\n";
			echo "window.parent.location='" . $_SESSION['SITE_IPATH'] . "/admin/?mod=profile';\n";
		}
	}
	else {
		createLog(("tentou modificar seus dados pessoais sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível atualizar');\n";
		echo "window.parent.location='" . $_SESSION['SITE_IPATH'] . "/admin/?mod=profile';\n";
	}
	echo "</script>\n";
}
else {
	if(!empty($_SESSION['userId'])) {
		$sql="SELECT * FROM users WHERE id='" . $_SESSION['userId'] . "'; ";
		createLog(("modificando dados pessoais"));
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$query = $q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if(!empty($data['id'])) {
?>
	var passwdf = '<div id="editpasswddiv"><input class="ui-state-default ui-corner-all" id="passwd" name="passwd" size="60" type="password" value="" /></div>';

	$(document).ready(function(){
		$("#editpasswd").toggle(
			function() {
				$("#editpasswd").html("<span class='ui-button-text'>Esconder</span>");
				$("#editpasswd").before(passwdf);
				$("#editpasswddiv").show();
				$("#passwd").focus(function(){ $(this).addClass("ui-state-focus"); });
				$("#passwd").blur(function(){ $(this).removeClass("ui-state-focus"); });
			},
			function() {
				$("#editpasswd").html("<span class='ui-button-text'>Editar</span>");
				$("#passwd").removeAttr("class");
	
				var uewdisplay = $(".ui-effects-wrapper").css("display");
				$(".ui-effects-wrapper").css("display", "none");
	
				$("#editpasswddiv").hide("slide", { direction: "left" }, 1000);
				setTimeout('$("#editpasswddiv").remove()', 1500);
				setTimeout('$(".ui-effects-wrapper").remove(); $(".ui-effects-wrapper").css("display", "' + uewdisplay + '");', 2000);
			}
		);
		
		$("#email").focus(function(){ $(this).addClass("ui-state-focus"); });
		$("#email").blur(function(){ $(this).removeClass("ui-state-focus"); });
		$("#email").focus().select();
		$("#born").mask("99/99/9999");
	});
</script>
<style type="text/css">
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:150px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var saveprofile = function (){
	var erro = 0;
	var msg = '';
	var name = $("#name");
	var email = $("#email");
	var born = $("#born");
	var passwd = $("#passwd");

	if(name.val() == '') {
		erro++;
		msg += "Nome<br />";
		name.focus().select();
		name.addClass('ui-state-error');
	}
	else { name.removeClass('ui-state-error'); }
	
	if(email.val() == '') {
		erro++;
		msg += "Email<br />";
		email.focus().select();
		email.addClass('ui-state-error');
	}
	else if(IsValidEmail(email.val()) == false) {
		erro++;
		msg += "<b class='error'>Email Inválido<br />";
		email.focus().select();
		email.addClass('ui-state-error');
	}
	else { email.removeClass('ui-state-error'); }

	if(born.val() == '') {
		erro++;
		msg += "Nascimento<br />";
		born.focus().select();
		born.addClass('ui-state-error');
	}
	else { born.removeClass('ui-state-error'); }
	
	if(passwd.val() == '') {
		erro++;
		msg += "Senha<br />";
		passwd.focus().select();
		passwd.addClass('ui-state-error');
	}
	else { passwd.removeClass('ui-state-error'); }

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else { submitform('profile'); }
};
</script>
<table class='sample ui-widget-header ui-corner-all' style="margin:5px auto;">
	<tbody>
		<tr>
			<td style="text-align:center;">
			    <a class="button">Seu &uacute;ltimo login foi em: <?php echo $_SESSION['userLastLogin']; ?></a><br /><br />
			    Caso queira mudar seu email e/ou senha, por favor, use o formul&aacute;rio abaixo.
			</td>
		</tr>
	</tbody>
</table>
<hr />
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="editprofile" method="post" target="addeditifrm">
	<table class="sample ui-widget-header ui-corner-all">
		<tr>
			<td class="label"><label for="name">Nome: </label></td>
			<td><input class="ui-state-default ui-corner-all" id="name" name="name" size="60" type="text" value="<?php echo $data['name']; ?>" /></td>
		</tr>
		<tr>
			<td class="label"><label for="email">Email: </label></td>
			<td>
				<input class="ui-state-default ui-corner-all" id="email" name="email" size="60" type="text" value="<?php echo $data['email']; ?>" readonly="readonly" />
				<br /><small><small>Por várias razões não é possível alterar o email de cadastro</small></small>
			</td>
		</tr>
		<tr>
			<td class="label"><label for="born">Nascimento: </label></td>
			<td><input class="ui-state-default ui-corner-all" id="born" name="born" size="11" type="text" value="<?php
			echo 
			//date("d/m/Y", strtotime($data['born']));
			implode("/", array_reverse(explode("-", $data['born'])));
			?>" /></td>
		</tr>
		<tr>
			<td class="label"><label for="passwd">Senha: </label></td>
			<td><a class="button" id="editpasswd">Editar</a></td>
		</tr>
		<tr>
			<td class="label"><label for="foto">Foto: </label></td>
			<td style="vertical-align:top;">
				<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($data['email']))); ?>.jpg?s=80" style="border:0;" />
				<br /><br /><small><small>Para ter sua imagem aqui, cadastre o mesmo email no gravatar</small></small>
			</td>
		</tr>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="saveprofile();" value="Salvar" title="Clique para Salvar" />
			</td>
		</tr>
	</table>
</form>
<?php
		}
		else {
			echo "alert('Desculpe, mas ocorreu um erro ao buscar seu perfil, refaça login e tente novamente');\n";
			echo "location='?logout';\n";
			echo "</script>\n";
		}
	}
	else { echo "<script type='text/javascript'>location='?logout';</script>\n"; }
}
