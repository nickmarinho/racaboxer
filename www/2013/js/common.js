$(document).ready(function(){
	if($("#busca_frm #w").val() != 'faça sua busca aqui no site...') {
		$("#busca_frm #w").css({ color : '#000000' });
	}
	$("#busca_frm #w").focus(function(){
		if($(this).val() == 'faça sua busca aqui no site...') {
			$(this).css({ color : '#000000' });
			$(this).val('');
		}
	});
	$("#busca_frm #w").blur(function(){
		if($(this).val() == '') {
			$(this).css({ color : '#CCCCCC' });
			$(this).val('faça sua busca aqui no site...');
		}
	});
	$("#busca_frm #busca_btn").click(function(){
		if($("#busca_frm #w").val() == '' || $("#busca_frm #w").val() == 'faça sua busca aqui no site...') {
			alert('Por favor digite o termo a ser buscado');
			$("#busca_frm #w").focus();
		}
		else {
			var word = $("#busca_frm #w").val();
			location = '/busca/w/' + word;
		}
	});
	
});

