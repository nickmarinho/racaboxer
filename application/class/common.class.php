<?php
/**
 * this class is to common tasks in the application
 * @copyright  2011 Luciano Marinho
 * @package    Márcio Bernardes
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-05-13 - friday
 * @version    1.0  
 */
class Common {
	/**
	 * on off functions
	 */
	public function onOffFunctions($type, $page, $id, $list, $ok) {
		if(!empty($type) && !empty($page) && !empty($id) && !empty($list) && !empty($ok)) {
			$class='';
			if($ok == 'check') {
				if(($list % 2) == 0){ $class = "overa"; }
				else{ $class = "overb"; }
			}
			else{ $class='noover'; }
			
			$return = "";
			if($list == 'zero') {
				$return .= "   <div class=\"ui-state-default ui-corner-all pag-icons\" onclick=\"javascript:" . $type . "onoff('" . $page . "','" . $id . "','0');\">\n";
			}
			else {
				$return .= "   <div class=\"ui-state-default ui-corner-all pag-icons\" onclick=\"javascript:" . $type . "onoff('" . $page . "','" . $id . "','" . $list . "');\">\n";
			}
			
			$return .= "<span class=\"ui-icon ui-icon-" . $ok . " title=\"Enable/Disable Display ID: " . $id . "\"></span></div>";

			$return .= "<script type='text/javascript' charset='UTF-8'>\n";
			$return .= "$(document).ready(function(){\n";
			
			if($list == 'zero') { $return .= "	$('#list_0').removeAttr('class')\n"; }
			else{ $return .= "		$('#list_".$list."').removeAttr('class')\n"; }
			
			$return .= "	.removeAttr('onmouseover')\n";
			$return .= "	.removeAttr('onmouseout')\n";
			$return .= "	.attr('class', '" . $class . "')\n";
			$return .= "	.attr('onmouseover', 'this.className=\'over\';')\n";
			$return .= "	.attr('onmouseout', 'this.className=\'" . $class . "\';')\n";
			$return .= "})\n";
			$return .= "</script>";
			
			return $return;
		}
	}
	
	/**
	 * image crop tpl
	 */
	public function imageCropTpl($id, $path) {
		if(!empty($id) && !empty($path)) {
			$return = "";
			$return .= "<script type='text/javascript' src='/js/jquery.imgareaselect.js' charset='UTF-8'></script>\n";
			$return .= "<script type='text/javascript' src='/js/admin.js' charset='UTF-8'></script>\n";
			$return .= "<link href='/css/jquery.imgareaselect-animated.css' rel='stylesheet' type='text/css' />\n";
			$return .= "<form action='/admin/saveimagecrop' id='fotocrop_frm' name='fotocrop_frm' method='post'>\n";
			$return .= " <table align='center' class='login' style='width:986px !important;'>\n";
			$return .= "  <tbody>\n";
			$return .= "   <tr onmouseout=\"this.className='impar';\" onmouseover=\"this.className='over';\" class='impar'>\n";
			$return .= "    <td align='center' id='tdbuttons'>\n";
			$return .= "     <center><img src='/img/dogs/" . $path . "' id='thumbnail_" . $id . "'>\n";
			$return .= "     <br />" . $path . "</center>\n";
			$return .= "     <input type='hidden' name='x1' id='x1' />\n";
			$return .= "     <input type='hidden' name='y1' id='y1' />\n";
			$return .= "     <input type='hidden' name='x2' id='x2' />\n";
			$return .= "     <input type='hidden' name='y2' id='y2' />\n";
			$return .= "     <input type='hidden' name='w' id='w' />\n";
			$return .= "     <input type='hidden' name='h' id='h' />\n";
			$return .= "     <input type='hidden' name='id' id='id' value='" . $id . "'' /><br />\n";
			$return .= "     <a href='javascript:void(0);' class='button' onclick='$(\"#fotocrop_frm\").submit();'>Save</a>&nbsp;\n";
			$return .= "     <a href='javascript:void(0);' class='button' onclick='window.parent.imageeditcropcancel(\"" . $id . "\");'>Cancel</a>\n";
			$return .= "     <script>\n";
			$return .= "		$('.button').button();\n";
			$return .= "		$('#thumbnail_" . $id . "').imgAreaSelect({\n";
			$return .= "			handles: true,\n";
			$return .= "			fadeSpeed: 200,\n";
			$return .= "			onSelectChange: preview\n";
			$return .= "		});\n";
			$return .= "     </script>\n";
			$return .= "    </td>\n";
			$return .= "   </tr>\n";
			$return .= "  </tbody>\n";
			$return .= " </table>\n";
			$return .= "</form>\n";
			return $return;
		}
	}
	
	/**
	 * style of the table.login
	 */
	public function styleTableLogin($size = '900') {
		return "<style>table.login{width:" . $size . "px !important;}</style>\n";
	}
	
