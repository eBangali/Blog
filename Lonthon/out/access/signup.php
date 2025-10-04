<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
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
<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'>
    </div>
    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
    <div class='well'>
        <h2 title='Sign Up'>Sign Up</h2>
      </div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php include_once (ebHashKey.'/hash-password.php'); ?>
<script language='javascript' type='text/javascript'>
$(document).ready(function()
{
  $("#selectCountry").change(function()
  {
    var pic_name = $(this).val();
	if(pic_name != '')  
	 {
	  $.ajax
	  ({
	     type: "POST",
		 url: "access-to-get-country-code.php",
		 data: "pic_name="+ pic_name,
		 success: function(data)
		 {
		   $("#selectedCountry").val(data);
		 }
	  });
	 }
	return false;
  });
});
</script>
<?php
/* Initialize validation */
$error = 0;
$full_name_error = "";
$email_error = "";
$code_mobile_error = "";
$ebusername_error = "";
$ebpassword_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

/* Get user IP */
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_user_filtered = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_user_filtered = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_user_filtered = $_SERVER['REMOTE_ADDR'];
}

/* Signup date */
$signup_date_filtered = date("Y-m-d H:i:s");

if (isset($_POST['register']))
{
    /* Full name */
    if (empty($_POST["full_name"])) {
        $full_name_error = "<b class='text-warning'>Name required</b>";
        $error = 1;
    } 
    elseif(!preg_match("/^[A-Za-z.,\-\ ]{3,32}$/", $_POST["full_name"])) {
        $full_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else {
        $full_name_filtered = $sanitization->test_input($_POST["full_name"]);
    }

    /* eMail */
    if (empty($_POST['email'])) {
        $email_error = "<b class='text-warning'>Email required.</b>";
        $error = 1;
    }
    elseif ($sanitization->validEmail($_POST['email']) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Formatting</b>";
        $error = 1;
    }
    elseif (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9\.-]+)\.([a-z]{2,6})(\.[a-z]{2,6})*$/", $_POST['email'])) {
        $email_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    elseif ($sanitization->validEmailDnsAndIP($_POST['email']) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Domain or DNS or IP</b>";
        $error = 1;
    }
    elseif ($sanitization->validEmailOnlyGmail($_POST['email']) === false) {
        $email_error = "<b class='text-warning'>Sorry only @gmail.com allowed!!!</b>";
        $error = 1;
    }
    else {
        $email_filtered = $sanitization->onlyUsernameInputForLowercase($_POST['email']);
    }

    /* selectCountryVal */
    if (isset($_POST['selectCountryVal'])) {
        $selectCountryVal = $_POST['selectCountryVal'];
        $countryOfSignup = new ebapps\login\registration_page();
        $countryOfSignup->selectedCountryAndCodeWhenSignup($selectCountryVal);

        if ($countryOfSignup->eBData) {
            foreach ($countryOfSignup->eBData as $valcountryOfSignup) {
                $countryNameWhenSignup_filtered = $valcountryOfSignup['country_name'];
                $countryCode = intval(substr($valcountryOfSignup['country_code'], 0, 1));
            }
        }
    }

    $codeCheckInMobile = intval(substr($_POST["code_mobile"], 0, 1));

    /* Mobile */
    if (empty($_POST["code_mobile"])) {
        $code_mobile_error = "<b class='text-warning'>Mobile number required</b>";
        $error = 1;
    } 
    elseif (!preg_match("/^[0-9]{3,16}$/", $_POST["code_mobile"])) {
        $code_mobile_error = "<b class='text-warning'>Mobile Number?</b>";
        $error = 1;
    }
    elseif ($codeCheckInMobile != $countryCode) {
        $code_mobile_error = "<b class='text-warning'>Country Code?</b>";
        $error = 1;
    }
    else {
        $code_mobile_filtered = $sanitization->test_input($_POST["code_mobile"]);
    }

    /* ebusername */
    if (empty($_POST['ebusername'])) {
        $ebusername_error = "<b class='text-warning'>Username required.</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[a-zA-Z0-9\-]{1,56}$/", $_POST['ebusername'])) {
        $ebusername_error = "<b class='text-warning'>Lowercase letters, dash and numbers are allowed.</b>";
        $error = 1;
    }
    else {
        $ebusername_filtered = $sanitization->onlyUsernameInputForLowercase($_POST['ebusername']);
    }

    /* ebpassword */
    if (empty($_POST['ebpassword'])) {
        $ebpassword_error = "<b class='text-warning'>ebpassword required.</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $_POST['ebpassword'])) {
        $ebpassword_error = "<b class='text-warning'>Password can contain English letters, numbers, dashes, and special characters. Max 56 characters.</b>";
        $error = 1;
    }
    else {
        $ebpassword_filtered = $sanitization->test_input($_POST['ebpassword']);
    }

    /* Submission form */
    if ($error == 0) {
        $haPass = new ebapps\hashpassword\hashPassword();
        $passWithHash_filtered = $haPass->hashPassword($ebpassword_filtered);

        /*** Generate random hash ***/ 
        $generate_email_hash_formate = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $generated_new_email_hash = ''; 
        for ($i = 0; $i < 40; $i++) {
            $generated_new_email_hash .= $generate_email_hash_formate[rand(0, strlen($generate_email_hash_formate) - 1)];
        }
        $hash_filtered = $generated_new_email_hash;

        /*** Signup ***/
        $userSignup = new ebapps\login\registration_page();
        $userSignup->registration(
            $full_name_filtered, 
            $email_filtered, 
            $code_mobile_filtered, 
            $ebusername_filtered, 
            $passWithHash_filtered, 
            $hash_filtered, 
            $signup_date_filtered, 
            $ip_user_filtered, 
            $countryNameWhenSignup_filtered
        );
    }
}
?>

<div class='well'>
<form method='post'>
          <fieldset class='group-select'>
            <input type='hidden' name='referrer' value='<?php if(isset($_SESSION['omrebusername'])){ echo $_SESSION['omrebusername']; } ?>'>
            <input type='hidden' name='signup_date' value='<?php echo date('r'); ?>' />
            <input type='hidden' name='user_ip_address' value='<?php echo $ip_user; ?>' />
            <?php echo $full_name_error;  ?>          
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Full name:</span>
            <input type='text' name='full_name' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            <?php echo $email_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>eMail:</span>
            <input type='text' name='email' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Country: </span>
            <select id='selectCountry' class='form-control' name='selectCountryVal'>
            <option>Select Country</option>
            <?php
            $country = new ebapps\login\registration_page();
            $country->select_country_id();
            ?>
            </select>
            </div>
            
            <?php echo $code_mobile_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Mobile:</span>
            <input class='form-control' id='selectedCountry' type='number' name='code_mobile' required  autofocus />
            </div>
            
            <?php echo $ebusername_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Username:</span>
            <input type='text' name='ebusername' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            
            <?php echo $ebpassword_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Password:</span>
            <input type='password' name='ebpassword' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            
            <div class='buttons-set'>
              <button type='submit' name='register' title='SIGN UP' class='button submit'> <span> SIGN UP </span> </button>
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