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
<?php include_once (ebaccess.'/access-permission-admin-minimum.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7'>
<div class="well">
<h2 title='Payment Gateways'>Payment Gateways</h2>
</div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php $formMail = new ebapps\login\registration_page(); ?>
<?php
/* Initialize validation */
$error = 0;
$gateway_error = "";
$payment_user_id_error = "";
$public_key_error = "";
$private_key_error = "";
$captcha_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['paymentGateway'])) {

    /* ===== Gateway ===== */
    if (empty($_POST["gateway"])) {
        $gateway_error = "<b class='text-warning'>Gateway required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z0-9\-\_\.\@]{3,180}$/", $_POST["gateway"])) {
        $gateway_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $gateway_filtered = $sanitization->test_input($_POST["gateway"]);
    }

    /* ===== payment_user_id ===== */
    if (empty($_POST["payment_user_id"])) {
        $payment_user_id_error = "<b class='text-warning'>Payment User ID Required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z0-9\-\_\.\@]{3,180}$/", $_POST["payment_user_id"])) {
        $payment_user_id_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $payment_user_id_filtered = $sanitization->test_input($_POST["payment_user_id"]);
    }

    /* ===== Public Key ===== */
    if (empty($_POST["public_key"])) {
        $public_key_error = "<b class='text-warning'>Public Key Required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z0-9\-\_\.\@]{3,180}$/", $_POST["public_key"])) {
        $public_key_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $public_key_filtered = $sanitization->test_input($_POST["public_key"]);
    }

    /* ===== Private Key ===== */
    if (empty($_POST["private_key"])) {
        $private_key_error = "<b class='text-warning'>Private Key Required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z0-9\-\_\.\@]{3,180}$/", $_POST["private_key"])) {
        $private_key_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $private_key_filtered = $sanitization->test_input($_POST["private_key"]);
    }

    /* ===== Captcha ===== */
    if (empty($_POST["answer"])) {
        $captcha_error = "<b class='text-warning'>Captcha required.</b>";
        $error = 1;
    } elseif (isset($_SESSION['captcha']) && $_POST['answer'] !== $_SESSION['captcha']) {
        unset($_SESSION['captcha']);
        $captcha_error = "<b class='text-warning'>Captcha?</b>";
        $error = 1;
    } else {
        $sanitization->test_input($_POST["answer"]);
    }

    /* ===== Final Submission ===== */
    if ($error === 0) {
        $formMail->paymentGatewaySetUp($gateway_filtered,$payment_user_id_filtered,$public_key_filtered,$private_key_filtered);
    }
}
?>

<?php include_once (ebaccess.'/access-payment-gateways-show.php'); ?>
<div class='well'>
<form method='post' enctype="multipart/form-data">
<fieldset class='group-select'>
<?php echo $gateway_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Gateway:</span>
<select class='form-control' name='gateway'>
<option value='stripeCardPay'>Stripe</option>
<option value='payPalPayment' selected='selected'>PayPal</option>
<option value='CashOnDelivery'>Cash on Delivery</option>
<option value='bKashPayment'>bKash</option>
</select>
</div>
<?php echo $payment_user_id_error; ?>
<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Payment User ID: </span>
<input type='text' name='payment_user_id' placeholder='Payment User ID' class='form-control' aria-describedby='sizing-addon2'>
</div>
<?php echo $public_key_error;  ?>
<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Client ID/ Publishable key: </span>
<input type='text' name='public_key' placeholder='Public Key' class='form-control' aria-describedby='sizing-addon2'>
</div>
<?php echo $private_key_error;  ?>
<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Secret Key: </span>
<input type='text' name='private_key' placeholder='Private Key' class='form-control' aria-describedby='sizing-addon2'>
</div>
<?php echo $captcha_error;  ?>
<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>
<?php
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
echo "<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
?>
</span>
<input type='text' name='answer' placeholder='Enter captcha here' class='form-control' aria-describedby='sizing-addon2' required>
</div>
<div class='buttons-set'>
<button type='submit' name='paymentGateway' title='Add Payment Gateway' class='button submit'> <span> Add Payment Gateway </span> </button>
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