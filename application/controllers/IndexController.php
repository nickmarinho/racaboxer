<?php
/**
 * Controller to index
 * @copyright  2011 Luciano Marinho
 * @package    Marcio Bernardes
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-04-12
 * @version    1.0  
 * @name IndexController.php
 */
class IndexController extends Zend_Controller_Action{
	private $pageName;
	private $title;
	private $keywords;
	private $description;
	
	protected $_formcontact;
	protected $_modelblog;
	protected $_modelcontact;
	protected $_modelpages;
	
	public function getpageName() { return $this->pageName; }
	public function setpageName($value) { $this->pageName = $value; }
	public function gettitle() { return $this->title; }
	public function settitle($value) { $this->title = $value; }
	public function getkeywords() { return $this->keywords; }
	public function setkeywords($value) { $this->keywords = $value; }
	public function getdescription() { return $this->description; }
	public function setdescription($value) { $this->description = $value; }
	
	public function setheaders() {
		$pageData = array();
		$pageData['pageName'] = $this->getpageName();
		$this->_helper->layout()->getView()->headTitle($this->gettitle());
		$this->_helper->layout()->getView()->headMeta()->appendName('keywords', $this->getkeywords());
		$this->_helper->layout()->getView()->headMeta()->appendName('description', $this->getdescription());
	}
	
	public function indexAction() {
		$request = $this->getRequest();
		$page = $request->get('page') <> '' ? $request->get('page') : 'home';
		$model = $this->_getModelPages();
		$this->setpageName($page);
		$pagedata = $model->fetchPageByUrl($page);
		$this->settitle(utf8_decode($pagedata[0]['title']));
		$this->setkeywords(utf8_decode($pagedata[0]['meta_keywords']));
		$this->setdescription(utf8_decode($pagedata[0]['meta_description']));
		$this->setheaders();
		$this->view->request = $request;
		$this->view->homelist = $pagedata;
		
		$modelblog = $this->_getModelBlog();
		$options = array();
		//$options['DATE_FORMAT(cdate, "%Y")'] = date("Y");
		$modelblog->setoptions($options);
		$modelblog->setlimit('10');
		$datalist = $modelblog->fetchFrontEntries();
		$this->view->datalist = $datalist;
	}

