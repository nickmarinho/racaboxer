<?php
/**
 * Controller to images
 * @copyright  2011 Luciano Marinho
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-10-04
 * @version    1.0  
 * @name ImagesController.php
 */
class ImagesController extends Zend_Controller_Action {
	private $pageName;
	private $title;
	private $keywords;
	private $description;
	private $maxthumbheight;
	private $maxthumbwidth;
	private $uploadfolder;
	private $imagesfolder;

	protected $_formimages;
	protected $_modelimages;
	
	public function getpageName() { return $this->pageName; }
	public function setpageName($value) { $this->pageName = $value; }
	public function gettitle() { return $this->title; }
	public function settitle($value) { $this->title = $value; }
	public function getkeywords() { return $this->keywords; }
	public function setkeywords($value) { $this->keywords = $value; }
	public function getdescription() { return $this->description; }
	public function setdescription($value) { $this->description = $value; }
	public function getmaxthumbheight() { return $this->maxthumbheight; }
	public function setmaxthumbheight($value = '200') { $this->maxthumbheight = $value; }
	public function getmaxthumbwidth() { return $this->maxthumbwidth; }
	public function setmaxthumbwidth($value = '200') { $this->maxthumbwidth = $value; }
	public function getuploadfolder() { return $this->uploadfolder; }
	public function setuploadfolder($value = "img/dogs/upload/") { $this->uploadfolder = $value; }
	public function getimagesfolder() { return $this->imagesfolder; }
	public function setimagesfolder($value = "img/dogs/") { $this->imagesfolder = $value; }

	public function setheaders() {
		$pageData = array();
		$pageData['pageName'] = $this->getpageName();
		$this->_helper->layout()->getView()->headTitle($this->gettitle());
		$this->_helper->layout()->getView()->headMeta()->appendName('keywords', $this->getkeywords());
		$this->_helper->layout()->getView()->headMeta()->appendName('description', $this->getdescription());
	}
	
	public function indexAction() {
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		
		$options = array();
		$email = $request->get('email');
		
		if(!empty($email)){ array_push($options, array('email' => $email)); }
		$model->setoptions($options);
	
		$p = $request->getParam('p');
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchFrontEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 30;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();

		$this->setpageName("images");
		$this->settitle("Página " . $currentPage . " da Galeria de Fotos Raça Boxer");
		$this->setkeywords("página, " . $currentPage . ",galeria, imagens, raça, boxer, fotos, figuras, pictures, cães, cachorro, cadela, dog");
		$this->setdescription("Está é a página " . $currentPage . " da galeria de fotos Raça Boxer, onde você vê as fotos mais legais de cães da raça boxer ou não");
		$this->setheaders();
	}

	public function fotoAction() {
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		$id = $request->get('id');
		
		if(!empty($id)) {
			$this->view->request = $request;
			$fotodata = $model->fetchEntry($id);
			$fotoantes = $model->fetchFotoAntes($id);
			$fotodepois = $model->fetchFotoDepois($id);
			
			if($fotodata == 'error') {
				$this->view->fotodata = 'error';
				$this->view->fotoantes = 'error';
				$this->view->fotodepois = 'error';
				$this->setpageName("foto");
				$this->settitle("Foto não encontrada na Galeria de Fotos Raça Boxer");
				$this->setkeywords("foto, não, encontrada, galeria, imagens, raça, boxer, fotos, figuras, pictures, cães, cachorro, cadela, dog");
				$this->setdescription("Está foto não foi encontrada na galeria de fotos Raça Boxer, ou foi removida da nossa base de dados");
			}
			else {
				$this->view->fotodata = $fotodata;
				$this->view->fotoantes = $fotoantes;
				$this->view->fotodepois = $fotodepois;
				$this->setpageName("foto");
				$this->settitle("Foto '" . $fotodata['id'] . " - " . $fotodata['title'] . "' da Galeria de Fotos Raça Boxer");
				$this->setkeywords("foto, " . $fotodata['id'] . ", " . $fotodata['title'] . ",galeria, imagens, raça, boxer, fotos, figuras, pictures, cães, cachorro, cadela, dog");
				$this->setdescription("Está é a foto '" . $fotodata['id'] . " - " . $fotodata['title'] . "' da galeria de fotos Raça Boxer. Comente, compartilhe, envie o link");
			}
		}
		else {
			$this->view->fotodata = 'error';
			$this->view->fotoantes = 'error';
			$this->view->fotodepois = 'error';
			$this->setpageName("foto");
			$this->settitle("Foto não encontrada na Galeria de Fotos Raça Boxer");
			$this->setkeywords("foto, não, encontrada, galeria, imagens, raça, boxer, fotos, figuras, pictures, cães, cachorro, cadela, dog");
			$this->setdescription("Está foto não foi encontrada na galeria de fotos Raça Boxer, ou foi removida da nossa base de dados");
		}
		
		$this->setheaders();
	}

