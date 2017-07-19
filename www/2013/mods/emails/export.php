<?php
if(!empty($_GET['page']) && !empty($_GET['filetype'])) {
	if(!empty($_SESSION['sqltoexport']) && !empty($_GET['all']) && $_GET['all'] == 'N') { $sql = $_SESSION['sqltoexport']; }
	else { $sql="SELECT * FROM " . $table . " "; }

	createLog(("exportando " . $table . " para '" . $_GET['filetype'] . "' - comando: '" . $sql . "' "));
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();
	$rows = $q->rowCount();

	if($rows > 0) {
		$return  = ""; $ultimaentrada = ""; $modificado = "";
		switch($_GET['filetype']) {
			case 'csv':
				$return .= "ID; Ativo; Name; Email; Cadastrado Em; Alterado\r\n";

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
				$return .= "    <td>Name</td>\n";
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

			case 'sql':
                $return .= "TRUNCATE TABLE " . $table . "; \n";
                $return .= "ALTER TABLE " . $table . " AUTO_INCREMENT=0; \n";
                while($data = $q->fetch(PDO::FETCH_ASSOC)) {
                    //id, author, title, meta_keywords, meta_description, url, rss, post, cdate, mdate, active
                    $return .= "INSERT INTO " . $table . " SET id='" . 
                                    $data['id'] . "', name='" . 
                                    addslashes(utf8_decode($data['name'])) . "', email='" . 
                                    addslashes(utf8_decode($data['email'])) . "', cdate='" . 
                                    $data['cdate'] . "', mdate='" . 
                                    $data['mdate'] . "', active='" . 
                                    $data['active'] . "';\n\n";
                }
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
			fclose($fd);
            chmod($directory . $filename, 0777);
			echo $url . $filename;
		}
		else echo 'error';
	}
}