	public function blogAction() {
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		
		$p = $request->getParam('p');
		$url = $request->getParam('url');
		
		if(!empty($url)) {
			$post = $model->fetchEntryByUrl($url);
			
			if($post['display'] == '1') {
				$this->setpageName(utf8_decode(stripslashes($post['title'])));
				$this->settitle(utf8_decode(stripslashes($post['title'])));
				$this->setkeywords(utf8_decode(stripslashes($post['meta_keywords'])));
				$this->setdescription(utf8_decode(stripslashes($post['meta_description'])));
			}
			else {
				$this->setpageName("Conteúdo não disponível");
				$this->settitle("Conteúdo não disponível");
				$this->setkeywords("postagem, não, disponível, existe");
				$this->setdescription("Esta postagem não está mais disponível ou não existe mais aqui");
			}

			$this->setheaders();
			$this->view->post = $post;
		}
		else {
			$this->setpageName('');
			$this->settitle("Página " . $p . " das postagens do site");
			$this->setkeywords("página, " . $p . ", postagens, site, luciano, marinho");
			$this->setdescription("Você está vendo a página " . $p . " das postagens do site do Luciano Marinho");
			$this->setheaders();
			$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($model->fetchFrontEntries()));
			$currentPage = isset($p) ? (int) htmlentities($p) : 1;
			$pager->setCurrentPageNumber($currentPage);
			$itemsPerPage = isset($c) ? (int) htmlentities($c) : 10;
			$pager->setItemCountPerPage($itemsPerPage);
			$this->view->paginator=$pager;
			$this->view->request = $request;
			$this->view->p = $p;
			$this->view->datalist = $pager->getCurrentItems();
		}
	}

	public function portfolioAction() {
		$page = 'portfolio';
		$this->setpageName($page);
		$this->settitle("Portfólio");
		$this->setkeywords("portfólio, clientes, partners, sites, desenvolvidos");
		$this->setdescription("Conheça nesta página de portfólio os clientes, sites desenvolvidos, partners do Luciano Marinho");
		$this->setheaders();
	}

	public function scriptsAction() {
		$request = $this->getRequest();
		$page = $request->getParam('page') != '' ? $request->getParam('page') : 'scripts';
		$model = $this->_getModelScripts();
		
		$this->setpageName($page);
		if($page == 'scripts') {
			$this->settitle("Scripts"); 
			$this->setkeywords("scripts, html, js, jquery, php, shell, sh"); 
			$this->setdescription("Aqui nesta página você pode ver, salvar, usar, compartilhar, emprestar, doar, scripts em html, recursos js, efeitos em jquery, scripts php, shell em sh feitos por Luciano Marinho");
			$pagedata = $model->fetchEntriesFront();
			$this->view->pagedata = $pagedata;
			$this->view->content = '';
			$this->setheaders();
		}
		else {
			$options = array();
			$options['name'] = $page;
			$pagedata = $model->fetchEntriesFront($options);

			if($pagedata[0]['display'] == '1') {
				$this->settitle(utf8_decode(stripslashes($pagedata[0]['title']))); 
				$this->setkeywords(utf8_decode(stripslashes($pagedata[0]['meta_keywords']))); 
				$this->setdescription(utf8_decode(stripslashes($pagedata[0]['meta_description'])));
				$this->view->category = $pagedata[0]['category_id'];
				$this->view->title = $pagedata[0]['title'];
				$this->view->scriptname = $pagedata[0]['scriptname'];
				$this->view->content = $pagedata[0]['content'];
			}
			else {
				$this->settitle("Script não disponível");
				$this->setkeywords("script, não, disponível, existe");
				$this->setdescription("Este script não está mais disponível ou não existe mais aqui");
				$this->view->category = '';
				$this->view->content = '';
			}

			$this->setheaders();
		}
		
		$this->view->page = $page;
	}

	public function contatoAction() {
		$request = $this->getRequest();
		$page = 'contato';
		$form = $this->_getFormContact();
		$modelcontact = $this->_getModelContact();
		
		$this->setpageName($page);
		$this->settitle("Entre em Contato");
		$this->setkeywords("entre, contato, mande, mensagem, doação, dõe, anuncie, raca, boxer");
		$this->setdescription("Entre em contato com o Raça Boxer através dessa página");
		$this->setheaders();

		if($request->isPost()) {
			$formData = $request->getPost();

			if($form->isValid($formData)) {
				$formData = array();
				$name = $form->getValue('name');
				$email = $form->getValue('email');
				$phone = $form->getValue('phone');
				$message = $form->getValue('message');
				$ip = $_SERVER['REMOTE_ADDR'];
				$cdate = Common::returnData();

				$formData['name'] = $name;
				$formData['email'] = $email;
				$formData['phone'] = $phone;
				$formData['message'] = $message;
				$formData['ip'] = $ip;
				$formData['cdate'] = $cdate;
				
				//$modelcontact->save($formData);

				$config = array(
					//"ssl" => "tls",
					//"port" => 587,
					//"auth" => "login",
					"auth" => "plain",
					"username" => "contato@racaboxer.com.br",
					"password" => "nick1978"
				);

				$emailtpl = file_get_contents(APP_PATH . "/class/email-tpl.html");
				$tr = new Zend_Mail_Transport_Smtp('smtp.racaboxer.com.br', $config);
				Zend_Mail::setDefaultTransport($tr);
				$mail = new Zend_Mail();
				
				$mess = "<b>Name:</b> " . utf8_decode($name) . "<br />
<b>Email:</b> " . $email . "<br />
<b>Phone:</b> " . $phone . "<br />
<b>Ip:</b> " . $ip . "<br />
<b>Message:</b> " . utf8_decode($message);
				$body = str_replace("##HEADMESS##", "Nova Mensagem", $emailtpl);
				$body = str_replace("##MESS##", $mess, $body);
				
				$mail->setBodyHtml(utf8_decode($body), 'iso-8859-1')
					->addTo('nickmarinho@gmail.com', 'Luciano Marinho')
					->setReplyTo($email, utf8_decode($name))
					->setFrom("contato@racaboxer.com.br", utf8_decode("Contato - Raça Boxer"))
					->setSubject('Uma nova mensagem de contato no site de: "' . utf8_decode($nome) . '" <' . $email . '>')
					;
				$mail->send();

				$form->reset($formData);
				$fontcolor="000080";
				$modalmessage = 'Enviado com sucesso !!!';
				include_once APP_PATH . "/class/modalmessage.php";
			}
			else {
				$form->populate($formData);
				$fontcolor="ED406B";
				$modalmessage = 'Erro, por favor verifique o formulário';
				include_once APP_PATH . "/class/modalmessage.php";
			}
		}

		$this->view->form = $form;
	}

	public function rssAction(){
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$posts = $this->_getModelBlog()->fetchFrontEntries();
		$feed = new Zend_Feed_Writer_Feed;
		$feed->setTitle('Raça Boxer - Tudo sobre o mundo dos cães');
		$feed->setLink('http://' . $_SERVER['SERVER_NAME']);
		$feed->setFeedLink('http://' . $_SERVER['SERVER_NAME'] . '/atom', 'atom');
		$feed->setFeedLink('http://' . $_SERVER['SERVER_NAME'] . '/rss', 'rss');
		$feed->setLastBuildDate(time());
		$feed->setDescription('Este é o Raça Boxer, um site dedicado ao cão boxer chamado Nick que viveu em nossas vidas por 9 anos e nos deixou muitas lembranças boas e saudades');
		$feed->addHub('http://www.racaboxer.com.br/');
		$feed->addAuthor(array('name'  => 'Luciano Marinho','email' => 'contato@racaboxer.com.br','uri' => 'http://www.racaboxer.com.br/'));
		
		foreach($posts as $post) {
			if($post['display'] == '1') {
				$entry = $feed->createEntry();
				$entry->setTitle((stripslashes($post['title'])));
				$entry->setLink('http://' . $_SERVER['SERVER_NAME'] . '/blog/' . date("Y-m-d", strtotime($post['cdate'])) . '/' . $post['id'] . '-' . $post['url'] . '.html');
				$entry->addAuthor(
					array(
						'name'  => (stripslashes($post['author'])),
						'email' => 'contato@lucianomarinho.com.br',
						'uri'   => 'http://www.lucianomarinho.com.br/',
					)
				);
				
				$entry->setDescription(
					(stripslashes($post['rss']))
					. "<br /><div><a href='http://" . $_SERVER['SERVER_NAME'] . "/blog/" . date("Y-m-d", strtotime($post['cdate'])) . '/' . $post['id'] . '-' . $post['url'] . ".html' target='_blank'>Clique para continuar lendo ...</a></div><br /><br />"
				);
				//$content = $post['post'];
				//$entry->setContent($content);
				
				if($post['mdate'] == '0000-00-00 00:00:00'){ $mdate = $post['cdate']; }
				else{ $mdate = $post['mdate']; }
				
				$entry->setDateCreated(new Zend_Date(strtotime($post['cdate'])));
				$entry->setDateModified(new Zend_Date(strtotime($mdate)));
				$feed->addEntry($entry);
			}
		}
		echo $feed->export('rss');
	}
	
	// forms
	protected function _getFormContact() {
		if(null === $this->_formcontact) {
			require_once APP_PATH . '/forms/FormContact.php';
			$this->_formcontact = new Form_Contact();
		}

		return $this->_formcontact;
	}

	// models
	protected function _getModelBlog() {
		if(null === $this->_modelblog) {
			require_once APP_PATH . '/models/Blog.php';
			$this->_modelblog = new Model_Blog();
		}
		
		return $this->_modelblog;
	}
	
	protected function _getModelContact() {
		if(null === $this->_modelcontact) {
			require_once APP_PATH . '/models/Contact.php';
			$this->_modelcontact = new Model_Contact();
		}
		
		return $this->_modelcontact;
	}
	
	protected function _getModelPages() {
		if(null === $this->_modelpages) {
			require_once APP_PATH . '/models/Pages.php';
			$this->_modelpages = new Model_Pages();
		}
		
		return $this->_modelpages;
	}
}
?>
