<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$ebusername_error = "";
$ebpassword_error = "";
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
if (empty($_REQUEST['ebusername']))
{
$ebusername_error = "<b class='text-warning'>Username required</b>";
$error =1;
}
/* valitation ebusername */
elseif(!preg_match("/^[a-zA-Z0-9\-]{1,56}$/",$ebusername))
{
$ebusername_error = "<b class='text-warning'>Lowercase letters, dash and numbers are allowed.</b>";
$error =1;
}
else
{
$ebusername = $sanitization->onlyUsernameInputForLowercase($_POST['ebusername']);
}
/* ebpassword */
if (empty($_REQUEST['ebpassword']))
{
$ebpassword_error = "<b class='text-warning'>Temporary Password required</b>";
$error =1;
}
elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $ebpassword))
{
$ebpassword_error = "<b class='text-warning'>Password can contain English letters, numbers, dashes, and special characters. Max 56 characters.</b>";
$error = 1;
}
else
{
$ebpassword = $sanitization->test_input($_POST['ebpassword']);
}
/* Submition form */
if($error == 0)
{
extract($_REQUEST);
include_once (eblogin.'/login.php'); 
$ebUserRetrieve = new ebapps\login\login();
$ebUserRetrieve -> login2system($ebusername, $ebpassword);
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

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='Temporary Login'>Temporary Login</h2>
</div>
<div class='well'>
<form method='post'>
<fieldset class='group-select'>
<?php echo $ebusername_error; ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Username: </span>
<input type='text' name='ebusername' placeholder='username' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<?php echo $ebpassword_error; ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Temporary Password: </span>
<input type='password' name='ebpassword' placeholder='Temporary Password' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>

<div class='buttons-set'>
<button type='submit' name='login' title='Login' class='button submit'> <span> Login </span> </button>
</div>
</fieldset>
</form>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>

</div>
</div>
</div>	
<?php include_once (eblayout.'/a-common-footer.php'); ?>
<?php exit(); } ?>