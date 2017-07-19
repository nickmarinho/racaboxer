<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . $cfg['SITE_IPATH'] . '/' . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="SELECT * FROM " . $table . " WHERE id='" . $_POST['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();

	if($q->rowCount() > 0) {
		$data = $q->fetch(PDO::FETCH_ASSOC);

		createLog("enviou email para " . $table . " id '" . $_POST['id'] . "' ");

		if(!empty($data['name']) && !empty($data['email'])) {
			$bcc='';
			$bcc.="nickmarinho@gmail.com,";

			$subject = $_POST['assunto'];
			$mess = stripslashes($_POST['mensagem']);
			$file = file_get_contents("../inc/email-tpl.html");

			$message = str_replace("##HEADMESS##", $subject, $file);
			$message = str_replace("##MESS##", $mess, $message);
			$headers = 'From: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
			'Reply-To: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
			'To: ' . $data['name'] . ' <' . $data['email'] . '>' . "\r\n" .
			'Bcc: ' . $bcc . "\r\n" .
			'MIME-Version: 1.0' . "\r\n" .
			'Content-type: text/html; charset=utf-8' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

			mail('', $subject, $message, $headers);

			echo "alert('Enviado com sucesso');\n";
			//echo "$('#editemail').reset();\n";
		}
	}
	else {
		createLog(("tentou enviar email de " . $table . " sem sucesso - id do contato: '" . $_POST['id'] . "' "));
		echo "alert('Não foi possível enviar');\n";
	}
	echo "</script>\n";
}
else {
	if(!empty($_GET['id'])) {
		$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
		createLog(("enviando email para id '" . $_GET['id'] . "' - comando: '" . $sql . "' "));
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if(!empty($data['id'])) {
?>
<style type="text/css">
#content .sampleedit{width:700px;}
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var sendemail = function (){
	var erro = 0;
	var msg = '';
	var assunto = $("#assunto");
	var mensagem = CKEDITOR.instances['mensagem'];

	if(assunto.val() == '') {
		erro++;
		msg += "Assunto<br />";
		assunto.focus().select();
		assunto.addClass('ui-state-error');
	}
	else { assunto.removeClass('ui-state-error'); }

	if(mensagem) {
		var mensagem = CKEDITOR.instances['mensagem'].getData();
		if(mensagem == '') {
			erro++;
			msg += "Mensagem do Email<br />";
		}
		else { $("#mensagem").val(mensagem); }
	}
	else {
		var mensagem = $("#mensagem");
		if(mensagem.val() == '') {
			erro++;
			msg += "Mensagem do Email<br />";
			mensagem.focus().select();
			mensagem.addClass('ui-state-error');
		}
		else { mensagem.removeClass('ui-state-error'); }
	}

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else {
		var assunto = $("#assunto").val();
		var mensagem = $("#mensagem").val();
		var dataString = 'mensagem='+mensagem+'&assunto='+assunto;

		$("#editemail").submit(function() {
			$.ajax(this.action, {
				data: $(dataString).serializeArray(),
				iframe: true,
				processData: false
			}).complete(function(data) {
				console.log(data);
				$("#emailpage_contatos").dialog('close');
			});
		});

		$("#editemail").submit();
	}
};

//$("html, body").animate({scrollTop:0}, '5000');
if(CKEDITOR.instances['mensagem']) { CKEDITOR.instances['mensagem'].destroy(true); };
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="editemail" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertFieldViewAddEdit("titulo", "Destinatário", $data['name'] . " &lt;" . $data['email'] . "&gt;");
echo insertField("text", "assunto", "Assunto", "71", "", "");
echo insertField("textarea", "mensagem", "Mensagem", "cols='69' rows='15'", "", "");
echo insertField("vazio", "", "", "2", "", "");
echo insertField("hidden", "id", "", "", $data['id'], "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="sendemail();" value="Enviar" title="Clique para Enviar" />
				<input class="button" type="button" onclick="$('#emailpage_<?php echo $_GET['mod']; ?>').dialog('close');" value="Fechar" title="Clique para fechar a janela" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
</form>
<?php
			include_once '../js/ckeditor/ckeditor.php';
			$ckeditor = new CKEditor();
			$ckeditor->basePath = $_SESSION['SITE_IPATH'] . '/js/ckeditor/';
			$ckeditor->config = array('language' => "pt-br");

			$_SESSION['ckfinder_baseUrl'] = $_SESSION['SITE_IPATH'] . "/img/emails/";
			include_once '../js/ckfinder/ckfinder.php';

			$ckfinder = new CKFinder();
			$ckfinder->BasePath = $_SESSION['SITE_IPATH'] . '/js/ckfinder/'; // Note: the BasePath property in the CKFinder class starts with a capital letter.
			$ckfinder->config = array(
				'CheckDoubleExtension' => true,
				'language' => 'pt-br',
				'removePlugins' => 'help,basket,flashupload',
				'SecureImageUploads' => true
			);

			$ckfinder->SetupCKEditorObject($ckeditor);
			$ckeditor->replace('mensagem');
		}
		else {
?>
	<h1 style="text-align:center;">contato não encontrado na nossa base de dados</h1>
<?php
		}
	}
}