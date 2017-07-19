var listpage = function(page) {
	$('#addedit').html(loading('aguarde')); putdots(); $('#grid').css('opacity', 0.3); $('#addedit').slideDown('5000').fadeIn('3000');

	location='?mod='+page;
};

var addpage = function(page) {
    var returnpage = location.search;
	$('#addedit').html(loading('aguarde')); putdots(); $('#grid').css('opacity', 0.3); $('#addedit').slideDown('5000').fadeIn('3000');

	$.get('?mod='+page, {a:'a', ajax:'1',returnpage:returnpage}, function(data) {
		if($("#addpage_"+page).length){ $("#addpage_"+page).remove(); }
		var newdiv = $(document.createElement('div')).attr('id', 'addpage_'+page).attr('class','sample ui-corner-all');
		$('#addedit').html(newdiv);
		$('#addpage_'+page).html(data);
		$("#addedit form").form();
		$("#addedit .button").button();
		$('#addedit').show();

		var firstInput = $('#addpage_'+page).find("input[type='text']:visible:first");
		firstInput.focus();
		$("a.loading").removeClass("loading");
	});
};

var canceladdpage = function(page) {
	geralog("cancelou adicionar '" + page + "'");
	$('#addedit').slideUp('500').fadeOut('slow');
	setTimeout("$('#addpage_"+page+"').remove()", 500);
	$('#grid').css('opacity', 1);
};

var editpage = function(page, id) {
    var returnpage = location.search;
	$('#addedit').html(loading('aguarde')); putdots(); $('#grid').css('opacity', 0.3); $('#addedit').slideDown('5000').fadeIn('3000');

	$.get('?mod='+page, {id:id, a:'e', ajax:'1',returnpage:returnpage}, function(data) {
		if($("#editpage_"+page).length){ $("#editpage_"+page).remove(); }
		var newdiv = $(document.createElement('div')).attr('id', 'editpage_'+page).attr('class','sample ui-corner-all');
		$('#addedit').html(newdiv);
		$('#editpage_'+page).html(data);
		$("#addedit form").form();
		$("#addedit .button").button();
		$('#addedit').show();

		var firstInput = $('#editpage_'+page).find("input[type='text']:visible:first");
		firstInput.focus();
		$("a.loading").removeClass("loading");
	});
};

var canceleditpage = function(page) {
	geralog("cancelou editar '" + page + "'");
	$('#addedit').slideUp('500').fadeOut('slow');
	setTimeout("$('#editpage_"+page+"').remove()", 500);
	$('#grid').css('opacity', 1);
};

var updatepage = function(page, id) {
	$('#edit' + page + '_'+ id).submit();
	$('.ui-dialog-titlebar-close').click();
	block();
};

