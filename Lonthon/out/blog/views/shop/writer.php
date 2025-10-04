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
<?php include_once('search.php'); ?>
<?php
if(isset($_GET['articleno']))
{
$articleno = strval($_GET['articleno']);
$contentsDetails = $this->item_details_contents($articleno);
$postApproveType = $contentsDetails['contents_approved'];
//
if($postApproveType == 'GPOST')
{
if(isset($_SESSION['memberlevel']))
{
if($_SESSION['memberlevel'] >= 1)
{
include_once("guest-writer-thumbnail.php");
}
}
else
{
?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
</div>
<div class='col-right sidebar col-md-3 col-xs-12 hidden-xs'>

</div>
</div>
</div>
<?php
}
}
elseif($postApproveType == 'OK')
{
include_once("writer-thumbnail.php");
}
}
?>
<?php 
/* Ajax Like will work if included.*/
include_once ('not-reload-script-blog.php'); 
?>
<?php include_once (eblayout.'/a-common-footer.php'); ?>