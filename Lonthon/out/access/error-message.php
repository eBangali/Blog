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
<?php include_once (ebcontents.'/views/shop/search.php'); ?>
<section class='content-wrapper'>
<div class='container'>
<div class='std'>
<div class='page-not-found'>
<p><img src='<?php echo themeResource; ?>/images/signal.png'>Oops! The Page you requested was not found!</p>
<div><a  href='<?php echo hostingAndRoot."/index.php"; ?>' type='button' class='btn-home'><span>Back To Home</span></a></div>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>