	/**
	 * add jquery value
	 */
	public function addJqueryValue($div, $type, $value) {
		if(!empty($div) && !empty($type) && !empty($value)){ return "$(document).ready(function(){ $(\"#" . $div. "\")." . $type . "(\"" . $value . "\"); });\n"; }
	}
	
	/**
	 * add jquery function
	 */
	public function addJqueryFunction($selector, $function, $options=null) {
		return "$(document).ready(function(){ $(\"" . $selector. "\")." . $function . "(" . $options . "); });\n";
	}
	
	/**
	 * view image tmp
	 */
	public function viewImageTpl($id, $path, $obs) {
		$return = "";
		$return .= "<center>\n";
		$return .= "<b>" . $id . "</b><br />\n";
		$return .= "<img src='/img/dogs/" . $path . "' style='border:1px dotted #000000;' /><br />\n";
		$return .= "<div class='notice'>" . stripslashes($obs) . "</div><br />\n";
		$return .= "</center>\n";
		return $return;
	}
	
	/**
	 * function for click in radio email change
	 */
	public function inputRadioEmailsChange() {
		$return = "";
		$return .= "$(document).ready(function(){\n";
		$return .= "	$(\"#emails-label\").attr('style','vertical-align:top;');\n";
		//$return .= "	var emailsvalue = $(\"input:radio[name='emails']:checked\").val();\n";
		$return .= "	$(\"input:radio[name='emails']\").change(function(){\n";
		$return .= "		if($(this).val() == 'selecionar')\n";
		$return .= "		{\n";
		$return .= "			if($(\"#selecionaremails_ifrm\").length){ $(\"#selecionaremails_ifrm\").remove(); }\n";
		$return .= "			if($(\"#emails-selecionados\").length){ $(\"#emails-selecionados\").remove(); }\n";
		$return .= "			$(\"label[for='emails-selecionar']\").after(iframeemailselecionar);\n";
		$return .= "			$(\"#selecionaremails_ifrm\").after(emailsel);\n";
		$return .= "			$(\"#emails-selecionados\").append(emails);\n";
		$return .= "		}\n";
		$return .= "		else\n";
		$return .= "		{\n";
		$return .= "			if($(\"#selecionaremails_ifrm\").length){ $(\"#selecionaremails_ifrm\").remove(); }\n";
		$return .= "			if($(\"#emails-selecionados\").length){ $(\"#emails-selecionados\").remove(); }\n";
		$return .= "		}\n";
		$return .= "	});\n";
		$return .= "	$(\"input:radio[name='emails']\").click();\n";
		$return .= "});\n";
		return $return;
	}
	
	/**
	 * add email cadastrado div
	 */
	public function addEmailCadastradoDiv($var, $id, $email) {
		if(!empty($var) && !empty($id) && !empty($email)) {
			$return = "";
			$return .= $var . " += \"<div id='email_" . $id . "' class='emails' onclick='emaildeselecionar(\";";
			$return .= $var . " += '\"';";
			$return .= $var . " += \"" . $id . "\";";
			$return .= $var . " += '\"';";
			$return .= $var . " += \", \";";
			$return .= $var . " += '\"';";
			$return .= $var . " += \"" . $email . "\";";
			$return .= $var . " += '\"';";
			$return .= $var . " += \");' title='" . $id . " - " . $email . "'>\";";
			$return .= $var . " +=\"<input type='hidden' name='emails[]' value='" . $id . "' />" . $c . " - " . $email . "</div>\";\n";
			return $return;
		}
	}
	
	/**
	 * inicializa var
	 */
	public function inicializaVar($var) {
		if(!empty($var)){ return "var " . $var . " = \"\";\n"; }
	}
	
	/**
	 * insert do botao subir todos
	 */
	public function subirTodos() {
		$return = "";
		$return .= "var emailsel = \"<center>";
		$return .= "<a href='javascript:void(0);' id='subirtodos' class='button' onclick='subirtodos();'>Subir Todos</a>";
		$return .= "</center>";
		$return .= "<div id='emails-selecionados'></div>\";\n";
		return $return;
	}
	
	/**
	 * insert iframe de selecionar emails
	 */
	public function iframeEmailSelecionar($id_newsletter) {
		if(!empty($id_newsletter)) {
			$return = "";
			$return .= "var iframeemailselecionar = \"<iframe id='selecionaremails_ifrm' src='/admin/selecionaremails?id=" . $id_newsletter . "' style='border:0;height:325px;width:850px;'></iframe>\";\n";
			return $return;
		}
	}
	
	/**
	 * add js script tag to head loading a js file
	 */
	public function addJsScriptToHead($filename) {
		if(!empty($filename)) {
			$return = "";
			echo "(function(){\n";
			$return .= "	if($('#" . $filename . "').length){ $('#" . $filename . "').remove(); }\n";
			$return .= "	var scri = document.createElement('script');\n";
			$return .= "	scri.type = 'text/javascript';\n";
			$return .= "	scri.id = '" . $filename . "';\n";
			$return .= "	scri.async = true;\n";
			$return .= "	scri.src = '/js/admin/" . $filename . ".js';\n";
			$return .= "	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(scri);\n";
			$return .= "})();\n";
			return $return;
		}
	}
	
