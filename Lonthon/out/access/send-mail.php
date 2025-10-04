<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
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
<?php include_once (ebaccess.'/access-permission-admin-minimum.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7'>
<div class='well'>
<h2 title='Send eMail'>Send eMail</h2>
</div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php $formMail = new ebapps\login\registration_page(); ?>
<?php
/* Initialize validation */
$error = 0;
$email_error = "";
$subjectfor_error = "";
$messagepre_error = "";
$captcha_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if(isset($_POST['massMailSend']))
{
    /* ======================
       EMAIL VALIDATION
    ======================= */
    $email_raw = trim($_POST["email"] ?? "");

    if(empty($email_raw)) {
        $email_error = "<b class='text-warning'>Email required.</b>";
        $error = 1;
    }
    elseif($sanitization->validEmail($email_raw) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Formatting</b>";
        $error = 1;
    }
    elseif(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z]+\.)+[a-z]{2,6}$/", $email_raw)) {
        $email_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    elseif($sanitization->validEmailDnsAndIP($email_raw) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Domain or DNS or IP</b>";
        $error = 1;
    }
    elseif($sanitization->validEmailOnlyGmail($email_raw) === false) {
        $email_error = "<b class='text-warning'>Only @gmail.com allowed!!!</b>";
        $error = 1;
    }
    else {
        $email_filtered = $sanitization->onlyUsernameInputForLowercase($email_raw);
    }

    /* ======================
       SUBJECT VALIDATION
    ======================= */
    $subjectfor_raw = trim($_POST["subjectfor"] ?? "");

    if(empty($subjectfor_raw)) {
        $subjectfor_error = "<b class='text-warning'>Required</b>";
        $error = 1;
    }
    elseif(!preg_match("/^[A-Za-z0-9\-\?\., ]{3,59}$/", $subjectfor_raw)) {
        $subjectfor_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else {
        $subjectfor_filtered = $sanitization->test_input($subjectfor_raw);
    }

    /* ======================
       MESSAGE VALIDATION
    ======================= */
    $messagepre_raw = $_POST["messagepre"] ?? "";

    if(empty($messagepre_raw)) {
        $messagepre_error = "<b class='text-warning'>Required</b>";
        $error = 1;
    }
    elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($messagepre_raw)) {
        $messagepre_error = "<b class='text-warning'>Only h2 to h6, p, b, strong, ol, ul, li, em, a, br tags are allowed.</b>";
        $error = 1;
    }
    else {
        $messagepre_filtered = $sanitization->testArea($messagepre_raw);
    }

    /* ======================
       CAPTCHA VALIDATION
    ======================= */
    $answer_raw = trim($_POST["answer"] ?? "");

    if(empty($answer_raw)) {
        $captcha_error = "<b class='text-warning'>Captcha required.</b>";
        $error = 1;
    }
    elseif(isset($_SESSION['captcha']) && $answer_raw !== $_SESSION['captcha']) {
        unset($_SESSION['captcha']);
        $captcha_error = "<b class='text-warning'>Captcha?</b>";
        $error = 1;
    }
    else {
        $sanitization->test_input($answer_raw);
    }

    /* ======================
       FINAL SUBMISSION
    ======================= */
    if($error == 0) {
        $formMail->ebSendMail($email_filtered, $subjectfor_filtered, $messagepre_filtered);
    }
}
?>

<div class='well'>
<form method="post">
<fieldset class='group-select'>
Email To: <?php echo $email_error; ?>
<input type='email' class='form-control' name='email' required  autofocus>
Subject: <?php echo $subjectfor_error;  ?>
<input type='text' class='form-control' name='subjectfor' placeholder='Subject' required  autofocus>
Message: <?php echo $messagepre_error;  ?>
<textarea class="form-control" name="messagepre" placeholder="Certain special characters are not allowed." id="HowToDo"></textarea>
<div class='input-group'>
<?php echo $captcha_error;  ?>
<?php
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
echo "<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
?>
<br />
<input type='text'  name='answer' placeholder='Enter captcha here' required />
</div>
<div class='buttons-set'><button type='submit' name='massMailSend' title='Mass eMail' class='button submit'> <span> SEND </span> </button></div>
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