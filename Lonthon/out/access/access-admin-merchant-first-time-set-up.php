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
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='Admin Business Info'>Admin Business Info</h2>
</div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

/* Initialize validation */
$error = 0;
$business_name_error = $business_vat_tax_gst_error = $business_title_one_error = "";
$business_title_two_error = $business_full_address_error = $business_city_town_error = "";
$business_state_province_region_error = $business_postal_code_error = $business_country_error = "";
$business_geolocation_latitude_error = $business_geolocation_longitude_error = "";
$cash_on_delivery_distance_meter_error = "";

if (isset($_POST['BusinessSettings'])) {

    /* business_name */
    if (empty($_POST['business_name'])) {
        $business_name_error = "<b class='text-warning'>Legal company name or Brand name required.</b>";
        $error = 1;
    } 
    elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,24}$/", $_POST['business_name']))
    {
    $business_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
    $error = 1;
    } 
    else {
        $business_name_filtered = $sanitization->test_input($_POST['business_name']);
    }

    /* business_vat_tax_gst */
    if (empty($_POST['business_vat_tax_gst'])) {
        $business_vat_tax_gst_error = "<b class='text-warning'>VAT/GST ID required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,24}$/", $_POST['business_vat_tax_gst'])) {
        $business_vat_tax_gst_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_vat_tax_gst_filtered = $sanitization->test_input($_POST['business_vat_tax_gst']);
    }

    /* business_title_one */
    if (empty($_POST['business_title_one'])) {
        $business_title_one_error = "<b class='text-warning'>Business title required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59}$/", $_POST['business_title_one'])) {
        $business_title_one_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_title_one_filtered = $sanitization->test_input($_POST['business_title_one']);
    }

    /* business_title_two */
    if (empty($_POST['business_title_two'])) {
        $business_title_two_error = "<b class='text-warning'>Business subtitle required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59}$/", $_POST['business_title_two'])) {
        $business_title_two_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_title_two_filtered = $sanitization->test_input($_POST['business_title_two']);
    }

    /* business_full_address */
    if (empty($_POST['business_full_address'])) {
        $business_full_address_error = "<b class='text-warning'>Business full address required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\/\ ]{3,120}$/", $_POST['business_full_address'])) {
        $business_full_address_error = "<b class='text-warning'>Invalid address format.</b>";
        $error = 1;
    } else {
        $business_full_address_filtered = $sanitization->test_input($_POST['business_full_address']);
    }

    /* business_city_town */
    if (empty($_POST['business_city_town'])) {
        $business_city_town_error = "<b class='text-warning'>City/Town required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\-\ ]{2,24}$/", $_POST['business_city_town'])) {
        $business_city_town_error = "<b class='text-warning'>Invalid City/Town.</b>";
        $error = 1;
    } else {
        $business_city_town_filtered = $sanitization->test_input($_POST['business_city_town']);
    }

    /* business_state_province_region */
    if (empty($_POST['business_state_province_region'])) {
        $business_state_province_region_error = "<b class='text-warning'>State/Province/Region required.</b>";
        $error = 1;
    } 
    elseif (!preg_match("/^[a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,24}$/", $_POST['business_state_province_region']))
    {
    $business_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
    $error = 1;
    }
    else {
        $business_state_province_region_filtered = $sanitization->test_input($_POST['business_state_province_region']);
    }

    /* business_postal_code */
    if (empty($_POST['business_postal_code'])) {
        $business_postal_code_error = "<b class='text-warning'>Postal code required.</b>";
        $error = 1;
    } elseif (!preg_match("/^[a-zA-Z0-9\-]{3,13}$/", $_POST['business_postal_code'])) {
        $business_postal_code_error = "<b class='text-warning'>Invalid postal code.</b>";
        $error = 1;
    } else {
        $business_postal_code_filtered = $sanitization->test_input($_POST['business_postal_code']);
    }

    /* business_country */
    if (!empty($_POST['business_country'])) {
        $business_country_filtered = $sanitization->test_input($_POST['business_country']);
    }

    /* Latitude & Longitude */
    if (!empty($_POST['business_geolocation_latitude'])) {
        if (!preg_match("/^-?[0-9]{1,3}\.[0-9]{1,8}$/", $_POST['business_geolocation_latitude'])) {
            $business_geolocation_latitude_error = "<b class='text-warning'>Invalid latitude.</b>";
            $error = 1;
        } else {
            $business_geolocation_latitude_filtered = $sanitization->test_input($_POST['business_geolocation_latitude']);
        }
    }
    if (!empty($_POST['business_geolocation_longitude'])) {
        if (!preg_match("/^-?[0-9]{1,3}\.[0-9]{1,8}$/", $_POST['business_geolocation_longitude'])) {
            $business_geolocation_longitude_error = "<b class='text-warning'>Invalid longitude.</b>";
            $error = 1;
        } else {
            $business_geolocation_longitude_filtered = $sanitization->test_input($_POST['business_geolocation_longitude']);
        }
    }

    /* cash_on_delivery_distance_meter */
    if (!empty($_POST['cash_on_delivery_distance_meter'])) {
        if (!preg_match("/^[0-9]{1,6}$/", $_POST['cash_on_delivery_distance_meter'])) {
            $cash_on_delivery_distance_meter_error = "<b class='text-warning'>Invalid distance value.</b>";
            $error = 1;
        } else {
            $cash_on_delivery_distance_meter_filtered = $sanitization->test_input($_POST['cash_on_delivery_distance_meter']);
        }
    }

    /* If no errors -> update */
    if ($error === 0) {
        $user = new ebapps\login\registration_page();
        $user->update_merchant_business_details($business_name_filtered,$business_vat_tax_gst_filtered,$business_title_one_filtered,$business_title_two_filtered,$business_full_address_filtered,$business_city_town_filtered,$business_state_province_region_filtered,$business_postal_code_filtered,$business_country_filtered,$business_geolocation_latitude_filtered,$business_geolocation_longitude_filtered,$cash_on_delivery_distance_meter_filtered);
    }
}
?>
<div class='well'>
<?php
$obj = new ebapps\login\registration_page();
$obj->update_admin_business_info_read();
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updateBusinessInfo ="<form method='post'>"; 
$updateBusinessInfo .="<fieldset class='group-select'>";
$updateBusinessInfo .="Business Username: $business_username"; 
$updateBusinessInfo .="<br/>"; 
$updateBusinessInfo .="<div>$business_name_error</div>";
$updateBusinessInfo .="Legal Company/ Brand name:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_name' placeholder='' required autofocus value='$business_name' />";
$updateBusinessInfo .="<div>$business_vat_tax_gst_error</div>";
$updateBusinessInfo .="VAT/GST ID:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_vat_tax_gst' placeholder='' required autofocus value='$business_vat_tax_gst' />"; 
$updateBusinessInfo .="<div>$business_title_one_error</div>";
$updateBusinessInfo .="Business title:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_title_one' placeholder='' required autofocus value='$business_title_one' />"; 
$updateBusinessInfo .="<div>$business_title_two_error</div>";
$updateBusinessInfo .="Business subtitle:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_title_two' placeholder='' required autofocus value='$business_title_two' />"; 
$updateBusinessInfo .="<div>$business_full_address_error</div>";
$updateBusinessInfo .="Business Address:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_full_address'  value='$business_full_address'/>";
$updateBusinessInfo .="<div>$business_city_town_error</div>";
$updateBusinessInfo .="City/Town:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_city_town' placeholder='City/Town' required autofocus value='$business_city_town' />";
$updateBusinessInfo .="<div>$business_state_province_region_error</div>";
$updateBusinessInfo .="State/Province/Region:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_state_province_region' placeholder='State/Province/Region' required autofocus value='$business_state_province_region' />";
$updateBusinessInfo .="<div>$business_postal_code_error</div>";
$updateBusinessInfo .="Postal Code:";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_postal_code' placeholder='Postal Code' required autofocus value='$business_postal_code' />";

