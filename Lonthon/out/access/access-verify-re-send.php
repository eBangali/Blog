<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblogin.'/session-inc.php'); ?>
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
<div class="well">
<h2 title='Send eMail verification'>Send eMail verification</h2>
</div>
<div class="well">
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
/* Initialize validation */
$error = 0;
$usernameemail_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

/* Check form submission */
if (isset($_POST['send_verification'])) 
{
    /* Full name (username/email) */
    if (empty($_POST["usernameemail"])) 
    {
        $usernameemail_error = "<b class='text-warning'>Username or eMail required</b>";
        $error = 1;
    } 
    /* Validation username/email */
    elseif (!preg_match("/^[a-zA-Z0-9\-\.\@]{1,56}$/", $_POST["usernameemail"])) 
    {
        $usernameemail_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } 
    else 
    {
        $usernameemail_filtered = $sanitization -> onlyUsernameInputForLowercase($_POST["usernameemail"]);
    }

    //
    /* Submit form */
    if ($error == 0) 
    {
        include_once (eblogin.'/registration-page.php');
        $user = new ebapps\login\registration_page();
        $user -> varify_email_re_sent($usernameemail_filtered);
    }
    //
}
?>

<form method='post'>
<fieldset class='group-select'>
<?php echo $usernameemail_error;  ?>
<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Username or eMail: </span><input type='text' value='<?PHP if(isset($_SESSION['ebusername'])){echo $_SESSION['ebusername']; } ?>' name='usernameemail' placeholder='username or eMail' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>
<div class='buttons-set'>
<button type='submit' name='send_verification' title='Send eMail Verification' class='button submit'> <span> Send eMail Verification </span> </button>
</div>
</fieldset>
</form>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>