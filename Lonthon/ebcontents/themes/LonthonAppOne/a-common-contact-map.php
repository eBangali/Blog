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

if (isset($_POST['contact_button']))
{
    /* email_address */
    if (empty($_POST["email_address"])) {
        $email_error = "<b class='text-warning'>Email required.</b>";
        $error = 1;
    }
    /* Check eMail Formatting */
    elseif ($sanitization->validEmail($_POST["email_address"]) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Formatting</b>";
        $error = 1;
    }
    /* Validation email */
    elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z]+\.)+[a-z]{2,6}$/", $_POST["email_address"])) {
        $email_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    /* Check eMail DNS, Domain and IP */
    elseif ($sanitization->validEmailDnsAndIP($_POST["email_address"]) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail</b>";
        $error = 1;
    }
    /* Check eMail only Gmail */
    elseif ($sanitization->validEmailOnlyGmail($_POST["email_address"]) === false) {
        $email_error = "<b class='text-warning'>Only @gmail.com allowed!!!</b>";
        $error = 1;
    }
    else {
        $email_address_filtered = $sanitization->test_input($_POST["email_address"]);
    }

    /* subjectfor */
    if (empty($_POST["subjectfor"])) {
        $subjectfor_error = "<b class='text-warning'>Subject required.</b>";
        $error = 1;
    }
    /* Validation subjectfor */
    elseif (!preg_match("/^([A-Za-z.,0-9\-\?\ ]+){3,40}$/", $_POST["subjectfor"])) {
        $subjectfor_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else {
        $subjectfor_filtered = $sanitization->test_input($_POST["subjectfor"]);
    }

    /* message */
    if (empty($_POST["messagepre"])) {
        $messagepre_error = "<b class='text-warning'>Message required.</b>";
        $error = 1;
    }
    /* Validation messagepre */
    elseif (!preg_match("/^[\p{L}\p{N}\s\.\,\?\!\-\#\(\)\/]{3,300}$/u", $_POST["messagepre"])) {
        $messagepre_error = "<b class='text-warning'>Use minimum 3 characters. Special characters like quotes, angle brackets, or emojis are not allowed.</b>";
        $error = 1;
    }
    /* Validation messagepre (allowed tags only) */
    elseif (!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["messagepre"])) {
        $messagepre_error = "<b class='text-warning'>Only h2 to h6, p, b, ol, ul, li, em, strong, a tags are allowed.</b>";
        $error = 1;
    }
    else {
        $messagepre_filtered = $sanitization->testArea($_POST["messagepre"]);
    }

    /* Captcha */
    if (empty($_POST["answer"])) {
        $captcha_error = "<b class='text-warning'>Captcha required.</b>";
        $error = 1;
    }
    elseif (isset($_SESSION['captcha']) && $_POST['answer'] !== $_SESSION['captcha']) {
        unset($_SESSION['captcha']);
        $captcha_error = "<b class='text-warning'>Captcha?</b>";
        $error = 1;
    }
    else {
        $captcha_filtered = $sanitization->test_input($_POST["answer"]);
    }

    /* Submission form */
    if ($error == 0) {
        $formMail->ebSendMailContact(
            $email_address_filtered, 
            $subjectfor_filtered, 
            $messagepre_filtered
        );
        include (ebpages.'/thanks.php');
        include_once (eblayout.'/a-common-footer.php');
        exit();
    }
}
?>

<section id='contact'>
<div class='container'>
<div class='row'>
<div class='col-xs-12 col-sm-6'>
<div class='well'>
<h2>E-MAIL US</h2>
<form method='post' name='eBformName'>
<fieldset>
<?php echo $email_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Your eMail</span>
<input type='email' name='email_address' placeholder='example@domain.com' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<?php echo $subjectfor_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>Subject</span>
<input type='text' name='subjectfor' placeholder='Subject' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<?php echo $messagepre_error;  ?>
<div class='form-group'>
<label>Your Message</label>
<textarea class='form-control' name='messagepre' placeholder='Your Message'></textarea>
</div>
<?php echo $captcha_error;  ?>
<div class='form-group'>
<?php
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
echo "<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
?>
</div>
<input class='form-control' type='text' name='answer' placeholder='Enter captcha here' required />
<div class='buttons-set'><button type='submit' name='contact_button' title='Submit' class='button submit'> <span> Submit </span> </button></div>
</fieldset>
</form>
</div>
</div>

<div class='col-xs-12 col-sm-6'>
<div class='wall'>
<h2>SITE LOCATION</h2>
<?php
include_once(eblogin.'/registration-page.php');
$social = new ebapps\login\registration_page();
$social -> site_location();
?>
<?php if($social->eBData >= 1){ foreach($social->eBData as $val){ extract($val); ?>
<?php if(!empty($business_name)){echo "<p><i class='fa fa-building fa-lg' aria-hidden='true'> $business_name</i></p>"; } ?>
<?php }} ?>
<?php include_once (eblayout.'/a-common-google-map.php'); ?>
</div>
</div>

</div>
</div>
</section>