<?php
/**
 * 
 * class to separate in two layouts 
 * @copyright  2011 Luciano Marinho
 * @package    Tancredi & Machado
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-03-06
 * @version    1.0  
 * @name Auth.php
 */
class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
		$controller = $request->getControllerName();
		$action = $request->getActionName();
    	$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity() && $controller == "admin")
		{
			$layout = Zend_Layout::startMvc(
				array(
					'layoutPath' => APP_PATH . '/layouts/scripts/admin',
					'layout' => 'layout'
				)
			);

			$view = $layout->getView();
			$view->setEncoding('UTF-8');
			$view->doctype('XHTML1_STRICT');
			$view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		}
		elseif($controller == "admin")
		{
			$layout = Zend_Layout::startMvc(
				array(
					'layoutPath' => APP_PATH . '/layouts/scripts/admin',
					'layout' => 'nolayout'
				)
			);

			$view = $layout->getView();
			$view->setEncoding('UTF-8');
			$view->doctype('XHTML1_STRICT');
			$view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		}
		else
		{
			$layout = Zend_Layout::startMvc(
				array(
					'layoutPath' => APP_PATH . '/layouts/scripts/frontend',
					'layout' => 'layout'
				)
			);
		}
	}
}