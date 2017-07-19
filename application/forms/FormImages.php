<?php
/**
 * Form to images
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-10-14
 * @version    1.0  
 * @name FormImages.php
 */
class Form_Images extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_images')
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

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('* Nome: ')
					->setAttrib('size', 85)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('* Email: ')
					->setAttrib('size', 85)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('* Title: ')
					->setAttrib('size', 85)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$obs = new Zend_Form_Element_Textarea('obs');
		$obs->setLabel('')
					->setAttrib('cols', 83)
					->setAttrib('rows', 20)
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

		$this->addElements(array($name,
								$email,
								$title,
								$obs,
								$submit));
					
		if(isset($options['id']))
		{
			$id = new Zend_Form_Element_Hidden('id', array('value' => $options['id']));
			$this->addElement($id);
		}
	}
}
