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
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<?php include_once (eblayout.'/a-common-header-title-one.php'); ?>
<?php include_once (eblayout.'/a-common-header-meta-scripts.php'); ?>
<?php include_once (eblayout.'/a-common-page-id-start.php'); ?>
<?php include_once (eblayout.'/a-common-header.php'); ?>
<nav>
  <div class='container'>
    <div>
      <?php include_once (eblayout.'/a-common-navebar.php'); ?>
      <?php include_once (eblayout.'/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>
<?php include_once (eblayout.'/a-common-page-id-end.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>  
<div class='well'>
<h2 title='Log In'>Log In</h2>
</div>
<div class='well'>
<form method='post'>
<?php echo $ebusername_error; ?>
<input type='text' name='ebusername' placeholder='Username' class='form-control' autofocus='1' required >
<?php echo $ebpassword_error; ?>
<input type='password' name='ebpassword' placeholder='Password' class='form-control' autofocus='1' required >
<div class='buttons-set'>
<button type='submit' name='login' title='Log In' class='button submit'> <span>Log In</span> </button>
</div>
</form>
<a  href='<?php echo outAccessLink; ?>/access-frogetlogin.php'><button type='button' title='Forgot Password?'><b>Forgot Password?</b></button></a>
<br />
<a  href='<?php echo outAccessLink; ?>/signup.php'><button type='button' title='Create New Account'><b>Create New Account</b></button></a>
</div>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>

</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>   
</div>
</div>
</div>	
<?php include_once (eblayout.'/a-common-footer.php'); ?>
<?php exit(); } ?>