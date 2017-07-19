(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.async = true;
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();

var block = function(){
	var btn = '<div onclick="$.unblockUI();" class="button ui-button ui-widget ui-state-default ui-corner-all edit ui-button-text-only"><span class="ui-icon ui-icon-close"></span></div>';
	$.blockUI({theme: true, message: '<center>favor aguarde ... ' + btn + '</center>'});
};

var voltar = function(file){
	$("#headh1").text('Envie a foto do seu cachorro');
	$("#thumbnail").removeAttr('src');
	$("#thumbnail").hide();
	$(".jcrop-holder").remove();
	$("#fotoupload_frm").fadeIn().slideDown(500);
	$("#fotoupload_progress").html('');
	$("#fotoupload_result").html('');
	$(".imgareaselect-outer").toggle();
	$.get('/galeria-enviar.html', { del : '1', file : file });
};

var returnuploadform = function(){
	$("#fotoupload_progress").html('');
	$("#fotoupload_frm").show();
	$("#headh1").text('Apenas suportadas as imagens: gif, jpeg, jpg ou png');
	alert('Apenas suportadas as imagens: gif, jpeg, jpg ou png');
};

var checkCoords = function(){
	if(parseInt($('#w').val())) return true;
	alert('Por favor selecione a area da foto a ser salva.');
	return false;
};

var showCoords = function(c){
	$('#x1').val(c.x);
	$('#y1').val(c.y);
	$('#x2').val(c.x2);
	$('#y2').val(c.y2);
	$('#w').val(c.w);
	$('#h').val(c.h);
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

var hiddenfunction = function(){
	$("#thumbnail").imgAreaSelect({
		handles: true,
		fadeSpeed: 200,
		onSelectChange: preview
	});
};

var ftmd = function(imgpath){
	block();
	if($("#ftmd").length){ $("#ftmd").remove(); }
	var newImg = new Image();
	newImg.src = imgpath;

	var newdiv = document.createElement('div');
		$("<div/>").attr('id', 'ftmd').css('display', 'none')
			.appendTo(document.body)
			.html("<img id='imgloaded' src='"+imgpath+"' />");

	$('#imgloaded').load(function(){
		curHeight = document.getElementById('imgloaded').height;
		curWidth = document.getElementById('imgloaded').width;
		if(curWidth > window.innerWidth){ $("#ftmd").dialog({ title : "", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : (window.innerWidth-75) }); }
		else{ $("#ftmd").dialog({ title : "", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : (curWidth+25) }); }
		
		$.unblockUI();
	});
};

$(document).ready(function(){
	$("#twtr-widget-1").css('left', '8px');
	$("#twtr-widget-1").css('margin', '0 0 10px 0');
	$("#twtr-widget-1").css('position', 'relative');
	$("#twtr-widget-1").css('width', '250px');
	$("#twtr-widget-1").css('z-index', '0');
	$("#phone").mask("(99) 9999-9999");

	$(".ui-icon").hover(
		function(){ if($(this).parent().get(0).tagName == 'DIV'){ $(this).parent().addClass("ui-state-hover"); } },
		function(){ if($(this).parent().get(0).tagName == 'DIV'){ $(this).parent().removeClass("ui-state-hover"); } }
	);

	var loadingImage = "<img src='/img/ajax_loader.gif' style='border:0;margin:10px 0 0 0;' />";
	$("#login_anunciantes").click(function(){
		if($("#login_frm").length){ $("#login_frm").remove(); }
		var newdiv = $(document.createElement('div'))
			.attr('id', 'login_frm');
		$("#maincontent").after(newdiv);
		$("#maincontent").hide();
		$("#login_frm").html("<center><b>Carregando login para anunciantes, por favor aguarde ...</b><br />" + loadingImage + "</center>");
	});

	$("#login_editores").click(function(){
		if($("#login_frm").length){ $("#login_frm").remove(); }
		var newdiv = $(document.createElement('div'))
			.attr('id', 'login_frm');
		$("#maincontent").after(newdiv);
		$("#maincontent").hide();
		$("#login_frm").html("<center><b>Carregando login para editores, por favor aguarde ...</b><br />" + loadingImage + "</center>");
	});

	$("#fotoupload_btn").click(function(){
		if($("#fotoupload_ifrm").length){ $("#fotoupload_ifrm").remove(); }
	
		var ifrm = $(document.createElement('iframe'))
		.attr("id", "fotoupload_ifrm")
		.attr("name", "fotoupload_ifrm")
		.attr("style", 'border:0;display:none;height:0;width:0;');
		ifrm.appendTo("#footer");
		
		var progressimg = '<center><img src="/img/progress_bar.gif" style="border:0;" /></center>';
		$("#fotoupload_progress").html(progressimg);
		
		$("#fotoupload_frm").attr('action', '/galeria-enviar.html');
		$("#fotoupload_frm").attr('target', 'fotoupload_ifrm');
		$("#fotoupload_frm").submit();
		$("#fotoupload_frm").hide();
	});
	
	$(".thumbnails").click(function(){
		var imgpath = $(this).attr('src');
		ftmd(imgpath);
	});
	
	$("[title]").qtip({
		position: {
			corner: {
				target: 'topRight',
				tooltip: 'bottomLeft'
			}
		},
		style: {
			name: 'cream',
			padding: '7px 13px',
			width: {
				max: 210,
				min: 0
			},
			tip: true
		}
	});
	$(".button").button();
	$("form").form();
});

function isMobile(){ var index = navigator.appVersion.indexOf("Mobile"); return (index > -1); }
function setStyleMobile(){ document.write('<link rel="stylesheet" href="/css/mobile.css" />\n'); }
function setStyleRegular(){ document.write('<link rel="stylesheet" href="/css/site.css" />\n'); }
if(isMobile()){ setStyleMobile(); }else{ setStyleRegular(); }