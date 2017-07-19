<?php
/**
 * Controller to admin
 * @copyright  2011 Luciano Marinho
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-03-06
 * @version    1.0  
 * @name AdminController.php
 */
include_once APP_PATH . "/class/common.class.php";
include_once APP_PATH . "/../www/js/ckeditor/ckeditor.php";

class AdminController extends Zend_Controller_Action {
	/* PROTECTED FORMS AND MODELS */
	protected $_formblog;
	protected $_modelblog;
	protected $_formimages;
	protected $_modelimages;
	protected $_modelnewsletter;
	protected $_formpages;
	protected $_modelpages;
	protected $_formsendmail;
	protected $_formusers;
	protected $_modelusers;
	private $title;
	private $maxthumbheight;
	private $maxthumbwidth;
	private $uploadfolder;
	private $imagesfolder;
	
	/* INIT */
	public function init() {
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity ()) {
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				echo "<script>document.location = '" . SITE_PATH . "/admin';</script>";
			}
			else{ $this->_forward('login'); }
		}
		else {
			$authNamespace = new Zend_Session_Namespace('Zend_Auth');
			$authNamespace->setExpirationSeconds(86400);
			$timeLeftTillSessionExpires = $_SESSION['__ZF']['Zend_Auth']['ENT'] - time();
			$userdata = $auth->getIdentity();
			$this->user_role = $userdata->role;
			$this->view->userdata = $auth->getIdentity();
			$this->view->sessionexpiresin = $timeLeftTillSessionExpires;
		}
	}
	
	/* LIST ACTIONS */
	public function listblogAction() {
		$this->view->layout()->disableLayout();
		$pagename = "blog";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Blog");
		$this->setheaders();

		//$registry = Zend_Registry::getInstance();
		//$acl = $registry->get('acl');
		//if(!$acl->isAllowed($this->user_role, 'sistema')){ $this->view->denymessage = "Desculpe, mas você não tem permissão para ver essa página!"; }
		
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$title = $request->get('title');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = '';
		$model = $this->_getModelBlog();
		$options = array();
		if(!empty($title)) {
			$options['title'] = $title;
			$_SESSION['title'] = $title;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->pagename = $pagename;
		$this->view->title = $title;
	}
	
	public function listemailsAction() {
		$this->view->layout()->disableLayout();
		$pagename = "emails";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Emails");
		$this->setheaders();
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$name = $request->get('name');
		$email = $request->get('email');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = '';
		$model = $this->_getModelEmails();
		$options = array();
		if(!empty($email)) {
			$options['email'] = $email;
			$_SESSION['email'] = $email;
		}
		if(!empty($name)) {
			$options['name'] = $name;
			$_SESSION['name'] = $name;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->pagename = $pagename;
		$this->view->email = $email;
		$this->view->name = $name;
	}
	
	public function listimagesAction() {
		$this->view->layout()->disableLayout();
		$pagename = "images";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Images");
		$this->setheaders();
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$name = $request->get('name');
		$email = $request->get('email');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = ''; 
		$model = $this->_getModelImages();
		$options = array();
		if(!empty($name)) {
			$options['name'] = $name;
			$_SESSION['name'] = $name;
		}
		if(!empty($email)) {
			$options['email'] = $email;
			$_SESSION['email'] = $email;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->pagename = $pagename;
		$this->view->name = $name;
		$this->view->email = $email;
	}
	
	public function listnewsletterAction() {
		$this->view->layout()->disableLayout();
		$pagename = "newsletter";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Newsletter");
		$this->setheaders();
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$title = $request->get('title');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = '';
		$model = $this->_getModelNewsletter();
		$options = array();
		if(!empty($title)) {
			$options['title'] = $title;
			$_SESSION['title'] = $title;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->pagename = $pagename;
		$this->view->title = $title;
	}
	
	public function listpagesAction() {
		$this->view->layout()->disableLayout();
		$pagename = "pages";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Pages");
		$this->setheaders();
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$title = $request->get('title');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = ''; 
		$model = $this->_getModelPages();
		$options = array();
		if(!empty($title)) {
			$options['title'] = $title;
			$_SESSION['title'] = $title;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->pagename = $pagename;
		$this->view->title = $title;
	}
	
	public function listusersAction() {
		$this->view->layout()->disableLayout();
		$pagename = "users";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Users");
		$this->setheaders();
		$request = $this->getRequest();
		$p = $request->get('p');
		$c = $request->get('c');
		$user = $request->get('user');
		$name = $request->get('name');
		$email = $request->get('email');
		$_SESSION['title'] = '';
		$_SESSION['name'] = '';
		$_SESSION['user'] = '';
		$_SESSION['email'] = ''; 
		$model = $this->_getModelUsers();
		$options = array();
		if(!empty($name)) {
			$options['name'] = $name;
			$_SESSION['name'] = $name;
		}
		if(!empty($user)) {
			$options['user'] = $user;
			$_SESSION['user'] = $user;
		}
		if(!empty($email)) {
			$options['email'] = $email;
			$_SESSION['email'] = $email;
		}
		$model->setoptions($options);
		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchEntries()));
		$currentPage = isset($p) ? (int) htmlentities($p) : 1;
		$pager->setCurrentPageNumber($currentPage);
		$itemsPerPage = isset($c) ? (int) htmlentities($c) : 20;
		$pager->setItemCountPerPage($itemsPerPage);
		$this->view->paginator=$pager;
		$this->view->totalItemCount = $pager->totalItemCount;
		$this->view->request = $request;
		$this->view->p = $p;
		$this->view->datalist = $pager->getCurrentItems();
		$this->view->user = $user;
		$this->view->name = $name;
		$this->view->email = $email;
	}
	
	/* SAVE ACTIONS */
	public function saveblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$url = $request->get('url');
		$title = utf8_encode($request->get('title'));
		$meta_keywords = utf8_encode($request->get('meta_keywords'));
		$meta_description = utf8_encode(addslashes($request->get('meta_description')));
		$rss = $request->get('rss');
		$post = $request->get('post');
		$author = addslashes($request->get('author'));
		$id = $request->get('id');
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		$formData['url'] = $url;
		$formData['title'] = $title;
		$formData['meta_keywords'] = $meta_keywords;
		$formData['meta_description'] = $meta_description;
		$formData['rss'] = $rss;
		$formData['post'] = $post;
		$formData['author'] = $author;
		$formData['mdate'] = $mdate;
		$form = $this->_getFormBlog();
		$model = $this->_getModelBlog();
		
		if($form->isValid($request->getPost())) {
			if(!empty($id)) {
				$formData['id'] = $id;
				$model->update($formData, $id);
				echo Common::successMess();
			}
			else {
				$formData['cdate'] = $cdate;
				$id = $model->save($formData);

				//require_once APP_PATH . '/../www/fb/fb_access.php';
				// add a status message
				//$status = $facebook->api('/me/feed', 'POST', array('message' => $post));
				//http://www.facebook.com/pages/Ra%C3%A7a-Boxer/193300814072202
				//$status = $facebook->api('/193300814072202/feed', 'POST', array('message' => $post));
				//var_dump($status);

				if($id) {
					echo Common::successMess();
					echo Common::modalboxClose('blog');
				}
			}
		}
		else {
			$form->setAction('/admin/saveblog');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}
	
	public function saveemailsAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$name = $request->get('name');
		$email = $request->get('email');
		$id = $request->get('id');
		$cdate = Common::returnData();
		$mdate = Common::returnData();
		$formData['name'] = $name;
		$formData['email'] = $email;
		$formData['mdate'] = $mdate;
		$model = $this->_getModelEmails();
		
		if(!empty($id)) {
			$formData['id'] = $id;
			$model->update($formData, $id);
			echo $id;
		}
		else {
			$formData['cdate'] = $cdate;
			$id = $model->save($formData);
			
			if($id) echo $id;
			else echo 'error';
		}
	}
	
	public function saveimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$path = $request->get('path');
		$title = addslashes($request->get('title'));
		$obs = $request->get('obs');
		$name = $request->get('name');
		$email = $request->get('email');
		$id = $request->get('id');
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		
		if(!empty($path)) {
			$resfolder = $this->createThumbnail($file, $x1, $y1, $x2, $y2, $w, $h);
			$formData['path'] = $resfolder . $file;
		}
		
		$formData['title'] = $title;
		$formData['obs'] = $obs;
		$formData['name'] = $name;
		$formData['email'] = $email;
		$formData['mdate'] = $mdate;
		$form = $this->_getFormImages();
		$model = $this->_getModelImages();
		
		if($form->isValid($request->getPost())) {
			if(!empty($id)) {
				$formData['id'] = $id;
				$model->update($formData, $id);
				echo Common::successMess();
				echo Common::modalboxClose('images');
			}
			else {
				$formData['cdate'] = $cdate;
				$id = $model->save($formData);
				
				if($id) {
					echo Common::successMess();
					echo Common::modalboxClose('images');
				}
			}
		}
		else {
			$form->setAction('/admin/saveimages');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}
	
	public function savenewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$title = $request->get('title');
		$content = $request->get('content');
		$emails = $request->get('emails');
		$id = $request->get('id');
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		$formData['title'] = $title;
		$formData['content'] = $content;
		$formData['mdate'] = $mdate;
		$form = $this->_getFormNewsletter();
		$model = $this->_getModelNewsletter();
		$modelemails = $this->_getModelEmails();
		
		if(!empty($title) && !empty($content)) {
			if(!empty($id)) {
				$curEmails = array();
				
				if($emails == "todos") {
					$todosemails = $modelemails->fetchEntries();
					foreach($todosemails as $todoemail){ foreach($todoemail as $k => $v){ if($k == 'id'){ if(!in_array($v, $curEmails)){ array_push($curEmails,$v); } } } }
				}
				else {
					for($i=0; $i < count($emails); $i++){ if(!in_array($emails[$i], $curEmails)){ array_push($curEmails, $emails[$i]); } }
				}
				
				$curEmails=serialize($curEmails);
				$formData['emails'] = $curEmails;
				$model->update($formData, $id);
				
				echo Common::successMess();
				echo Common::modalboxClose('newsletter');
			}
			else {
				$formData['cdate'] = $cdate;
				$curEmails = array();
				
				if($emails == "todos") {
					$todosemails = $modelemails->fetchEntries();
					
					foreach($todosemails as $todoemail) {
						foreach($todoemail as $k => $v){ if($k == 'id'){ if(!in_array($v, $curEmails)){ array_push($curEmails,$v); } } }
					}
				}
				else { for($i=0; $i<count($emails); $i++){ array_push($curEmails,$emails[$i]); } }
				
				$curEmails=serialize($curEmails);
				$formData['emails'] = $curEmails;
				$id = $model->save($formData);
				
				echo Common::successMess();
				echo Common::modalboxClose('newsletter');
			}
		}
		else {
			$form->setAction('/admin/savenewsletter');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}
		
	public function saveimagecropAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->setmaxthumbheight();
		$this->setmaxthumbwidth();
		$this->setimagesfolder();
		$request = $this->getRequest();
		$id = $request->get('id');
		$x1 = $request->get('x1');
		$y1 = $request->get('y1');
		$x2 = $request->get('x2');
		$y2 = $request->get('y2');
		$width  = $request->get('w');
		$height  = $request->get('h');
		$mdate = Common::returnData();
		$maxthumbheight = $this->getmaxthumbheight();
		$maxthumbwidth = $this->getmaxthumbwidth();
		$imagesfolder = $this->getimagesfolder();
		$model = $this->_getModelImages();
		
		if(!empty($id)) {
			$row = $model->fetchEntry($id);		
			
			if(file_exists(str_replace("//", "/", $imagesfolder . $row['path']))) {
				if($width && $height) {
					// o novo nome unico para o arquivo
					$tempFile = array_reverse(explode("/",$row['path']));
					@ereg("(...)$",$tempFile[0],$regs);
					$file = md5($tempFile[0].uniqid("IMAGE"));
					$file .= ".".strtolower($regs[1]);

					// cria a nova pasta onde o arquivo renomeado vai passar a morar
					$resfolder = Common::createPath($imagesfolder, $file);
					// renomeia o arquivo
					rename($imagesfolder . $row['path'], $imagesfolder . $resfolder . $file);
					// caminho pro arquivo existente
					$sourcepath = $imagesfolder . $resfolder . $file;
					$thumbpath = $imagesfolder . $resfolder . $file;

					// aqui vamos de fato aplicar o novo tamanho para o arquivo
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
					
					// cria o arquivo
					$this->resizeImage($thumbpath, $maxthumbwidth, $maxthumbheight);
					
					// joga a informacao na array
					$formData['path'] = $resfolder . $file;
					$formData['mdate'] = $mdate;
					
					// atualiza a informacao da imagem e a data da alteracao
					$model->update($formData, $id);
					
					echo "<script>alert(\"Atualizado !!!\"); window.parent.imageeditcropcancel('" . $id . "');</script>";
				}
				else { echo "<h1 style=\"text-align:center;\">Você não selecionou a área na imagem</h1><script>setTimeout(\"history.back();\", 3000);</script>"; }
			}
			else { echo "<h1 style=\"text-align:center;\">Arquivo não encontrado</h1><script>setTimeout(\"history.back();\", 3000);</script>"; }
		}
		else { echo "<h1 style=\"text-align:center;\">Este ID não existe</h1><script>setTimeout(\"history.back();\", 3000);</script>"; }
	}

	public function savepagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$url = $request->get('url');
		$title = addslashes($request->get('title'));
		$meta_keywords = $request->get('meta_keywords');
		$meta_description = addslashes($request->get('meta_description'));
		$content = $request->get('content');
		$id = $request->get('id');
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		$formData['url'] = $url;
		$formData['title'] = $title;
		$formData['meta_keywords'] = $meta_keywords;
		$formData['meta_description'] = $meta_description;
		$formData['content'] = $content;
		$formData['mdate'] = $mdate;
		$form = $this->_getFormPages();
		$model = $this->_getModelPages();

		if($form->isValid($request->getPost())) {
			if(!empty($id)) {
				$formData['id'] = $id;
				$model->update($formData, $id);
				echo Common::successMess();
				echo Common::modalboxClose('pages');
			}
			else {
				$formData['cdate'] = $cdate;
				$id = $model->save($formData);
				
				if($id) {
					echo Common::successMess();
					echo Common::modalboxClose('pages');
				}
			}
		}
		else {
			$form->setAction('/admin/savepages');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}

	public function saveusersAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$name = utf8_decode(addslashes($request->get('name')));
		$email = $request->get('email');
		$user = $request->get('user');
		$passwd = $request->get('passwd');
		$permissions = $request->get('permissions');
		$id = $request->get('id');
		$mdate = Common::returnData();
		$cdate = Common::returnData();
		$formData['name'] = $name;
		$formData['email'] = $email;
		$formData['user'] = $user;
		if(!empty($passwd)) $formData['passwd'] = md5($passwd);
		$formData['permissions'] = $permissions;
		$formData['mdate'] = $mdate;
		$form = $this->_getFormUsers();
		$model = $this->_getModelUsers();
		
		if($form->isValid($request->getPost())) {
			if(!empty($id)) {
				$formData['id'] = $id;
				$model->update($formData, $id);
				echo Common::successMess();
				echo Common::modalboxClose('users');
			}
			else {
				$formData['cdate'] = $cdate;
				$id = $model->save($formData);
				
				if($id) {
					echo Common::successMess();
					echo Common::modalboxClose('users');
				}
			}
		}
		else {
			$form->setAction('/admin/saveusers');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}
	
	/* ADD ACTIONS */
	public function addblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$form = $this->_getFormBlog();
		$form->setAction('/admin/saveblog');
		
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('post');
		echo Common::initializeCkeditorInstance('rss');
		//echo Common::addJqueryFunction('form', 'form');
		echo Common::imageTemplate('post');
		echo Common::closeJsScript();
	}

	public function addimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$form = $this->_getFormImages();
		$form->setAction('/admin/saveimages');
		
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('obs');
		echo Common::closeJsScript();
	}

	public function addnewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$form = $this->_getFormNewsletter();
		$form->setAction('/admin/savenewsletter');
		
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::addJsScriptToHead('addnewsletter');
		echo Common::addJsScriptToHead('selecionaremails');
		echo Common::closeJsScript();
	}
	
	public function selecionaremailsAction() {
		$this->view->layout()->disableLayout();
		$model = $this->_getModelEmails();
		$request = $this->getRequest();
		$id = $request->get('id');
		$active = $request->get('active'); 
		$options = array();
		if(!empty($active)){ $options['active'] = $active; }
		else{ $options['active'] = '1'; }
		$model->setoptions($options);
		
		if(!empty($id)) {
			$modelnewsletter = $this->_getModelNewsletter();
			$row = $modelnewsletter->fetchEntry($id);
			$row = unserialize($row['emails']);
			
			if(count($row) > 0) {
				$notin = "";
				$totalrow = count($row);
				
				for($i=0; $i < $totalrow; $i++) {
					if(($totalrow-1) == $i){ $notin .= $row[$i]; }
					else{ $notin .= $row[$i] . ","; }
				}
				
				$model->setnotin($notin);
			}
		}
		
		$this->view->datalist = $model->fetchEntries();
	}

	public function addpagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$form = $this->_getFormPages();
		$form->setAction('/admin/savepages');
		
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('content');
		echo Common::imageTemplate('content');
		echo Common::closeJsScript();
	}

	public function addusersAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$form = $this->_getFormUsers();
		$form->setAction('/admin/saveusers');
		echo $form;
	}

	/* EDIT ACTIONS */
	public function editblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$form = $this->_getFormBlog(array('id' => $id));
		$form->setAction('/admin/saveblog');
		
		$formData = array();
		foreach($row as $key => $value) {
			$formData[$key] = stripslashes($value);
		}
		
		$form->populate($formData);
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('post');
		echo Common::initializeCkeditorInstance('rss');
		echo Common::addJqueryFunction('form', 'form');
		echo Common::imageTemplate('post');
		echo Common::closeJsScript();
	}

	public function editemailsAction() {
		$this->view->layout()->disableLayout();
		$request = $this->getRequest();
		$model = $this->_getModelEmails();
		$id = $request->get('id');
		$div = $request->get('div');
		$row = $model->fetchEntry($id);
		$this->view->row = $row;
		$this->view->div = $div;
	}
	
	public function editimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$form = $this->_getFormImages(array('id' => $id));
		$form->setAction('/admin/saveimages');
		
		$formData = array();
		foreach($row as $key => $value) {
			$formData[$key] = stripslashes($value);
		}
		
		$form->populate($formData);
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('obs');
		echo Common::imageTemplate('obs');
		echo Common::closeJsScript();
	}
	
	public function editimagecropAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->setmaxthumbheight();
		$this->setmaxthumbwidth();
		$this->setuploadfolder();
		$this->setimagesfolder();
		$maxthumbheight = $this->getmaxthumbheight();
		$maxthumbwidth = $this->getmaxthumbwidth();
		$uploadfolder = $this->getuploadfolder();
		$imagesfolder = $this->getimagesfolder();
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		$id = $request->get('id');
		
		if(!empty($id)) {
			$row = $model->fetchEntry($id);
			
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagesfolder;
			$targetFile =  str_replace('//','/',$targetPath) . $row['path'];
			
			echo "<link href='/css/themes/ui-lightness/jquery-ui-1.8.16.custom.css' rel='stylesheet' type='text/css' />\n";
			echo "<script type='text/javascript' src='/js/jquery-1.7.1.min.js'></script>\n";
			echo "<script type='text/javascript' src='/js/jquery-ui-1.8.16.custom.min.js'></script>\n";
			echo "<script type='text/javascript' src='/js/jquery.blockUI.js'></script>\n";
			echo "<script type='text/javascript' src='/js/jquery.qtip-1.0.0-rc3.js'></script>\n";
			echo "<script type='text/javascript' src='/js/jquery.ui.form.js'></script>\n";
			echo "<script type='text/javascript' src='/js/themeswitchertool.js'></script>\n";
			
			if(is_file($targetFile)) {
				list($width, $height, $imageType) = getimagesize($targetFile);
				
				if($width > '714' || $height > '714') {
					$this->resizeImage($targetFile, '714', '714');
				}
				
				echo Common::imageCropTpl($row['id'], $row['path']);
			}
			else {
				echo '     <center><a href="javascript:void(0);" id="imageeditcropcancel" class="button" onclick="window.parent.imageeditcropcancel(\'' . $row['id'] . '\');">Arquivo Não Encontrado</a></center>';
				echo '     <script>$("#imageeditcropcancel").button();</script>';
			}
		}
		else { echo "<h1>Por favor escolha corretamente a foto a editar</h1>"; }
	}

	public function editnewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelNewsletter();
		$modelemails = $this->_getModelEmails();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$emailscadastrados = unserialize($row['emails']);
		
		$form = $this->_getFormNewsletter(array('id' => $id));
		$form->setAction('/admin/savenewsletter');
		
		$formData = array();
		foreach($row as $key => $value) {
			$formData[$key] = stripslashes($value);
		}
		
		$form->populate($formData);
		echo Common::styleTableLogin();
		echo $form;
		
		echo Common::openJsScript();
		echo Common::addJsScriptToHead('selecionaremails');
		echo Common::iframeEmailSelecionar($id);
		echo Common::subirTodos();
		$var = "emails";
		echo Common::inicializaVar($var);
		
		if($emailscadastrados <> '') {
			$formData['emails'] = 'selecionar';
			
			$c=1;
			for($i=0; $i < count($emailscadastrados); $i++) {
				$ecad = $modelemails->fetchEntry($emailscadastrados[$i]);
				echo Common::addEmailCadastradoDiv($var, $ecad['id'], $ecad['email']);
				$c++;
			}
			
			echo Common::addJqueryValue('selecionaremails_ifrm', 'after', 'emailsel');
		}
		
		echo Common::initializeCkeditorInstance('content');
		echo Common::inputRadioEmailsChange();
		echo Common::closeJsScript();
	}
	
	public function editpagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelPages();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$form = $this->_getFormPages(array('id' => $id));
		$form->setAction('/admin/savepages');
		
		$formData = array();
		foreach($row as $key => $value) {
			if($key == 'content') $formData[$key] = utf8_encode(stripslashes($value));
			else $formData[$key] = stripslashes($value);
		}
		
		$form->populate($formData);
		echo Common::styleTableLogin();
		echo $form;
		echo Common::openJsScript();
		echo Common::initializeCkeditorInstance('content');
		echo Common::imageTemplate('content');
		echo Common::closeJsScript();
	}

	public function editusersAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelUsers();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$form = $this->_getFormUsers(array('id' => $id));
		$form->setAction('/admin/saveusers');
		
		$formData = array();
		foreach($row as $key => $value) {
			$formData[$key] = stripslashes($value);
		}
		
		$form->populate($formData);
		echo $form;
	}

	/* REMOVE ACTIONS */
	public function removeblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id')) {
			$model = $this->_getModelBlog();
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}

	public function removeemailsAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id')) {
			$model = $this->_getModelEmails();
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}

	public function removeimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->setimagesfolder();
		$imagesfolder = $this->getimagesfolder();
		$request = $this->getRequest();
		$id = $request->get('id');
		if($id) {
			$model = $this->_getModelImages();
			$row = $model->fetchEntry($id);
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagesfolder;
			$filename =  str_replace('//','/',$targetPath) . $row['path'];
			
			if(is_file($filename)) unlink($filename);
			
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}
	
	public function removenewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id')) {
			$model = $this->_getModelNewsletter();
			if($model->delete($request->get('id'))){ echo '1'; }
		}
		else echo 'error';
	}
	
	public function removepagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id')) {
			$model = $this->_getModelPages();
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}
	
	public function removeusersAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id')) {
			$model = $this->_getModelUsers();
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}	
	
	/* VIEW ACTIONS */
	public function viewblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		if(count($row) > 0) echo (stripslashes($row['post']));
		else echo "Nada para mostrar";
	}
	
	public function viewimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		if(count($row) > 0){ echo Common::viewImageTpl($row['id'], $row['path'], $row['obs']); }
		else echo "Nada para mostrar";
	}
	
	public function viewnewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelNewsletter();
		$modelemails = $this->_getModelEmails();
		$totalemails = $modelemails->fetchEntries();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$emails = unserialize($row['emails']);
		if(count($row) > 0) {
			echo "<link href='/css/admin.css' type='text/css' />\n";
			echo "<center>\n";
			echo "<h1>" . $row['id'] . " - " . $row['title'] . "</h1>\n";
			echo "<div style='display:block;'>" . stripslashes($row['content']) . "</div>\n";
			echo "<hr />\n";
			echo "<table class='sample' border='0' cellpadding='0' cellspacing='0' style='border:0;margin:0;padding:0;width:800px !important;'>";
			echo " <thead>";
			echo "  <tr>";
			echo "   <th colspan='2'>Emails</th>";
			echo "  </tr>";
			
			if($emails <> '') {
				echo "  <tr>";
				echo "   <th>Nome</th>";
				echo "   <th>Email</th>";
				echo "  </tr>";
				echo " </thead>";
				echo " <tbody>";
				
				if(count($totalemails) == count($emails)) {
						echo "  <tr>";
						echo "   <td colspan='2' style='text-align:center;'>Todos</td>";
						echo "  </tr>";
				}
				else {
					for($i=0; $i < count($emails); $i++) {
						$emaildata = $modelemails->fetchEntry($emails[$i]);
						
						echo "  <tr>";
						echo "   <td>" . $emaildata['name'] . "</td>";
						echo "   <td>" . $emaildata['email'] . "</td>";
						echo "  </tr>";
					}
				}
			}
			else {
				echo " </thead>";
				echo " <tbody>";
				echo "  <tr>";
				echo "   <td style='text-align:center;'>Nenhum email cadastrado nessa news ainda</td>";
				echo "  </tr>";
			}
			
			echo " </tbody>";
			echo "</table>";
			echo "<hr />";
			echo "<br />";
			echo "<br />\n";
			echo "</center>\n";
		}
		else echo "Nada para mostrar";
	}
	
	public function viewpagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelPages();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		if(count($row) > 0) echo utf8_encode(stripslashes($row['content']));
		else echo "Nada para mostrar";
	}
	
	/* SEND MAIL */
	public function sendmailAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$name = $request->get('name');
		$email = $request->get('email');
		$subject = $request->get('subject');
		$message = $request->get('message');
		$options = array();
		
		if(!empty($name)){ $options['name'] = $name; }
		if(!empty($email)){ $options['email'] = $email; }
		if(!empty($subject)){ $options['subject'] = $subject; }
		if(!empty($message)){ $options['message'] = $message; }
		
		$form = $this->_getFormSendMail($options);
		
		if(!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
			$config = array(
				//"ssl" => "tls",
				//"port" => 587,
				//"auth" => "login",
				"auth" => "plain",
				"username" => "contato@racaboxer.com.br",
				"password" => "nick1978"
			);
			
			$emailtpl = file_get_contents(APP_PATH . "/class/email-tpl.html");
			$body = str_replace("##HEADMESS##", $subject, $emailtpl);
			$body = str_replace("##MESS##", $message, $body);
			
			$tr = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
			Zend_Mail::setDefaultTransport($tr);
			$mail = new Zend_Mail();
			$mail->setBodyHtml(stripslashes($body), 'iso-8859-1')
				->addTo($email, $name)
				->addBcc('nickmarinho@gmail.com')
				->setReplyTo('contato@racaboxer.com.br', utf8_decode("Contato - Raça Boxer"))
				->setFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
				->setSubject(utf8_decode($subject))
				;
			
			if($mail->send()) echo "<center><span class='success'>Mensagem enviada !</span></center>";
			else { echo "<center><span class='error'>Não pude enviar sua mensagem !</span></center>"; }
		}
		else {
			$form->setAction('/admin/sendmail');
			$form->populate($options);
			echo Common::styleTableLogin();
			echo $form;
			$body = utf8_decode("<div style='background:#EAA946;border-radius:4px 4px 4px 4px;box-shadow: 0.4em 0.4em 0.4em rgba(0, 0, 0, 0.5);font-size:1.0em;font-weight:bold;height:140px;margin:10px auto 0px;padding:1em 2em;text-align:center;width:400px;'><p><a href='http://www.racaboxer.com.br/' target='_blank'><img alt='Raca Boxer Logo' src='http://www.racaboxer.com.br/img/logo/banner120x80.gif' style='border: 0px solid; float: left; margin: 5px; width: 120px; height: 80px;' /></a></p><p>Participe tamb&eacute;m da nossa p&aacute;gina no Facebook, <a href='https://www.facebook.com/pages/Ra%C3%A7a-Boxer/193300814072202' target='_blank'>basta clicar aqui !</a></p><p>Indique amigos, sendo c&atilde;o boxer ou n&atilde;o a enviar fotos para nosso site.</p><p>Mordidinhas e Muito Obrigado !</p></div>");
			echo Common::openJsScript();
			
			if(!empty($name)){ echo Common::addJqueryValue('name', 'val', $name); }
			if(!empty($email)){ echo Common::addJqueryValue('email', 'val', $email); }
			if(!empty($subject)){ echo Common::addJqueryValue('subject', 'val', $subject); }
			if(!empty($body)){ echo Common::addJqueryValue('message', 'val', $body); }

			echo Common::initializeCkeditorInstance('message');
			echo Common::closeJsScript();
		}
	}
	
	public function sendnewsletterAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelNewsletter();
		$modelemails = $this->_getModelEmails();
		$id = $request->get('id');
		
		if(!empty($id)) {
			$row = $model->fetchEntry($id);
			$emails = unserialize($row['emails']);
			
			if(count($emails) > 0) {
				$config = array("auth" => "plain","username" => "contato@racaboxer.com.br","password" => "nick1978");
				$transport = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
				//Zend_Mail::setDefaultTransport($transport); // adicionado quando envia o email
				Zend_Mail::setDefaultFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"));
				Zend_Mail::setDefaultReplyTo("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"));
				
				for($i=0; $i < count($emails); $i++) {
					$emaildata = $modelemails->fetchEntry($emails[$i]);
					$mail = new Zend_Mail();
					$mail->addTo($emaildata['email'], $emaildata['name']);
					$mail->setBodyHtml(utf8_decode(stripslashes($row['content'])), 'iso-8859-1');
					$mail->setSubject(utf8_decode($row['title']));
					$mail->send($transport);
				}
				
				Zend_Mail::clearDefaultFrom();
				Zend_Mail::clearDefaultReplyTo();
				
				$sended = Common::returnData();
				$formData['sended'] = $sended;
				$formData['sended_times'] = ($row['sended_times']+1);
				$model->update($formData, $id);
				
				echo "1"; // enviado com sucesso
			}
			else { echo "2"; } // nao tem email pra enviar
		}
		else { echo "3"; } // nao selecionou o id da news corretamente
	}
	
	public function sendconfirmationmailAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelImages();
		$id = $request->get('id');
		
		if(!empty($id)) {
			$row = $model->fetchEntry($id);
			
			$config = array(
				//"ssl" => "tls",
				//"port" => 587,
				//"auth" => "login",
				"auth" => "plain",
				"username" => "contato@racaboxer.com.br",
				"password" => "nick1978"
			);
			
			$tr = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
			Zend_Mail::setDefaultTransport($tr);
			$mail = new Zend_Mail();
			
			/* ATTACH FILE TO EMAIL */
			$imagesfolder = "img/dogs";
			$attachfile = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagesfolder . $row['path'];
			list($widthb, $heightb, $imageTypeb) = getimagesize($attachfile);
			$imgBinary = file_get_contents($attachfile);
			$at = $mail->createAttachment($imgBinary);
			$at->type        = $imageTypeb;
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding    = Zend_Mime::ENCODING_BASE64;
			$at->id = 'cid_' . md5_file($attachfile);
			$at->filename    = $file;
			/* END ATTACH FILE TO EMAIL */

			$mess = "<b>Nome:</b> " . $row['name'] . "<br /><b>Email:</b> " . $row['email'] . "<br /><b>Nome do Cão:</b> " . $row['title'] . "<br />
				<b>Obs:</b> " . $row['obs'] . "<br /><br />
				<center>
					<img border=\"0\" src=\"cid:" . $at->id . "\" alt=\"" . $row['title'] . "\" style=\"border: 1px solid;box-shadow: 5px 5px 5px;\" /><br /><br />
					<a href='http://" . $_SERVER['SERVER_NAME'] . "/galeria/foto_" . $id . ".html'>Clique aqui para ver sua foto no site, compartilhar e/ou comentar !</a>
				</center><br /><br />";
			$emailtpl = file_get_contents(APP_PATH . "/class/email-tpl.html");
			$body = str_replace("##HEADMESS##", "Confira seus dados", $emailtpl);
			$body = str_replace("##MESS##", $mess, $body);
			$mail->setBodyHtml(utf8_decode($body), 'iso-8859-1')
				->addTo($row['email'], $row['name'])
				->addBcc('nickmarinho@gmail.com')
				->setReplyTo('contato@racaboxer.com.br', utf8_decode("Contato - Raça Boxer"))
				->setFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
				->setSubject(utf8_decode('Olá ' . $row['name'] . ' sua foto foi aprovada para aparecer no site Raça Boxer !'))
				;
			
			if($mail->send()) echo "1";
			else { echo "2"; }
		}
		else { echo "Por favor selecione o ID corretamente"; }
	}
	
	/* ACTIVE */
	public function activeonoffemailsAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id')) {
			$id = $request->get('id');
			$list = $request->get('list');
			$model = $this->_getModelEmails();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['active'] == '1') {
				$formData['active'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'emails', $id, $list, 'cancel'); }
			}
			else {
				$formData['active'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'emails', $id, $list, 'check'); }
			}
		}
	}

	public function activeonoffusersAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id')) {
			$id = $request->get('id');
			$list = $request->get('list');
			$model = $this->_getModelUsers();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['active'] == '1') {
				$formData['active'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'users', $id, $list, 'cancel'); }
			}
			else {
				$formData['active'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'users', $id, $list, 'check'); }
			}
		}
	}

	/* DISPLAY */
	public function displayonoffblogAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id')) {
			$id = $request->get('id');
			$list = $request->get('list');
			
			$model = $this->_getModelBlog();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['display'] == '1') {
				$formData['display'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'blog', $id, $list, 'cancel'); }
			}
			else {
				$formData['display'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'blog', $id, $list, 'check'); }
			}
		}
	}

	public function displayonoffimagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id')) {
			$id = $request->get('id');
			$list = $request->get('list');
			$model = $this->_getModelImages();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['display'] == '1') {
				$formData['display'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'images', $id, $list, 'cancel'); }
			}
			else {
				$formData['display'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'images', $id, $list, 'check'); }
			}
		}
	}

	public function displayonoffpagesAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id')) {
			$id = $request->get('id');
			$list = $request->get('list');
			$model = $this->_getModelPages();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['display'] == '1') {
				$formData['display'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'pages', $id, $list, 'cancel'); }
			}
			else {
				$formData['display'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('display', 'pages', $id, $list, 'check'); }
			}
		}
	}
	
	/* FETCH BY ID */
	public function fetchbyidemailsAction() {
		$this->view->layout()->disableLayout();
		$request = $this->getRequest();
		$model = $this->_getModelEmails();
		$id = $request->get('id');
		$div = $request->get('div');
		$row = $model->fetchEntry($id);
		$this->view->row = $row;
		$this->view->div = $div;
	}

	/* MAIL ACTIONS */
	public function mailimagesAction() {
		$request = $this->getRequest();
		$id = $request->get('id');
		$config = array(
			//"ssl" => "tls",
			//"port" => 587,
			//"auth" => "login",
			"auth" => "plain",
			"username" => "contato@racaboxer.com.br",
			"password" => "nick1978"
		);
		$model = $this->_getModelImages();
		list($id, $path, $title, $obs, $cdate, $mdate, $nome, $email) = $model->fetchEntry($id);
		$tr = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
		Zend_Mail::setDefaultTransport($tr);
		$mail = new Zend_Mail();
		$body = utf8_decode("
<div style='background:#EAA946;border-radius:4px 4px 4px 4px;box-shadow: 0.4em 0.4em 0.4em rgba(0, 0, 0, 0.5);font-size:1.0em;font-weight:bold;margin:10px auto 0px;padding:1em 2em;text-align:left;' width='500'>
Olá " . $nome . " <a href='http://" . $_SERVER['HTTP_HOST'] . "/galeria/foto_" . $id . ".html' target='_blank'>clique aqui para ver a foto do seu cachorro no site.</a><br />
</div>
");
		$mail->setBodyHtml($body, 'iso-8859-1')
			->addTo($email, utf8_decode($nome))
			->setFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
			->setReplyTo("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
			->setSubject('A foto do seu cachorro já pode ser vista no site')
			;
		//$mail->send();

		echo $body . "<div class='notice'>E-mail enviado com sucesso !!!</div>";
	}
	
	public function indexAction() {
		$this->settitle("Home of Admin");
		$this->setheaders();
		$auth = Zend_Auth::getInstance();
		$user = $auth->getIdentity();
		$model = $this->_getModelUsers()->fetchLastLogin($user->id);
		$userdata=array();
		$userdata['name'] = $user->name;

		if(!empty($model['last_login'])) {
			$userdata['last_login'] = $model['last_login'];
			$this->view->userdata = $userdata;
		}
	}
	
	/* LOGIN LOGOUT ACTION */
	public function loginAction() {
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity ()) { $this->_forward('index'); }
		
		$this->settitle("Login Page");
		$this->setheaders();
		$request = $this->getRequest();
		$form = $this->_getAdminLoginForm();
		$errorMessage = "";

		if($request->isPost()) {
			if($form->isValid($request->getPost())) {
				$registry = Zend_Registry::getInstance();
				$dbAdapter = $registry->get('dbAdapter');
				$user = $request->getParam('user');
				$passwd = $request->getParam('passwd');
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
				$authAdapter->setTableName('users')
					->setIdentityColumn('user')
					->setCredentialColumn('passwd');
				$authAdapter->setIdentity($user);
				$authAdapter->setCredential(md5($passwd));
				$auth = Zend_Auth::getInstance ();
				$result = $auth->authenticate($authAdapter);
				$authNamespace = new Zend_Session_Namespace('Zend_Auth');
				$authNamespace->setExpirationSeconds(86400);
				//Zend_Session::setOptions(array('remember_me_seconds' => '86400'));

				if($result->isValid()) {
					$data = $authAdapter->getResultRowObject(null, 'passwd');
					$auth->getStorage()->write($data);
					$userdata = $auth->getIdentity();
					$registry->userdata = $data;
					
					$_SESSION['user_active'] = $registry->userdata->active;
					$_SESSION['last_login'] = $registry->userdata->last_login;
					
					if($_SESSION['user_active'] == 1) {
						$model = $this->_getModelUsers();
						$mdata = Common::returnData();
						$model->update(array("last_login" => $mdata), $registry->userdata->id);
						header("location: " . SITE_PATH . "/admin ");
					}
					else {
						$auth->clearIdentity();
						echo "<script type=\"text/javascript\">alert('User disable to log in');location='" . SITE_PATH . "/admin/login';</script>";
					}
				}
				else $errorMessage = "User/Password wrong";
			}
		}

		$this->view->assign('errorMessage', $errorMessage);
		$this->view->form = $form;
	}

	public function logoutAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$auth = Zend_Auth::getInstance();
		if(! $auth->hasIdentity ()) $this->_redirect(SITE_PATH . '/admin/login');
		setcookie("jquery-ui-theme", "", time()-4200);

		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		echo "<script type=\"text/javascript\">location='" . SITE_PATH . "/admin/';</script>";
	}
	
	public function arearestritaAction() {
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$request = $this->getRequest();
		$form = $this->_getAreaRestritaForm();
		$errorMessage = "";

		if($request->isPost()) {
			if($form->isValid($request->getPost())) {
				$registry = Zend_Registry::getInstance();
				$dbAdapter = $registry->get('dbAdapter');
				$user = $request->getParam('user');
				$passwd = $request->getParam('passwd');
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
				$authAdapter->setTableName('users')
					->setIdentityColumn('user')
					->setCredentialColumn('passwd');
				$authAdapter->setIdentity($user);
				$authAdapter->setCredential(md5($passwd));
				$auth = Zend_Auth::getInstance ();
				$result = $auth->authenticate($authAdapter);
				$authNamespace = new Zend_Session_Namespace('Zend_Auth');
				$authNamespace->setExpirationSeconds(86400);
				//Zend_Session::setOptions(array('remember_me_seconds' => '86400'));

				if($result->isValid()) {
					$data = $authAdapter->getResultRowObject(null, 'passwd');
					$auth->getStorage()->write($data);
					$userdata = $auth->getIdentity();
					$registry->userdata = $data;
					
					$_SESSION['user_active'] = $registry->userdata->active;
					$_SESSION['last_login'] = $registry->userdata->last_login;
					
					if($_SESSION['user_active'] == 1) {
						$model = $this->_getModelUsers();
						$mdata = Common::returnData();
						$model->update(array("last_login" => $mdata), $registry->userdata->id);
						header("location: " . SITE_PATH . "/admin ");
					}
					else {
						$auth->clearIdentity();
						echo "<script type=\"text/javascript\">alert('User disable to log in');location='" . SITE_PATH . "/admin/login';</script>";
					}
				}
				else echo "User/Password wrong";
			}
		}
		else echo $form;
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
	
	public function gettitle() { return $this->title; }
	public function settitle($value) { $this->title = $value; }
	public function setheaders() { $this->_helper->layout()->getView()->headTitle($this->gettitle()); }
	
	/* GET FORMS */
	protected function _getAdminLoginForm() {
		require_once APP_PATH . '/forms/FormAdminLogin.php';
		$form = new Form_AdminLogin();
		$form->setAction($this->_helper->url('login'));
		return $form;
	}

	protected function _getAreaRestritaForm() {
		require_once APP_PATH . '/forms/FormAreaRestrita.php';
		$form = new Form_AreaRestrita();
		$form->setAction($this->_helper->url('arearestrita'));
		return $form;
	}

	protected function _getFormBlog($options = null) {
		if(null === $this->_formblog) {
			require_once APP_PATH . '/forms/FormBlog.php';
			$this->_formblog = new Form_Blog($options);
		}
		
		return $this->_formblog;
	}
	
	protected function _getFormImages($options = null) {
		if(null === $this->_formimages) {
			require_once APP_PATH . '/forms/FormImages.php';
			$this->_formimages = new Form_Images($options);
		}
		
		return $this->_formimages;
	}
	
	protected function _getFormNewsletter($options = null) {
		if(null === $this->_formnewsletter) {
			require_once APP_PATH . '/forms/FormNewsletter.php';
			$this->_formnewsletter = new Form_Newsletter($options);
		}
		
		return $this->_formnewsletter;
	}
	
	protected function _getFormPages($options = null) {
		if(null === $this->_formpages) {
			require_once APP_PATH . '/forms/FormPages.php';
			$this->_formpages = new Form_Pages($options);
		}
		
		return $this->_formpages;
	}
	
	protected function _getFormSendMail($options = null) {
		if(null === $this->_formsendmail) {
			require_once APP_PATH . '/forms/FormSendMail.php';
			$this->_formsendmail = new Form_SendMail($options);
		}
		
		return $this->_formsendmail;
	}
	
	protected function _getFormUsers($options = null) {
		if(null === $this->_formusers) {
			require_once APP_PATH . '/forms/FormUsers.php';
			$this->_formusers = new Form_Users($options);
		}
		
		return $this->_formusers;
	}
	
	/* GET MODELS */
	protected function _getModelBlog() {
		if(null === $this->_modelblog) {
			require_once APP_PATH . '/models/Blog.php';
			$this->_modelblog = new Model_Blog();
		}
		
		return $this->_modelblog;
	}

	protected function _getModelEmails() {
		if(null === $this->_modelemails) {
			require_once APP_PATH . '/models/Emails.php';
			$this->_modelemails = new Model_Emails();
		}
		
		return $this->_modelemails;
	}

	protected function _getModelImages() {
		if(null === $this->_modelimages) {
			require_once APP_PATH . '/models/Images.php';
			$this->_modelimages = new Model_Images();
		}
		
		return $this->_modelimages;
	}

	protected function _getModelNewsletter() {
		if(null === $this->_modelnewsletter) {
			require_once APP_PATH . '/models/Newsletter.php';
			$this->_modelnewsletter = new Model_Newsletter();
		}
		
		return $this->_modelnewsletter;
	}

	protected function _getModelPages() {
		if(null === $this->_modelpages) {
			require_once APP_PATH . '/models/Pages.php';
			$this->_modelpages = new Model_Pages();
		}
		
		return $this->_modelpages;
	}

	protected function _getModelUsers() {
		if(null === $this->_modelusers) {
			require_once APP_PATH . '/models/Users.php';
			$this->_modelusers = new Model_Users();
		}
		
		return $this->_modelusers;
	}
		
	/* SETTERS & GETTERS */
	public function getmaxthumbheight() { return $this->maxthumbheight; }
	public function setmaxthumbheight($value = '200') { $this->maxthumbheight = $value; }
	public function getmaxthumbwidth() { return $this->maxthumbwidth; }
	public function setmaxthumbwidth($value = '200') { $this->maxthumbwidth = $value; }
	public function getuploadfolder() { return $this->uploadfolder; }
	public function setuploadfolder($value = "img/dogs/upload/") { $this->uploadfolder = $value; }
	public function getimagesfolder() { return $this->imagesfolder; }
	public function setimagesfolder($value = "img/dogs/") { $this->imagesfolder = $value; }
}
?>
