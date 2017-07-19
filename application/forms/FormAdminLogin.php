<?php
/**
 * Form to admin login
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-08-23
 * @version    1.0  
 * @name FormAdminLogin.php
 */
class Form_AdminLogin extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_adminlogin')
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

		$login = new Zend_Form_Element_Text('user');
		$login->setLabel('User: ')
					->addErrorMessage('Invalid User')
					->setAttrib('title', 'type your username')
					->setAttrib('size', 40)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$password = new Zend_Form_Element_Password('passwd', array('renderPassword' => true));
		$password->setLabel('Pass: ')
					->addErrorMessage('Invalid Password')
					->setAttrib('title', 'type your password')
					->setAttrib('size', 40)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$submit = new Zend_Form_Element_Button('submitbutton');
		$submit->setLabel('Ok')
				->setAttrib('title', 'click here to enter into admin')
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
		
		$this->addElements(array(
					$login, 
					$password,
					$submit,
					));
	}
}
