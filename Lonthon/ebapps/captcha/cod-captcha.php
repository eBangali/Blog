<?php
namespace ebapps\captcha;
include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;
/*** ***/
class codCaptchaClass extends dbconfig
{
/*** ***/
public function codCaptchaFun()
{
$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$randomString = ''; 
for ($i = 0; $i < 8; $i++)
{
$randomString .= $chars[rand(0, strlen($chars)-1)];
}     
$_SESSION['codCaptcha'] = $randomString;	
return $randomString;
}

}

?>