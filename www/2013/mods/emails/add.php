<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo "<script charset='" . JSCHARSET . "' type='text/javascript' src='" . JQUERY_FILE . "'></script>\n";
	echo "<script charset='" . JSCHARSET . "' type='text/javascript'>\n";

	$sql="INSERT INTO " . $table . " SET
                name='" . $_POST['name'] . "',
                email='" . $_POST['email'] . "',
                active='" . $_POST['active'] . "',
                cdate='" . CDATE . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$id = $q->execute();

	if($id) {
		createLog(("cadastrou novo " . $table . " id '" . $id . "' - comando: '" . $sql . "' "));
		echo "alert('Cadastrado com sucesso');\n";
        $returnpage = !empty($_GET['returnpage']) ? $_GET['returnpage'] : "?mod=" . $_GET['mod'];
        if(!empty($returnpage)) { echo "var wl='" . $_SESSION["SITE_IPATH"] . "/admin/" . $returnpage . "&done=1';\n"; }
		echo "window.top.location.href=wl;\n";
	}
	else {
		createLog(("tentou cadastrar " . $table . " sem sucesso - comando: '" . $sql . "' "));
		echo "alert('Não foi possível cadastrar');\n";
	}
	echo "</script>\n";
}
else {
?>
<style type="text/css">
.sampleedit{padding:5px 0;}
.sampleedit .label{padding:0 5px 0 0;text-align:right;text-transform:uppercase;width:100px;}
</style>
<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
var save<?php echo str_replace("-", "", $_GET['mod']); ?> = function (){
	var erro = 0;
	var msg = '';
<?php
$campos = array("name" => "Name",
                "email" => "Email");
echo gerajQueryCheckForm($campos);
?>
	var active = $("input[name='active']:checked");
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
	else { submitform('<?php echo $_GET['mod']; ?>'); }
};

var pos = $(".sampleedit tr:first-child").position();
$("html, body").animate({scrollTop:pos.top}, '5000');

if(CKEDITOR.instances['rss']) { CKEDITOR.instances['rss'].destroy(true); };
if(CKEDITOR.instances['post']) { CKEDITOR.instances['post'].destroy(true); };
</script>
<form action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>" encoding="multipart/form-data" enctype="multipart/form-data" id="add<?php echo str_replace("-", "", $_GET['mod']); ?>" method="post">
	<table class="sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset">
<?php
echo insertField("text", "name", "Name", "75", "", "");
echo insertField("text", "email", "Email", "75", "", "");
echo insertField("radio", "active", "Ativo", "", array("S" => "Sim","N" => "N&atilde;o"), "S");
echo insertField("vazio", "", "", "2", "", "");
?>
		<tr>
			<td colspan='2' style="text-align:center;">
				<input class="button" type="button" onclick="save<?php echo str_replace("-", "", $_GET['mod']); ?>();" value="Enviar" title="Clique para Enviar" />
				<input class="button" type="button" onclick="canceladdpage('<?php echo str_replace("-", "", $_GET['mod']); ?>');" value="Cancelar" title="Clique para Cancelar" />
			</td>
		</tr>
<?php
echo insertField("vazio", "", "", "2", "");
?>
	</table>
</form>
<?php
}