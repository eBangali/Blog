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
<h2 title='Admin Business Info'>Admin Business Info</h2>
</div>

<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
/* Initialize validation */
$error = 0;
$business_name_error = "";
$business_vat_tax_gst_error  = "";
$business_title_one_error = "";
$business_title_two_error = "";
$business_full_address_error = "";
$business_city_town_error = "";
$business_state_province_region_error = "";
$business_postal_code_error = "";
$business_country_error = "";
$business_geolocation_latitude_error = "";
$business_geolocation_longitude_error = "";
$cash_on_delivery_distance_meter_error = "";

/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

if(isset($_POST['BusinessSettings'])) {

    /* business_name */
    if (empty($_POST['business_name'])) {
        $business_name_error = "<b class='text-warning'>Legal company name or Brand name required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,20})$/", $_POST['business_name'])) {
        $business_name_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_name_filtered = $sanitization->test_input($_POST['business_name']);
    }

    /* business_vat_tax_gst */
    if (empty($_POST['business_vat_tax_gst'])) {
        $business_vat_tax_gst_error = "<b class='text-warning'>VAT/GST ID required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59})$/", $_POST['business_vat_tax_gst'])) {
        $business_vat_tax_gst_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_vat_tax_gst_filtered = $sanitization->test_input($_POST['business_vat_tax_gst']);
    }

    /* business_title_one */
    if (empty($_POST['business_title_one'])) {
        $business_title_one_error = "<b class='text-warning'>Legal company title or Brand title required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,30})$/", $_POST['business_title_one'])) {
        $business_title_one_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_title_one_filtered = $sanitization->test_input($_POST['business_title_one']);
    }

    /* business_title_two */
    if (empty($_POST['business_title_two'])) {
        $business_title_two_error = "<b class='text-warning'>Legal company title or Brand title required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59})$/", $_POST['business_title_two'])) {
        $business_title_two_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_title_two_filtered = $sanitization->test_input($_POST['business_title_two']);
    }

    /* business_full_address */
    if (empty($_POST['business_full_address'])) {
        $business_full_address_error = "<b class='text-warning'>Legal Business Full Address Required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59})$/", $_POST['business_full_address'])) {
        $business_full_address_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_full_address_filtered = $sanitization->test_input($_POST['business_full_address']);
    }

    /* business_city_town */
    if (empty($_POST['business_city_town'])) {
        $business_city_town_error = "<b class='text-warning'>City/Town required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59})$/", $_POST['business_city_town'])) {
        $business_city_town_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_city_town_filtered = $sanitization->test_input($_POST['business_city_town']);
    }

    /* business_state_province_region */
    if (!empty($_POST['business_state_province_region']) && preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,59})$/", $_POST['business_state_province_region'])) {
        $business_state_province_region_filtered = $sanitization->test_input($_POST['business_state_province_region']);
    }

    /* business_postal_code */
    if (empty($_POST['business_postal_code'])) {
        $business_postal_code_error = "<b class='text-warning'>Postal code required.</b>";
        $error = 1;
    } elseif (!preg_match("/^([a-zA-Z0-9\?\#\.\,\-\(\)\ ]{1,20})$/", $_POST['business_postal_code'])) {
        $business_postal_code_error = "<b class='text-warning'>Some characters are not allowed.</b>";
        $error = 1;
    } else {
        $business_postal_code_filtered = $sanitization->test_input($_POST['business_postal_code']);
    }

    /* business_country */
    if (!empty($_POST['business_country']) && preg_match("/^([A-Za-z0-9\?\.\,\-\#\ ]{2,59})$/", $_POST['business_country'])) {
        $business_country_filtered = $sanitization->test_input($_POST['business_country']);
    }

    /* business_geolocation_latitude */
    if (!empty($_POST['business_geolocation_latitude']) && preg_match("/^[0-9\.]{1,16}$/", $_POST['business_geolocation_latitude'])) {
        $business_geolocation_latitude_filtered = $sanitization->test_input($_POST['business_geolocation_latitude']);
    }

    /* business_geolocation_longitude */
    if (!empty($_POST['business_geolocation_longitude']) && preg_match("/^[0-9\.]{1,16}$/", $_POST['business_geolocation_longitude'])) {
        $business_geolocation_longitude_filtered = $sanitization->test_input($_POST['business_geolocation_longitude']);
    }

    /* cash_on_delivery_distance_meter */
    if (!empty($_POST['cash_on_delivery_distance_meter']) && preg_match("/^[0-9]{1,6}$/", $_POST['cash_on_delivery_distance_meter'])) {
        $cash_on_delivery_distance_meter_filtered = $sanitization->test_input($_POST['cash_on_delivery_distance_meter']);
    }

    /* Submit form */
    if ($error == 0) {
        $user = new ebapps\login\registration_page();
        $user->update_merchant_business_details($business_name_filtered,$business_vat_tax_gst_filtered,$business_title_one_filtered,$business_title_two_filtered,$business_full_address_filtered,$business_city_town_filtered,$business_state_province_region_filtered,$business_postal_code_filtered,$business_country_filtered,$business_geolocation_latitude_filtered,$business_geolocation_longitude_filtered,$cash_on_delivery_distance_meter_filtered);
    }
}
?>

