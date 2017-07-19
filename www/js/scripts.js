var showImage = function(path){
	if($('#divimageloaded').length){ $('#divimageloaded').remove(); }
	var divimageloaded = $(document.createElement('div'))
	.attr('style', 'display:none;text-align:center;')
	.attr('id', 'divimageloaded');
	divimageloaded.appendTo('#layout');
	
	var imageloaded = $(document.createElement('img'))
	.attr('id', 'imageloaded')
	.attr('src', path)
	.attr('style', 'text-align:center;')
	.appendTo('#divimageloaded');
	
	var imagelayer = new Image();
	imagelayer.src = path;
	
	if(imagelayer.complete) {
		$("#divimageloaded").dialog( { title : "Visualizando Imagem", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width:$(window).width()-150 } );
		/*
		jQuery(document).ready(function(){
			jQuery.fn.modalBox({
				setWidthOfModalLayer: (imagelayer.width+100),
				directCall: { 
					element : '#divimageloaded'
				}
			});
		});
		*/
	}
};

var upperc = function(x)
{
	var y=document.getElementById(x).value
	document.getElementById(x).value=y.toUpperCase()
};

var isValidEmailOLD = function(e)
{
	//var email = document.getElementById(e).value;
	var email = e;
	var sPatt = /^\w+$/;
	var mPatt = /^\w+$/;
	var ePatt1 = /^\w+@\w+(\.\w{2,3})$/;
	var ePatt2 = /^\w+@\w+(\.\w{2,3})(\.\w{2,3})$/;

	if((ePatt1.test(email) || ePatt2.test(email)) != true)
	{
		//alert('Email invalido');
		return false;
	}
	else return true;
};

var isValidEmail = function(email)
{
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return filter.test(email);
};


var textCounter = function(field, countfield, maxlimit)
{
	if(field.value.length > maxlimit) // if too long...trim it!
		field.value = field.value.substring(0, maxlimit);
		// otherwise, update 'characters left' counter
	else countfield.value = maxlimit - field.value.length;
};

var greetings = function()
{
	today = new Date();
	if(today.getMinutes() < 10){ pad = "0"; } 
	else{ pad = ""; document.write ; }

	var hour = today.getHours();
	var minutes = today.getMinutes();

	if(today.getDate() < 10){ var day = "0"+today.getDate(); }
	else{ var day = today.getDate(); }

	if(today.getMonth() < 10){ var month = "0"+(today.getMonth()+1); }
	else{ var month = (today.getMonth()+1); }

	var year = today.getFullYear();

	document.write("<h3>"+day+"/"+month+"/"+year+" "+hour+":"+minutes+" - ");

	if((hour >=06) && (hour <=11)){ document.write("Bom dia</h3>"); }
	if((hour >=12) && (hour <=17)){ document.write("Boa Tarde</h3>"); } 
	if((hour >=18) && (hour <=23)){ document.write("Boa Noite</h3>"); } 
	if((hour >=00) && (hour <=05)){ document.write("Bom Dia</h3>"); }

	document.write("</h3>");
};

var saveFoto = function(){
	var erro = 0;
	var msg = "";

	if($("#w").val() == '')
	{
		erro++;
		msg += "<b>Clique, segure e arraste a area a ser salva dentro da imagem</b><br />";
	}

	if($("#nome").val() == '')
	{
		erro++;
		msg += "<b>Digite seu nome</b><br />";
	}

	if($("#email").val() == '')
	{
		erro++;
		msg += "<b>Digite seu email</b><br />";
	}
	else if(!isValidEmail($("#email").val()))
	{
		erro++;
		msg += "<b>Digite um email correto</b><br />";
	}

	if($("#title").val() == '')
	{
		erro++;
		msg += "<b>Digite o nome do cachorro</b><br /><br />";
	}

	if($("#obs").val() == '')
	{
		erro++;
		msg += "<b>Escreva algo no campo OBS</b><br /><br />";
	}

	if(erro > 0)
	{
		$("#errormsg").html("<h1 style='color:#000080;font-size:12px;text-align:center;'>Os campos com * são de preenchimento obrigatório, faltam preencher (" + erro + ") deles</h1> <br /> " + msg + " <br /> ");
		$("#errormsg").attr('title','<h1 style="color:#FF0000;font-size:11px;text-align:center;">Por favor corriga os erros a seguir:</h1>');
		//$("#errormsg").dialog( { width:$(window).width()-150 } );
	}
	else
	{
		var loadingImage = "<img src='/img/loading.gif' style='border:0;margin:10px 0 0 0;' />";
		$("#tdbuttons").html($("#tdbuttons").html() + loadingImage);
		$("#tdbuttons").removeAttr('onclick');
		$("#fotocrop_frm").submit();
	}
};

var saveContact = function(){
	var erro = 0;
	var msg = "";

	if($("#name").val() == '')
	{
		erro++;
		msg += "<b>Digite seu nome</b><br />";
	}

	if($("#email").val() == '')
	{
		erro++;
		msg += "<b>Digite seu email</b><br />";
	}
	else if(!isValidEmail($("#email").val()))
	{
		erro++;
		msg += "<b>Digite um email válido</b><br />";
	}

	if($("#message").val() == '')
	{
		erro++;
		msg += "<b>Digite sua mensagem</b><br />";
	}
	
	if($("#recaptcha_response_field").val() == '')
	{
		erro++;
		msg += "<b>Digite o texto como é mostrado na imagem</b><br /><br />";
	} 

	if(erro > 0)
	{
		$("#errormsg").html("<h1 style='color:#000080;font-size:12px;text-align:center;'>Os campos com * são de preenchimento obrigatório, faltam preencher (" + erro + ") deles</h1> <br /> " + msg + " <br /> ");
		$("#errormsg").dialog( { title : "Por favor corriga os erros a seguir", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width:$(window).width()-150 } );
	}
	else
	{
		var loadingImage = "<img src='/img/loading.gif' style='border:0;margin:10px 0 0 0;' />";
		$("#tdbuttons").html($("#tdbuttons").html() + loadingImage);
		$("#tdbuttons").removeAttr('onclick');
		
		$("#form_contact").submit();
	}
};
