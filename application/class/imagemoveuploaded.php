<?php
/* 
 * linhas a menos no AdminController.php, great!
 */
$formData['imagem'] = $imagem;

chmod($path . $imagem, 0777);
$comando = "/usr/bin/convert -resize 256 " . $path . $imagem . " " . $path . "__" . $imagem;
$comando .= "; sleep 2; mv "  . $path . "__" . $imagem . " " . $path . $imagem;
shell_exec($comando);
chmod($path . $imagem, 0777);

$comando = "/usr/bin/convert -resize 16 " . $path . $imagem . " " . $path . "thumb_" . $imagem;
shell_exec($comando);
chmod($path . "thumb_" . $imagem, 0777);
?>
