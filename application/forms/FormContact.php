<?php
/**
 * Form to contact
 * @copyright  2011 Luciano Marinho
 * @package    Luciano Marinho
 * @author Nick Marinho <nickmarinho@gmail.com> - 2011-08-23
 * @version    1.0  
 * @name FormContact.php
 */
class Form_Contact extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		$this->setName('form_contact')
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
					->setAttrib('size', 60)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);
		  
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('* Email: ')
					->setAttrib('size', 60)
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$phone = new Zend_Form_Element_Text('phone');
		$phone->setLabel('Telefone: ')
					->setAttrib('size', 60)
					->setRequired(false)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$message = new Zend_Form_Element_Textarea('message');
		$message->setLabel('* Mensagem: ')
					->setAttrib('cols', 58)
					->setAttrib('rows', 7)
					->setAttrib('onkeyup', 'textCounter(this.form.message,this.form.remLen,200);')
					//->setAttrib('onkeyup', 'upperc(this.id);')
					->setRequired(true)
					->addValidator('NotEmpty')
					->setDecorators($decoratorOptions);

		$pubKey="6LcoM70SAAAAAHaymFMeJnObJNFnJewEi73QqwRI";
		$privKey="6LcoM70SAAAAANtGwqSjH3u0ZPP-jQA8-xekMpln";
		$recaptcha = new Zend_Service_ReCaptcha($pubKey, $privKey); 
		$recaptcha->setParams(array('ssl' => true,'xhtml' => true))
				->setOptions(array('label' => 'escreva as frases',
								'theme' => 'clean',
								'tabindex' => 2
				));
		$captcha = new Zend_Form_Element_Captcha('challenge', array(
			'captcha' => 'ReCaptcha',
			'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)
		));

		// a forma que vejo no site local contraria do que vejo no site online
		$captchaDecoratorsOnLine = array('Captcha','Description','Errors',
									array(
										array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'center', 'colspan' => '2')
									),
									array(
										array('row' => 'HtmlTag'), array('tag' => 'tr')
									)
								);
				
		$captchaDecoratorsLocal = array('Captcha','Description','Errors',
									array(
										array('row' => 'HtmlTag'), array('tag' => 'tr')
									),
									array(
										array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'center', 'colspan' => '2')
									)
								);
				
		$captcha->setLabel('Captcha');
		
		//if($_SERVER['SERVER_NAME'] == 'racaboxer.localhost')
		//{
		//	$captcha->setDecorators($captchaDecoratorsLocal);
		//}
		//else 
		$captcha->setDecorators($captchaDecoratorsOnLine);
		
		$submit = new Zend_Form_Element_Button('submitbutton');
		$submit->setLabel('Enviar')
				->setAttrib('onclick', 'javascript:saveContact();')
				->setAttrib('class', 'button')
				->setDecorators(array('ViewHelper','Description','Errors', 
									array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'center', 'colspan' => '2', 'id' => 'tdbuttons')),
									array(array('row' => 'HtmlTag'), array('tag' => 'tr','class' => 'impar', 'onmouseover' => 'this.className=\'over\';', 'onmouseout' => 'this.className=\'impar\';'))
								));
		
		$this->addElements(array(
					$name,
					$email,
					$phone,
					$message,
					$captcha,
					$submit
					));
	}
}
