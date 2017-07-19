<?php
/**
 * Controller to blog
 * @copyright  2011 Luciano Marinho
 * @package    Marcio Bernardes
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-09-26
 * @version    1.0  
 * @name BlogController.php
 */
class BlogController extends Zend_Controller_Action
{
	private $pageName;
	private $title;
	private $keywords;
	private $description;
	
	protected $_formcontact;
	protected $_modelblog;
	protected $_modelcontact;
	protected $_modelpages;
	
	public function getpageName()
	{
		return $this->pageName;
	}
	
	public function setpageName($value)
	{
		$this->pageName = $value;
	}
	
	public function gettitle()
	{
		return $this->title;
	}
	
	public function settitle($value)
	{
		$this->title = $value;
	}
	
	public function getkeywords()
	{
		return $this->keywords;
	}
	
	public function setkeywords($value)
	{
		$this->keywords = $value;
	}
	
	public function getdescription()
	{
		return $this->description;
	}
	
	public function setdescription($value)
	{
		$this->description = $value;
	}
	
	public function setheaders()
	{
		$pageData = array();
		$pageData['pageName'] = $this->getpageName();
		$this->_helper->layout()->getView()->headTitle($this->gettitle());
		$this->_helper->layout()->getView()->headMeta()->appendName('keywords', $this->getkeywords());
		$this->_helper->layout()->getView()->headMeta()->appendName('description', $this->getdescription());
	}
	
	public function indexAction()
	{
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		$this->setpageName("blog");
		$this->settitle("Página Inicial do Blog");
		$this->setkeywords("blog, página, inicial, postagens, posts");
		$this->setdescription("Está é a página inicial do blog do site raça boxer, veja as postagens mais legais sobre cães da raça boxer");
		$this->setheaders();
		$datalist = $model->fetchFrontEntries();
		$this->view->datalist = $datalist;
	}

	public function postsAction()
	{
		$request = $this->getRequest();
		$model = $this->_getModelBlog();
		
		$c = $request->getParam('c');
		$p = $request->getParam('p');
		$cdate = $request->getParam('cdate');
		$id = $request->getParam('id');
		$url = $request->getParam('url');
		
		if(!empty($id) && !empty($url))
		{
			$post = $model->fetchEntryByUrl($url);
			
			if($post['display'] == '1')
			{
				$this->setpageName((stripslashes($post['title'])));
				$this->settitle((stripslashes($post['title'])));
				$this->setkeywords((stripslashes($post['meta_keywords'])));
				$this->setdescription((stripslashes($post['meta_description'])));
			}
			else
			{
				$this->setpageName("Conteúdo não disponível");
				$this->settitle("Conteúdo não disponível");
				$this->setkeywords("postagem, não, disponível, existe");
				$this->setdescription("Esta postagem não está mais disponível ou não existe mais aqui");
			}

			$this->setheaders();
			$this->view->post = $post;
		}
		else
		{
			$currentPage = isset($p) ? (int) htmlentities($p) : 1;
			
			if(strlen($cdate) == '4') // posts by year
			{
				$this->setpageName('');
				$this->settitle("Postagens do ano " . $cdate . " do blog");
				$this->setkeywords("postagens, ano, " . $cdate . ", blog, raça, boxer");
				$this->setdescription("Você está vendo as postagens do ano de " . $cdate . " do blog Raça Boxer");
				$this->setheaders();
				$model->setorder('cdate DESC');
				$model->setoptions(array('DATE_FORMAT(cdate, "%Y")' => $cdate));
				$datalist = $model->fetchFrontEntries();
			}
			elseif(strlen($cdate) == '7') // posts by month
			{
				$this->setpageName('');
				$this->settitle("Postagens do mês " . $cdate . " do blog");
				$this->setkeywords("postagens, mês, " . $cdate . ", blog, raça, boxer");
				$this->setdescription("Você está vendo as postagens do mês " . $cdate . " do blog Raça Boxer");
				$this->setheaders();
				$model->setorder('cdate DESC');
				$model->setoptions(array('DATE_FORMAT(cdate, "%Y-%m")' => $cdate));
				$datalist = $model->fetchFrontEntries();
			}
			else // posts by day
			{
				$this->setpageName('');
				$this->settitle("Postagens do dia " . $cdate . " do blog");
				$this->setkeywords("postagens, dia, " . $cdate . ", blog, raça, boxer");
				$this->setdescription("Você está vendo as postagens do dia " . $cdate . " do blog Raça Boxer");
				$this->setheaders();
				$model->setorder('cdate DESC');
				$model->setoptions(array('DATE_FORMAT(cdate, "%Y-%m-%d")' => $cdate));
				$datalist = $model->fetchFrontEntries();
			}

			$this->view->request = $request;
			$this->view->datalist = $datalist;
		}
	}

	// forms
	protected function _getFormContact()
	{
		if(null === $this->_formcontact)
		{
			require_once APP_PATH . '/forms/FormContact.php';
			$this->_formcontact = new Form_Contact();
		}

		return $this->_formcontact;
	}

	// models
	protected function _getModelBlog()
	{
		if(null === $this->_modelblog)
		{
			require_once APP_PATH . '/models/Blog.php';
			$this->_modelblog = new Model_Blog();
		}
		
		return $this->_modelblog;
	}
	
	protected function _getModelPages()
	{
		if(null === $this->_modelpages)
		{
			require_once APP_PATH . '/models/Pages.php';
			$this->_modelpages = new Model_Pages();
		}
		
		return $this->_modelpages;
	}
}
?>