var viewpage = function(page, id) {
	geralog("visualizou: pagina('" + page + "'), id('" + id + "') ");

	if($("#viewpage_"+page).length){ $("#viewpage_"+page).remove(); }
	$.get('?mod='+page, {id:id, a:'view', ajax:'1'}, function(data){
		var newdiv = $(document.createElement('div')).attr('id', 'viewpage_'+page);
		$('#content').append(newdiv);
		$('#viewpage_'+page).html(data);
		$('#viewpage_'+page+' form').form();
		$('#viewpage_'+page+' .button').button();
		$('#viewpage_'+page).dialog({ title : "Visualizando ID: " + id, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
	});

};

var emailpage = function(page, id) {
	block();
	geralog("enviando email: pagina('" + page + "'), id('" + id + "') ");

	if($("#emailpage_"+page).length){ $("#emailpage_"+page).remove(); }
	$.get('?mod='+page, {id:id, a:'sendemail', ajax:'1'}, function(data){
		var newdiv = $(document.createElement('div')).attr('id', 'emailpage_'+page);
		$('#content').append(newdiv);
		$('#emailpage_'+page).html(data).dialog({ title : "Enviando Email", modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
		$("#editemail").form();
		$("#editemail .button").button();
		$.unblockUI();
	});
	$('#emailpage_contatos').parent(function(){
		$(this).animate({scrollTop:0}, '5000');
	});
};

var previewpage = function(page, id) {
	$('#addedit').html(loading('aguarde')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
	geralog("visualizou: pagina('" + page + "'), id('" + id + "') ");

	if($("#previewpage_"+page).length){$("#previewpage_"+page).remove();}
	$.get('?mod='+page, {id:id, a:'preview', ajax:'1'}, function(data){
		var newdiv = $(document.createElement('div')).attr('id', 'previewpage_'+page);
		$('#content').append(newdiv);
		$('#previewpage_'+page).html(data)
			.dialog({title : "Visualizando ID: " + id, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150});
		$.unblockUI();
		$('#previewpage_'+page+' .button').button();
	});
	$('#addedit').slideUp('500').fadeOut('slow');
};

var removepage = function(page, id, list) {
	if(confirm("Deseja realmente remover ?")){
		$('#addedit').html(loading('aguarde')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
		$.get('?mod='+page, {id : id, a:'d', list:list, ajax:'1'}, function(data) {
			if(data == '1') {
				alert("Removido !");
				$("#list_"+list).slideUp('500').fadeOut('slow');
				setTimeout("$('#list_"+list+"').remove();",1000);
				if($("#list tr").length <= 2){$("#list_empty").slideDown('5000').fadeIn('3000');}
			}
			else alert(data);
			$('#addedit').slideUp('500').fadeOut('slow');
		});
	}
};

var cleanpage = function(page) {
	if(confirm("Deseja realmente limpar ?\n\nATENÇÃO: Não tem como voltar atrás !!!")){
		$('#addedit').html(loading('aguarde')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
		$.get('?mod='+page, {a:'clean', ajax:'1'}, function(data) {
			if(data == '1') {
				alert("Histórico limpo com sucesso !");
				location='?mod=log';
			}
			else alert(data);
			$('#addedit').slideUp('500').fadeOut('slow');
		});
	}
};

var trashpage = function(page, id, list) {
	if(confirm("Deseja realmente remover ?")){
		$('#addedit').html(loading('aguarde')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
		$.get('?mod='+page, {id : id, a:'trash', list:list, ajax:'1'}, function(data) {
			if(data == '1') {
				alert("Removido !");
				$("#list_"+list).slideUp('500').fadeOut('slow');
				setTimeout("$('#list_"+list+"').remove();",1000);
				if($("#list tr").length <= 2){$("#list_empty").slideDown('5000').fadeIn('3000');}
			}
			else alert(data);
			$('#addedit').slideUp('500').fadeOut('slow');
		});
	}
};

var changeactive = function(page, id, list) {
	$.get('?mod='+page, {id : id, a:'changeactive', list:list, ajax:'1'}, function(data) {
		if(data == 'S') {
			$("#active_" + list + " div span").removeClass('ui-icon ui-icon-check');
			$("#active_" + list + " div span").addClass('ui-icon ui-icon-cancel');
		}
		else {
			$("#active_" + list + " div span").removeClass('ui-icon ui-icon-cancel');
			$("#active_" + list + " div span").addClass('ui-icon ui-icon-check');
		}

		if(data == 'S') {}
		else if(data == 'N') {}
		else { alert(data); }
	});
};

var changelido = function(page, id, list) {
	$.get('?mod='+page, {id : id, a:'changelido', list:list, ajax:'1'}, function(data) {
		if(data == 'S') {
			$("#lido_" + list + " div span").removeClass('ui-icon ui-icon-check');
			$("#lido_" + list + " div span").addClass('ui-icon ui-icon-cancel');
		}
		else {
			$("#lido_" + list + " div span").removeClass('ui-icon ui-icon-cancel');
			$("#lido_" + list + " div span").addClass('ui-icon ui-icon-check');
		}

		if(data == 'S') {}
		else if(data == 'N') {}
		else { alert(data); }
	});
};

var exportpage = function(page, filetype) {
	if(confirm("Deseja realmente exportar os dados ?")) {
		if(confirm("Clique ok para exportar tudo ou cancelar para exportar como aparece na tela")) {
			$('#addedit').html(loading('aguarde ... ')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
			$.get('?mod='+page, {a:'x', ajax:'1', filetype:filetype, page:page, all:'S'}, function(data){
				if(data != 'error'){ window.open(data,'','height:10px;width:10px;'); }
				$('#addedit').slideUp('5000').fadeOut('3000');
			});
		}
		else {
			$('#addedit').html(loading('aguarde ... ')); putdots(); $('#addedit').slideDown('5000').fadeIn('3000');
			$.get('?mod='+page, {a:'x', ajax:'1', filetype:filetype, page:page, all:'N'}, function(data){
				if(data != 'error'){ window.open(data,'','height:10px;width:10px;'); }
				$('#addedit').slideUp('5000').fadeOut('3000');
			});
		}
	}
	$("a.loading").removeClass("loading");
};

var removethis = function(div){
	$('#' + div).slideUp('500').fadeOut('slow');
	setTimeout("$('#" + div + "').remove()", 1000);
};

var block = function() {
	var btn = '<div onclick="$.unblockUI();" class="button ui-button ui-widget ui-state-default ui-corner-all edit ui-button-text-only"><span class="ui-icon ui-icon-close"></span></div>';
	$.blockUI({theme: true, message: '<center>favor aguarde ... ' + btn + '</center>'});
};

var previewimage = function(imgpath) {
	block();
	geralog("visualizou imagem: '" + imgpath + "'");

	if( $("#previewimage").length ){$("#previewimage").remove();}
	var newImg = new Image();
	newImg.src = imgpath;

	//var newdiv = document.createElement('div');
	$("<div/>").attr('id', 'previewimage').css('display', 'none')
		.appendTo(document.body)
		.html("<img id='imgloaded' src='"+imgpath+"' />");

	//addJsToHead('../js/lake.js');
	//$("#imgloaded").lake({
	//	'speed': 1,
	//	'scale': 0.5,
	//	'waves': 10
	//});

	$('#imgloaded').load(function() {
		var arraypath = imgpath.split('/');
		var arrayreverse = array_reverse(arraypath);
		var imgtitle = arrayreverse[0];

		curHeight = document.getElementById('imgloaded').height;
		curWidth = document.getElementById('imgloaded').width;
		if(curWidth > window.innerWidth){$("#previewimage").dialog({title : imgtitle, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : (window.innerWidth-75)});}
		else{$("#previewimage").dialog({title : imgtitle, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : (curWidth+50)});}
	});

	$.unblockUI();
};

var addCssToHead = function(file) {
	var fth = document.createElement('link');
		fth.async = true;
		fth.href = file;
		fth.rel = 'stylesheet';
		fth.type = 'text/css';
	$("head").append(fth);
};

var addJsToHead = function(file) {
	var fth = document.createElement('script');
		fth.async = true;
		fth.src = file;
		fth.type = 'text/javascript';
	$("head").append(fth);
};

var onCKEditor = function(id, toolbar) {
	if($("#" + id).length) {
		var hEd = CKEDITOR.instances[id];
		if(hEd) {CKEDITOR.remove(hEd);}
		hEd = CKEDITOR.replace(id, { uiColor : '#DEDEDE', language : 'pt-br', height : '400px', width : $(window).width()-300, toolbar : toolbar });
		$("#onCKEditorBtn").slideUp('5000').fadeOut('5000');
		if($("#onCKEditorBtn_"+id).length) {$("#onCKEditorBtn_"+id).slideUp('5000').fadeOut('5000');$("#onCKEditorBtn_"+id).remove();}
	}
};

var removeUploadedFile = function(element) {
	block();
	var filepath = $(element.parentNode).find('.qq-upload-completefilepath').text();
	var filename = $(element.parentNode).find('.qq-upload-file').text();
	if($(element.parentNode.parentNode.parentNode).find('input[type="hidden"]').length) {
		$(element.parentNode.parentNode.parentNode).find('input[type="hidden"]').remove();
	}

	$.get('../inc/removefile.php', {filepath : filepath, filename : filename}, function(data){
		$(element.parentNode).slideUp('slow').fadeOut('1000').remove();
		if($(".qq-upload-button").length){$(".qq-upload-button").css('display', 'block');}
	});

	$.unblockUI();
};

var removeUploadedFileById = function(element, campo, id, mod) {
	if(confirm('Deseja realmente remover esse arquivo ? Não tem como voltar atrás')) {
		block();
		var filepath = $(element.parentNode).find('.qq-upload-completefilepath').text();
		var filename = $(element.parentNode).find('.qq-upload-file').text();

		var littlepathwithimage = filepath.replace('../img/'+campo+'/', '');
		var littlepath = littlepathwithimage.replace(filename, '');
		geralog('removendo imagem: "' + littlepath + filename + '" de: "' + campo + '"');

		$.get('../inc/removefile.php', {
			filepath : filepath,
			filename : filename
		}, function(data){
			$(element.parentNode).slideUp('slow').fadeOut('1000').remove();
			if($("#"+campo+" .qq-uploader").length && $("#"+campo+" .qq-upload-button").length ){ $("#"+campo+" .qq-uploader").css('display', 'block'); $("#"+campo+" .qq-upload-button").css('display', 'block'); }
			if($("input[name='"+campo+"']").length) { $("input[name='"+campo+"']").remove(); }
		});

		$.unblockUI();
	}
};

var removeUploadedFileBySession = function() {
	block();

	$.get('/inc/removefile.php', {filepath : 'session', filename : 'session'}, function(data){
		//if(data == 1) { alert('Removido com sucesso'); }
	});

	$.unblockUI();
};

var sendbirthmess = function(id) {
	block();

	if( $("#birthmess").length ){ $("#birthmess").remove(); }
	//var newdiv = document.createElement('div');
	$("<div/>").attr('id', 'birthmess').css('display', 'none').appendTo(document.body);

	$.get('?mod=users', { id:id, ajax:'1', a:'birthmess' }, function(data){
		$('#birthmess').html(data).dialog({ title : "Mensagem de Aniversário", modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : (window.innerWidth-150) });
		$('#birthmess .button').button();
		$('#birthmess .form').form();
	});

	$.unblockUI();
}

var array_reverse = function(array, preserve_keys) {
	// Return an array with elements in reverse order
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Karol Kowalski
	var arr_len=array.length, newkey=0, tmp_ar = {}

	for(var key in array) {
		newkey=arr_len-key-1;
		tmp_ar[(!!preserve_keys)?newkey:key]=array[newkey];
	}

	return tmp_ar;
};

var IsValidEmail = function(email) {
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return filter.test(email);
};

var IsValidCpf = function(cpf) {
	Numero = cpf.substring(0,12);
	Digito = cpf.substring(12,14);

	for(var i=0; i<Numero.length; i++) {
		Numero = Numero.replace('.','');
		Numero = Numero.replace('/','');
		Numero = Numero.replace('-','');
	}

	var j = -1;
	var CPF = Numero;
	var peso1 = '100908070605040302';
	var peso2 = '111009080706050403';
	var soma1 = 0;
	var soma2 = 0;
	var digito1 = 0;
	var digito2 = 0;

	for(var i = 1; i < 9 - Numero.length+1; i++){CPF = eval("'" + 0 + CPF + "'");}

	for(var i = 1; i < CPF.length+1; i++) {
		j = j + 2;
		soma1 += CPF.substring(i, i-1) * peso1.substring(j-1, j+1);
	}

	soma1 %= 11;

	if(soma1  < 2){digito1 = 0;}
	else{digito1 = 11 - soma1;}

	j = -1;

	for(var i = 1; i < CPF.length+1; i++) {
		j = j + 2;
		soma2 += CPF.substring(i, i-1) * peso2.substring(j-1, j+1);
	}

	soma2 += digito1 * 2;
	soma2 %= 11;

	if(soma2  < 2){digito2 = 0;}
	else{digito2 = 11 - soma2;}

	if(eval("'" + digito1 + digito2 + "'") != Digito){return false;}
	else{return true;}
};

var IsValidCnpj = function(num){
	var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais, cnpj;
	cnpj = num.replace(/\D+/g, '');
	digitos_iguais = 1;

	if (cnpj.length != 14) {
		//alert('CNPJ inválido');
		return false;
	}

	for (i = 0; i < cnpj.length - 1; i++)
	if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
		digitos_iguais = 0;
		break;
	}
	if (!digitos_iguais) {
			tamanho = cnpj.length - 2;
			numeros = cnpj.substring(0,tamanho);
			digitos = cnpj.substring(tamanho);
			soma = 0;
			pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
			pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0)) {
			//alert('CNPJ inválido');
			return false;
		}

		tamanho = tamanho + 1;
		numeros = cnpj.substring(0,tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--) {
			soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
			pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1)) {
			//alert('CNPJ inválido');
			return false;
		}
		else {
			// alert('CNPJ  OK !');
			return true;
		}
	}
	else {
		//alert('CNPJ inválido');
		return false;
	}
};

var submitform = function(page) {
	if($("#add"+page).length) {
		$("#add"+page).submit(function() {
			$.ajax(this.action, {
				data: $(":text", this).serializeArray(),
				files: $(":file", this),
				iframe: true,
				processData: false
			}).complete(function(data) {
				console.log(data);
			});
		});

		$("#add"+page).submit();
	}
	else if($("#edit"+page).length) {
		$("#edit"+page).submit(function() {
			$.ajax(this.action, {
				data: $(":text", this).serializeArray(),
				files: $(":file", this),
				iframe: true,
				processData: false
			}).complete(function(data) {
				console.log(data);
			});
		});

		$("#edit"+page).submit();
	}
};

var geralog = function(log) {
	if(log != '') { $.get('../inc/geralog.php', {log:log}, function(){}); }
};

var loading = function(msg){
	if($("#loading").length) {$("#loading").remove();}
	return '<center id="loading"><img src="../img/loader.gif" style="border:0;" /><br />' + msg + '<span id="loadingdots"></span></center>';
};

var loadingmini = function(){
	if($("#loading").length) {$("#loading").remove();}
	return '<img id="loading" src="../img/loader-mini.gif" style="border:0;" />';
};

var putdots = function() {
	if($("#loadingdots").length && $("#loadingdots").html().length == '3') { $("#loadingdots").html(''); }
	$("#loadingdots").html($("#loadingdots").html()+'.');
	setTimeout("putdots()", 750);
};

$(document).ready(function(){
	function addtohead(f) { var ath = $(document.createElement('link')).attr("href", f).attr("rel", "stylesheet").attr("type", 'text/css'); ath.appendTo("head"); }
	function isMobile() { var index = navigator.appVersion.indexOf("Mobile"); return (index > -1); }
	function setStyleMobile() { addtohead("../css/admin.css"); addtohead("../css/megamenu.css"); $("#toplogo").css('max-width', '335px'); }
	function setStyleRegular() { addtohead("../css/admin.css"); addtohead("../css/megamenu.css"); }
	if(isMobile()) { setStyleMobile(); } else { setStyleRegular(); }

	$("#logout").click(function(){ if(confirm("Deseja realmente sair  ?")){ location="?logout"; } });
	$("#moreperm")
		.css("float", "left")
		.css("margin-right", "10px")
		.css("position", "relative")
		.css("width", "20px")
		.click(function(){
			var newpermid = $("#addpermissions table").length;
			var data  = "	<table id='perm_"+newpermid+"' class='sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset'>\n";
				data += "		<tr>\n";
				data += "			<td class='label ui-state-default'><label for='permKey'>Chave: </label></td>\n";
				data += "			<td><label><input type='text' value='' size='75' name='permKey[]' id='permKey' class='ui-state-default ui-corner-all'></label></td>\n";
				data += "		</tr>\n";
				data += "		<tr>\n";
				data += "			<td class='label ui-state-default'><label for='permName'>Nome: </label></td>\n";
				data += "			<td><label><input type='text' value='' size='75' name='permName[]' id='permName' class='ui-state-default ui-corner-all'></label></td>\n";
				data += "		</tr>\n";
				data += "	</table>\n";

			if($('#addedit #perm_0').length) {
				$('#addpermissions').append(data);
				$("#perm_"+newpermid).slideDown('3000').fadeIn('5000');
				$("#perm_"+newpermid+" form").form();
				$("#perm_"+newpermid+" .button").button();

				$("input[type='text']").hover(
					function() {
						if($(this).parent().get(0).tagName == 'A') {
							$(this).parent().addClass("ui-state-hover");
						}
					},
					function() {
						if($(this).parent().get(0).tagName == 'A') {
							$(this).parent().removeClass("ui-state-hover");
						}
					}
				);

				var pos = $(".sampleedit:last-child tr:first-child").position();
				$("html, body").animate({scrollTop:pos.top}, '5000');
				var firstInput = $('.sampleedit:last-child tr:first-child').find("input[type='text']:visible:first");
				firstInput.focus();
			}
			else {
				var page = 'permissions';
				block();
				$('#addedit').html(loading('aguarde'));
				$('#addedit').slideDown('5000').fadeIn('3000');

				$.get('/admin/?mod='+page, {a:'a', ajax:'1'}, function(data) {
					if($("#addpage_"+page).length){ $("#addpage_"+page).remove(); }
					var newdiv = $(document.createElement('div')).attr('id', 'addpage_'+page).attr('class','sample ui-corner-all');
					$('#addedit').html(newdiv);
					$('#addpage_'+page).html(data);
					$("#addedit form").form();
					$("#addedit .button").button();

					$.unblockUI();
					var firstInput = $('.sampleedit:last-child tr:first-child').find("input[type='text']:visible:first");
					firstInput.focus();
				});
			}
		});
	$(".button").button();
	$("form").form();
	$(".tabsone").tabs();
	$(".pagenums").button();
	$("#voltar").button();
	$("#share").button();

	$(".ui-icon").hover(
		function() {
			if($(this).parent().get(0).tagName == 'DIV' || $(this).parent().get(0).tagName == 'A') {
				$(this).parent().addClass("ui-state-hover");
			}
		},
		function() {
			if($(this).parent().get(0).tagName == 'DIV' || $(this).parent().get(0).tagName == 'A') {
				$(this).parent().removeClass("ui-state-hover");
			}
		}
	);

	$(".ui-button-text").hover(
		function() {
			if($(this).parent().get(0).tagName == 'A') {
				$(this).parent().addClass("ui-state-hover");
			}
		},
		function() {
			if($(this).parent().get(0).tagName == 'A') {
				$(this).parent().removeClass("ui-state-hover");
			}
		}
	);

	$(".overg").hover(
		function() {
			$(this).addClass("overgb");
		},
		function() {
			$(this).removeClass("overgb");
		}
	);

	$(".overa").hover(
		function() {
			$(this).removeClass("overa");
			$(this).addClass("over");
		},
		function() {
			$(this).removeClass("over");
			$(this).addClass("overa");
		}
	);

	$(".overb").hover(
		function() {
			$(this).removeClass("overb");
			$(this).addClass("over");
		},
		function() {
			$(this).removeClass("over");
			$(this).addClass("overb");
		}
	);

	var ws = '';
	if($(window).width() > 300 && $(window).width() < 700) { ws=690; }
	else if($(window).width() > 500 && $(window).width() < 900) { ws=890; }
	else if($(window).width() > 700 && $(window).width() < 1100) { ws=1090; }
	else if($(window).width() > 900 && $(window).width() < 1300) { ws=1290; }
	else if($(window).width() > 1100 && $(window).width() < 1500) { ws=1490; }
	else if($(window).width() > 1300 && $(window).width() < 1700) { ws=1690; }
	else if($(window).width() > 1500 && $(window).width() < 1900) { ws=1890; }
	else { ws=300; }
	$("#content .sampleedit").css('width', ws+'px');

	$.each($(".datepicker"), function(){ $(this).mask("99/9999"); });
	setTimeout("$('.accordion').accordion({ header: 'h3' });",1500);

	/* reloginho */
	var newdiv = $("<div/>").attr('id', 'timecx').css('display', 'block').css('font-weight', 'bold').css('padding', '2px 4px !important');
	$("#copyright").append(newdiv);

	setInterval(function() {
		var d = new Date();
		var dts = (d.getDate() < 10 ? '0' : '') + d.getDate() + '/' +
			(d.getMonth() < 9 ? '0' : '') + (d.getMonth()+1) + '/' +
			d.getFullYear();
		var tts = d.getHours() + ':' +
			(d.getMinutes() < 10 ? '0' : '') + d.getMinutes() + ':' +
			(d.getSeconds() < 10 ? '0' : '') + d.getSeconds();
		$('#timecx').html(dts + ' ' + tts)
			//.button()
		;
	}, 1000);
	/* end reloginho */
});

$("a.button").click(function(){ $(this).addClass("loading"); });
$("input.button").click(function(){ $(this).addClass("loading"); });
$("input[value='Salvar']").click(function(){ $(this).addClass("loading"); });

var simAll = function() {
	if($("#groups_choices").length) {
		var inputs = $("#groups_choices input[type='radio']");
		$.each(inputs, function(i,v){ if($(this).val() == '1') { $(this).attr('checked', true); }; });
	}
};

var naoAll = function() {
	if($("#groups_choices").length) {
		var inputs = $("#groups_choices input[type='radio']");
		$.each(inputs, function(i,v){ if($(this).val() == '0') { $(this).attr('checked', true); }; });
	}
};

var permiteAll = function() {
	if($("#perms_choices").length) {
		var inputs = $("#perms_choices input[type='radio']");
		$.each(inputs, function(i,v){ if($(this).val() == '1') { $(this).attr('checked', true); }; });
	}
};

var negaAll = function() {
	if($("#perms_choices").length) {
		var inputs = $("#perms_choices input[type='radio']");
		$.each(inputs, function(i,v){ if($(this).val() == '0') { $(this).attr('checked', true); }; });
	}
};

var herdaAll = function() {
	if($("#perms_choices").length) {
		var inputs = $("#perms_choices input[type='radio']");
		$.each(inputs, function(i,v){ if($(this).val() == 'X') { $(this).attr('checked', true); }; });
	}
};

var savepermissions = function() { submitform('permissions'); };

var morepermissions = function() {
	var newpermid = $("#addpermissions table").length;
	var data  = "	<table id='perm_"+newpermid+"' class='sampleedit ui-widget ui-widget-content ui-corner-all ui-helper-reset'>\n";
		data += "		<tr>\n";
		data += "			<td class='label ui-state-default'><label for='permKey'>Chave: </label></td>\n";
		data += "			<td><label><input type='text' value='' size='75' name='permKey[]' id='permKey' class='ui-state-default ui-corner-all'></label></td>\n";
		data += "		</tr>\n";
		data += "		<tr>\n";
		data += "			<td class='label ui-state-default'><label for='permName'>Nome: </label></td>\n";
		data += "			<td><label><input type='text' value='' size='75' name='permName[]' id='permName' class='ui-state-default ui-corner-all'></label></td>\n";
		data += "		</tr>\n";
		data += "	</table>\n";

	if($('#addedit #perm_0').length) {
		$('#addpermissions').append(data);
		$("#perm_"+newpermid).slideDown('3000').fadeIn('5000');
		$("#perm_"+newpermid+" form").form();
		$("#perm_"+newpermid+" .button").button();
	}
	else {
		var page = 'permissoes';
		block();
		$('#addedit').html(loading('aguarde'));
		$('#addedit').slideDown('5000').fadeIn('3000');

		$.get('/admin/?mod='+page, {a:'a', ajax:'1'}, function(data) {
			if($("#addpage_"+page).length){ $("#addpage_"+page).remove(); }
			var newdiv = $(document.createElement('div')).attr('id', 'addpage_'+page).attr('class','sample ui-corner-all');
			$('#addedit').html(newdiv);
			$('#addpage_'+page).html(data)
				//.dialog({ title : "Adicionando '" + page + "' ", modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 })
				;
			$("#addedit form").form();
			$("#addedit .button").button();

			$.unblockUI();
			var firstInput = $('#addpage_'+page).find("input[type='text']:visible:first");
			firstInput.focus();
		});
	}

	$("input[type='text']").hover(
		function() {
			if($(this).parent().get(0).tagName == 'A') {
				$(this).parent().addClass("ui-state-hover");
			}
		},
		function() {
			if($(this).parent().get(0).tagName == 'A') {
				$(this).parent().removeClass("ui-state-hover");
			}
		}
	);

	var pos = $(".sampleedit:last-child tr:first-child").position();
	$("html, body").animate({scrollTop:pos.top}, '5000');
	var firstInput = $('.sampleedit:last-child tr:first-child').find("input[type='text']:visible:first");
	firstInput.focus();
};

// Numeric only control handler
jQuery.fn.ForceNumericOnly =
function() {
    return this.each(function() {
        $(this).keydown(function(e) {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
                key == 8 ||
                key == 9 ||
                key == 46 ||
                (key >= 37 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

var jcrop_api, boundx, boundy;
var callJCrop = function(inputname) {
    $("#" + inputname + " #target").Jcrop({
        setSelect: [ 100, 100, 200, 200 ],
        onChange: updateJCropPreview,
        onSelect: updateJCropPreview,
        addClass: 'jcrop-dark'
    },function(){
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        // Store the API in the jcrop_api variable
        jcrop_api = this;
    });

}

var updateJCropPreview = function(c){
  if (parseInt(c.w) > 0) {
    var rx = 200 / c.w;
    var ry = 200 / c.h;

    $('#preview').parent().css({
      //width: Math.round(c.w) + 'px',
      //height: Math.round(c.h) + 'px'
    });
    
    $('#preview').css({
      width: Math.round(rx * boundx) + 'px',
      height: Math.round(ry * boundy) + 'px',
      marginLeft: '-' + Math.round(rx * c.x) + 'px',
      marginTop: '-' + Math.round(ry * c.y) + 'px'
    });
    
    $("#x1").val(parseInt(c.x));
    $("#y1").val(parseInt(c.y));
    $("#x2").val(parseInt(c.x2));
    $("#y2").val(parseInt(c.y2));
    $("#w").val(parseInt(c.w));
    $("#h").val(parseInt(c.h));
  }
};

var changeCropImage = function(filepath, filename) {
	if(confirm("Deseja realmente fazer isso ?\n\nA imagem atual será removida, e não terá volta!")) {
		$.get('../inc/removefile.php', { filepath : filepath, filename : filename }, function(data){
			if($("#imagecrop").length){ $("#imagecrop").remove(); }
			if($("#imgpreview").length){ $("#imgpreview").remove(); }
			if($(".qq-upload-button").length){ $(".qq-upload-button").css('display', 'block'); $(".qq-upload-button input").click(); }
		});
	}
}

var removeAcentos = function(valor, campo) {
	var varString = valor;
	var stringAcentos   = 'áàâãäéèêëíìîïóòôõöúùûüçñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÇÑ';
	var stringSemAcento = 'aaaaaeeeeiiiiooooouuuucnAAAAAEEEEIIIIOOOOOUUUUCN';
	var varRes = "";
	for(var i = 0; i < varString.length; i++) {
		if(varString[i] == stringAcentos.substring(i,1)){ varRes += stringSemAcento.substring(i,1); }
		else{ varRes += varString[i]; }
	}
	$("#"+campo).val(varRes);
};

var espacosToTracos = function(valor, campo) {
	var varString = valor;
	var varRes = "";
	varRes = varString.replace(/ /g,"-").replace(/(\-)\1+/gi,"-");
	$("#"+campo).val(varRes);
};

var espacosToVirgulas = function(valor, campo) {
	var varString = valor;
	var varRes = "";
	varRes = varString.replace(/ /g,",").replace(/(\-)\1+/gi,",");
	$("#"+campo).val(varRes);
};

