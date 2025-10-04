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
<div class='well'>
<h2 title='Change password'>Change password</h2>
</div>

<?php include_once (ebHashKey.'/hash-password.php'); ?>
<?php
/* Initialize validation */
$error = 0;
$ebchangepassword_error = "";
$ebconfirmpassword_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['change_password']))
{
    /* ebchangepassword */
    if (empty($_POST['ebchangepassword'])) 
    {
        $ebchangepassword_error = "<b class='text-warning'>Password required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $_POST['ebchangepassword'])) 
    {
        $ebchangepassword_error = "<b class='text-warning'>Password can contain English letters, numbers, dashes, and special characters. Max 56 characters.</b>";
        $error = 1;
    }
    else 
    {
        $ebchangepassword_filtered = $sanitization->test_input($_POST['ebchangepassword']);
    }

    /* ebconfirmpassword */
    if (empty($_POST['ebconfirmpassword'])) 
    {
        $ebconfirmpassword_error = "<b class='text-warning'>Confirm Password required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $_POST['ebconfirmpassword'])) 
    {
        $ebconfirmpassword_error = "<b class='text-warning'>Password can contain English letters, numbers, dashes, and special characters. Max 56 characters.</b>";
        $error = 1;
    }
    elseif ($_POST['ebconfirmpassword'] !== $_POST['ebchangepassword']) 
    {
        $ebconfirmpassword_error = "<b class='text-warning'>Password does not match</b>";
        $error = 1;
    }
    else 
    {
        $ebconfirmpassword_filtered = $sanitization->test_input($_POST['ebconfirmpassword']);
    }

    /* Submission form */
    if ($error == 0) 
    {
        if ($ebchangepassword_filtered === $ebconfirmpassword_filtered) 
        {
            include_once (ebHashKey.'/hash-password.php');
            $addHashToPass = new ebapps\hashpassword\hashPassword();
            $ebconfirmpasswordTow = $addHashToPass->hashPasswordChange($ebconfirmpassword_filtered);

            include_once (eblogin.'/registration-page.php'); 
            $ebUserTemp = new ebapps\login\registration_page();
            $ebUserTemp->changepassword($ebconfirmpasswordTow);
        }
    }
}
?>

<div class='well'>
<form method='post'>
<fieldset class='group-select'>
<?php echo $ebchangepassword_error; ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>New Password:</span>
<input type='password' name='ebchangepassword' placeholder='Password' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<?php echo $ebconfirmpassword_error; ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Confirm New Password: </span>
<input type='password' name='ebconfirmpassword' placeholder='Confirm New Password' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>

<div class='buttons-set'>
<button type='submit' name='change_password' title='Change' class='button submit'> <span> Change </span> </button>
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