<?php
/* 
 * this is the modal message that is showed when add or edit database things.
 * url can be set to var $link, this will redirect the page after show this message
 *
 * Luciano Marinho - nickmarinho@gmail.com - 2011-04-18 08:29
 */
echo "<script>\n";
echo "$(document).ready(function()
	{
		var newButton = $(document.createElement('div'))
		.attr('class', 'success')
		.attr('id', 'msgreturn')
		.attr('style', 'display:none;')
		;
		newButton.appendTo('#tdbuttons');
		$('#msgreturn').html('<h1 style=\"color:#" . $fontcolor . ";text-align:center;\">" . $modalmessage . "</h1>');

		jQuery.fn.modalBox({
			usejqueryuidragable : true,
			killModalboxWithCloseButtonOnly : true,
			directCall: {
				element : '#msgreturn'
			}
		});\n";

if(empty($link)) echo "setTimeout(\"jQuery.fn.modalBox('close');\", 2000);\n";
else echo "setTimeout(\"location='" . $link . "';\", 2000);\n";

echo "	});\n
	</script>\n";
?>