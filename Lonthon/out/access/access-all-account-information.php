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
<div class='col-xs-12 col-md-9 sidebar-offcanvas'>
<div class="well">
<h2 title='User Info'>User Info</h2>
</div>
<?php 
include_once (eblogin.'/registration-page.php');
$objFilter = new ebapps\login\registration_page();
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();

/* Initialize validation */
$error = 0;
$usernameEmailMobile_error = "";

if (isset($_POST['searchUsernameEmailMobile']))
{
    /* usernameEmailMobile */
    if (empty($_POST['usernameEmailMobile'])) {
        $usernameEmailMobile_error = "<b class='text-warning'>Required.</b>";
        $error = 1;
    } else {
        $usernameEmailMobile_filtered = $sanitization->onlyUsernameInputForLowercase($_POST["usernameEmailMobile"]);
    }

    /* Submission form */
    if ($error == 0) {
        $objFilter->search_all_user_read($usernameEmailMobile_filtered);

        $updateAccount  = "<div class='well'>"; 
        $updateAccount .= "<div class='table-responsive'>"; 
        $updateAccount .= "<table class='table'>";
        $updateAccount .= "<thead>";
        $updateAccount .= "<tr>";
        $updateAccount .= "<th>Option</th>";
        $updateAccount .= "<th>Username</th>";
        $updateAccount .= "<th>Refer</th>";
        $updateAccount .= "<th>Invited By</th>";
        $updateAccount .= "<th>Level</th>";
        $updateAccount .= "<th>Power</th>";
        $updateAccount .= "<th>Type</th>";
        $updateAccount .= "<th>Name</th>";
        $updateAccount .= "<th>Mobile</th>";
        $updateAccount .= "<th>Mobile Verify</th>";
        $updateAccount .= "<th>eMail</th>";
        $updateAccount .= "<th>eMail Verified</th>";
        $updateAccount .= "<th>Country</th>";
        $updateAccount .= "<th>Code</th>";
        $updateAccount .= "</tr>";
        $updateAccount .= "</thead>";
        $updateAccount .= "<tbody>";

        if ($objFilter->eBData >= 1) {
            foreach ($objFilter->eBData as $val) {
                // variables from DB row
                $ebusername = $val['ebusername'];
                $omrusername = $val['omrusername'];
                $position_names = $val['position_names'];
                $member_level = $val['member_level'];
                $account_type = $val['account_type'];
                $full_name = $val['full_name'];
                $mobile = $val['mobile'];
                $mobileactive = $val['mobileactive'];
                $email = $val['email'];
                $active = $val['active'];
                $country = $val['country'];
                $address_verification_codes = $val['address_verification_codes'];

                $updateAccount .= "<tr>";
                $updateAccount .= "<td>";
                $updateAccount .= "<form action='access-all-account-information-edit.php' method='get'>";
                $updateAccount .= "<fieldset class='group-select'>";
                $updateAccount .= "<ul>";
                $updateAccount .= "<input type='hidden' name='ebusername' value='$ebusername' />";
                $updateAccount .= "<button type='submit' name='EditMemberLevel' title='Edit' class='button submit'>Edit</button>";
                $updateAccount .= "</ul>";
                $updateAccount .= "</fieldset>";
                $updateAccount .= "</form>";
                $updateAccount .= "</td>";
                $updateAccount .= "<td>$ebusername</td>";

                $objRefer = new ebapps\login\registration_page();
                $objRefer->totalReferFirstLevel($ebusername);
                if ($objRefer->eBData) {
                    foreach ($objRefer->eBData as $valRefer) {
                        $totalreferfirst_l = $valRefer['totalreferfirst_l'];
                        $updateAccount .= "<td>$totalreferfirst_l</td>";
                    }
                }

                $updateAccount .= "<td>$omrusername</td>";
                $updateAccount .= "<td>".$position_names."</td>";
                $updateAccount .= "<td>".$member_level."</td>";
                $updateAccount .= "<td>".$account_type."</td>";
                $updateAccount .= "<td>".$full_name."</td>";
                $updateAccount .= "<td>$mobile</td>";
                $updateAccount .= "<td>$mobileactive</td>";
                $updateAccount .= "<td>$email</td>";
                $updateAccount .= "<td>$active</td>";
                $updateAccount .= "<td>$country</td>";
                $updateAccount .= "<td>$address_verification_codes</td>";
                $updateAccount .= "</tr>";
            }
        }
        $updateAccount .= "</tbody>";
        $updateAccount .= "</table>";
        $updateAccount .= "</div>";
        $updateAccount .= "</div>";

        echo $updateAccount;
    }
}
?>

