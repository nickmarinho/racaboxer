$(document).ready(function(){
	$("#paginationdiv").find(".pag-icons").each(function(){
		$(this).hover(
			function(){ $(this).addClass("ui-state-hover"); },
			function(){ $(this).removeClass("ui-state-hover") }
		);
	});

	setTimeout("$.unblockUI();", 500);
	
	$("#submitbutton").button();
	$("#button").button();
	$(".button").button();
	$("form").form();
	/*
	$("[title]").qtip({
		position: {
			corner: {
				//target: 'bottomRight',
				target: 'topRight',
				tooltip: 'bottomLeft'
			}
		},
		style: {
			name: 'orange',
			padding: '7px 13px',
			width: {
				max: 600,
				min: 0
			},
			tip: true
		}
	});
	*/
	setTimeout("$.unblockUI();", 500);
	
	
});