	public function enviarAction() {
		$request = $this->getRequest();
		$file = $request->get('file');
		$del = $request->get('del');
		$x1 = $request->get('x1');
		$y1 = $request->get('y1');
		$x2 = $request->get('x2');
		$y2 = $request->get('y2');
		$w  = $request->get('w');
		$h  = $request->get('h');
		$nome = $request->get('nome');
		$email = $request->get('email');
		$title = $request->get('title');
		$obs = $request->get('obs');
		$ip = $_SERVER['REMOTE_ADDR'];
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		$this->setmaxthumbheight();
		$this->setmaxthumbwidth();
		$this->setuploadfolder();
		$this->setimagesfolder();
		$maxthumbheight = $this->getmaxthumbheight();
		$maxthumbwidth = $this->getmaxthumbwidth();
		$uploadfolder = $this->getuploadfolder();
		$imagesfolder = $this->getimagesfolder();

		$formData['name'] = $nome;
		$formData['email'] = $email;
		$formData['title'] = $title;
		$formData['obs'] = $obs;
		$formData['cdate'] = $cdate;
		$formData['ip'] = $ip;

		$config = array(
			//"ssl" => "tls",
			//"port" => 587,
			//"auth" => "login",
			"auth" => "plain",
			"username" => "contato@racaboxer.com.br",
			"password" => "nick1978"
		);
		
		if($del == 1){ unlink($uploadfolder . $file); }
		
		if($request->isPost()) {
			$this->view->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);

			if(!empty($_FILES)) {
				// nome do arquivo temp
				$tempFile = $_FILES['fotodocao']['tmp_name'];
				@ereg("(...)$",$_FILES['fotodocao']['name'],$regs);
				
				// nome unico para o arquivo
				$file = md5($tempFile.uniqid("IMAGE"));
				$file .= ".".strtolower($regs[1]);
				
				echo "<link type='text/css' rel='stylesheet' href='/css/themes/ui-lightness/jquery-ui-1.8.16.custom.css' />\n";
				echo "<link type='text/css' rel='stylesheet' href='/css/jquery.imgareaselect-animated.css' />\n";
				echo "<script type='text/javascript' src='/js/jquery-1.7.1.min.js'></script>\n";
				echo "<script type='text/javascript' src='/js/jquery-ui-1.8.16.custom.min.js'></script>\n";
				echo "<script type='text/javascript' src='/js/jquery.imgareaselect.js'></script>\n";
				echo "<script type='text/javascript' src='/js/jquery.ui.form.js'></script>\n";
				echo "<script type='text/javascript' src='/js/crop.js'></script>\n";
				echo "<script type='text/javascript'>\n";
				
				if(strtolower($regs[1]) == 'gif' || strtolower($regs[1]) == 'jpeg' || strtolower($regs[1]) == 'jpg' || strtolower($regs[1]) == 'png') {
					//create the upload directory with the right permissions if it doesn't exist
					if(!is_dir($uploadfolder)) { mkdir($uploadfolder, 0777, true); }
					
					$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $uploadfolder;
					$targetFile =  str_replace('//','/',$targetPath) . $file;
					move_uploaded_file($tempFile, $targetFile);
					
					list($width, $height, $imageType) = getimagesize($targetFile);
					//if($width > '716' || $height > '716')
					//{
						$this->resizeImage($targetFile, '716', '716');
					//}
					
					echo "$(\"#fotoupload_progress\", window.parent.document).html('');\n";
					echo "file = '" . $file . "';\n";
					echo "showcropfrm('" . $file . "');\n";
					echo "$(document).ready(function(){;\n";
					echo "	$('#fotocrop_frm', window.parent.document).form();\n";
					echo "});\n";
				}
				else {
					echo "$(\"#fotoupload_progress\", window.parent.document).html('');\n";
					echo "$(\"#fotoupload_frm\", window.parent.document).show();\n";
					echo "$(\"#headh1\", window.parent.document).text('Apenas suportadas as imagens: gif, jpeg, jpg ou png');\n";
					echo "alert('Apenas suportadas as imagens: gif, jpeg, jpg ou png');\n";
				}
				echo "</script>\n";
			}
			else {
				if(!empty($file)) {
					$model = $this->_getModelImages();
					$model->setoptions(array("email" => $email));
					$alreadyhas = $model->fetchEntries();

					if(count($alreadyhas) > 0) {
						unlink($uploadfolder . $file);
						echo "<script>";
						echo 'var msg = "Desculpe mas já temos no site uma foto sua e permitimos apenas uma por pessoa \n\n";';
						echo 'alert(msg + "Por favor, entre em contato para mudar-mos a existente no site");';
						echo "location='/galeria-enviar.html'";
						echo "</script>";
					}
					else {
						$resfolder = $this->createThumbnail($file, $x1, $y1, $x2, $y2, $w, $h);
						$formData['path'] = $resfolder . $file;
						unlink($uploadfolder . $file);
						$id = $model->save($formData);
						
						$tr = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
						Zend_Mail::setDefaultTransport($tr);
						$mail = new Zend_Mail();

						/* ATTACH FILE TO EMAIL */
						$attachfile = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagesfolder . $resfolder . $file;
						list($widthb, $heightb, $imageTypeb) = getimagesize($attachfile);
						$imgBinary = file_get_contents($attachfile);
						$at = $mail->createAttachment($imgBinary);
						$at->type        = $imageTypeb;
						$at->disposition = Zend_Mime::DISPOSITION_INLINE;
						$at->encoding    = Zend_Mime::ENCODING_BASE64;
						$at->id = 'cid_' . md5_file($attachfile);
						$at->filename    = $file;
						/* END ATTACH FILE TO EMAIL */
						
						$messfile = file_get_contents(APP_PATH . "/templates/galeria-enviar.phtml");
						$messbody = str_replace("", "", $messfile);
						$messbody = str_replace("", "", $messbody);
						
						$mess = "<b>Name:</b> " . $nome . "<br /><b>Email:</b> " . $email . "<br /><b>Nome do Cão:</b> " . $title . "<br />
							<b>Ip:</b> " . $ip . "<br /><b>Obs:</b> " . $obs . "<br /><br />
							<center>
								<img border=\"0\" src=\"cid:" . $at->id . "\" alt=\"" . $title . "\" style=\"border: 1px solid;box-shadow: 5px 5px 5px;\" /><br /><br />
								<a href=\"http://" . $_SERVER['SERVER_NAME'] . "/liberar-foto.html?id=" . $id . "\">Clique aqui para liberar a foto</a><br /><br />
								<a href=\"http://" . $_SERVER['SERVER_NAME'] . "/admin/\">ou vá para a tela de admin do site</a>
							</center><br /><br />";

						$emailtpl = file_get_contents(APP_PATH . "/class/email-tpl.html");
						$body = str_replace("##HEADMESS##", "Nova Imagem", $emailtpl);
						$body = str_replace("##MESS##", $mess, $body);
						$mail->setBodyHtml(utf8_decode($body), 'iso-8859-1')
							->addTo('nickmarinho@gmail.com', 'Luciano Marinho')
							->setReplyTo($email, utf8_decode($nome))
							->setFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
							->setSubject('Uma nova imagem no site de: "' . utf8_decode($nome) . '" <' . $email . '>')
							;
						
						$mail->send();

						echo "<script>";
						echo "alert('Foto enviada com sucesso, obrigado !'); ";
						echo "location='/galeria-enviar.html';";
						echo "</script>";
					}
				}
				else{ echo "nada a exibir"; }
			}
		}
		else {
			$this->setpageName("enviar");
			$this->settitle("Envie a Foto do seu cão");
			$this->setkeywords("envie, foto, cão, raça, boxer, send, picture, your, dog, puppy, pet");
			$this->setdescription("Envie a foto do seu cão para aparecer no nosso site, pode ser boxer ou não");
			$this->setheaders();
		}
	}

	protected function resizeImage($targetFile, $thumbwidth, $thumbheight) {
		list($width, $height, $imageType) = getimagesize($targetFile);
		if($width > $height) {
			$newwidth = $thumbwidth;
			$divisor = $width / $thumbwidth;
			$newheight = floor( $height / $divisor);
		}
		else {
			$newheight = $thumbheight;
			$divisor = $height / $thumbheight;
			$newwidth = floor( $width / $divisor );
		}
		
		$imageType = image_type_to_mime_type($imageType);
		$croppedimage = imagecreatetruecolor( $newwidth, $newheight );

		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($targetFile);
			break;
			case "image/pjpeg":
			case "image/jpeg":
				$source=imagecreatefromjpeg($targetFile);
			break;
			case "image/jpg":
				$source=imagecreatefromjpeg($targetFile);
			break;
			case "image/png":
				$source=imagecreatefrompng($targetFile);
			break;
			case "image/x-png":
				$source=imagecreatefrompng($targetFile);
			break;
			default:
				return false;
			break;
		}

		imagecopyresampled($croppedimage, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		switch($imageType) {
			case "image/gif":
				imagegif($croppedimage, $targetFile);
			break;
			case "image/pjpeg":
			case "image/jpeg":
				imagejpeg($croppedimage, $targetFile, 100);
			break;
			case "image/jpg":
				imagejpeg($croppedimage, $targetFile, 100);
			break;
			case "image/png":
				imagepng($croppedimage, $targetFile);
			break;
			case "image/x-png":
				imagepng($croppedimage, $targetFile);
			break;
			default:
				return false;
			break;
		}
	}
	
	protected function createThumbnail($file, $x1, $y1, $x2, $y2, $width, $height) {
		$this->setmaxthumbheight();
		$this->setmaxthumbwidth();
		$this->setuploadfolder();
		$this->setimagesfolder();
		$maxthumbheight = $this->getmaxthumbheight();
		$maxthumbwidth = $this->getmaxthumbwidth();
		$uploadfolder = $this->getuploadfolder();
		$imagesfolder = $this->getimagesfolder();
		$sourcepath = $uploadfolder . $file;
		$resfolder = Common::createPath($imagesfolder, $file);
		$thumbpath = $imagesfolder . $resfolder . $file;
		
		if(file_exists($sourcepath) && $width && $height) {
			list($originalwidth, $originalheight, $imageType) = getimagesize($sourcepath);
			$imageType = image_type_to_mime_type($imageType);
			$croppedimage = imagecreatetruecolor($width, $height);

			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($sourcepath);
				break;
				case "image/pjpeg":
				case "image/jpeg":
					$source=imagecreatefromjpeg($sourcepath);
				break;
				case "image/jpg":
					$source=imagecreatefromjpeg($sourcepath);
				break;
				case "image/png":
					$source=imagecreatefrompng($sourcepath);
				break;
				case "image/x-png":
					$source=imagecreatefrompng($sourcepath);
				break;
				default:
					return false;
				break;
			}

			imagecopy($croppedimage, $source, 0, 0, $x1, $y1, $originalwidth, $originalheight);
			
			switch($imageType) {
				case "image/gif":
					imagegif($croppedimage, $thumbpath);
				break;
				case "image/pjpeg":
				case "image/jpeg":
					imagejpeg($croppedimage, $thumbpath, 100);
				break;
				case "image/jpg":
					imagejpeg($croppedimage, $thumbpath, 100);
				break;
				case "image/png":
					imagepng($croppedimage, $thumbpath);
				break;
				case "image/x-png":
					imagepng($croppedimage, $thumbpath);
				break;
				default:
					return false;
				break;
			}
			
			$this->resizeImage($thumbpath, $maxthumbwidth, $maxthumbheight);
			
			return $resfolder;
		}
		else return false;
	}
	
	// forms
	protected function _getFormImages() {
		if(null === $this->_formimages) {
			require_once APP_PATH . '/forms/Images.php';
			$this->_formimages = new Form_Images();
		}
		
		return $this->_formimages;
	}
	
	// models
	protected function _getModelImages() {
		if(null === $this->_modelimages) {
			require_once APP_PATH . '/models/Images.php';
			$this->_modelimages = new Model_Images();
		}
		
		return $this->_modelimages;
	}
}
?>
