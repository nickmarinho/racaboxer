		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a style='margin:10px 0;' href='javascript:void(0);' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'>
			<span class='ui-button-text' style='padding:0.8px 6px !important;'>
				<b>quantidade por p&aacute;gina: </b>
			</span>
		</a>
		<select id='qtyperpage'>
<?php
$values = array('' => '--',
				'5' => '5',
				'10' => '10',
				'20' => '20',
				'40' => '40',
				'80' => '80',
				'120' => '120',
				'160' => '160',
				'180' => '180',
				'240' => '240',
				'280' => '280',
				'320' => '320',
				'350' => '350',
				'400' => '400',
				'500' => '500',
				'620' => '620',
				'680' => '680',
				'720' => '720',
				'800' => '800',
				'950' => '950',
				'1000' => '1000',
				'2000' => '2000');
foreach($values as $k => $v) {
	$selected='';
	if(!empty($_GET['c']) && $_GET['c'] == $k) { $selected = ' selected=selected'; }
	echo "<option value='" . $k . "' " . $selected . ">" . $v . "</option>\n";
}
?>
		</select>
		<script charset="<?php echo JSCHARSET; ?>" type="text/javascript">
			if($('#qtyperpage').length) {
				$('#qtyperpage').change(function() {
					if($('#qtyperpage').val() != '') {
						var windowgoto='';
						wls = window.location.search.replace(/\?/gi,'');
						querystring = wls.split('&');
						for(var i=0; i<querystring.length; i++) {
							qs = querystring[i].split('=');
							if(qs[0] != '' && qs[0] != null && qs[0] != 'c' && qs[0] != '?' && qs[1] != '?') {
								windowgoto += qs[0] + '=' + qs[1] + '&';
							}
						}
						windowgoto += 'c=' + $('#qtyperpage').val();
						location=window.location.pathname + '?' + windowgoto;
					}
				});
			}
		</script>