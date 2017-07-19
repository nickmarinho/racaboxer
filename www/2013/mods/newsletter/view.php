<style type="text/css">
.preview {margin:0 auto;padding:0;width:99.9%;}
.preview tbody td:first-child{background:#DEDEDE;text-align:right;padding-right:5px;width:120px;}
.view {border:1px dotted #DEDEDE;margin:0 auto;padding:10px;width:95%;}
</style>
<div class="ui-widget ui-widget-content ui-corner-all">
	<br />
	<fieldset>
		<legend>Visualizando <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?></legend>
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
	$emails = "";

	if($data['cdate'] <> "0000-00-00 00:00:00"){ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); }
	else{ $cdatenow = strtotime($data['cdate']); $cdatenow = date('d/m/Y g:ia', $cdatenow); }
	
	if(!empty($data['emails'])) {
		$emailsarr = unserialize($data['emails']);
		$emailsarrcnt = count($emailsarr);
		$emailscnt = 0;
		
		foreach($emailsarr as $k => $v) {
			if(!empty($v)) {
				$sqlema = "SELECT name, email FROM `emails` WHERE id='" . $v . "' ";
				$connema = Db_Instance::getInstance();
				$qema = $connema->prepare($sqlema);
				$qema->execute();
				$dataema = $qema->fetch(PDO::FETCH_ASSOC);

				if(!empty($dataema['name']) && !empty($dataema['email'])) {
					$emails .= "<span class='emailsaddress ui-widget-header' onclick='javascript:emailpage(\"emails\", \"" . $v . "\");' title='Clique para enviar email'>";
					$emails .= $dataema['name'] . " &lt;" . $dataema['email'] . "&gt;";
					$emails .= "</span>";
				}
			}
			
			$emailscnt++;
		}
	}

	echo insertFieldView("data", "Data", $cdatenow);
    echo insertFieldView("title", "<b>Título</b>", $data['title']);
    echo insertFieldView("content", "<b>Conteúdo</b>", stripslashes($data['content']));
    echo insertFieldView("emails", "<b>Emails</b>", $emails);
}
?>
				</tbody>
			</table>
		</div>
		<center>
			<input type="button" class="button" onclick="printhis('Dados de <?php echo $_SESSION[$_GET['mod']]['MODULE_TITLE']; ?> - ID: <?php echo $_GET['id']; ?>');" value="Imprimir" />&nbsp;
			<input type="button" class="button" onclick="$('#viewpage_<?php echo $_GET['mod']; ?>').dialog('close');" value="Fechar" /><br /><br />
			<b>Você pode fechar a janela apertando ESC</b>
		</center>
	</fieldset>
	<br />
</div>
