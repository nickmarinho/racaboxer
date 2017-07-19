var file = '';
var fototocrop = '';

var voltar = function(file){
	$("#headh1", window.parent.document).text('Envie a foto do seu cachorro');
	$("#thumbnail", window.parent.document).removeAttr('src');
	$("#thumbnail", window.parent.document).hide();
	$(".jcrop-holder", window.parent.document).remove();
	$("#fotoupload_frm", window.parent.document).fadeIn().slideDown(500);
	$("#fotoupload_progress", window.parent.document).html('');
	$("#fotoupload_result", window.parent.document).html('');
	$(".imgareaselect-outer", window.parent.document).toggle();
	$.get('/galeria-enviar.html', { del : '1', file : file });
};

var checkCoords = function(){
	if(parseInt($('#w', window.parent.document).val())) return true;
	alert('Por favor selecione a area da foto a ser salva.');
	return false;
};

var showCoords = function(c){
	$('#x1', window.parent.document).val(c.x);
	$('#y1', window.parent.document).val(c.y);
	$('#x2', window.parent.document).val(c.x2);
	$('#y2', window.parent.document).val(c.y2);
	$('#w', window.parent.document).val(c.w);
	$('#h', window.parent.document).val(c.h);
};

var preview = function(img, selection){
	if(!selection.width || !selection.height) return;

	var scaleX = 100 / selection.width;
	var scaleY = 100 / selection.height;

	$('#preview img').css({
		width: Math.round(scaleX * 300),
		height: Math.round(scaleY * 300),
		marginLeft: -Math.round(scaleX * selection.x1),
		marginTop: -Math.round(scaleY * selection.y1)
	});

	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);    
}

var returnuploadform = function(){
	$("#fotoupload_progress", window.parent.document).html('');
	$("#fotoupload_frm", window.parent.document).show();
	$("#headh1", window.parent.document).text('Apenas suportadas as imagens: gif, jpeg, jpg ou png');
	alert('Apenas suportadas as imagens: gif, jpeg, jpg ou png');
};

var showcropfrm = function(filo){
	fototocrop += '<br style="clear:both;"/>';
	fototocrop += '<form action="/galeria-enviar.html" id="fotocrop_frm" name="fotocrop_frm" method="post">';
	fototocrop += '<table align="center" class="login" style="width:715px !important;">';
	fototocrop += ' <tbody>';
	fototocrop += '  <tr onmouseout="this.className=\'impar\';" onmouseover="this.className=\'over\';" class="impar">';
	fototocrop += '   <td id="name-label"><label class="required" for="name">* Nome:</label></td>';
	fototocrop += '   <td><input type="text" size="75" value="" id="nome" name="nome"></td>';
	fototocrop += '  </tr>';
	fototocrop += '  <tr onmouseout="this.className=\'impar\';" onmouseover="this.className=\'over\';" class="impar">';
	fototocrop += '   <td id="email-label"><label class="required" for="email">* Email:</label></td>';
	fototocrop += '   <td><input type="text" size="75" value="" id="email" name="email"></td>';
	fototocrop += '  </tr>';
	fototocrop += '  <tr onmouseout="this.className=\'impar\';" onmouseover="this.className=\'over\';" class="impar">';
	fototocrop += '   <td id="title-label"><label class="optional" for="title">* Nome do Cachorro:</label></td>';
	fototocrop += '   <td><input type="text" size="75" value="" id="title" name="title"></td>';
	fototocrop += '  </tr>';
	fototocrop += '  <tr onmouseout="this.className=\'impar\';" onmouseover="this.className=\'over\';" class="impar">';
	fototocrop += '   <td id="obs-label">';
	fototocrop += '    <label class="required" for="obs">* Obs:</label>';
	fototocrop += '    <input type="text" value="200" maxlength="3" size="3" name="remLen" readonly="readonly"></td>';
	fototocrop += '   <td><textarea onkeyup="textCounter(this.form.obs,this.form.remLen,200);" rows="3" cols="73" id="obs" name="obs"></textarea></td>';
	fototocrop += '  </tr>';
	fototocrop += '  <tr onmouseout="this.className=\'impar\';" onmouseover="this.className=\'over\';" class="impar">';
	fototocrop += '   <td align="center" id="tdbuttons" colspan="2">';
	fototocrop += '    <input class="button" onclick="javascript:saveFoto();" type="button" value="Salvar" />&nbsp;<input class="button" type="button" id="fotoupload_vtr" onclick="voltar(\''+filo+'\');" value="Voltar" />';
	fototocrop += '    <input type="hidden" name="x1" id="x1" /><input type="hidden" name="y1" value="" id="y1" />';
	fototocrop += '    <input type="hidden" name="x2" id="x2" /><input type="hidden" name="y2" value="" id="y2" />';
	fototocrop += '    <input type="hidden" name="w" value="" id="w" /><input type="hidden" name="h" value="" id="h" />';
	fototocrop += '    <input type="hidden" name="file" value="'+filo+'" id="file" />';
	fototocrop += '   </td>';
	fototocrop += '  </tr>';
	fototocrop += ' </tbody>';
	fototocrop += '</table>';
	fototocrop += '</form>';
	fototocrop += '<hr />';
	fototocrop += '<div id="errormsg" />';

	$("#headh1", window.parent.document).text('Clique, segure e com o mouse dentro da imagem selecione a area da foto a ser salva e preencha os dados abaixo');
	$("#thumbnail", window.parent.document).attr('src', 'img/dogs/upload/' + file);
	$("#thumbnail", window.parent.document).fadeIn().slideDown(500);
	$("#fotoupload_result", window.parent.document).html(fototocrop);
	$("#fotoupload_result", window.parent.document).fadeIn().slideDown(500);
	$("#fakebutton", window.parent.document).click();
};
