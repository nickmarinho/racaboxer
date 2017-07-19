$(document).ready(function(){
	/*
	$("[title]").qtip({
		position: {
			corner: {
				target: 'topRight',
				tooltip: 'bottomLeft'
			}
		},
		style: {
			name: 'racaboxer',
			padding: '7px 13px',
			width: {
				max: 210,
				min: 0
			},
			tip: true
		}
	});
	*/
});

var printhis = function(title){
	if($("#printcontent").length) {
		var mywindowcontent = "";
		mywindowcontent += "<html>\n";
		mywindowcontent += "<head>\n";
		mywindowcontent += "<title>" + title + "</title>\n";
		mywindowcontent += "<link type='text/css' href='../css/print.css' rel='stylesheet' />\n";
		mywindowcontent += "<link type='text/css' href='../css/fileuploader.css' rel='stylesheet' />\n";
		mywindowcontent += "</head>\n";
		mywindowcontent += "<body>\n";
		mywindowcontent += $("#printcontent").html();
		mywindowcontent += "</body>\n";
		mywindowcontent += "</html>\n";
		var mywindow = window.open('', 'Imprimir', 'scrollbars=1,scrollbars=yes,height=' + $(window).height()-100 + ',width=' + $(window).width()-150);
		mywindow.document.write(mywindowcontent);
		mywindow.document.close();
		mywindow.print();
		//return true;
	}
};

var addmorecontact = function(div) {
	var qty = $("#"+div).find("div").length;
	var newcontato  = "				<div id='contato_" + qty + "'>\n";
		newcontato += "					<select id='contatotipo_" + qty + "' name='contatotipo[]'>\n";
		newcontato += "						<option value='--'></option>\n";
		newcontato += "						<option value='celular'>Celular</option>\n";
		newcontato += "						<option value='casa'>Casa</option>\n";
		newcontato += "						<option value='trabalho'>Trabalho</option>\n";
		newcontato += "					</select> <input class='phone' id='contatonumero_" + qty + "' size='14' name='contatonumero[]' type='text' value='' />\n";
		newcontato += "					<div onclick='javascript:removethis(\"contato_" + qty + "\");' class='ui-state-default ui-corner-all img-icons'><span class='ui-icon ui-icon-minus' title='Remover Este'></span></div>\n";
		newcontato += "				</div>\n";
	$("#"+div).append(newcontato);
	$("#contato_" + qty).form();
	$('.phone').mask('(99) 9999-9999');
};

var addmoremess = function(div) {
	var qty = $("#"+div).find("div").length;
	var newmess = "				<div id='messenger_" + qty + "'>\n";
		newmess += "					<select id='messtipo_" + qty + "' name='messtipo[]'>\n";
		newmess += "						<option value='--'></option>\n";
		newmess += "						<option value='email'>Email</option>\n";
		newmess += "						<option value='gtalk'>GTalk</option>\n";
		newmess += "						<option value='msn'>Msn</option>\n";
		newmess += "						<option value='skype'>Skype</option>\n";
		newmess += "						<option value='yahoo'>Yahoo</option>\n";
		newmess += "					</select> <input id='messvalor_" + qty + "' size='40' name='messvalor[]' type='text' value='' />\n";
		newmess += "					<div onclick='javascript:removethis(\"messenger_" + qty + "\");' class='ui-state-default ui-corner-all img-icons'><span class='ui-icon ui-icon-minus' title='Remover Este'></span></div>\n";
		newmess += "				</div>\n";
	$("#"+div).append(newmess);
	$("#messenger_" + qty).form();
};

var sendconfirmationemail = function(page, id){
	var returnmsg = "";
	returnmsg = "<img style='background:url(\"/img/loader-mini.gif\");height:15px;width:15px;' src='/img/1x1.gif' />";
	$("#confirmationmail_"+id).html(returnmsg);
	
	$.get('?mod='+page, {id:id, a:'sendconfirmationemail', ajax:'1'}, function(data) {
		if(data == "1") { alert("Enviado com sucesso !"); }
		else if(data == "2") { alert("Não foi possível enviar"); }
		else { alert(data); }

		returnmsg = "<div class='ui-state-default ui-corner-all img-icons'><span class='ui-icon ui-icon-mail-closed'></span></div>\n";
		
		$("#confirmationmail_"+id).html(returnmsg);
	});
};

var sendnewsletter = function(id){
	var returnmsg = "";
	returnmsg = "<img style='background:url(\"/img/loader-mini.gif\");height:15px;width:15px;' src='/img/1x1.gif' />";
	$("#newsletter_"+id).html(returnmsg);
	
	$.get('?mod='+page, {id:id, a:'sendnewsletter', ajax:'1'}, function(data) {
		if(data == "1") { alert("Enviado com sucesso !"); }
		else if(data == "2") { alert("Não foi possível enviar"); }
		else { alert(data); }

		returnmsg = "<div class='ui-state-default ui-corner-all img-icons'><span class='ui-icon ui-icon-mail-closed'></span></div>\n";
		
		$("#newsletter_"+id).html(returnmsg);
	});
};

