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
<div class="well">
<h2 title='Edit Access Level Power'>Edit Access Level Power</h2>
</div> 
<?php 
include_once (eblogin.'/registration-page.php'); 
?>
<?php
if(isset($_REQUEST['EditMemberLevel']))
{
extract($_REQUEST);
$obj = new ebapps\login\registration_page();
$obj->edit_view_user_power($ebusername);
}
?>
<?php
/* Initialize valitation */
$error = 0;
$userpower_level_names_error = "";
$userpower_level_power_error = "";
$userpower_position_error = "";

?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['UpdateMember']))
{
extract($_REQUEST);

/* userpower_position */
if (empty($_REQUEST['userpower_position']))
{
$userpower_position_error = "<b class='text-warning'>Position name required.</b>";
$error =1;
}
else
{
$userpower_position = $sanitization->test_input($_POST['userpower_position']);
}
//
if (isset($_REQUEST['userpower_position']))
{
$userpower_position = strval($_POST['userpower_position']);
$levelNames = new ebapps\login\registration_page();
$levelNames->selectedUserPositionToLevelName($userpower_position);
if($levelNames->eBData)
{
foreach($levelNames->eBData as $vallevelNames)
{
extract($vallevelNames);
$userpower_level_names = $userpower_level_names;
}
}
}
//
if (isset($_REQUEST['userpower_position']))
{
$userpower_position = strval($_POST['userpower_position']);
$levelPower = new ebapps\login\registration_page();
$levelPower->selectedUserPositionToPower($userpower_position);
if($levelPower->eBData)
{
foreach($levelPower->eBData as $vallevelPower)
{
extract($vallevelPower);
$userpower_level_power = $userpower_level_power;
}
}
}

/* Submition form */
if($error == 0)
{
$user = new ebapps\login\registration_page();
extract($_REQUEST);
$user->submit_user_power($email, $ebusername, $userpower_level_names, $userpower_level_power, $userpower_position);
}
//
}
?>
<div class="well">
<?php
$obj = new ebapps\login\registration_page();
$obj->edit_view_user_power($ebusername);
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updateAccountInfo ="<form method='post'>"; 
$updateAccountInfo .="<fieldset class='group-select'>";
$updateAccountInfo .="Username: $ebusername ";
$updateAccountInfo .="<input type='hidden' name='email' value='$email' />";
$updateAccountInfo .="<input type='hidden' name='ebusername' value='$ebusername' />";
$updateAccountInfo .="Level Power:$member_level $userpower_level_power_error";
$updateAccountInfo .="Level Name: $position_names $userpower_position_error";
$updateAccountInfo .="<select class='form-control' name='userpower_position'>";
$objCountry = new ebapps\login\registration_page();
$objCountry->select_userpower();
if($objCountry->eBData)
{
foreach($objCountry->eBData as $val)
{
extract($val);
$updateAccountInfo .="<option value='$userpower_position'>".$userpower_position." (Power $userpower_level_power Section $userpower_level_names) "."</option>";
}
}
$updateAccountInfo .="</select>";
$updateAccountInfo .="<div class='buttons-set'>";
$updateAccountInfo .="<button type='submit' name='UpdateMember' title='Update' class='button submit'>Update</button>";
$updateAccountInfo .="</div>";
$updateAccountInfo .="</fieldset>";
$updateAccountInfo .="</form>";
echo $updateAccountInfo;  
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