<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblogin.'/session-inc-reffer.php'); ?>
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
<!-- Level Starts -->
<div class='well'>
<h2 title='Invite Someone'>Invite Someone</h2>
<div class='side-nav-categories'>
<div class='box-content box-category'>
<ul>
<?php include_once (eblogin.'/registration-page.php'); ?>
<?php 
$count1stLevel = new ebapps\login\registration_page();
$count1stLevel ->countFirstLevelOfInvite();
if($count1stLevel->eBData)
{
foreach($count1stLevel->eBData as $count1stLevelval): extract($count1stLevelval);
echo "<b>Directly Invited : $countFirstLevelTotal</b>";
endforeach;
} 
?>
</ul>
</div> 
</div>
</div>
<!-- Level End -->
<?php 
include_once ("invitefnf.php");
?>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>