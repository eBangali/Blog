<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblogin.'/session-inc-verify.php'); ?>
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
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='eMail verification'>eMail verification</h2>
</div>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php
if (
    isset($_GET['email']) && !empty($_GET['email']) &&
    isset($_GET['emailhash']) && !empty($_GET['emailhash'])
) {
    /* Data Sanitization */
    include_once(ebsanitization.'/sanitization.php'); 
    $sanitization = new ebapps\sanitization\formSanitization();

    /* Validation eMail */
    $email = $sanitization->test_input($_GET['email']);

    /* Validation hash */
    $emailhash = $sanitization->test_input($_GET['emailhash']);

    /* Updating verification */
    $userVerify = new ebapps\login\registration_page();
    $userVerify->varify_email($email, $emailhash);
}
?>

</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>

</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer-admin.php'); ?>