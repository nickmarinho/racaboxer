<?php
require_once 'facebook.php';
$app_id = "162744593790875";
$app_secret = "53c0dd24b8e9760fb88a221f4b9250fc";
$facebook = new Facebook(array(
	'appId' => $app_id,
	'secret' => $app_secret,
	'cookie' => true
));
if(is_null($facebook->getUser()))
{
	header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
	exit;
}
?>