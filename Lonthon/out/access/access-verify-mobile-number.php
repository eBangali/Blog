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
<div class="well">
<h2 title='Mobile Number Verification'>Mobile Number Verification</h2>
</div>
<div class="well">
<?php include_once (eblogin.'/registration-page.php'); ?>

<?php 
$objMobile = new ebapps\login\registration_page(); 
$objMobile->varify_mobile(); 
?>

<?php
require_once 'twilio-php/src/Twilio/autoload.php'; // adjust as needed
use Twilio\Rest\Client;

$twilioSid = 'YOUR_TWILIO_SID';
$twilioToken = 'YOUR_TWILIO_AUTH_TOKEN';
$twilioNumber = '+1234567890';

$client = new Client($twilioSid, $twilioToken);
?>

<?php if ($objMobile->eBData) { foreach ($objMobile->eBData as $val): extract($val); ?>
    <?php 
    try {
        $client->messages->create(
            $mobile,
            [
                'from' => $twilioNumber,
                'body' => "Your verification code is: $mobile_verification_codes"
            ]
        );
        echo "<div class='text-success'><b>SMS sent to $mobile</b></div>";
    } catch (Exception $e) {
        echo "<div class='text-danger'><b>SMS failed:</b> " . $e->getMessage() . "</div>";
    }
    ?>
<?php endforeach; } ?> 
</div>

<?php
$error = 0;
$smsCode_error = "";
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_REQUEST['verifyMobile'])) {
    extract($_REQUEST);
    
    if (empty($_REQUEST["smsCode"])) {
        $smsCode_error = "<b class='text-warning'>Code required</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z0-9]{1,6}$/", $smsCode)) {
        $smsCode_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $smsCode = $sanitization->test_input($_POST["smsCode"]);
    }

    if ($error == 0) {
        $user = new ebapps\login\registration_page();
        $user->verifyMobile($smsCode);
    }
}
?>
<div class="well">
<form method='post'>
<fieldset class='group-select'>
<?php echo $smsCode_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Type Code </span>
<input type='text' name='smsCode' placeholder='Type Code' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<div class='buttons-set'>
<button type='submit' name='verifyMobile' title='Verify mobile number' class='button submit'> <span> Verify mobile number </span> </button>
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