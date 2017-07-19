<?php
include_once "../config.php";

if(!empty($_GET['dir'])) {
	$dir = dirname(__FILE__) . '/../' . $_GET['dir'];

	if ($handle = opendir($dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") { $files[0]=$entry; }
		}
		closedir($handle);

		sort($files);

		if(!empty($files[0])) {
            $entry = $files[0];
            
            //$sourceFile = dirname(__FILE__) . "/../../" . $_GET['dir'] . $entry;
            $img = Images_Thumb::getInstance();
            $img->setsourcefile($entry);
            $img->setsourcefolder($_GET['dir']);
            $img->settargetfolder($_GET['dir']);
            $img->setthumbheight(600);
            $img->setthumbwidth(600);
            $img->action();
            
    		echo "<div id='imagecrop'>\n";
    		echo "  <b class='gbuttona'>Selecione a área a ser salva na imagem</b>\n";
    		echo "  <br /><br />\n";
    		echo "  <table>\n";
    		echo "   <tr>\n";
    		echo "     <td>\n";
    		echo "       <img src='" . $_GET['dir'] . $entry . "' id='target' />\n";
    		echo "     </td>\n";
    		echo "     <td>\n";
    		echo "       <div style='width:200px;height:200px;overflow:hidden;'>\n";
    		echo "         <img src='" . $_GET['dir'] . $entry . "' id='preview' alt='Preview' class='jcrop-preview' />\n";
    		echo "       </div>\n";
    		echo "     </td>\n";
    		echo "   </tr>\n";
    		echo "  </table>\n";
    		echo "  <input id='filename' name='filename' type='hidden' value='" . $entry . "' /><input id='foldername' name='foldername' type='hidden' value='" . $_GET['dir'] . "' />\n";
    		echo "  <input id='x1' name='x1' type='hidden' value='' /><input id='x2' name='x2' type='hidden' value='' />\n";
    		echo "  <input id='y1' name='y1' type='hidden' value='' /><input id='y2' name='y2' type='hidden' value='' />\n";
    		echo "  <input id='w' name='w' type='hidden' value='' /><input id='h' name='h' type='hidden' value='' />\n";
            
    		echo "  <br /><br />\n";
    		echo "  <b class='gbuttonb' onclick='javascript:changeCropImage(\"" . $_GET['dir'] . "\", \"" . $entry . "\");'>Mudar Foto</b>\n";
            echo "</div>\n";
		}
        else { echo "Não existem imagens\n"; }
	}
}
