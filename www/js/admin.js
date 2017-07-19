<!--
/* nao remover */
var newfieldcount = 0;
var filteron = 0;
var imageeditcropfile = '';
var imageeditcropcancelbtnnum = 0;
/* fim nao remover */

var block = function(){
	var btn = '<div onclick="$.unblockUI();" class="button ui-button ui-widget ui-state-default ui-corner-all edit ui-button-text-only"><span class="ui-icon ui-icon-close"></span></div>';
	$.blockUI({theme: true, message: '<center>please wait ' + btn + '</center>'});
};

var previewpage = function(page, id){
	if($("#previewpage_"+page).length){ $("#previewpage_"+page).remove(); }
	$.get('/admin/view'+page, {id:id}, function(data){
		var newdiv = $(document.createElement('div')).attr('id', 'previewpage_'+page);
		$('#list').append(newdiv);
		$('#previewpage_'+page).html(data)
			.dialog({ title : "Preview of '" + page + "' - ID: " + id, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
	});
};

var listpage = function(page, p, cpfcnpj, email, id_cliente, name, nome, title, user){
	block();
	if(cpfcnpj == null){ cpfcnpj=''; } if(email == null){ email=''; } if(id_cliente == null){ id_cliente=''; }
	if(name == null){ name=''; } if(nome == null){ nome=''; } if(title == null){ title=''; } if(user == null){ user=''; }
	$.get('/admin/list'+page+'', { p : p, cpfcnpj : cpfcnpj, email : email, id_cliente : id_cliente, name : name, nome : nome, title : title, user : user}, function(data)
	{
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		loadjsposload();
		$.unblockUI();
	});
};

var addpage = function(page){
	if($("#addpage_"+page).length){ $("#addpage_"+page).remove(); }
	var newdiv = $(document.createElement('div')).attr('id', 'addpage_'+page);
	$('#list').append(newdiv);
	
	$.get('/admin/add'+page, {}, function(data){
		$('#addpage_'+page).html(data)
			.dialog({ title : "Adding '" + page + "'", modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
		$("form").form();
		$(".button").button();
	});
};

var editpage = function(page, id){
	if($("#editpage_"+page).length){ $("#editpage_"+page).remove(); }
	var newdiv = $(document.createElement('div')).attr('id', 'editpage_'+page);
	$('#list').append(newdiv);
	
	$.get('/admin/edit'+page, {id:id}, function(data){
		$('#editpage_'+page).html(data)
			.dialog({ title : "Editing '" + page + "' - ID: " + id, modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
		$("form").form();
		$(".button").button();
	});
};

var editpageinline = function(page, id, div){
	$("#list_"+div).removeAttr('onclick');
	$.get('/admin/edit' + page, {id : id, div : div}, function(savedfield){
		$("#list_"+div).html(savedfield);
		$('#namenew_'+id).focus().select();
	});
};

var removepage = function(page, id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/remove'+page, {id : id, list:list}, function(data){
			if(data == '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;
				if($("#list tr").length <= 2){ $("#list_empty").fadeIn().slideDown(500); }
			}
			else alert(data);
	    });
	}
};

var activeonoff = function(page, id, list){
	if(list == '0'){ var list = 'zero'; $.get('/admin/activeonoff'+page, {id : id, list : list}, function(data){$('#activeonoff_' + id).html(data)}); }
	else{ $.get('/admin/activeonoff'+page, {id : id, list : list}, function(data){$('#activeonoff_' + id).html(data)}); }
};

var displayonoff = function(page, id, list){
	if(list == '0'){ var list = 'zero'; $.get('/admin/displayonoff'+page, {id : id, list : list}, function(data){$('#displayonoff_' + id).html(data)}); }
	else{ $.get('/admin/displayonoff'+page, {id : id, list : list}, function(data){$('#displayonoff_' + id).html(data)}); }
};

var sendmail = function(n, e){
	if($("#sendmail").length){ $("#sendmail").remove(); }
	var newdiv = $(document.createElement('div')).attr('id', 'sendmail').css('display','none');
	$('body').append(newdiv);
	
	$.get('/admin/sendmail', { name : n, email : e }, function(data){
		$('#sendmail').html(data)
			.dialog({ title : "Send Email to '" + n + "' <" + e + "> ", modal : '1', resizable: true, show: 'fadeIn', hide: 'fadeOut', width : $(window).width()-150 });
		$("form").form();
		$(".button").button();
	});
};




















/* LISTS */
var listblog = function(p, email, name, title, user){
	$.get('/admin/listblog', {p : p, title : title}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

var listemails = function(p, cpfcnpj, email, id_cliente, name, nome, title, user){
	$.get('/admin/listemails', {p : p, name : name, email : email}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

var listimages = function(p, email, name, title, user){
	jQuery.fn.modalBox({usejqueryuidragable : 'false'});
	$.get('/admin/listimages', {p : p, name : name, email : email}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

var listnewsletter = function(p, email, name, title, user){
	jQuery.fn.modalBox({usejqueryuidragable : 'false'});
	$.get('/admin/listnewsletter', {p : p, name : name, email : email}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

var listpages = function(p, email, name, title, user){
	$.get('/admin/listpages', {p : p, title : title}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

var listusers = function(p, email, name, title, user){
	$.get('/admin/listusers', {p : p, user : user, name : name, email : email}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		//$("#grid").simpleResizableTable();
	});
};

/* REMOVE */
var removeblog = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removeblog', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button openmodalbox' onclick='javascript:void(0);'>\n";
						newfield += "Nothing in database, click here to add<input type='hidden' name='ajaxhref' value='/admin/addblog' /></a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};

var removeimages = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removeimages', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove(); $('#listifrm_"+id+"').remove(); ",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button openmodalbox' onclick='javascript:void(0);'>\n";
						newfield += "Nothing in database, click here to add<input type='hidden' name='ajaxhref' value='/admin/addimages' /></a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};

var removenewsletter = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removenewsletter', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button openmodalbox' onclick='javascript:void(0);'>\n";
						newfield += "Nothing in database, click here to add<input type='hidden' name='ajaxhref' value='/admin/addnewsletter' /></a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};

var removepages = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removepages', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button openmodalbox' onclick='javascript:void(0);'>\n";
						newfield += "Nothing in database, click here to add<input type='hidden' name='ajaxhref' value='/admin/addpages' /></a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};

var removeusers = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removeusers', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button openmodalbox' onclick='javascript:void(0);'>\n";
						newfield += "Nothing in database, click here to add<input type='hidden' name='ajaxhref' value='/admin/addusers' /></a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};

/* FILTER */
var filter = function(page, event){
	if($("#ftitle").length){
		if($("#ftitle").val() != 'filter by title' && $("#ftitle").val() != ''){var title = $("#ftitle").val();}
	}
	if($("#fname").length){
		if($("#fname").val() != 'filter by name' && $("#fname").val() != ''){var name = $("#fname").val();}
	}
	if($("#fuser").length){
		if($("#fuser").val() != 'filter by user' && $("#fuser").val() != ''){var user = $("#fuser").val();}
	}
	if($("#femail").length){
		if($("#femail").val() != 'filter by email' && $("#femail").val() != ''){var email = $("#femail").val();}
	}

	var keyNum = 0;
    if(window.event){keyNum = event.keyCode;} // IE
    else if(event.which){keyNum = event.which;} // Netscape/Firefox/Opera
	
	if(keyNum == '13')
	{
		filteron++;
		
		$.get('/admin/list'+page, {title : title, name : name, user : user, email : email}, function(data){
			$("#maincontent").html(data);
			$('.button').button();
			loadfilters();
			filteron--;
		});
	}
}

var filterm = function(page){
	if($("#ftitle").length){
		if($("#ftitle").val() != 'filter by title' && $("#ftitle").val() != ''){var title = $("#ftitle").val();}
	}
	if($("#fname").length){
		if($("#fname").val() != 'filter by name' && $("#fname").val() != ''){var name = $("#fname").val();}
	}
	if($("#fuser").length){
		if($("#fuser").val() != 'filter by user' && $("#fuser").val() != ''){var user = $("#fuser").val();}
	}
	if($("#femail").length){
		if($("#femail").val() != 'filter by email' && $("#femail").val() != ''){var email = $("#femail").val();}
	}

	filteron++;
	
	
	$.get('/admin/list'+page, {title : title, name : name, user : user, email : email}, function(data){
		$("#maincontent").html(data);
		$('.button').button();
		loadfilters();
		filteron--;
	});
}

var loadfilters = function(){
	if($("#ftitle").length){
		$("#ftitle").attr('style', 'color:#CCC;');
		$("#ftitle").val('filter by title');
		$("#ftitle").click(function(){
			if($("#ftitle").val() == 'filter by title'){$("#ftitle").attr('style', 'color:#000;');$("#ftitle").val('');}
			else{$("#ftitle").attr('style', 'color:#000;');}
		});
		$("#ftitle").blur(function(){
			if($("#ftitle").val() == ''){$("#ftitle").val('filter by title');$("#ftitle").attr('style', 'color:#CCC;');}
			else if($("#ftitle").val() == 'filter by title'){$("#ftitle").attr('style', 'color:#CCC;');}
			else{$("#ftitle").attr('style', 'color:#000;');}
		});
	}

	if($("#fname").length){
		$("#fname").attr('style', 'color:#CCC;');
		$("#fname").val('filter by name');
		$("#fname").click(function(){
			if($("#fname").val() == 'filter by name'){$("#fname").attr('style', 'color:#000;');$("#fname").val('');}
			else{$("#fname").attr('style', 'color:#000;');}
		});
		$("#fname").blur(function(){
			if($("#fname").val() == ''){$("#fname").val('filter by name');$("#fname").attr('style', 'color:#CCC;');}
			else if($("#fname").val() == 'filter by name'){$("#fname").attr('style', 'color:#CCC;');}
			else{$("#fname").attr('style', 'color:#000;');}
		});
	}

	if($("#fuser").length){
		$("#fuser").attr('style', 'color:#CCC;');
		$("#fuser").val('filter by user');
		$("#fuser").click(function(){
			if($("#fuser").val() == 'filter by user'){$("#fuser").attr('style', 'color:#000;');$("#fuser").val('');}
			else{$("#fuser").attr('style', 'color:#000;');}
		});
		$("#fuser").blur(function(){
			if($("#fuser").val() == ''){$("#fuser").val('filter by user');$("#fuser").attr('style', 'color:#CCC;');}
			else if($("#fuser").val() == 'filter by user'){$("#fuser").attr('style', 'color:#CCC;');}
			else{$("#fuser").attr('style', 'color:#000;');}
		});
	}

	if($("#femail").length){
		$("#femail").attr('style', 'color:#CCC;');
		$("#femail").val('filter by email');
		$("#femail").click(function(){
			if($("#femail").val() == 'filter by email'){$("#femail").attr('style', 'color:#000;');$("#femail").val('');}
			else{$("#femail").attr('style', 'color:#000;');}
		});
		$("#femail").blur(function(){
			if($("#femail").val() == ''){$("#femail").val('filter by email');$("#femail").attr('style', 'color:#CCC;');}
			else if($("#femail").val() == 'filter by email'){$("#femail").attr('style', 'color:#CCC;');}
			else{$("#femail").attr('style', 'color:#000;');}
		});
	}
};

var loadjsposload = function(){
	var ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
    ga.src = '/js/admin-pos-load.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
};

var loadtools = function(){
	if($("#name").length && $("#name").val() != '')
	{
		var varString = $("#name").val();
		var stringAcentos   = 'áàâãäéèêëíìîïóòôõöúùûüçñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÇÑ';
		var stringSemAcento = 'aaaaaeeeeiiiiooooouuuucnAAAAAEEEEIIIIOOOOOUUUUCN';
		var varRes = "";

		for(var i = 0; i < varString.length; i++){
			if(varString[i] == stringAcentos.substring(i,1)){varRes += stringSemAcento.substring(i,1);}
			else{varRes += varString[i];}
		}
		
		varRes = varRes.replace(/ /g,"-")
		.replace(/(\-)\1+/gi,"-")
		//.replace(/[^0-9a-zA-Z]/g,"-")
		.toLowerCase();
		$("#name").val(varRes);
	}
	
	if($("#url").length && $("#url").val() != '')
	{
		var varString = $("#url").val();
		var stringAcentos   = 'áàâãäéèêëíìîïóòôõöúùûüçñÁÀÂÃÄÉÈÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÇÑ';
		var stringSemAcento = 'aaaaaeeeeiiiiooooouuuucnAAAAAEEEEIIIIOOOOOUUUUCN';
		var varRes = "";

		for(var i = 0; i < varString.length; i++){
			if(varString[i] == stringAcentos.substring(i,1)){varRes += stringSemAcento.substring(i,1);}
			else{varRes += varString[i];}
		}
		
		varRes = varRes.replace(/ /g,"-")
		.replace(/(\-)\1+/gi,"-")
		//.replace(/[^0-9a-zA-Z]/g,"-")
		.toLowerCase();
		$("#url").val(varRes);
	}
	
	if($("#meta_keywords").length && $("#meta_keywords").val() != '')
	{
		var varString = $("#meta_keywords").val();
		varRes = varString.replace(/ /g,",")
		.replace(/(,)\1+/gi,",")
		.toLowerCase();
		$("#meta_keywords").val(varRes);
	}	
};

var formatblogfields = function(){
	if($("#title").val() != '')
	{
		var title = $("#title").val();
		$("#url").val(title);
		$("#meta_keywords").val(title);
		$("#meta_description").val(title);
		loadtools();
		$("#meta_description").focus();
	}
};

var formatpagesfields = function(){
	if($("#title").val() != '')
	{
		var title = $("#title").val();
		$("#url").val(title);
		$("#meta_keywords").val(title);
		$("#meta_description").val(title);
		loadtools();
		$("#meta_description").focus();
	}
};

var imagetemplate = function(field){
	var template = "<div style=\"text-align:center;\">\n";
	template += "<img src=\"\" style=\"cursor:pointer;width:300px;\" onclick=\"showImage('');\" /><br />\n";
	template += "</div>\n";
	//$("#"+field).val($("#"+field).val()+template);
	alert(template);
};

var imageeditcrop = function(id){
	$("#editimagecropifrm_"+id).attr('src', '/admin/editimagecrop?id='+id);
	$("#editimagecropifrm_"+id).show();
	$("#listifrm_"+id).fadeIn().slideDown(1000);
}

var imageeditcropcancel = function(id){
	$("#listifrm_"+id).fadeOut().slideUp(1000);
	$("#editimagecropifrm_"+id).hide();
	$("#editimagecropifrm_"+id).removeAttr('src');
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

var ptemplate = function(field){
	var template = "<p></p>\n";
	//$("#"+field).val($("#"+field).val()+template);
};

/* EMAILS : functions in line then i put it all together */
var addemails = function(){
	var newfield = "<tr id='list_"+newfieldcount+"' class='impar' onmouseover='this.className=\"over\";' onmouseout='this.className=\"impar\"'>\n";
	newfield += " <td id='td_"+newfieldcount+"' colspan='3' style='width:944px;'>\n";
	newfield += "  <input type='text' name='namenew_"+newfieldcount+"' id='namenew_"+newfieldcount+"' value='' onkeypress='javascript:saveemails(\""+newfieldcount+"\", event);' size='20' />&nbsp;\n";
	newfield += "  <input type='text' name='emailnew_"+newfieldcount+"' id='emailnew_"+newfieldcount+"' value='' onkeypress='javascript:saveemails(\""+newfieldcount+"\", event);' size='20' />\n";
	newfield += "  <a class='button' onclick='javascript:savememails(\""+newfieldcount+"\");'>Ok</a>\n";
	newfield += "  <a class='button' onclick=\"canceladd('"+newfieldcount+"');\">Cancel</a>\n";
	newfield += " </td>\n";
	newfield += "</tr>\n";
	
	if($("#list_empty").length){$("#list_empty").fadeOut().slideUp(500);}
	$("#list").append(newfield);
	$('#namenew_'+newfieldcount).focus().select();
	$(".button").button();
	newfieldcount++;
};

var canceladd = function(list){
    $("#list_"+list).fadeOut().slideUp(500);
    setTimeout("$('#list_"+list+"').remove();",1000);
    newfieldcount--;
    
    if($("#list tr").length <= 2 && $("#list_empty").length){$("#list_empty").fadeIn().slideDown(500);}
};

var saveemails = function(div, event){
	var keyNum = 0;
	if(window.event){keyNum = event.keyCode;} // IE
	else if (event.which){keyNum = event.which;} // Netscape/Firefox/Opera

	if(keyNum == '13' && $("#namenew_"+div).val() != '' && $("#emailnew_"+div).val() != '')
	{
		var name = $("#namenew_"+div).val();
		var email = $("#emailnew_"+div).val();
		$.get('/admin/saveemails', {name : name, email : email, display : '1'}, function(id){
			if(id == 'error'){alert(id);}
			else{
				$.get('/admin/fetchbyidemails', {id : id, div : div}, function(savedfield){
					$("#list_"+div).html(savedfield);
					var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');";
					//this below doesnt work, so, i do in pure javascript
					//$("#list_"+data).attr("onclick", js);
					document.getElementById("td_"+div).setAttribute("onclick", js);
				});
			}
		});
	}
};

var savememails = function(div, event){
	if($("#namenew_"+div).val() != '' && $("#emailnew_"+div).val() != '')
	{
		var name = $("#namenew_"+div).val();
		var email = $("#emailnew_"+div).val();
		$.get('/admin/saveemails', {name : name, email : email, display : '1'}, function(id){
			if(id == 'error'){alert(id);}
			else{
				$.get('/admin/fetchbyidemails', {id : id, div : div}, function(savedfield){
					$("#list_"+div).html(savedfield);
					var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');";
					//this below doesnt work, so, i do in pure javascript
					//$("#list_"+data).attr("onclick", js);
					document.getElementById("td_"+div).setAttribute("onclick", js);
				});
			}
		});
	}
};

var canceledit = function(id, div, pagetoreload){
	$.get('/admin/fetchbyid'+pagetoreload, {id : id, div : div}, function(updatedfield){
		$("#list_"+div).html(updatedfield);

		if(pagetoreload == 'emails'){ var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');"; }
		else{ var js = "javascript:edit"+pagetoreload+"('" + id + "', '" + div + "');"; }
		
		//this below doesnt work, so, i do in pure javascript
		//$("#list_"+div).attr("onclick", js);
		document.getElementById("td_"+div).setAttribute("onclick", js);
	});
};

var updateemails = function(id, div, event){
	var keyNum = 0;
	if(window.event){keyNum = event.keyCode;} // IE
	else if (event.which){keyNum = event.which;} // Netscape/Firefox/Opera
	
	if(keyNum == '13' && $("#namenew_"+div).val() != '' && $("#emailnew_"+div).val() != '')
	{
		var name = $("#namenew_"+id).val();
		var email = $("#emailnew_"+id).val();
		$.get('/admin/saveemails', {id : id, div : div, name : name, email : email}, function(data){
			if(data == 'error'){alert(data);}
			else{
				$.get('/admin/fetchbyidemails', {id : data, div : div}, function(updatedfield){
					$("#list_"+div).html(updatedfield);

					var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');";
					//this below doesnt work, so, i do in pure javascript
					//$("#list_"+div).attr("onclick", js);
					document.getElementById("td_"+div).setAttribute("onclick", js);
				});
			}
		});
	}
	
	if(keyNum == '27')
	{
		$.get('/admin/fetchbyidemails', {id : id, div : div}, function(updatedfield){
			$("#list_"+div).html(updatedfield);

			var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');";
			//this below doesnt work, so, i do in pure javascript
			//$("#list_"+div).attr("onclick", js);
			document.getElementById("td_"+div).setAttribute("onclick", js);
		});
	}
};

var updatememails = function(id, div){
	if($("#namenew_"+div).val() != '' && $("#emailnew_"+div).val() != '')
	{
		var name = $("#namenew_"+id).val();
		var email = $("#emailnew_"+id).val();
		$.get('/admin/saveemails', {id : id, div : div, name : name, email : email}, function(data){
			if(data == 'error'){alert(data);}
			else{
				$.get('/admin/fetchbyidemails', {id : data, div : div}, function(updatedfield){
					$("#list_"+div).html(updatedfield);

					var js = "javascript:editpageinline('emails', '" + id + "', '" + div + "');";
					//this below doesnt work, so, i do in pure javascript
					//$("#list_"+div).attr("onclick", js);
					document.getElementById("td_"+div).setAttribute("onclick", js);
				});
			}
		});
	}
};

var sendnewsletter = function(id){
	var returnmsg = "";
	returnmsg = "<img style='background:url(\"/img/loading.gif\");height:15px;width:15px;' src='/img/1x1.gif' />";
	$("#newsletter_"+id).html(returnmsg);
	
	$.get('/admin/sendnewsletter', { id:id }, function(data) {
		if(data == "1") { alert("Enviado com sucesso !"); }
		else if(data == "2") { alert("Não tem emails para enviar"); }
		else if(data == "3") { alert("Não selecionou a newsletter corretamente"); }
		else { alert(data); }

		returnmsg = "";
		returnmsg += "   <div class='ui-state-default ui-corner-all pag-icons' onclick='javascript:sendnewsletter(\"" + id + "\");'>\n";
		returnmsg += "    <span class='ui-icon ui-icon-mail-closed' title='Send Newsletter ID: \"" + id + "\"'></span>\n";
		returnmsg += "   </div>\n";
		
		$("#newsletter_"+id).html(returnmsg);
		listnewsletter();
	});
};

var sendconfirmationmail = function(id){
	var returnmsg = "";
	returnmsg = "<img style='background:url(\"/img/loading.gif\");height:15px;width:15px;' src='/img/1x1.gif' />";
	$("#confirmationmail_"+id).html(returnmsg);
	
	$.get('/admin/sendconfirmationmail', {id : id}, function(data){
		if(data == "1"){alert("Enviado com sucesso !");}
		else if(data == "2"){alert("Não foi possível enviar");}
		else{alert(data);}

		returnmsg = "";
		returnmsg += "   <div class='ui-state-default ui-corner-all pag-icons' onclick='javascript:sendconfirmationmail(\"" + id + "\");'>\n";
		returnmsg += "    <span class='ui-icon ui-icon-mail-closed' title='Send mail to confirm ID: \"" + id + "\"'></span>\n";
		returnmsg += "   </div>\n";
		
		$("#confirmationmail_"+id).html(returnmsg);
	});
};

var removeemails = function(id, list){
	if(confirm('Do you really want to remove this ?')){
		$.get('/admin/removeemails', {id : id}, function(data){
			if(data = '1')
			{
				$("#list_"+list).fadeOut().slideUp(500);
				setTimeout("$('#list_"+list+"').remove();",1000);
				newfieldcount--;

				if($("#list tr").length <= 2){
					if($("#list_empty").length){
						$("#list_empty").fadeIn().slideDown(500);
					}
					else{
						var newfield = "<tr id='list_empty' class='impar' onmouseover='this.className=\"over\"' onmouseout='this.className=\"impar\"'>\n";
						newfield += " <td colspan='7' style='text-align:center;'><a class='button' onclick='addemails();'>\n";
						newfield += "Nothing in database, click here to add</a></td>\n";
						newfield += "</tr>\n";
						$("#list").append(newfield);
						$(".button").button();
					}
				}
			}
			else alert(data);
	    });
	}
};
/* end of EMAIL functions */

var formataCampos = function(camp, Mascara){
	var campo = document.getElementById(camp);
	var boleanoMascara;
	exp = /\-|\.|\/|\(|\)| /g
	campoSoNumeros = campo.value.toString().replace(exp, "");
	var posicaoCampo = 0;
	var NovoValorCampo = "";
	var TamanhoMascara = campoSoNumeros.length;;
	for (i = 0; i <= TamanhoMascara; i++)
	{
		boleanoMascara = ((Mascara.charAt(i) == "-") || (Mascara.charAt(i) == ".") || (Mascara.charAt(i) == "/"))
		boleanoMascara = boleanoMascara || ((Mascara.charAt(i) == "(") || (Mascara.charAt(i) == ")") || (Mascara.charAt(i) == " "))
		if(boleanoMascara)
		{
			NovoValorCampo += Mascara.charAt(i);
			TamanhoMascara++;
		}
		else
		{
			NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
			posicaoCampo++;
		}
	}
	campo.value = NovoValorCampo;
	return true;
}

var mascaracpf = function(campo){
	if(document.getElementById(campo).value.length > 14){return mascaracnpj(campo);}
	document.getElementById(campo+'-label').innerHTML = '<label class="required" for="cpfcnpj">* <span style="font-size:13px;">CPF</span>/Cnpj:</label>';
	return formataCampos(campo, '000.000.000-00');
}

var mascaracnpj = function(campo){
	document.getElementById(campo+'-label').innerHTML = '<label class="required" for="cpfcnpj">* CPF/<span style="font-size:13px;">Cnpj</span>:</label>'
    return formataCampos(campo, '00.000.000/0000-00');
}

var getHost = function(){
	var url = window.location.href;
	var nohttp = url.split('//')[1];
	var domain = nohttp.split('/')[0];
	var subdomain = nohttp.split('/')[1];
	var module = nohttp.split('/')[2];
	return "http://" + domain + "/" + subdomain + "/" + module;
}

$(window).unload(function(){block();});
$(document).ready(function(){
	$("#top").click(function(){window.open('/');});
	$("#submitbutton").button();
	$("#button").button();
	$(".button").button();
	$("#submitbutton").click(function(){block();$("#form_adminlogin").submit();});
	$("form").form();
	//$("#themeswitch").themeswitcher();
	
	$("#menu ul li").hover(
		function(){ $(this).addClass("ui-state-hover"); },
		function(){ $(this).removeClass("ui-state-hover"); }
	);
	
	setTimeout("$.unblockUI();", 1500);
	//$(".ui-widget").css('font-size','0.8em');
});
//-->