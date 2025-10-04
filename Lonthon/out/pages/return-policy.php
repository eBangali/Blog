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
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class="well">
<h2 title='Returns Policy'>Returns Policy</h2>
</div>
<div class='well'>
<article>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading1">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
# Returns Policy
</a>
</h4>
</div>
<div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
<div class="panel-body">
<ol>
<li>If your product is defective / damaged or incorrect/incomplete at the time of delivery, please send message in respective query section. Your product may be eligible for refund or replacement depending on the product category and condition.</li>
<li>For device related issues after usage we will refer you to the brand warranty center (if applicable).</li>
</ol>
</div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading2">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
# Valid reasons to return an item
</a>
</h4>
</div>
<div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
<div class="panel-body">
<ol>
<li>Delivered Product is damaged (physically destroyed or broken) / defective on arrival</li>
<li>Delivered Product is incorrect (presentation different from website) / incomplete (missing parts)</li>
<li>Delivered Product is “No longer needed” (you no longer have a use for the product / you have changed your mind about the purchase / the size of a fashion product does not fit / you do not like the product after opening the package) - eligible for selected products only</li>
</ol>
</div>
</div>
</div>

</div>
</article>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php
if(isset($_SESSION['memberlevel']) >= 1)
{ 
include_once (ebaccess.'/access-my-account.php');
} 
?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>