<?php
/**
 * Controller to shopping cart
 * @copyright  2011 Luciano Marinho
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-10-21
 * @version    1.0  
 * @name ShopController.php
 */
class ShopController extends Zend_Controller_Action
{
	private $pageName;
	private $title;
	private $keywords;
	private $description;

	protected $_modelshop;
	
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
		$model = $this->_getModelShop();
		$options = array();
		$options['display'] = '1';
		$model->setoptions($options);
		$this->view->datalist = $model->fetchEntries();
		
		$this->setpageName("loja");
		$this->settitle("Loja");
		$this->setkeywords("loja, produto, compra, paypal");
		$this->setdescription("Está é a Loja Raça Boxer onde você encontra produtos variados sobre e para animais");
		$this->setheaders();
	}

	// models
	protected function _getModelShop()
	{
		if(null === $this->_modelshop)
		{
			require_once APP_PATH . '/models/Shop.php';
			$this->_modelshop = new Model_Shop();
		}
		
		return $this->_modelshop;
	}
}
?>
