		<a style='margin:10px 0;' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'>
			<span class='ui-button-text' style='padding:0.8px 6px !important;'>
				<b>letras iniciais: </b>
			</span>
		</a>
		<select id='initialname' name='initialname'>
			<option value=''>--</option>
<?php
foreach($names as $namesk => $namesv) {
	$selected='';
	if(!empty($_GET['initialname']) && $_GET['initialname'] == $namesk) { $selected = ' selected=selected'; }
	echo "<option value='" . $namesk . "' " . $selected . ">" . $namesv . "</option>\n";
}
?>
		</select>
		<select id='initialorder' name='initialorder'>
			<option value=''>--</option>
<?php
foreach( range( 'a', 'z' ) as $i ) {
	$selected='';
	if(!empty($_GET['initialorder']) && $_GET['initialorder'] == $i) { $selected = ' selected=selected'; }
	echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>\n";
}
?>
		</select>
		<a id="initialreset" style='margin:10px 0;' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'>
			<span class='ui-button-text' style='padding:0.8px 6px !important;'>
				<b>resetar</b>
			</span>
		</a>
		<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
			if($("#initialreset").length) {
				$("#initialreset").click(function(){
					var windowgoto='';
					wls = window.location.search.replace(/\?/gi,'');
					querystring = wls.split('&');
					for(var i=0; i<querystring.length; i++) {
						qs = querystring[i].split('=');
						if(qs[0] != '' && qs[0] != null && qs[0] != 'initialname' && qs[0] != 'initialorder' && qs[0] != '?' && qs[1] != '?') {
							windowgoto += qs[0] + '=' + qs[1] + '&';
						}
					}
					location=window.location.pathname + '?' + windowgoto;
				});
			}

			if($('#initialname').length && $('#initialorder').length) {
				$('#initialname').change(function() {
					if($('#initialname').val() != '' && $('#initialorder').val() != '' ) {
						var windowgoto='';
						wls = window.location.search.replace(/\?/gi,'');
						querystring = wls.split('&');
						for(var i=0; i<querystring.length; i++) {
							qs = querystring[i].split('=');
							if(qs[0] != '' && qs[0] != null && qs[0] != 'initialname' && qs[0] != 'initialorder' && qs[0] != '?' && qs[1] != '?') {
								windowgoto += qs[0] + '=' + qs[1] + '&';
							}
						}
						windowgoto += 'initialname=' + $('#initialname').val() + '&initialorder=' + $('#initialorder').val();
						location=window.location.pathname + '?' + windowgoto;
					}
				});

				$('#initialorder').change(function() {
					if($('#initialname').val() != '' && $('#initialorder').val() != '' ) {
						var windowgoto='';
						wls = window.location.search.replace(/\?/gi,'');
						querystring = wls.split('&');
						for(var i=0; i<querystring.length; i++) {
							qs = querystring[i].split('=');
							if(qs[0] != '' && qs[0] != null && qs[0] != 'initialname' && qs[0] != 'initialorder' && qs[0] != '?' && qs[1] != '?') {
								windowgoto += qs[0] + '=' + qs[1] + '&';
							}
						}
						windowgoto += 'initialname=' + $('#initialname').val() + '&initialorder=' + $('#initialorder').val();
						location=window.location.pathname + '?' + windowgoto;
					}
				});
			}
		</script>