<?php
/**
 * this class is only to concentrate the functions in separate of the other code 
 * @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2013-04-01
 */
class Emails
{
	private static $instance;
	protected $_nomefrom;
	protected $_nometo;
	protected $_emailto;
	protected $_replytonome;
	protected $_replytoemail;
	protected $_addbccs;
	protected $_subject;
	protected $_message;
	protected $_attachmentfilename;
	protected $_attachmentnameinmail;
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setnomefrom($nomefrom){ $this->_nomefrom = $nomefrom; }
	public function getnomefrom(){ return $this->_nomefrom; }
	public function setnometo($nometo){ $this->_nometo = $nometo; }
	public function getnometo(){ return $this->_nometo; }
	public function setemailto($emailto){ $this->_emailto = $emailto; }
	public function getemailto(){ return $this->_emailto; }
	public function setreplytonome($replytonome){ $this->_replytonome = $replytonome; }
	public function getreplytonome(){ return $this->_replytonome; }
	public function setreplytoemail($replytoemail){ $this->_replytoemail = $replytoemail; }
	public function getreplytoemail(){ return $this->_replytoemail; }
	public function setaddbccs($addbccs){ $this->_addbccs = $addbccs; }
	public function getaddbccs(){ return $this->_addbccs; }
	public function setsubject($subject){ $this->_subject = $subject; }
	public function getsubject(){ return $this->_subject; }
	public function setmessage($message){ $this->_message = $message; }
	public function getmessage(){ return $this->_message; }
	public function setattachmentfilename($attachmentfilename){ $this->_attachmentfilename = $attachmentfilename; }
	public function getattachmentfilename(){ return $this->_attachmentfilename; }
	public function setattachmentnameinmail($attachmentnameinmail){ $this->_attachmentnameinmail = $attachmentnameinmail; }
	public function getattachmentnameinmail(){ return $this->_attachmentnameinmail; }
	
	public function sendthis()
	{
		$nomefrom = $this->getnomefrom();
		$nometo = $this->getnometo();
		$emailto = $this->getemailto();
		$replytonome = $this->getreplytonome();
		$replytoemail = $this->getreplytoemail();
		$subject = $this->getsubject();
		$message = $this->getmessage();
		$attachmentfilename = $this->getattachmentfilename();
		$attachmentnameinmail = $this->getattachmentnameinmail();
		
		if(!empty($nometo) && !empty($emailto) && !empty($subject) && !empty($message))
		{
			include_once CLASS_PATH . '/phpmailer/class.phpmailer.php';
			$mail = PHPMailer::getInstance();
			$mail->IsSMTP();
			$mail->Host = SMTP_HOST;
			$mail->Port = SMTP_PORT;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl"; // enable this if use smtp.gmail.com
			//$mail->SMTPDebug = 2; // let this commented, just uncomment if you want to see in front end 
			$mail->Username = SMTP_USER;
			$mail->Password = SMTP_PASS;
			$mail->From = SMTP_USER;
			
			$namefrom = !empty($nomefrom) ? $nomefrom : EMAIL_FROM_NAME;
			$mail->FromName = $namefrom;
			
			$mail->AddAddress($emailto, $nometo);
			
			$addbccs = $this->getaddbccs();
			if(!empty($addbccs) && is_array($addbccs))
			{
				foreach($addbccs as $addbccemail => $addbccnome)
				{
					$mail->AddBcc($addbccemail, $addbccnome);
				}
			}
			
			$mail->IsHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $message;
			
			if(!empty($replytonome) && !empty($replytoemail)){ $mail->AddReplyTo($replytoemail, $replytonome); }

			if(!empty($attachmentfilename) && !empty($attachmentnameinmail))
			{
				$mail->AddAttachment($attachmentfilename, $attachmentnameinmail);
			}
			
			if($mail->Send()) return true;
			$mail->ClearAllRecipients();
			$mail->ClearAttachments();
		}
		
		return false;
	}
}