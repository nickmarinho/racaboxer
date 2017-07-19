<?php
/**
 * Controller to errors
 * @copyright  2011 Luciano Marinho
 * @package   Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-11-13
 * @version    1.0  
 * @name ErrorController.php
 */
class ErrorController extends Zend_Controller_Action
{
	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');
		
		switch($errors->type)
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
				//$this->getResponse()->setHttpResponseCode(404);
				//$this->view->message = 'Página não encontrada !';
			break;
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				//$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Página não encontrada !';
			break;
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				//$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Página não encontrada !';
			break;
			
			default:
				// application error
				//$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Erro na Aplicação';
			break;
		}
		
		//$this->view->exception	= $errors->exception;
		//$this->view->request	= $errors->request;
	}
}
?>
