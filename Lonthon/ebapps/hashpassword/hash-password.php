<?php
namespace ebapps\hashpassword;
include_once(ebbd.'/dbconfig.php');
use ebapps\dbconnection\dbconfig;
/*** ***/
class hashPassword extends dbconfig
{
public function hashPassword($ebpassword)
{
$shah_1 = sha1($ebpassword);
return $shah_1;
}
//
public function hashPasswordChange($ebconfirmpassword)
{
$shah_1 = sha1($ebconfirmpassword);
return $shah_1;
}
//
public function hashPasswordMD5($ebpassword)
{
$hashMD5 = md5($ebpassword);
return $hashMD5;
}
//
public function hashEmail()
{
$generate_email_hash_formate = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$generated_new_email_hash = ''; 
for ($i = 0; $i < 40; $i++)
{
$generated_new_email_hash .= $generate_email_hash_formate[rand(0, strlen($generate_email_hash_formate)-1)];
}
return $generated_new_email_hash;
}
}
?>
