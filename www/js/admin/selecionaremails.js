var ck = function(id){
	$('#'+id).click();
}

var descertodos = function(){
	$('#descertodos').append('<img id="stloading" src="/img/loading.gif" style="display:;" />');
	$('#listdiv div').each(function(){ ck(this.id); });
	$('#stloading').remove();
}

var subirtodos = function(){
	$('#descertodos').append('<img id="stloading" src="/img/loading.gif" style="display:;" />');
	$('#emails-selecionados div').each(function(){ ck(this.id); });
	$('#stloading').remove();
}

var emailselecionar = function(id, email)
{
	var newemail  = "<div id='email_" + id + "' class='emails' onclick='emaildeselecionar(\"" + id + "\", \"" + email + "\");' title='" + id + " - " + email + "'>";
		newemail += " <input type='hidden' name='emails[]' value='" + id + "' />";
		newemail += " " + email + " ";
		newemail += "</div>\n";
	$("#emails-selecionados", window.top.document).append(newemail);
	$('#email_' + id).remove();
};

var emaildeselecionar = function(id, email)
{
	var emailback  = '<div id="email_' + id +'" class="emails" onclick="emailselecionar(\'' + id + '\', \'' + email + '\');" title="' + id + ' - ' + email + '">';
		emailback += email;
		emailback += '</div>';
		$('#selecionaremails_ifrm').contents().find('#listdiv').append(emailback);
		$('#email_' + id).remove();
};

$(document).ready(function(){
	$('#descertodos').button();
	$('#subirtodos').button();
	$('.button').button();
});
