<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php
if(empty($_SESSION['ebusername']))
{
?>
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
    <div class='well'>
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
/* Initialize valitation */
$error = 0;
$full_name_error = "";
$code_mobile_error = "";
$ebusername_error = "";
$ebpassword_error = "";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if (isset($_REQUEST['register']))
{
extract($_REQUEST);
/* Full name */
if (empty($_REQUEST["full_name"]))
{
$full_name_error = "<b class='text-warning'>Name required</b>";
$error =1;
} 

elseif(!preg_match("/^[[A-Za-z.,\-\ ]{3,32}$/",$full_name))
{
$full_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
$error =1;
}
else 
{
$full_name = $sanitization->test_input($_POST["full_name"]);
}

/* selectCountryVal */
if (isset($_REQUEST['selectCountryVal']))
{
$selectCountryVal = $_POST['selectCountryVal'];
$countryOfSignup = new ebapps\login\registration_page();
$countryOfSignup->selectedCountryAndCodeWhenSignup($selectCountryVal);
if($countryOfSignup->eBData)
{
foreach($countryOfSignup->eBData as $valcountryOfSignup)
{
extract($valcountryOfSignup);
$countryNameWhenSignup = $country_name;
$countryCode = intval(substr($country_code, 0, 1)); 
}
}
}
$codeCheckInMobile = intval(substr($_POST["code_mobile"], 0, 1));
/* Mobile */
if (empty($_REQUEST["code_mobile"]))
{
$code_mobile_error = "<b class='text-warning'>Mobile number required</b>";
$error =1;
} 

elseif (!preg_match("/^[0-9]{3,16}$/",$code_mobile))
{
$code_mobile_error = "<b class='text-warning'>Some characters are not allowed.</b>";
$error =1;
}
elseif ($codeCheckInMobile != $countryCode)
{
$code_mobile_error = "<b class='text-warning'>Country Code?</b>";
$error =1;
}
else 
{
$code_mobile = $sanitization->test_input($_POST["code_mobile"]);
}
/* ebusername */
if (empty($_REQUEST['ebusername']))
{
$ebusername_error = "<b class='text-warning'>Username Required.</b>";
$error =1;
}
/* valitation ebusername Tested allow (zakir)(zakir333)(zakir_9us2)*/
elseif(!preg_match("/^[a-zA-Z0-9\-]{1,56}$/",$ebusername))
{
$ebusername_error = "<b class='text-warning'>Lowercase letters, dash and numbers are allowed.";
$error =1;
}
else
{
$ebusername = $sanitization->onlyUsernameInputForLowercase($_POST['ebusername']);
}
/* ebpassword */
if (empty($_REQUEST['ebpassword']))
{
$ebpassword_error = "<b class='text-warning'>Password Required.</b>";
$error =1;
}

else
{
$ebpassword = $sanitization->test_input($_POST['ebpassword']);
}
/* Submition form */
if($error ==0)
{
extract($_REQUEST);
//
$haPasswordInvited = new ebapps\hashpassword\hashpassword();
$passeBhash = $haPasswordInvited -> hashPassword($ebpassword);
$ebpassword = $passeBhash;
/*** ***/ 
$userSignUpInvited = new ebapps\login\registration_page();
$userSignUpInvited->registrationInvitedSignup($full_name, $code_mobile, $ebusername, $ebpassword, $email, $signup_date, $user_ip_address, $countryNameWhenSignup);
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
        <form method='post'>
          <fieldset class='group-select'>
            <input type='hidden' name='signup_date' value='<?php echo date('r'); ?>' />
            <input type='hidden' name='user_ip_address' value='<?php echo $ip_user; ?>' />
            <input class='form-control' type='hidden' name='email' value='<?php echo $_GET['email']; ?>' />
            <?php echo $full_name_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Full Name </span>
            <input type='text' name='full_name' placeholder='Your name' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
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
            <span class='input-group-addon' id='sizing-addon2'>Mobile </span>
            <input class='form-control' id='selectedCountry' type='text' name='code_mobile' required  autofocus />
            </div>
            
            <?php echo $ebusername_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Username: </span>
            <input type='text' name='ebusername' placeholder='Username' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            
            <?php echo $ebpassword_error;  ?>
            <div class='input-group'>
            <span class='input-group-addon' id='sizing-addon2'>Password: </span>
            <input type='password' name='ebpassword' placeholder='Password' class='form-control' aria-describedby='sizing-addon2' required  autofocus>
            </div>
            
            <div class='buttons-set'>
              <button type='submit' name='register' title='Signup' class='button submit'> <span> Signup </span> </button>
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
<?php
}
?>
