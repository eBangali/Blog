<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
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
        <h2 title='Admin Signup!'>Admin Signup!</h2>
      </div>
      <?php include_once (eblogin.'/registration-page.php'); ?>
      <?php include_once (ebHashKey.'/hash-password.php'); ?>
<script>
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
$full_name_error = $code_mobile_error = $email_error = $ebusername_error = $eBNewpassword_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['register']))
{
    /* ===== Full name ===== */
    if (empty($_POST["full_name"])) {
        $full_name_error = "<b class='text-warning'>Name required</b>";
        $error = 1;
    } elseif (!preg_match("/^[A-Za-z.,\-\ ]{3,32}$/", $_POST["full_name"])) {
        $full_name_error = "<b class='text-warning'>Only letters, dot, comma, dash and spaces allowed (3–32 chars).</b>";
        $error = 1;
    } else {
        $full_name_filtered = $sanitization->test_input($_POST["full_name"]);
    }

    /* ===== Country ===== */
    $countryNameWhenSignup = "";
    $countryCode = "";
    if (!empty($_POST['selectCountryVal'])) {
        $selectCountryVal = $_POST['selectCountryVal'];
        $countryOfSignup = new ebapps\login\registration_page();
        $countryOfSignup->selectedCountryAndCodeWhenSignup($selectCountryVal);

        if($countryOfSignup->eBData) {
            foreach($countryOfSignup->eBData as $valcountryOfSignup) {
                $countryNameWhenSignup = $valcountryOfSignup['country_name'];
                $countryCode = preg_replace('/\D/', '', $valcountryOfSignup['country_code']); // numeric only
            }
        }
    }

    /* ===== Mobile ===== */
    if (empty($_POST["code_mobile"])) {
        $code_mobile_error = "<b class='text-warning'>Mobile number required</b>";
        $error = 1;
    } elseif (!preg_match("/^[0-9]{3,16}$/", $_POST["code_mobile"])) {
        $code_mobile_error = "<b class='text-warning'>Only numbers allowed (3–16 digits).</b>";
        $error = 1;
    } elseif ($countryCode && strpos($_POST["code_mobile"], $countryCode) !== 0) {
        $code_mobile_error = "<b class='text-warning'>Mobile must start with country code $countryCode</b>";
        $error = 1;
    } else {
        $code_mobile_filtered = $sanitization->test_input($_POST["code_mobile"]);
    }

    /* ===== Email ===== */
    if (empty($_POST['email'])) {
        $email_error = "<b class='text-warning'>Email required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9\.-]+)\.([a-z]{2,6})(\.[a-z]{2,6})*$/", $_POST['email'])) {
        $email_error = "<b class='text-warning'>Invalid email format.</b>";
        $error = 1;
    } elseif ($sanitization->validEmailDnsAndIP($_POST['email']) === false) {
        $email_error = "<b class='text-warning'>Invalid eMail Domain or DNS or IP</b>";
        $error = 1;
    } elseif ($sanitization->validEmailOnlyGmail($_POST['email']) === false) {
        $email_error = "<b class='text-warning'>Sorry only @gmail.com allowed!!!</b>";
        $error = 1;
    } else {
        $email_filtered = $sanitization->onlyUsernameInputForLowercase($_POST['email']);
    }

    /* ===== Username ===== */
    if (empty($_POST["ebusername"])) {
        $ebusername_error = "<b class='text-warning'>Username required</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\-]{1,56}$/", $_POST["ebusername"])) {
        $ebusername_error = "<b class='text-warning'>Only letters, numbers and dash allowed (max 56 chars).</b>";
        $error = 1;
    } else {
        $ebusername_filtered = $sanitization->onlyUsernameInputForLowercase($_POST["ebusername"]);
    }

    /* ===== Password ===== */
    if (empty($_POST["eBNewpassword"])) {
        $eBNewpassword_error = "<b class='text-warning'>Password required</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9@\#\%\^\&\*\(\)\!\.\,\_\-\+\=\[\]\~\`\:\/\?\|]{1,56}$/", $_POST["eBNewpassword"])) {
        $eBNewpassword_error = "<b class='text-warning'>Password can contain letters, numbers & special chars (max 56 chars).</b>";
        $error = 1;
    } else {
        $eBNewpassword_filtered = $sanitization->test_input($_POST["eBNewpassword"]);
    }

    /* ===== If all good -> save ===== */
    if ($error == 0) {
        $hashObj = new ebapps\hashpassword\hashPassword();

        $hashedPassword = $hashObj->hashPassword($eBNewpassword_filtered);
        $emailHash = $hashObj->hashEmail();

        $signup_date = date("Y-m-d H:i:s");
        $user_ip_address = $_SERVER['REMOTE_ADDR'];

        $userNewUser = new ebapps\login\registration_page();
        $userNewUser->registration_admin_once_and_only(
            $ebusername_filtered, 
            $hashedPassword, 
            $email_filtered, 
            $emailHash, 
            $full_name_filtered, 
            $signup_date, 
            $user_ip_address, 
            $code_mobile_filtered, 
            $countryNameWhenSignup
        );
    }
}
?>
<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
$ip_user=$_SERVER['HTTP_CLIENT_IP'];
//Is it a proxy address
}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
$ip_user=$_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
$ip_user=$_SERVER['REMOTE_ADDR'];
}
?>
      <div class='well'> 
        <form method='post'>
          <fieldset class='group-select'>
            <input type='hidden' name='signup_date' value='<?php echo date('r'); ?>'>
            <input type='hidden' name='user_ip_address' value='<?php echo $ip_user ?>'>
            <?php echo $full_name_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon'>Full Name</span>
            <input type='text' name='full_name' placeholder='Your name' class='form-control' required>
            </div>
            <?php echo $email_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon'>eMail</span>
            <input type='text' name='email' placeholder='username@gmail.com' class='form-control' required>
            </div>
            <?php echo $ebusername_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon'>Username</span>
            <input type='text' name='ebusername' placeholder='username' class='form-control' required>
            </div>
            <?php echo $eBNewpassword_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon'>Password</span>
            <input type='password' name='eBNewpassword' placeholder='password' class='form-control' required>
            </div>
            <div class='input-group'>
            <span class='input-group-addon'>Country: </span>
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
            <span class='input-group-addon'>Mobile with country code</span>
            <input class='form-control' id='selectedCountry' type='tel' name='code_mobile' required>
            </div>        
            <div class='buttons-set'>
              <button type='submit' name='register' title='Signup' class='button submit'> <span> Signup </span></button>
            </div>
          </fieldset>
        </form>
        <!--End main-container --> 
      </div>
    </div>
    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>

    </div>
  </div>
</div>
<?php 
include_once (eblayout.'/a-common-footer-admin.php'); 
exit();
?>