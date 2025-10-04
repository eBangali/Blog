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
<?php include_once (ebaccess."/access-permission-online-minimum.php"); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='Account Settings'>Account Settings</h2>
</div>
<?php include_once (ebHashKey.'/hash-password.php'); ?>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
/* Initialize validation */
$error = 0;
$full_name_error ="";
$gender_error ="";
$mobile_error ="";
$email_error ="";
$position_names_error ="";
$address_line_1_error ="";
$address_line_2_error ="";
$city_town_error ="";
$state_province_region_error ="";
$postal_code_error ="";
$paypalid_error ="";
$bkashid_error ="";
$facebook_link_error ="";
$twitter_link_error ="";
$github_link_error ="";
$linkedin_link_error ="";
$pinterest_link_error ="";
$youtube_link_error ="";
$instagram_link_error ="";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if (isset($_POST['updateregister']))
{
    /* full_name */
    $full_name_filtered = "";
    if (empty($_POST["full_name"]))
    {
        $full_name_error = "<b class='text-warning'>Name required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^([A-Za-z0-9\?\.\,\-\ ]{3,59})$/", $_POST["full_name"]) 
        || !$sanitization->checkDisallowedHTMLTagsAndValues($_POST['full_name']))
    {
        $full_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else
    {
        $full_name_filtered = $sanitization->test_input($_POST["full_name"]);
    }

    /* gender */
    $gender_filtered = "";
    if (empty($_POST["gender"]))
    {
        $gender_error = "<b class='text-warning'>Gender required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^([A-Za-z]+)$/", $_POST["gender"]))
    {
        $gender_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else
    {
        $gender_filtered = $sanitization->test_input($_POST["gender"]);
    }

    /* mobile */
    $mobile_filtered = "";
    if (empty($_POST["mobile"]))
    {
        $mobile_error = "<b class='text-warning'>Mobile number required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[0-9]{5,15}$/", $_POST["mobile"]))
    {
        $mobile_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    }
    else
    {
        $mobile_filtered = $sanitization->test_input($_POST["mobile"]);
    }

    /* email */
    $email_filtered = "";
    if (empty($_POST["email"]))
    {
        $email_error = "<b class='text-warning'>Email required</b>";
        $error = 1;
    }
    elseif (!preg_match("/^[A-Za-z0-9._]+@[a-z0-9.\-]{1,16}[a-z]{3,4}$/", $_POST["email"]) 
        || $sanitization->validEmail($_POST["email"]) === false
        || $sanitization->validEmailOnlyGmail($_POST["email"]) === false)
    {
        $email_error = "<b class='text-warning'>Invalid Gmail address</b>";
        $error = 1;
    }
    else
    {
        $email_filtered = $sanitization->onlyUsernameInputForLowercase($_POST["email"]);
    }

    /* position_names */
    $position_names_filtered = "";
    if (!empty($_POST["position_names"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\ ]{3,59})$/", $_POST["position_names"]) 
            || !$sanitization->checkDisallowedHTMLTagsAndValues($_POST['position_names']))
        {
            $position_names_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $position_names_filtered = $sanitization->test_input($_POST["position_names"]);
        }
    }

    /* address_line_1 */
    $address_line_1_filtered = "";
    if (!empty($_POST["address_line_1"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{3,59})$/", $_POST["address_line_1"]) 
            || !$sanitization->checkDisallowedHTMLTagsAndValues($_POST['address_line_1']))
        {
            $address_line_1_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $address_line_1_filtered = $sanitization->test_input($_POST["address_line_1"]);
        }
    }

    /* address_line_2 */
    $address_line_2_filtered = "";
    if (!empty($_POST["address_line_2"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{3,59})$/", $_POST["address_line_2"]) 
            || !$sanitization->checkDisallowedHTMLTagsAndValues($_POST['address_line_2']))
        {
            $address_line_2_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $address_line_2_filtered = $sanitization->test_input($_POST["address_line_2"]);
        }
    }

    /* city_town */
    $city_town_filtered = "";
    if (!empty($_POST["city_town"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{3,59})$/", $_POST["city_town"]))
        {
            $city_town_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $city_town_filtered = $sanitization->test_input($_POST["city_town"]);
        }
    }

    /* state_province_region */
    $state_province_region_filtered = "";
    if (!empty($_POST["state_province_region"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{3,59})$/", $_POST["state_province_region"]))
        {
            $state_province_region_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $state_province_region_filtered = $sanitization->test_input($_POST["state_province_region"]);
        }
    }

    /* postal_code */
    $postal_code_filtered = "";
    if (!empty($_POST["postal_code"]))
    {
        if (!preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{3,59})$/", $_POST["postal_code"]))
        {
            $postal_code_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $postal_code_filtered = $sanitization->test_input($_POST["postal_code"]);
        }
    }

    /* paypalid */
    $paypalid_filtered = "";
    if (!empty($_POST["paypalid"]))
    {
        if (!preg_match("/^[A-Za-z0-9._]+@[a-z0-9.\-]{1,16}[a-z]{3,4}$/", $_POST["paypalid"]))
        {
            $paypalid_error = "<b class='text-warning'>Some characters are not allowed.</b>";
            $error = 1;
        }
        else
        {
            $paypalid_filtered = $sanitization->test_input($_POST["paypalid"]);
        }
    }

    /* bkashid */
    $bkashid_filtered = "";
    if (!empty($_POST["bkashid"]))
    {
        if (!preg_match("/^[0-9]{11,11}$/", $_POST["bkashid"])) 
        {
        $bkashid_error = "<b class='text-warning'>bKash ID must be between 11 digits.</b>";
        $error = 1;
        }
        else
        {
            $bkashid_filtered = $sanitization->test_input($_POST["bkashid"]);
        }
    }

    /* social links */
    $facebook_link_filtered = !empty($_POST["facebook_link"]) && preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["facebook_link"]) ? $sanitization->test_input($_POST["facebook_link"]) : "";
    $twitter_link_filtered  = !empty($_POST["twitter_link"])  && preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["twitter_link"])  ? $sanitization->test_input($_POST["twitter_link"])  : "";
    $github_link_filtered   = !empty($_POST["github_link"])   && preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["github_link"])   ? $sanitization->test_input($_POST["github_link"])   : "";
    $linkedin_link_filtered = !empty($_POST["linkedin_link"]) && preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["linkedin_link"]) ? $sanitization->test_input($_POST["linkedin_link"]) : "";
    $pinterest_link_filtered= !empty($_POST["pinterest_link"])&& preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["pinterest_link"]) ? $sanitization->test_input($_POST["pinterest_link"]) : "";
    $youtube_link_filtered  = !empty($_POST["youtube_link"])  && preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["youtube_link"])  ? $sanitization->test_input($_POST["youtube_link"])  : "";
    $instagram_link_filtered= !empty($_POST["instagram_link"])&& preg_match("/^([a-zA-Z0-9\@\,\.\/\+\?\-\=\_\-]{3,255})$/", $_POST["instagram_link"]) ? $sanitization->test_input($_POST["instagram_link"]) : "";

    /* Submission form */
    if($error == 0)
    {
        $update = new ebapps\login\registration_page();
        $update->update_account_information(
            $email_filtered,
            $full_name_filtered,
            $gender_filtered,
            $mobile_filtered,
            $position_names_filtered,
            $address_line_1_filtered,
            $address_line_2_filtered,
            $city_town_filtered,
            $state_province_region_filtered,
            $postal_code_filtered,
            $paypalid_filtered,
            $bkashid_filtered,
            $facebook_link_filtered,
            $twitter_link_filtered,
            $github_link_filtered,
            $linkedin_link_filtered,
            $pinterest_link_filtered,
            $youtube_link_filtered,
            $instagram_link_filtered
        );
    }
}
?>

