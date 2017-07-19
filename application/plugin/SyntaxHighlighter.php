<?php
/**
 * Enables syntax highlighting for code blocks.
 *
 * @uses Zend
 * @uses _Controller_Plugin_Abstract
 * @author Hector Virgen <hector@virgentech.com>
 * @copyright Copyright (c) 2009-2010, Virgen Technologies
 * @category Application
 * @package Plugins
 */
class Application_Plugin_SyntaxHighlighter extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Scans the response for code syntax and adds necessary javascript/css
	 * to enable syntax highlighting.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		if(!$request->isDispatched()){ return; }

		// Prevent this plugin from running again.
		Zend_Controller_Front::getInstance()->unregisterPlugin($this);

		// Search the response for code syntax blocks.
		$response = $this->getResponse();
		$html = $response->getBody();
		$dom = new Zend_Dom_Query($html);
		
		$results = $dom->query('pre[class*="brush"], script[type="syntaxhighlighter"]');
		if(!count($results)) return;

		// Determine which brushes (languages) are used.
		$brushes = array();
		$regex = '/brush:[\s]*([a-z]+)/';
		foreach($results as $result)
		{
			$class = $result->getAttribute('class');
			if(!$class) continue;
			$matches = array();
			preg_match_all($regex, $class, $matches);
			if(isset($matches[1][0]))
			{
				$brush = $matches[1][0];
				if($brush == 'js' OR $brush == 'javascript'){ $brush = 'jScript'; }
				$brushes[$brush] = ucfirst($brush);
			}
		}

		// Return early if no brushes were found.
		if(empty($brushes)) return;

		// Get view to add CSS and JS
		$view = Zend_Controller_Front::getInstance()
			->getParam('bootstrap')
			->getResource('view');

		// Add CSS
		$headLink = $view->getHelper('headLink');
		$headLink->appendStylesheet('/css/syntaxhighlighter/shCore.css');
		$headLink->appendStylesheet('/css/syntaxhighlighter/shThemeFadeToGrey.css');

		// Add JS
		$headScript = $view->getHelper('headScript');
		$headScript->appendFile("/js/jquery/jquery-1.5.1.js"); // core
		$headScript->appendFile("/js/syntaxhighlighter/shCore.js"); // core
		$headScript->appendFile("/js/syntaxhighlighter/shBrushXml.js"); // required

		foreach($brushes as $brush){ $headScript->appendFile("/js/syntaxhighlighter/shBrush{$brush}.js"); }
		$headScript->appendScript("
		$(function()
		{
			SyntaxHighlighter.config.clipboardSwf = '/js/syntaxhighlighter/clipboard.swf';
			SyntaxHighlighter.all();
		});
		");
	}
}