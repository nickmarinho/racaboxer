<?php
if(!empty($_GET['id'])) {
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();

	if($q->rowCount() > 0) {
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$bcc='';

		createLog("enviou email para " . $table . " id '" . $_GET['id'] . "' ");

		if(!empty($data['emails'])) {
			$emails = unserialize($data['emails']);
			
			foreach($emails as $k => $v) {
				$sqle="SELECT name, email FROM `emails` WHERE id='" . $v . "'; ";
				$conne = Db_Instance::getInstance();
				$qe = $conne->prepare($sqle);
				$qe->execute();

				if($qe->rowCount() > 0) {
					$datae = $qe->fetch(PDO::FETCH_ASSOC);
					
					if(!empty($datae['name']) && !empty($datae['email']) && !empty($data['title'])) {
						$subject = $data['title'];
						$bcc .= $datae['name'] . "<" . $datae['email'] . ">, ";
						$mess  = "<center>Confira nossa newsletter</center> <br /><br />\n";
						$mess .= $data['content'] . "<br /><br /><br />\n";
						$mess .= "<center><a href='https://www.facebook.com/racaboxer'>E não esqueça de curtir nossa página no Facebook</a></center><br /><br /><br />\n";
						$mess .= "<center><a href='http://" . $_SERVER['SERVER_NAME'] . "/unsubscribe-news.php?id=" . $v . "'>Clique aqui para não receber mais nossa newsletter</a></center><br /><br />\n";
						$mess .= "<br /><br />\n";
						$file = file_get_contents("../inc/email-tpl.html");

						$messagea = str_replace("##HEADMESS##", "", $file);
						$messageb = str_replace("##SERVER##", $_SERVER['SERVER_NAME'], $messagea);
						$messagec = str_replace("##LOGO##", "img/logo/logo.png", $messageb);
						$messaged = str_replace("##MESS##", $mess, $messagec);
						$headers = 'From: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
						'Reply-To: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
						'To: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
						'Bcc: ' . $bcc . "\r\n" .
						'MIME-Version: 1.0' . "\r\n" .
						'Content-type: text/html; charset=utf-8' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

						if(mail('', $subject, $messaged, $headers)) {
							$sqlu="UPDATE " . $table . " SET sended='" . date("Y-m-d H:i:s") . "' WHERE id='" . $_GET['id'] . "'; ";
							$connu = Db_Instance::getInstance();
							$qu = $connu->prepare($sqlu);
							$qu->execute();
							
							echo "1";
						}
						else echo "2";
					}
				}
			}
		}
	}
	else {
		createLog(("tentou enviar email de " . $table . " sem sucesso - id do contato: '" . $_POST['id'] . "' "));
		echo "2";
	}
}
else { echo "Id da newsletter informado não foi encontrado"; }