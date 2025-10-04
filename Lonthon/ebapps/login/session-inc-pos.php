<?php
include_once(eblogin.'/login.php'); 
$userOfeB = new ebapps\login\login();
$userOfeB -> getsession();
if(empty($_SESSION['ebusername']))
{
include (ebaccess.'/access-login-pos.php');
}
?>