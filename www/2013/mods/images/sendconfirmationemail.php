<?php
if(!empty($_GET['id'])) {
	$sql="SELECT * FROM " . $table . " WHERE id='" . $_GET['id'] . "'; ";
	$conn = Db_Instance::getInstance();
	$q = $conn->prepare($sql);
	$q->execute();

	if($q->rowCount() > 0) {
		$data = $q->fetch(PDO::FETCH_ASSOC);

		createLog("enviou email para " . $table . " id '" . $_GET['id'] . "' ");

		if(!empty($data['name']) && !empty($data['email'])) {
			$bcc='';
			$bcc.="nickmarinho@gmail.com,";

			$subject = "Sua foto foi publicada em nosso site";
			$mess  = "<center><img src='http://" . $_SERVER['SERVER_NAME'] . "/" . $data['path'] . "' style='border:1px solid #000000;' /></center> <br /><br />\n";
			$mess .= "<b>Nome:</b> " . $data['name'] . "<br />\n";
			$mess .= "<b>Email:</b> " . $data['email'] . "<br />\n";
			$mess .= "<b>Cachorro:</b> " . $data['title'] . "<br />\n";
			$mess .= "<b>OBS:</b> " . $data['obs'] . "<br />\n";
			$mess .= "<br /><br />\n";
			$mess .= "<center><a href='http://" . $_SERVER['SERVER_NAME'] . "/galeria/foto_" . $_GET['id'] . ".html'>Clique aqui para ver sua foto no site, compartilhar e/ou comentar !</a></center><br /><br />\n";
			$mess .= "<center><a href='https://www.facebook.com/pages/Ra%C3%A7a-Boxer/193300814072202'>E não esqueça de curtir nossa página no Facebook</a></center><br /><br />\n";
			$mess .= "<br /><br />\n";
			$file = file_get_contents("../inc/email-tpl.html");

			$messagea = str_replace("##HEADMESS##", "Olá " . $data['name'] . ", sua foto foi aprovada para estar no nosso site !", $file);
			$messageb = str_replace("##SERVER##", $_SERVER['SERVER_NAME'], $messagea);
			$messagec = str_replace("##LOGO##", "img/logo/logo.png", $messageb);
			$messaged = str_replace("##MESS##", $mess, $messagec);
			$headers = 'From: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
			'Reply-To: Contato Raca Boxer <contato@racaboxer.com.br>' . "\r\n" .
			'To: ' . $data['name'] . ' <' . $data['email'] . '>' . "\r\n" .
			'Bcc: ' . $bcc . "\r\n" .
			'MIME-Version: 1.0' . "\r\n" .
			'Content-type: text/html; charset=utf-8' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

			if(mail('', $subject, $messaged, $headers)) { echo "1"; }
			else echo "2";

		}
	}
	else {
		createLog(("tentou enviar email de " . $table . " sem sucesso - id do contato: '" . $_POST['id'] . "' "));
		echo "2";
	}
}
