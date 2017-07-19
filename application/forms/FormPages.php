<?php
/**
 * Form to pages
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-09-28
 * @version    1.0  
 * @name FormPages.php
 */
class Form_Pages extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_pages')
			->setEnctype('multipart/form-data')
			->setMethod('post')
			->setDecorators(array(
								'FormElements',
								array('HtmlTag', array('tag' => 'table', 'class' => 'login', 'align' => 'center')),
								'Form'
							));

		$decoratorOptions = array(
			'ViewHelper',
			'Errors',
			array(array('data' => 'HtmlTag'), array('tag' => 'td')),
			array('Label', array('tag' => 'td')),
			array(array('row' => 'HtmlTag'), array(
												'tag' => 'tr', 
												'class' => 'impar', 
												'onmouseover' => 'this.className=\'over\';', 
												'onmouseout' => 'this.className=\'impar\';')
											)
		);

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('* Title: ')
					->setAttrib('size', 130)
					->setAttrib('onchange', 'javascript:formatblogfields();')
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$url = new Zend_Form_Element_Text('url');
		$url->setLabel('* Url: ')
					->setAttrib('size', 130)
					->setAttrib('onkeyup', 'javascript:loadtools();')
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$meta_keywords = new Zend_Form_Element_Text('meta_keywords');
		$meta_keywords->setLabel('* Keywords: ')
					->setAttrib('size', 130)
					->setAttrib('onchange', 'javascript:loadtools();')
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$meta_description = new Zend_Form_Element_Text('meta_description');
		$meta_description->setLabel('* Description: ')
					->setAttrib('size', 130)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$content = new Zend_Form_Element_Textarea('content');
		$content->setLabel('')
					->setAttrib('cols', 130)
					->setAttrib('rows', 50)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$submit = new Zend_Form_Element_Submit('submitbutton');
		$submit->setLabel('Ok')
				->setAttrib('class', "button openmodalbox")
				->setDecorators(array(
										'ViewHelper',
										'Description',
										'Errors', 
										array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'center', 'colspan' => '2', 'id' => 'tdbuttons')),
										array(
											array('row' => 'HtmlTag'), 
											array(
												'tag' => 'tr',
												'class' => 'impar', 
												'onmouseover' => 'this.className=\'over\';', 
												'onmouseout' => 'this.className=\'impar\';'
											)
										)
									)
								);

		$this->addElements(array($title,
								$url,
								$meta_keywords,
								$meta_description,
								$content,
								$submit));
					
		if(isset($options['id']))
		{
			$id = new Zend_Form_Element_Hidden('id', array('value' => $options['id']));
			$this->addElement($id);
		}
	}
}
