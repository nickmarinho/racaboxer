<?php
if(!empty($_GET['page']) && !empty($_GET['filetype'])) {
	if(!empty($_SESSION['sqltoexport']) && !empty($_GET['all']) && $_GET['all'] == 'N') { $sql = $_SESSION['sqltoexport']; }
	else { $sql="SELECT * FROM " . $table . " "; }

	createLog(("exportando " . $table . " para '" . $_GET['filetype'] . "' - comando: '" . $sql . "' "));
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$query = $q->execute();
	$rows = count($query);

	if($rows > 0) {
		$return  = ""; $modificado = "";
		switch($_GET['filetype']) {
			case 'csv':
				$return .= "ID;Ativo;Nome;Email;Cadastrado Em;Alterado\r\n";
				
				while($data = $q->fetch(PDO::FETCH_ASSOC)) {
					if($data['mdate'] == '' || $data['mdate'] == '0000-00-00 00:00:00') { $modificado = 'nunca'; }
					else { $modificado = $data['mdate']; }

					$active = $data['active'] == 'S' ? 'Sim' : 'Não';

					$return .= $data['id'] . ";" . $active . ";" . $data['name'] . ";" . $data['email'] . ";" . $data['cdate'] . ";" . $modificado . "\r\n";
				}

				$return = utf8_decode($return);
			break;
			
			case 'xls':
				$return .= "<html>\n";
				$return .= " <head>\n";
				$return .= "  <title>Lista de " . $_SESSION[$_GET['mod']]['MODULE_TITLE'] . "</title>\n";
				$return .= "  <meta http-equiv='Content-Type' content='text/csv; charset=" . CHARSET . "'>\n";
				$return .= " </head>\n";
				$return .= " <body>\n";
				$return .= "  <table>\n";
				$return .= "   <tr>\n";
				$return .= "    <td>ID</td>\n";
				$return .= "    <td>Ativo</td>\n";
				$return .= "    <td>Nome</td>\n";
				$return .= "    <td>Email</td>\n";
				$return .= "    <td>Cadastrado Em</td>\n";
				$return .= "    <td>Alterado</td>\n";
				$return .= "   </tr>\n";
				
				while($data = $q->fetch(PDO::FETCH_ASSOC)) {
					if($data['mdate'] == '' || $data['mdate'] == '0000-00-00 00:00:00') { $modificado = 'nunca'; }
					else { $modificado = $data['mdate']; }
					
					$active = $data['active'] == 'S' ? 'Sim' : 'Não';

					$return .= "   <tr>\n";
					$return .= "    <td>" . $data['id'] . "</td>\n";
					$return .= "    <td>" . $active . "</td>\n";
					$return .= "    <td>" . $data['name'] . "</td>\n";
					$return .= "    <td>" . $data['email'] . "</td>\n";
					$return .= "    <td>" . $data['cdate'] . "</td>\n";
					$return .= "    <td>" . $modificado . "</td>\n";
					$return .= "   </tr>\n";
				}
				$return .= "  </table>\n";
				$return .= " </body>\n";
				$return .= "</html>\n";
			break;
			
			default:
			break;
		}
		
		$directory = EXPORT_FOLDER . DS;
		$url = EXPORT_URL;
		$filename = strtolower(str_replace(' ', '-', $_GET['page'])) . "--" . date("Y") . "-" . date("m") . "-" . date("d") . "--" . date("H") . "-" . date("i") . "." . $_GET['filetype'];
		$file = $directory . $filename;
		$fd = fopen($file, "w");
		if(fwrite($fd, $return)) {
			echo $url . $filename;
			fclose($fd);
		}
		else echo 'error';
	}
}