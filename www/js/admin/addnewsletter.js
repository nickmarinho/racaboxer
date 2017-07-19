var body  = "<div style='background:#EAA946;border-radius:4px 4px 4px 4px;box-shadow: 0.4em 0.4em 0.4em rgba(0, 0, 0, 0.5);font-size:1.0em;";
	body += "font-weight:bold;height:140px;margin:10px auto 0px;padding:1em 2em;text-align:center;width:400px;'><p>";
	body += "<a href='http://www.racaboxer.com.br/' target='_blank'>";
	body += "<img alt='RB Logo' src='http://www.racaboxer.com.br/img/logo/banner120x80.gif' style='border:0px solid;float:left;margin:5px;";
	body += "width: 120px; height: 80px;' /></a></p><p>Mensagem</p></div>";

$(document).ready(function(){
	var content_instance = CKEDITOR.instances['content'];
	if(content_instance){ CKEDITOR.remove(content_instance); }
	
	$('#content').val(body);
	$('#content').ckeditor();
	$("#emails-label").attr('style','vertical-align:top;');
	$(".button").button();
	
	$("#submitbutton").click(function(){
		var content_editor_data = CKEDITOR.instances.content.getData();
		$("#content").text(content_editor_data);
	});
	
	$("input:radio[name='emails']").change(function(){
		//echo "			var emailsvalue = $("input:radio[name='emails']:checked").val();
		if($(this).val() == 'selecionar')
		{
			if($("#selecionaremails_ifrm").length){ $("#selecionaremails_ifrm").remove(); }
			if($("#emails-selecionados").length){ $("#emails-selecionados").remove(); }
			var efrm = '';
			efrm += '<iframe id="selecionaremails_ifrm" src="/admin/selecionaremails" style="border:0;height:325px;width:850px;"></iframe>';
			efrm += '<center><a href="javascript:void(0);" id="subirtodos" class="button" onclick="subirtodos();">Subir Todos</a></center><div id="emails-selecionados"></div>';
			$("label[for='emails-selecionar']").after(efrm);
		}
		else
		{
			if($("#selecionaremails_ifrm").length){ $("#selecionaremails_ifrm").remove(); }
			if($("#emails-selecionados").length){ $("#emails-selecionados").remove(); }
		}
	});
});

