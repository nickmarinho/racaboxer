<?php
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__) . '/../application'));
define('SITE_NAME', 'Raça Boxer');
define('SITE_TITLE', 'Raça Boxer');

// path online
//define('HOME_PATH', '/var/www/vhosts/racaboxer/httpdocs/www');
//define('SITE_PATH', '/www');

// path home linux
define('HOME_PATH', '/var/www/racaboxer/www');
define('SITE_PATH', '');

// path home windows
//define('HOME_PATH', 'C:\xampp\htdocs\racaboxer\www');
//define('SITE_PATH', '');

define('FILE_PATH', APP_PATH . "../www/");
define('DS', DIRECTORY_SEPARATOR);
define('FRONT_PATH', '');
	define('IMG_PATH', FRONT_PATH . DS . 'img' . DS);
	
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('APP_ENV', APPLICATION_ENV);

/** Ensure library/ is on include_path */
set_include_path(implode(PATH_SEPARATOR, array(
	get_include_path(),
	//'/var/www/ZendFramework-1.11.10/library',
	//'C:\xampp\htdocs\ZendFramework-1.11.10\library',
	realpath(APP_PATH . '/../library'),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APP_ENV,
    APP_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();

/*
$path = get_include_path();
echo "<pre>";
die(print_r($path));
*/