<div class='well'>
<?php
$obj = new ebapps\login\registration_page();
$obj->update_account_info_read();
if($obj->eBData)
{
foreach($obj->eBData as $val)
{
extract($val);
$editAcc ="<form method='post'>"; 
$editAcc .="<fieldset class='group-select'>";
//
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Picture: </span>";
if(!empty($profile_picture_link) and file_exists(docRoot.$profile_picture_link))
{
$editAcc .="<img class='rounded float-right' alt='$full_name' title='$full_name' height='120' src='$profile_picture_link' />";
}
else
{
$editAcc .="<img class='rounded float-right' alt='$full_name' title='$full_name' height='120' src='".themeResource."/images/person.jpg' />";
}
$editAcc .="</div>";
$editAcc .= "<div class='input-group'><span class='input-group-addon' id='sizing-addon2'><b>Upload Picture <a href='".outAccessLink."/access-image-upload-croper.php'><button type='button' name='upload_image' class='button submit' title='Upload Picture'><span> Upload Picture</span></button></a></b></div>";
//
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Username: </span><span class='form-control' aria-describedby='sizing-addon2'>$ebusername</span></div>";
//
if(!empty($position_names))
{
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Designation: </span><span class='form-control' aria-describedby='sizing-addon2'>".$position_names."</span></div>";
}
//
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Power: </span><span class='form-control' aria-describedby='sizing-addon2'>$member_level</span></div>";
//
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Type: </span><span class='form-control' aria-describedby='sizing-addon2'>".$account_type."</span></div>";
//
$editAcc .="$full_name_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Full Name:</span><input type='text' name='full_name' value='$full_name' placeholder='Full name' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>";
//
$editAcc .="$gender_error";
$editAcc .="<div class='input-group'>";
$editAcc .="<span class='input-group-addon' id='sizing-addon2'>Gender:</span>";
$editAcc .="<select class='form-control' name='gender'>";
if(!empty($gender))
{
$editAcc .="<option selected value='$gender'>".$gender."</option>";
}
$editAcc .="<option value='Male'>Male</option>";
$editAcc .="<option value='Female'>Female</option>";
$editAcc .="</select>";
$editAcc .="</div>";
//
$editAcc .="$email_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>eMail ID:</span><input type='email' name='email' value='$email' placeholder='eMail' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>";

if($active == 0)
{
$editAcc .= "<b>eMail Not Verified</b>";
$editAcc .= " ";
$editAcc .= "<a href='".outAccessLink."/access-verify-re-send.php'><button type='button' class='button submit' title='Verify eMail'><span> Verify eMail </span></button></a>";
}
if($active == 1)
{
$editAcc .= "<b>Verified eMail</b>";
}
$editAcc .="$mobile_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Mobile: </span><input type='text' name='mobile' value='$mobile' placeholder='Mobile No' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>";
if($mobileactive == 0)
{
$editAcc .= "<b>Mobile Not Verified <a href='".outAccessLink."/access-verify-mobile-number.php'><button type='button' class='button submit' title='Verify Mobile.'><span> Verify Mobile </span></button></a></b>";
}
if($mobileactive == 1)
{
$editAcc .= "<b>Verified Mobile</b>";
}
$editAcc .="<br>$position_names_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Level:</span><input type='text' name='position_names' value='$position_names' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$address_line_1_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Address 1: </span><input type='text' name='address_line_1' value='$address_line_1' placeholder='Address' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$address_line_2_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Address 2:</span><input type='text' name='address_line_2' value='$address_line_2' placeholder='Address' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$city_town_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>City: </span><input type='text' name='city_town' value='$city_town' placeholder='City/Town' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$state_province_region_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>State: </span><input type='text' name='state_province_region' value='$state_province_region' placeholder='State/Province/Region' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$postal_code_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Postal Code: </span><input type='text' name='postal_code' value='$postal_code' placeholder='Postal code' class='form-control' aria-describedby='sizing-addon2'></div>";
//
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Country: </span><span class='form-control' aria-describedby='sizing-addon2'>$country</span></div>";
//
if($address_verified == 0)
{
$editAcc .= "<div class='input-group'><span class='input-group-addon' id='sizing-addon2'><b>Address Not Verified <a href='".outAccessLink."/access-verify-address.php'><button type='button' class='button submit' title='Verify Address'><span> Verify Address</span></button></a></b></div>";
}
if($address_verified == 1)
{
$editAcc .= "<div class='input-group'><span class='input-group-addon' id='sizing-addon2'><b>Verified Address</b><span></div>";
}
if($active ==1 and $mobileactive == 1 and $address_verified == 1 and $member_level <=3)
{
$editAcc .= "<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Request for POS: </span><a href='".outAccessLink."/upgrade-your-access-levels-for-pos.php'><button type='button' class='button submit' title='Request for POS'><span>Request for POS</span></button></a></div>";
}
$editAcc .="OMR: $omrusername ";
$editAcc .="$paypalid_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>PayPal ID : </span><input type='email' name='paypalid' value='$paypalid' placeholder='PayPal ID' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$bkashid_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>bKash ID : </span><input type='text' name='bkashid' value='$bkashid' placeholder='bKash ID' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$facebook_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>FaceBook: </span><input type='text' name='facebook_link' value='$facebook_link' placeholder='facebook.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$twitter_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Twitter: </span><input type='text' name='twitter_link' value='$twitter_link' placeholder='twitter.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$github_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>GitHub: </span><input type='text' name='github_link' value='$github_link' placeholder='github.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$linkedin_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Linkedin: </span><input type='text' name='linkedin_link' value='$linkedin_link' placeholder='linkedin.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$pinterest_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Pinterest: </span><input type='text' name='pinterest_link' value='$pinterest_link' placeholder='pinterest.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$youtube_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Youtube: </span><input type='text' name='youtube_link' value='$youtube_link' placeholder='youtube.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";
$editAcc .="$instagram_link_error";
$editAcc .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Instagram: </span><input type='text' name='instagram_link' value='$instagram_link' placeholder='instagram.com/username/' class='form-control' aria-describedby='sizing-addon2'></div>";

$editAcc .="<div class='buttons-set'>";
$editAcc .="<button type='submit' name='updateregister' title='Update' class='button submit'>Update</button>";
$editAcc .="</div>"; 
$editAcc .="<div class='buttons-set'><a href='".outAccessLink."/access-change-passsword.php'><button type='button' class='button submit' title='Change Password'><span> Change Password </span></button></a></div>";  
$editAcc .="</fieldset>";
$editAcc .="</form>";
echo $editAcc;  
}
}
?>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>