<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebHashKey.'/hash-password.php'); ?>
<?php include_once (eblogin.'/login.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$ebusername_error = "<b>Username</b>";
$ebpassword_error = "<b>Password</b>";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['login']))
{
extract($_REQUEST);

/* ebusername */
if (empty($_REQUEST["ebusername"]))
{
$ebusername_error = "<b class='text-warning'>Username required.</b>";
$error =1;
}
/* valitation ebusername */
elseif(! preg_match("/^[a-zA-Z0-9\-]{1,56}$/",$ebusername))
{
$ebusername_error = "<b class='text-warning'>Lowercase letters, dash and numbers are allowed.</b>";
$error =1;
}
else
{
$ebusername = $sanitization -> onlyUsernameInputForLowercase($_POST["ebusername"]);
}
/* ebpassword */
if (empty($_REQUEST["ebpassword"]))
{
$ebpassword_error = "<b class='text-warning'>Password required.</b>";
$error =1;
}
elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $ebpassword))
{
$ebpassword_error = "<b class='text-warning'>Password can contain English letters, numbers, dashes, and special characters. Max 56 characters.</b>";
$error = 1;
}
else
{
$ebpassword = $sanitization -> test_input($_POST["ebpassword"]);
}
/* Submition form */
if($error == 0)
{
extract($_REQUEST);
$haPassword = new ebapps\hashpassword\hashPassword();
$ebpassword = $haPassword -> hashPassword($ebpassword);
$userLogin = new ebapps\login\login();
$userLogin -> login2system($ebusername, $ebpassword);
}

}
?>
<?php
if(empty($_SESSION['ebusername']))
{
?>
<div class='well'>
<form method='post'>
<?php echo $ebusername_error; ?>
<input type='text' name='ebusername' placeholder='Username' class='form-control' autofocus='1' required >
<?php echo $ebpassword_error; ?>
<input type='password' name='ebpassword' placeholder='Password' class='form-control' autofocus='1' required >
<div class='buttons-set'>
<button type='submit' name='login' title='Log In to Read More' class='button submit'> <span>Log In to Read More</span> </button>
</div>
</form>
<br />
<a  href='<?php echo outAccessLink; ?>/access-frogetlogin.php'><button type='button' title='Forgot Password?'><b>Forgot Password?</b></button></a>
<br />
<a  href='<?php echo outAccessLink; ?>/signup.php'><button type='button' title='Create New Account'><b>Create New Account</b></button></a>
</div>
<?php 
} 
?>