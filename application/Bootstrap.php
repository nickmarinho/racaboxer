<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initPlugins()
	{
		require_once APP_PATH . '/plugin/Auth.php';
		
		Zend_Controller_Front::getInstance()->registerPlugin(new Application_Plugin_Auth());
	}
	
	protected function _initDocType()
	{
		$this->bootstrap('View');
		
		$view = $this->getResource('View');
		$view->doctype('XHTML1_STRICT');

		$registry = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
		$config = new Zend_Config_Ini(APP_PATH . '/configs/application.ini', APPLICATION_ENV );		
		
		$registry->configuration = $config;
		$session = Zend_Registry::getInstance();
		$session->set('config', $config);
		$config = $registry->configuration;
		$dbAdapter = Zend_Db::factory($config->resources->db->adapter, $config->resources->db->params->toArray());
		$session->set('dbAdapter', $dbAdapter);
		
		Zend_Db_Table::setDefaultAdapter($dbAdapter);
	
		require_once APP_PATH . '/class/common.class.php';
	}
}