<div class='well'>
<form method='post'>
<div class="input-group">
  <?php echo $usernameEmailMobile_error; ?>
  <input type="text" name='usernameEmailMobile' class="form-control" placeholder="Username or Email Or Mobile" aria-describedby="basic-addon2" required autofocus>
  <span class="input-group-addon" id="basic-addon2">
    <button type='submit' name='searchUsernameEmailMobile' title='Search For User'>Search For User</button>
  </span>
</div>
</form>
</div>

<?php
$objFilter->all_user_account_info_read();
$updateAccount  ="<div class='well'>"; 
$updateAccount .="<div class='table-responsive'>"; 
$updateAccount .="<table class='table'>";
$updateAccount .="<thead>";
$updateAccount .="<tr>";
$updateAccount .="<th>Option</th>";
$updateAccount .="<th>Username</th>";
$updateAccount .="<th>Refer</th>";
$updateAccount .="<th>Invited By</th>";
$updateAccount .="<th>Level</th>";
$updateAccount .="<th>Power</th>";
$updateAccount .="<th>Type</th>";
$updateAccount .="<th>Name</th>";
$updateAccount .="<th>Mobile</th>";
$updateAccount .="<th>Mobile Verify</th>";
$updateAccount .="<th>eMail</th>";
$updateAccount .="<th>eMail Verified</th>";
$updateAccount .="<th>Country</th>";
$updateAccount .="<th>Code</th>";
$updateAccount .="</tr>";
$updateAccount .="</thead>";
$updateAccount .="<tbody>";
if($objFilter->eBData >= 1)
{
foreach($objFilter->eBData as $val)
{
extract($val);
$updateAccount .="<tr>";
$updateAccount .="<td>";
$updateAccount .="<form action='access-all-account-information-edit.php' method='get'>";
$updateAccount .="<fieldset class='group-select'>";
$updateAccount .="<ul>";
$updateAccount .="<input type='hidden' name='ebusername' value='$ebusername' />";
$updateAccount .="<button type='submit' name='EditMemberLevel' title='Edit' class='button submit'>Edit</button>";
$updateAccount .="</ul>";
$updateAccount .="</fieldset>";
$updateAccount .="</form>";
$updateAccount .="</td>";
$updateAccount .="<td>$ebusername</td>";
$objRefer = new ebapps\login\registration_page();
$objRefer->totalReferFirstLevel($ebusername);
if($objRefer->eBData)
{
foreach($objRefer->eBData as $valRefer)
{
extract($valRefer);
$updateAccount .="<td>$totalreferfirst_l</td>";
}
}
$updateAccount .="<td>$omrusername</td>";
$updateAccount .="<td>".$position_names."</td>";
$updateAccount .="<td>".$member_level."</td>";
$updateAccount .="<td>".$account_type."</td>";
$updateAccount .="<td>".$full_name."</td>";
$updateAccount .="<td>$mobile</td>";
$updateAccount .="<td>";
$updateAccount .="$mobileactive";
$updateAccount .="</td>";
$updateAccount .="<td>$email</td>";
$updateAccount .="<td>$active</td>";
$updateAccount .="<td>$country</td>";
$updateAccount .="<td>$address_verification_codes</td>";
$updateAccount .="</tr>";
}
}
$updateAccount .="</tbody>";
$updateAccount .="</table>";
$updateAccount .="</div>";
$updateAccount .="</div>";
echo $updateAccount;
?>
</div>
    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
      <?php include_once (ebaccess."/access-my-account.php"); ?>
    </div>
  </div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>