	/**
	 * input image template
	 */
	public function imageTemplate($whereInput) {
		if(!empty($whereInput)) {
			$return = "";
			$return .= "$(document).ready(function(){\n";
			$return .= "	$(\"#" . $whereInput . "-label\").attr('style','vertical-align:top;');\n";
			$return .= "	var imagetemplate = \"<a class='button' href='javascript:void(0);' onclick='imagetemplate(\";\n";
			$return .= "	imagetemplate += '\"" . $whereInput . "\"';\n";
			$return .= "	imagetemplate += \");'>Image</a>\";\n";
			$return .= "	$(\"#" . $whereInput . "-label\").append(imagetemplate);\n";
			$return .= "});\n";
			return $return;
		}
	}
	
	/**
	 * function that remove if are initialized instance of ckeditor with the same name in this page and start it again
	 */
	public function initializeCkeditorInstance($name) {
		if(!empty($name)) {
			$return = "";
			$return .= "$(document).ready(function(){\n";
			$return .= "	var " . $name . "_instance = CKEDITOR.instances['" . $name . "'];\n";
			$return .= "	if(" . $name . "_instance){ CKEDITOR.remove(" . $name . "_instance); }\n";
			$return .= "	CKEDITOR.replace('" . $name . "');\n";
			$return .= "	$(\"#submitbutton\").click(function(){\n";
			$return .= "		var " . $name . "_editor_data = CKEDITOR.instances." . $name . ".getData();\n";
			$return .= "		$(\"#" . $name . "\").text(" . $name . "_editor_data);\n";
			$return .= "	});\n";
			$return .= "});\n";
			return $return;
		}
	}
	
	/**
	 * open js script
	 */
	public function openJsScript() {
		return "<script type='text/javascript' charset='UTF-8'>";
	}
	
	/**
	 * close js script
	 */
	public function closeJsScript() {
		return "</script>";
	}
	
	/**
	 * function to return the success message
	 */
	public function successMess() {
		return '<div class="success">Saved !!!</div>';
	}
	
	/**
	 * function to return the error message
	 * Enter description here ...
	 */
	public function errorMess() {
		return "<div class='error'>Erro ao salvar, reveja o formulário !!!</div>\n";
	}
	
	/**
	 * function to close modalbox window and refresh the list
	 */
	public function modalboxClose($pagetorefresh = null) {
		$page = $pagetorefresh <> '' ? 'list' . $pagetorefresh . '();' : '';
		return "<script>setTimeout(\"jQuery.fn.modalBox('close');$('.ui-icon-closethick').click();" . $page . "\",1000);</script>";
	}
	
	/**
	 * Function to normalize names. Removing spaces and changing to lowercase
	 * Enter description here ...
	 * @param unknown_type $name
	 */
	public function normalizeNames($name) {
		if(!empty($name)) {
			$name = strtolower($name);
			return str_replace(' ', '-', $name); 
		}
	}

	/**
	 * this funcion returns a date actual to use in database 'Y-m-d H:i:s'
	 *
	 * @return date
	 */
	public function returnData() {
		return date(date("Y-m-d H:i:s"),
			    mktime(
				    date("H"),
				    date("i"),
				    date("s"),
				    date("m"),
				    date("d"),
				    date("Y")
			    )
		    );
	}

	/**
	 * this function is to create the structure of directories to save images based on md5 of the name of files
	 * ex. for id ehtyuiyje56321655142222.png
	 * will create the 'e' dir 
	 * and 'eh' 
	 * and 'eht' 
	 * and 'ehty' 
	 * and 'ehtyu' 
	 * after create this 5 recursive folders finally will save the file into it.
	 *
	 * @param string $path Path initial of the files
	 * @param int $id Id generated by database
	 * @return int $path The directory to save the files
	 */
	public function createPath($path, $file) {
		if($path <> '' && $file <> '' && strlen($file) >= 5) {
			$curdir="/";
			for($i=1; $i<=5; $i++) {
				$curdir .= substr($file, 0, $i) . "/";
				$folder = $path . $curdir;
				if(!is_dir($folder)) { @mkdir($folder, '0777', true); }
				@chmod($folder, 0777);
			}
			
			return $curdir;
		}
		else return false;
	}

	/**
	 * this function create a dinamic password
	 *
	 * @return string
	 */
	public function genPasswd() {
		$senha="";
		$letras = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
			);

		for($i=0;$i<8;$i++) {
			$pegaletra = rand(0,56);
			$senha.= $letras[$pegaletra];
		}

		return $senha;
	}
}