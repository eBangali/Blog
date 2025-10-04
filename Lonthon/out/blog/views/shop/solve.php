<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<?php include_once (ebcontents.'/views/shop/seo.php'); ?>
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
<?php include_once ('breadcrumbs.php'); ?>
<?php include_once (eblayout.'/a-common-share-button-for-blog.php'); ?>
<?php include_once ('search.php'); ?>
<section class='contentIndex'>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<?php
if(isset($_GET['articleno']))
{
$articleno = strval($_GET['articleno']);
$contentsDetails = $this->item_details_contents($articleno);
$postApproveType = $contentsDetails['contents_approved'];
$requiredLogin = $contentsDetails['contents_og_login_required'];
//
if($postApproveType == 'GPOST')
{
if(isset($_SESSION['memberlevel']))
{
if($_SESSION['memberlevel'] >= 1)
{
if(!empty($contents_video_link))
{
include_once ('guest-post-header-video.php');
}
else
{
include_once ('guest-post-header.php');
}
include_once ('guest-post-details.php'); 
}
}
}
//
if($postApproveType == 'OK')
{
if(!empty($contents_video_link))
{
include_once ('post-header-video.php');
}
else
{
include_once('post-header.php');
}
include_once('post-details.php');
}
}
?>
<?php include_once ('scroll-down-post-ajax.php'); ?>
</div>
<div class='col-right sidebar col-md-3 col-xs-12 hidden-xs hidden-sm hidden-md'>
<?php include_once("rightWidgetForPostCategory.php"); ?>
</div>
</div>
</div>
</section>
<?php include_once ('not-reload-script-blog.php'); ?>
<?php include_once (eblayout.'/a-common-footer.php'); ?>
