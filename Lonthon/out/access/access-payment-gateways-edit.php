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
<div class='well'>
<h2 title='Edit Payment Gateway'>Edit Payment Gateway</h2>
</div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php $paymentUadate = new ebapps\login\registration_page(); ?>
<?php
/* Initialize validation */
$error = 0;
$payment_user_id_error = "";
$public_key_error = "";
$private_key_error = "";
$captcha_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['AcceptPayment'])) {
  
    $payment_gateways_id = intval($_POST['payment_gateways_id']);
    $gateway = strval($_POST['gateway']);

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
        $paymentUadate->payment_gateways_accept_payment_getway($payment_gateways_id,$gateway,$payment_user_id_filtered,$public_key_filtered,$private_key_filtered);
    }
}
?>
<?php
if (isset($_POST['DonotAcceptPayment'])) {

    $error = 0;

    $payment_gateways_id = $_POST['payment_gateways_id'] ?? '';
    $gateway             = $_POST['gateway'] ?? '';

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
        $paymentUadate->payment_gateways_donot_accept($payment_gateways_id,$gateway,$payment_user_id_filtered,$public_key_filtered,$private_key_filtered);
    }
}
?>
<?php
if (isset($_POST['DeletePaymentOption'])) {
    $error = 0;
    $payment_gateways_id = intval($_POST['payment_gateways_id']);
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
    if ($error === 0)
    {
        $paymentUadate->payment_gateways_delete_payment_option($payment_gateways_id);
    }
}
?>

<?php
$paymentGateWayEdit = new ebapps\login\registration_page();
$paymentGateWayEdit -> payment_gateways_show_for_edit(); 
if($paymentGateWayEdit->eBData)
{
foreach($paymentGateWayEdit->eBData as $valpaymentGateWayEdit): extract($valpaymentGateWayEdit);
$paymentGetways ="<div class='well'>";
$paymentGetways .="<form method='post' enctype='multipart/form-data'>";
$paymentGetways .="<fieldset class='group-select'>";
$paymentGetways .="<input type='hidden' name='payment_gateways_id' value='$payment_gateways_id'>";
$paymentGetways .="<div class='input-group'>";
$paymentGetways .="<span class='input-group-addon' id='sizing-addon2'>Gateway:</span>";
$paymentGetways .="<select class='form-control' name='gateway'>";
$paymentGetways .="<option value='$payment_gateways_brand' selected>$payment_gateways_brand</option>";
$paymentGetways .="</select>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Payment User ID: $payment_user_id_error</span>";
$paymentGetways .="<input type='text' name='payment_user_id' placeholder='Payment ID' value='$payment_gateways_username' class='form-control' aria-describedby='sizing-addon2'>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Client ID/ Publishable key: $public_key_error</span>";
$paymentGetways .="<input type='text' name='public_key' placeholder='Public Key' value='$payment_gateways_public_key' class='form-control' aria-describedby='sizing-addon2'>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>Secret Key: $private_key_error</span>";
$paymentGetways .="<input type='text' name='private_key' placeholder='Private Key' value='$payment_gateways_privet_key' class='form-control' aria-describedby='sizing-addon2'>";
$paymentGetways .="</div>";

$paymentGetways .="<div class='input-group'> <span class='input-group-addon' id='sizing-addon2'>";
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
$paymentGetways .="<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
$paymentGetways .="$captcha_error";
$paymentGetways .="</span>";
$paymentGetways .="<input type='text' name='answer' placeholder='Enter captcha here' class='form-control' aria-describedby='sizing-addon2' required>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='buttons-set'>";
$paymentGetways .="<button type='submit' name='DeletePaymentOption' title='Delete Payment Option' class='button submit'> <span>Delete Payment Option</span> </button>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='buttons-set'>";
$paymentGetways .="<button type='submit' name='DonotAcceptPayment' title='Do not Accept Payment' class='button submit'> <span>Do not Accept Payment</span> </button>";
$paymentGetways .="</div>";
$paymentGetways .="<div class='buttons-set'>";
$paymentGetways .="<button type='submit' name='AcceptPayment' title='Accept Payment' class='button submit'> <span> Accept Payment</span> </button>";
$paymentGetways .="</div>";
$paymentGetways .="</fieldset>";
$paymentGetways .="</form>";
$paymentGetways .="</div>";
echo $paymentGetways;
endforeach;
}

?>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>