<?php
if(isset($_GET['newmess'])) {
	$sql="INSERT INTO `users-birthmess` SET
              id_birth='" . $_GET['id_birth'] . "',
              id_mess='" . $_GET['id_mess'] . "',
              message='" . urldecode($_GET['message']) . "',
              cdate='" . $_GET['cdate'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$id = $q->execute();
	
	if($id) {
		echo '							<table class="ui-widget ui-widget-content ui-corner-all ui-helper-reset" style="display:none;opacity:0.10;">' . "\n";
		echo '								<tbody>' . "\n";

		echo insertFieldViewAddEdit("data", "Nº " . $id, date("d/m/Y g:ia", strtotime($_GET['cdate'])));
		echo insertFieldViewAddEdit("enviadapor", "Enviada por", $_SESSION['userName']);
		echo insertFieldViewAddEdit("mensagem", "Mensagem", nl2br(urldecode($_GET['message'])));

		echo '								</tbody>' . "\n";
		echo '							</table>' . "\n";
	}
}
else {
?>
<style type="text/css">
#content .sampleedit{width:700px;}
.sample{padding:5px 0;}
.sample .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var responder = function (){
	var erro = 0;
	var msg = '';
	var message = $("#message");

	if(message.length && message.val() == '') {
		erro++;
		msg += "Digite a mensagem a enviar<br />";
		message.focus().select();
		message.addClass('ui-state-error');
	}
	else { message.removeClass('ui-state-error'); }

	if(erro) {
		if($("#errormsg").length){ $("#errormsg").remove(); }
		$('<div/>').attr('id','errormsg')
			.html(msg)
			.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
	}
	else {
		var message = encodeURI($('#message').val());

		$.get('?mod=users', { newmess:'1', ajax:'1', a:'birthmess', id_birth:'<?php echo $_GET['id']; ?>', id_mess:'<?php echo $_SESSION['userId']; ?>', cdate : $('#cdate').val(), message : message }, function(data){
			var datac = decodeURI(data);
			$("#mensagens").append(datac);
			$("td").not(".label").css("text-align", "left");
			$("#mensagens table:last-child").slideDown('3000').fadeIn('5000').animate({
				opacity: 1.00
			}, 2000, function(){});

			$("#formmensagens").slideUp('3000').fadeOut('5000').animate({
				opacity: 0
			}, 2000, function(){});
		});
	}
};

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');

$("#mensagens td").not(".label").css("background", "#FFFFFF");
$("#mensagens td").not(".label").css("text-align", "left");
$("#mensagens td .label").css("text-align", "right");
</script>
<link rel="stylesheet" href="<?php echo $_SESSION["SITE_IPATH"]; ?>/css/fileuploader.css" type="text/css" />
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="addusers" method="post" target="addeditifrm">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
		<thead class="ui-widget-header ui-corner-all">
			<tr><th class="ui-state-default ui-corner-tl ui-corner-tr" colspan="12"><a class="button">Mensagens de Aniversário</a></th></tr>
		</thead>
		<tbody>
			<tr>
				<td id="mensagens">
<?php
$sqlbm = "SELECT bm.*, u.name
			FROM `users-birthmess` bm, `users` u
			WHERE 1
				AND bm.id_birth='" . $_GET['id'] . "'
				AND u.id=bm.id_mess
				AND DATE_FORMAT(bm.cdate, '%Y%m') = '" . date("Ym") . "' ";
$connbm = Db_Instance::getInstance();
$qbm = $connbm->prepare($sqlbm);
$qbm->execute();
$rows = $qbm->rowCount();

if($rows) {
	while($databm = $qbm->fetch(PDO::FETCH_ASSOC)) {
		echo '							<table class="ui-widget ui-widget-content ui-corner-all ui-helper-reset">' . "\n";
		echo '								<tbody>' . "\n";

		echo insertFieldViewAddEdit("data", "Dia", date("d/m/Y g:ia", strtotime($databm['cdate'])));
		echo insertFieldViewAddEdit("enviadapor", "Enviada por", $databm['name']);
		echo insertFieldViewAddEdit("mensagem", "Mensagem", nl2br($databm['message']));

		echo '								</tbody>' . "\n";
		echo '							</table>' . "\n";
	}
}
else {
	echo "<center><h1>Não enviaram mensagem ainda, seja o primeiro</h1></center>\n";
}
?>
			</td>
		</tr>

<?php
$sqluem = "SELECT bm.*, u.name
			FROM `users-birthmess` bm, `users` u
			WHERE 1
				AND bm.id_birth='" . $_GET['id'] . "'
				AND bm.id_mess='" . $_SESSION['userId'] . "'
				AND DATE_FORMAT(bm.cdate, '%Y%m') = '" . date("Ym") . "' ";
$connuem = Db_Instance::getInstance();
$quem = $connuem->prepare($sqluem);
$quem->execute();
$rows = $quem->rowCount();

if($rows == 0) {
?>
		<tr id="formmensagens">
			<td>
				<form id="responder">
				<table class="ui-widget ui-widget-content ui-corner-all ui-helper-reset">
					<tbody>
<?php
echo insertFieldViewAddEdit("data", "Data", '<a href="#r' . $_GET['id'] . '" name="r' . $_GET['id'] . '">' . date("d/m/Y g:ia") . '</a>');
echo insertField("hidden", "cdate", "", "", date("Y-m-d H:i:s"), "");
echo insertField("textarea", "message", "Mensagem", "cols='68' rows='8' ", "", "");
echo insertField("vazio", "", "", "2", "", "");
?>
						<tr>
							<td colspan='2' style="text-align:center !important;">
								<input class="button" type="button" onclick="responder();" value="Enviar" title="Envie uma mensagem" />
								<input class="button" type="button" onclick="$('#birthmess').dialog('close');" value="Cancelar" title="Clique para Cancelar" />
							</td>
						</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
					</tbody>
				</table>
				</form>
			</td>
		</tr>
<?php
}
?>


<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
</form>
<?php
}