<div class='well'>
<?php
$obj = new ebapps\login\registration_page();
$obj->update_merchant_business_info_read();
if($obj->eBData)
{
foreach($obj->eBData as $val)
{
extract($val);
$updateBusinessInfo ="<form method='post'>"; 
$updateBusinessInfo .="<fieldset class='group-select'>";
//
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Username: </span><span class='form-control' aria-describedby='sizing-addon2'>$business_username</span></div>";
//
$updateBusinessInfo .="$business_name_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Business Name:</span><input type='text' name='business_name' value='$business_name' placeholder='Business name' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
//
$updateBusinessInfo .="$business_vat_tax_gst_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>VAT/GST ID:</span><input type='text' name='business_vat_tax_gst' value='$business_vat_tax_gst' placeholder='VAT/GST/TAX ID' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>";
//
$updateBusinessInfo .="$business_title_one_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Business Title:</span><input type='text' name='business_title_one' value='$business_title_one' placeholder='Business title' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
$updateBusinessInfo .="$business_title_two_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Business Subtitle:</span><input type='text' name='business_title_two' value='$business_title_two' placeholder='Business title' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
$updateBusinessInfo .="$business_full_address_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Address:</span><input type='text' name='business_full_address' value='$business_full_address' placeholder='Business address' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
$updateBusinessInfo .="$business_city_town_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>City/Town:</span><input type='text' name='business_city_town' value='$business_city_town' placeholder='City/Town' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
$updateBusinessInfo .="$business_state_province_region_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>State/Province/Region:</span><input type='text' name='business_state_province_region' value='$business_state_province_region' placeholder='State/Province/Region' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
$updateBusinessInfo .="$business_postal_code_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Postal Code:</span><input type='text' name='business_postal_code' value='$business_postal_code' placeholder='Postal Code' class='form-control' aria-describedby='sizing-addon2' required  autofocus></div>"; 
//
$updateBusinessInfo .="$business_country_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Country:</span>";
$updateBusinessInfo .="<select class='form-control' aria-describedby='sizing-addon2' name='business_country'>";
if(!empty($business_country))
{
$updateBusinessInfo .="<option value='$business_country'>".$business_country."</option>";
}
else
{
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
}
$updateBusinessInfo .="</select>";
$updateBusinessInfo .="</div>";
//
$updateBusinessInfo .="$business_geolocation_latitude_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Latitude : </span><input type='text' name='business_geolocation_latitude' value='$business_geolocation_latitude' placeholder='GPS Latitude' class='form-control' aria-describedby='sizing-addon2'></div>"; 
$updateBusinessInfo .="$business_geolocation_longitude_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>Longitude : </span><input type='text' name='business_geolocation_longitude' value='$business_geolocation_longitude' placeholder='GPS Longitude' class='form-control' aria-describedby='sizing-addon2'></div>";	
$updateBusinessInfo .="$cash_on_delivery_distance_meter_error";
$updateBusinessInfo .="<div class='input-group'><span class='input-group-addon' id='sizing-addon2'>COD in Meter: </span><input type='text' name='cash_on_delivery_distance_meter' value='$cash_on_delivery_distance_meter' placeholder='Cash on Delivery Distance in Meter' class='form-control' aria-describedby='sizing-addon2'></div>";  
$updateBusinessInfo .="<div class='buttons-set'>";
$updateBusinessInfo .="<button type='submit' name='BusinessSettings' title='Update' class='button submit'>Update</button>";
$updateBusinessInfo .="</div>";
$updateBusinessInfo .="</fieldset>";
$updateBusinessInfo .="</form>";
echo $updateBusinessInfo;  
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