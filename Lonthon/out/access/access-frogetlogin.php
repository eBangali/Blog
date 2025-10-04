<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
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
<?php include_once (ebcontents.'/views/shop/search.php'); ?>
<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'>
    </div>
    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
    <div class='well'>
<h2 title='Forgot Password?'>Forgot Password?</h2>
</div>
<div class='well'>
<?php include_once (eblogin.'/login.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$usernameemail_error = "";
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
if (isset($_REQUEST['retrieve']))
{
extract($_REQUEST);
/* Full name */
if (empty($_REQUEST["usernameemail"]))
{
$usernameemail_error = "<b class='text-warning'>Username or eMail or Mobile Number Required.</b>";
$error =1;
} 
/* valitation fullname  */
elseif (! preg_match("/^[a-z0-9\-\@\.]{1,56}$/",$usernameemail))
{
$usernameemail_error = "<b class='text-warning'>Some characters are not allowed.</b>";
$error =1;
}
else 
{
$usernameemail = $sanitization->onlyUsernameInputForLowercase($_POST["usernameemail"]);
}
/* Submition form */
if($error ==0)
{
$user = new ebapps\login\login();
extract($_REQUEST);
$user -> retrieve($usernameemail);
}
}
?>
<form method='post'>
<fieldset class='group-select'>
<?php echo $usernameemail_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Username or eMail:</span>

<input type='text' name='usernameemail' placeholder='Username or eMail' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>

<div class='buttons-set'>
<button type='submit' name='retrieve' title='Submit' class='button submit'> <span> Submit </span> </button>
</div>
</fieldset>
</form>
<br />
<a  href='<?php echo outAccessLink; ?>/signup.php'><button class='button submit' type='button' title='Register New User'><b>Register New User</b></button></a>
    </div> 
    </div>
    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
    
    </div>
  </div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>