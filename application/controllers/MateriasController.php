<?php
/**
 * Controller to materias
 * @copyright  2011 Luciano Marinho
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2012-01-17
 * @version    1.0  
 * @name MateriasController.php
 */
include_once APP_PATH . "/class/common.class.php";
include_once APP_PATH . "/../www/js/ckeditor/ckeditor.php";

class MateriasController extends Zend_Controller_Action
{
	/* PROTECTED FORMS AND MODELS */
	protected $_formmaterias;
	protected $_modelmaterias;
	
	/* INIT */
	public function init()
	{
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity ())
		{
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{
				echo "<script>document.location = '" . SITE_PATH . "/admin';</script>";
			}
			else{ $this->_forward('login'); }
		}
		else
		{
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
	public function listmateriasAction()
	{
		$this->view->layout()->disableLayout();
		$pagename = "blog";
		$_SESSION['pagename'] = $pagename;
		$this->settitle("List Blog");
		$this->setheaders();

		$registry = Zend_Registry::getInstance();
		$acl = $registry->get('acl');
		if(!$acl->isAllowed($this->user_role, 'sistema')){ $this->view->denymessage = "Desculpe, mas você não tem permissão para ver essa página!"; }
		
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
		if(!empty($title)){
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
	
	/* SAVE ACTIONS */
	public function savemateriasAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$formData = array();
		$url = $request->get('url');
		$title = $request->get('title');
		$meta_keywords = $request->get('meta_keywords');
		$meta_description = addslashes($request->get('meta_description'));
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
		
		if($form->isValid($request->getPost()))
		{
			if(!empty($id)){
				$formData['id'] = $id;
				$model->update($formData, $id);
				echo Common::successMess();
			}
			else
			{
				$formData['cdate'] = $cdate;
				$id = $model->save($formData);

				//require_once APP_PATH . '/../www/fb/fb_access.php';
				// add a status message
				//$status = $facebook->api('/me/feed', 'POST', array('message' => $post));
				//http://www.facebook.com/pages/Ra%C3%A7a-Boxer/193300814072202
				//$status = $facebook->api('/193300814072202/feed', 'POST', array('message' => $post));
				//var_dump($status);

				if($id){
					echo Common::successMess();
					echo Common::modalboxClose('blog');
				}
			}
		}
		else{
			$form->setAction('/admin/saveblog');
			$form->populate($formData);
			echo Common::errorMess();
			echo $form;
		}
	}
	
	/* ADD ACTIONS */
	public function addmateriasAction()
	{
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

	/* EDIT ACTIONS */
	public function editmateriasAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		$form = $this->_getFormBlog(array('id' => $id));
		$form->setAction('/admin/saveblog');
		
		$formData = array();
		foreach($row as $key => $value){
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

	/* REMOVE ACTIONS */
	public function removemateriasAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		
		if($request->get('id'))
		{
			$model = $this->_getModelBlog();
			if($model->delete($request->get('id'))) echo '1';
		}
		else echo 'error';
	}

	/* VIEW ACTIONS */
	public function viewmateriasAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		$id = $request->get('id');
		$row = $model->fetchEntry($id);
		if(count($row) > 0) echo (stripslashes($row['post']));
		else echo "Nada para mostrar";
	}
	
	/* ACTIVE */
	public function activeonoffmateriasAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		if($request->get('id'))
		{
			$id = $request->get('id');
			$list = $request->get('list');
			$model = $this->_getModelEmails();
			$data = $model->fetchEntry($id);
			$mdate = Common::returnData();
			$formData = array();
			$formData['mdate'] = $mdate;
			
			if($data['active'] == '1')
			{
				$formData['active'] = '0';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'emails', $id, $list, 'cancel'); }
			}
			else
			{
				$formData['active'] = '1';
				if($model->update($formData, $id)){ echo Common::onOffFunctions('active', 'emails', $id, $list, 'check'); }
			}
		}
	}

	public function indexAction()
	{
		$this->settitle("Home of Materias");
		$this->setheaders();
		$auth = Zend_Auth::getInstance();
		$user = $auth->getIdentity();
		$model = $this->_getModelUsers()->fetchLastLogin($user->id);
		$userdata=array();
		$userdata['name'] = $user->name;

		if(!empty($model['last_login']))
		{
			$userdata['last_login'] = $model['last_login'];
			$this->view->userdata = $userdata;
		}
	}
	
	/* LOGIN LOGOUT ACTION */
	public function loginAction()
	{
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity ()){ $this->_forward('index'); }
		
		$this->settitle("Login Page");
		$this->setheaders();
		$request = $this->getRequest();
		$form = $this->_getAdminLoginForm();
		$errorMessage = "";

		if($request->isPost())
		{
			if($form->isValid($request->getPost()))
			{
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

				if($result->isValid())
				{
					$data = $authAdapter->getResultRowObject(null, 'passwd');
					$auth->getStorage()->write($data);
					$userdata = $auth->getIdentity();
					$registry->userdata = $data;
					
					$_SESSION['user_active'] = $registry->userdata->active;
					$_SESSION['last_login'] = $registry->userdata->last_login;
					
					if($_SESSION['user_active'] == 1)
					{
						$model = $this->_getModelUsers();
						$mdata = Common::returnData();
						$model->update(array("last_login" => $mdata), $registry->userdata->id);
						header("location: " . SITE_PATH . "/admin ");
					}
					else
					{
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

	public function logoutAction()
	{
		$this->view->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$auth = Zend_Auth::getInstance();
		if(! $auth->hasIdentity ()) $this->_redirect(SITE_PATH . '/admin/login');
		setcookie("jquery-ui-theme", "", time()-4200);

		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		echo "<script type=\"text/javascript\">location='" . SITE_PATH . "/admin/';</script>";
	}
	
	public function gettitle()
	{
		return $this->title;
	}
	
	public function settitle($value)
	{
		$this->title = $value;
	}
	
	public function setheaders()
	{
		$this->_helper->layout()->getView()->headTitle($this->gettitle());
	}
	
	/* GET FORMS */
	protected function _getMateriasLoginForm()
	{
		require_once APP_PATH . '/forms/FormAdminLogin.php';
		$form = new Form_AdminLogin();
		$form->setAction($this->_helper->url('login'));
		return $form;
	}

	protected function _getFormMaterias($options = null)
	{
		if(null === $this->_formmaterias)
		{
			require_once APP_PATH . '/forms/FormBlog.php';
			$this->_formmaterias = new Form_Blog($options);
		}
		
		return $this->_formmaterias;
	}
	
	/* GET MODELS */
	protected function _getModelMaterias()
	{
		if(null === $this->_modelmaterias)
		{
			require_once APP_PATH . '/models/Blog.php';
			$this->_modelmaterias = new Model_Blog();
		}
		
		return $this->_modelmaterias;
	}

	protected function _getModelUsers()
	{
		if(null === $this->_modelusers)
		{
			require_once APP_PATH . '/models/Users.php';
			$this->_modelusers = new Model_Users();
		}
		
		return $this->_modelusers;
	}
}
?>