<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebaccess."/access-permission-online-minimum.php"); ?>
<?php if (isset($_SESSION['ebusername'])){ ?>
<?php
/* Initialize valitation */
$error = 0;
$email_error = "";
$email_filtered = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php');
include_once(ebHashKey.'/hash-password.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['InviteAFriend']))
{
    /* eMail */
    if (empty($_POST['email']))
    {
        $email_error = "<b class='text-warning'>Email required.</b>";
        $error = 1;
    }
    /* Check eMail Formatting */
    elseif ($sanitization->validEmail($_POST['email']) === false)
    {
        $email_error = "<b class='text-warning'>Invalid eMail Formatting</b>";
        $error = 1;
    }
    /* valitation email */
    elseif (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z]+\.)+[a-z]{2,6}$/", $_POST['email']))
    {
        $email_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    /* Check eMail DNS, Domain and IP */
    elseif ($sanitization->validEmailDnsAndIP($_POST['email']) === false)
    {
        $email_error = "<b class='text-warning'>Invalid eMail Domain or DNS or IP</b>";
        $error = 1;
    }
    /* Check eMail only Gmail */
    elseif ($sanitization->validEmailOnlyGmail($_POST['email']) === false)
    {
        $email_error = "<b class='text-warning'>Only @gmail.com allowed!!!</b>";
        $error = 1;
    }
    else
    {
        $email_filtered = $sanitization->onlyUsernameInputForLowercase($_POST['email']);
    }

    /* Submition form */
    if ($error == 0)
    {
        include_once(eblogin.'/registration-page.php');
        $inviteAFriend = new ebapps\login\registration_page();
        $inviteAFriend->inviteAFriend($email_filtered);
    }
}
?>
<div class='well'>
<form method='post'>
<fieldset class='group-select'>
<?php echo $email_error;  ?>
<div class='input-group'>
<span class='input-group-addon' id='sizing-addon2'>eMail: </span>
<input type='text' name='email' placeholder='Enter email address' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
</div>
<div class='buttons-set'>
<button type='submit' name='InviteAFriend' title='Invite' class='button submit'> <span> Invite </span> </button>
</div>
</fieldset>
</form>
</div>
<?php } ?>