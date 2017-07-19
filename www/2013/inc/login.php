<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$url = !empty($_GET['url']) ? $_GET['url'] : !empty($_POST['url']) ? $_POST['url'] : $_SESSION['SITE_IPATH'] . '';

	// substr(md5($email), 0, 6))
	if(!empty($_POST['username']) && !empty($_POST['password'])) {
		$table="users";

		$sql="SELECT * FROM " . $table . " WHERE login = '" . $_POST['username'] . "' AND passwd = '" . md5($_POST['password']) . "' AND active='S' ; ";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);
		
		if($result['id'] > 0) {
			$acllogin = new Acl($result['id']);
			$_SESSION['userLogged'] = true;
			$_SESSION['userId'] = $result['id'];
			$_SESSION['userName'] = $result['name'];
			$_SESSION['userEmail'] = $result['email'];
			$_SESSION['userLogin'] = $result['login'];
			$_SESSION['userPasswd'] = $_POST['password'];
			$_SESSION['userBorn'] = $result['born'];
			$_SESSION['userLastLogin'] = $result['lastlogin'] <> '0000-00-00 00:00:00' ? date("d/m/Y H:i", strtotime($result['lastlogin'])) : 'nunca';
			$_SESSION['userGroups'] = $acllogin->userRoles;
			$_SESSION['mess'] = '';
			$_SESSION['mod'] = '';
			$cdate = getDateToDb();
			
			$sql="UPDATE " . $table . " SET lastlogin = '" . $cdate . "' WHERE id = '" . $result['id'] . "' ; ";
			$conn = Db_Instance::getInstance();
			$q = $conn->prepare($sql);
			$q->execute();

			createLog("entrou do sistema");
		}
		else {
			echo "alert('Usuário e/ou Senha inválidos ou Usuário não ativo');";
			createLog("tentativa de acesso ao site, erro: login ou senha inv&aacute;lidos ou usu&aacute;rio inativo: '" . $sql . "'");
			$mess = ("Usuário e/ou Senha inválidos ou Usuário não ativo");
			$_SESSION['mess'] = $mess;
		}
	}
	else {
		echo "alert('Usuário e/ou Senha não preenchidos corretamente');";
		createLog("tentativa de acesso ao site, erro: login ou senha vazios");
		$mess = ("Usuário e/ou Senha não preenchidos corretamente");
		$_SESSION['mess'] = $mess;
	}
	
	echo "location='" . $url . "';\n";
	echo "</script>\n";
	
	exit;
}

$mess="";
if(!empty($_SESSION['mess'])) { $mess=$_SESSION['mess']; }
?>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
$(document).ready(function(){
	if($("#username").length){ $("#username").focus(); }

	$("#password").pstrengthc();

	$('#username, #password').keyup(function (event) {
		if (event.keyCode == '13') { login(); }
		return false;
	});
});

var login = function(){
	$("#loginbtn").addClass("loading").attr('disabled', true);
	$("#loginfrm").submit();
};
</script>

						<form accept-charset="<?php echo JSCHARSET; ?>" method="post" action="<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?login" id="loginfrm">
							<table class="sample" style='margin:5px auto;width:400px;'>
								<tr><td colspan='2' id="logintitle"><a class="button">Entre com seu usuário e senha</a></td></tr>
								<tr><td class="username_label"><label for="username">Usu&aacute;rio: </label></td><td class="username"><input type="text" id="username" value="" name="username" size="40" /></td></tr>
								<tr><td class="password_label"><label for="password">Senha: </label></td><td class="password"><input type="password" id="password" value="" name="password" size="40" /></td></tr>
								<tr><td colspan='2' style='text-align:center;'><input class="button" id="loginbtn" type="button" value="Ok" onclick="login();" /><input name="url" id="url" type="hidden" value="<?php echo $url; ?>" /></td></tr>
								<tr><td colspan='2' style='text-align:center;'><br /></td></tr>
								<tr><td colspan='2' style='text-align:center;'><?php if(!empty($mess)) { echo '<a class="button error">' . $mess . '</a>'; } ?></td></tr>
							</table>
						</form>
