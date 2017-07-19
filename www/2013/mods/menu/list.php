				<h1 id="pagetitle">Menu do Sistema - <small><small>SEGURE E ARRASTE AO POSICIONAMENTO DESEJADO</small></small>
				    <div id="addmenu" class='tdpag-icons'>
						<div class="ui-state-default ui-corner-all pag-icons"><span class="ui-icon ui-icon-plusthick" title="Adicionar"></span></div>
				    </div>
				</h1>
				<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
				    $(function() {
					    //Make parent top menus sortable
					    $('#sortable_parent').sortable({
						    handle: '.p-menu-title',
						    cursor: 'hand',
						    placeholder: 'ui-state-highlight-top',
						    update : function () {
							    $.post("?mod=menu&a=s&ajax=1", {
								    page: $('#sortable_parent').sortable('serialize')
							    }, function(data){
									    var okbtn="<a class='button okbtn'>Salvo com sucesso !</a>";
									    $("#menuadmin").after(okbtn);
									    $(".okbtn").button();

									    setTimeout('$(".okbtn").fadeOut("1500").slideUp("2500");', 2500);
									    setTimeout('$(".okbtn").remove();', 2500);
							    });
						    }
					    });
					    //$("#sortable_parent").disableSelection();
				    });
				    $(function() {
					    //Make sub menus sortable
					    /*
					    $(".all-sub-menu").sortable({
						    connectWith: '.all-sub-menu', 
						    placeholder: 'ui-state-highlight',
						    dropOnEmpty: false
					    }).disableSelection();
					    */
				    });
				    $("#addmenu")
						.css("float", "left")
						.css("margin-right", "10px")
						.css("position", "relative")
						.css("width", "20px")
						.click(function(){
							if($("#addmenutpl").length){ $("#addmenutpl").remove(); }
							if($("#editmenutpl").length){ $("#editmenutpl").remove(); }
							
							$.get('<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=menu&a=a&ajax=1', {}, function(data){
								var newdiv = $(document.createElement('div')).attr('id', 'addmenutpl').css('display', 'none').html(data);
								$('#addmenu').after(newdiv);
								$("#addmenutpl").slideDown('3000').fadeIn('5000');
								$("#addmenutpl form").form();
								$("#addmenutpl .button").button();
								$("#addmenutpl [title]").qtip({
									position: {
										corner: {
											target: 'topRight',
											tooltip: 'bottomLeft'
										}
									},
									style: {
										name: 'grotta',
										padding: '7px 13px',
										width: {
											max: 210,
											min: 0
										},
										tip: true
									}
								});
					    });
					});
				    var canceladdmenu = function() {
						if($("#addmenutpl").length){ $("#addmenutpl").remove(); }
						if($(".qtip").length){ $(".qtip").remove(); }
				    };
				    var savemenu = function() {
						var erro=0;
						var msg="";
						var action=$("#action");
						var label=$("#label");
						var link=$("#link");
						var modn=$("#mod");
						var parent=$("#parent");

						if(label.val() == '') {
							erro++;
							msg += "Título<br />";
							label.focus().select();
							label.addClass('ui-state-error');
						}
						else { label.removeClass('ui-state-error'); }

						if(parent.val() == '') { var parentv='0'; }
						else { var parentv=parent.val(); }

						if(erro) {
							if($("#errormsg").length){ $("#errormsg").remove(); }
							$('<div/>').attr('id','errormsg')
								.html(msg)
								.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
						}
						else {
							$.get('<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=menu&a=s&ajax=1&new=1', {
								modn:modn.val(),
								action:action.val(),
								label:label.val(),
								link:link.val(),
								parent:parentv
							}, function(data){
								if(data == '1') {
									alert('Salvo com sucesso');
									var wl='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=<?php echo $_GET['mod']; ?>&done=1';
									window.top.location.href=wl;
								}
								else if(data == 'errEmpty') {
									alert('Preencha corretamente o formulário');
								}
								else { alert(data); }
							});
						}
				    };
				    var canceleditmenu = function() {
						if($("#editmenutpl").length){ $("#editmenutpl").remove(); }
						if($(".qtip").length){ $(".qtip").remove(); }
				    };
				    var saveemenu = function() {
						var erro=0;
						var msg="";
						var id=$("#id");
						var action=$("#action");
						var label=$("#label");
						var link=$("#link");
						var modn=$("#mod");
						var parent=$("#parent");

						if(label.val() == '') {
							erro++;
							msg += "Título<br />";
							label.focus().select();
							label.addClass('ui-state-error');
						}
						else { label.removeClass('ui-state-error'); }

						if(parent.val() == '') { var parentv='0'; }
						else { var parentv=parent.val(); }

						if(erro) {
							if($("#errormsg").length){ $("#errormsg").remove(); }
							$('<div/>').attr('id','errormsg')
								.html(msg)
								.dialog({ title : "Preencha os campos obrigat&oacute;rios!", modal : '1', resizable: false, show: 'fadeIn', hide: 'fadeOut', width : '430' });
						}
						else {
							$.get('<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=menu&a=s&ajax=1&edit=1', {
								id:id.val(),
								action:action.val(),
								label:label.val(),
								link:link.val(),
								modn:modn.val(),
								parent:parentv
							}, function(data){
								if(data == '1') {
									alert('Salvo com sucesso');
									var wl='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=<?php echo $_GET['mod']; ?>&done=1';
									window.top.location.href=wl;
								}
								else if(data == 'errEmpty') {
									alert('Preencha corretamente o formulário');
								}
								else { alert(data); }
							});
						}
				    };
				    $(document).ready(function(){
						$(".editmenu").click(function(){
							if($("#addmenutpl").length){ $("#addmenutpl").remove(); }
							if($("#editmenutpl").length){ $("#editmenutpl").remove(); }
					    
							var fullid = this.id;
							var fullidarr = fullid.split("_");
							var idname = fullidarr[0];
							var id = fullidarr[1];

							$.get('<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=menu', { id:id, a:'e', ajax:'1' }, function(data){
								var newdiv = $(document.createElement('div')).attr('id', 'editmenutpl').css('display', 'none').html(data);
								$('#addmenu').after(newdiv);
								$("#editmenutpl").slideDown('3000').fadeIn('5000');
								$("#editmenutpl form").form();
								$("#editmenutpl .button").button();
								$("#editmenutpl [title]").qtip({
									position: {
										corner: {
											target: 'topRight',
											tooltip: 'bottomLeft'
										}
									},
									style: {
										name: 'grotta',
										padding: '7px 13px',
										width: {
											max: 210,
											min: 0
										},
										tip: true
									}
								});
					    });
					});
					$(".delmenu").click(function(){
					    if(confirm('Deseja realmente remover ?')) {
							var removepermissions='NAO';
							if(confirm('Remover também as permissões desse módulo?')) { removepermissions='SIM'; }

							var fullid = this.id;
							var fullidarr = fullid.split("_");
							var idname = fullidarr[0];
							var id = fullidarr[1];

							$.get('<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=menu&a=d&ajax=1', { id:id, removepermissions : removepermissions }, function(data){
								if(data == '1') {
									alert('Removido com sucesso');
									var wl='<?php echo $_SESSION['SITE_IPATH']; ?>/admin/?mod=<?php echo $_GET['mod']; ?>&done=1';
									window.top.location.href=wl;
								}
								else { alert("Não foi possível remover \n\n"+data); }
							});
					    }
					    else { if($(".qtip").length){ $(".qtip").remove(); } } // remover os qtip que ficam se criando o tempo todo por causa dos titulos
					});
				    });
				</script>
				<style type="text/css">
				    .editmenu { float:left;margin-left:5px;margin-right:5px;position:relative;width:20px; }
				    .delmenu { float:left;margin-right:5px;position:relative;width:20px; }
				</style>

				<div id="menuadmin">
				<ul id="sortable_parent" class="all-menu">
<?php
echo generateMenusAdmin();
?>
				</ul>
				</div>
