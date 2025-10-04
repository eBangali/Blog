<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblogin.'/session-inc-verify.php'); ?>
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
<h2 title='Address verification'>Address verification</h2>
<p>Submit address verify verification code.</p>
</div> 
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$addressCode_error = "";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['submit_address_verification_code']))
{
extract($_REQUEST);
/* Full name */
if (empty($_REQUEST["addressCode"]))
{
$addressCode_error = "<b class='text-warning'>Code Required</b>";
$error =1;
} 
/* valitation fullname  */
elseif (! preg_match("/^[0-9]{1,8}$/",$addressCode))
{
$addressCode_error = "<b class='text-warning'>Some characters are not allowed.</b>";
$error =1;
}
else 
{
$addressCode = $sanitization->test_input($_POST["addressCode"]);
}
//
/* Submition form */
if($error ==0)
{
$user = new ebapps\login\registration_page();
extract($_REQUEST);
$user -> varify_address($addressCode);
}
//
}
?>
<div class='well'>
<form method='post'>
<fieldset class='group-select'>
<?php echo $addressCode_error;  ?>
<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Address Verified Code: </span><input type='text' name='addressCode' placeholder='Code' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>
<div class='buttons-set'>
<button type='submit' name='submit_address_verification_code' title='Verify Address' class='button submit'>Verify Address</button>
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