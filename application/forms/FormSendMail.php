<?php
/**
 * Form to send mail from admin of the site
 * 
 * @copyright  2011 Luciano Marinho
 * @package    Raça Boxer
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-11-02
 * @version    1.0  
 * @name FormSendMail.php
 */
class Form_SendMail extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_sendmail')
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
		$name->setLabel('* Name: ')
					->setAttrib('size', 130)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('* Email: ')
					->setAttrib('size', 130)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
					
		$subject = new Zend_Form_Element_Text('subject');
		$subject->setLabel('* Subject: ')
					->setAttrib('size', 130)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
					
		$message = new Zend_Form_Element_Textarea('message');
		$message->setLabel('')
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

		if(isset($options['name'])){ $name->setValue($options['name']); }
		if(isset($options['email'])){ $email->setValue($options['email']); }
		if(isset($options['subject'])){ $subject->setValue($options['subject']); }
		if(isset($options['message'])){ $message->setValue($options['message']); }
		
		$this->addElements(array($name,
								$email,
								$subject,
								$message,
								$submit));
	}
}
?>