if(!empty($business_country))
{
$updateBusinessInfo .="<div>$business_country_error</div>";
$updateBusinessInfo .="Country: <input class='form-control' type='text' name='business_country' value='$business_country' />";
}
else
{
$updateBusinessInfo .="<div>$business_country_error</div>";
$updateBusinessInfo .="Country:";
$updateBusinessInfo .="<select class='form-control' name='business_country'>";

$objCountry = new ebapps\login\registration_page();
$objCountry->select_user_country();
if($objCountry->eBData)
{
foreach($objCountry->eBData as $val)
{
extract($val);
$updateBusinessInfo .="<option value='$country_name'>".$country_name."</option>";
}
}
$updateBusinessInfo .="</select>";
}
$updateBusinessInfo .="<div>$business_geolocation_latitude_error</div>";
$updateBusinessInfo .="Business GPS Latitude :";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_geolocation_latitude' value='$business_geolocation_latitude' />";
$updateBusinessInfo .="<div>$business_geolocation_longitude_error</div>";
$updateBusinessInfo .="Business GPS Longitude :";
$updateBusinessInfo .="<input class='form-control' type='text' name='business_geolocation_longitude' value='$business_geolocation_longitude' />";
$updateBusinessInfo .="<div>$cash_on_delivery_distance_meter_error</div>";
$updateBusinessInfo .="Cash on Delivery Distance in Meter:";
$updateBusinessInfo .="<input class='form-control' type='text' name='cash_on_delivery_distance_meter' value='$cash_on_delivery_distance_meter' />";  
$updateBusinessInfo .="<div class='buttons-set'><button type='submit' name='BusinessSettings' title='Update' class='button submit'> <span> Update </span> </button></div>";
$updateBusinessInfo .="</fieldset>";
$updateBusinessInfo .="</form>";
echo $updateBusinessInfo;  
}
}
?>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>

</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer-admin.php'); ?>
<?php exit(); ?>