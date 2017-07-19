<?php
/**
 * Form to newsletter
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-10-27
 * @version    1.0
 * @name FormNewsletter.php
 */
class Form_Newsletter extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_newsletter')
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
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$emails = new Zend_Form_Element_Radio('emails');
		$emails->setLabel('* Emails: ')
					->setDecorators($decoratorOptions);
		$emails->addMultiOption("todos", "Todos");
		$emails->addMultiOption("selecionar", "Selecionar");
				  
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
								$emails,
								$content,
								$submit));
					
		if(isset($options['id']))
		{
			$id = new Zend_Form_Element_Hidden('id', array('value' => $options['id']));
			$this->addElement($id);
		}
	}
}
