<?php
/* Aqui incluímos a real classe do FCKEditor que irá criar o editor */
require_once 'Common/ckeditor/ckeditor.php';

class Common_ckeditor_ckeditorcommon
{
	private $_ck;

	/**
	 * Este médoto será chamado sempre que a classe for instanciada,
	 * chamado construtor.
	 * Faz as tarefas iniciais da classe.
	 *
	 * @param string
	 * @return void
	 */
	public function __construct($name)
	{
		/* Instancia o FCKeditor e guarda em uma variável */
		$this->_ck = new CKEditor($name) ;
	}

	/**
	 * Método que devolve a instância do FCK
	 *
	 * @return FCKeditor
	 */
	public function getInstance()
	{
		return $this->_ck;
	}
}

