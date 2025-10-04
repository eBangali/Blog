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
<h2 title='Frequently Asked Questions'>Frequently Asked Questions</h2>
</div>
<div class='well'>
<article>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading6">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" aria-controls="collapse2">
#6. What is your current conversion rate?
</a>
</h4>
</div>
<div id="collapse6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading6">
<div class="panel-body">
Our current conversion rate <?php echo primaryCurrency; ?> <?php echo convertPrimary; ?> = <?php echo secondaryCurrency; ?> <?php echo convertSecondary; ?>.
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading5">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapse2">
#5. Which currencies does <?php echo domain; ?> accept?
</a>
</h4>
</div>
<div id="collapse5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
<div class="panel-body">
We accept primary currency as <?php echo primaryCurrency; ?> and secondary currency as <?php echo secondaryCurrency; ?>
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading4">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse2">
#4. Can I pay with any Credit or Debit card?
</a>
</h4>
</div>
<div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
<div class="panel-body">
You can choose to pay on <?php echo domain; ?> with any Visa, Master or American Express Cards through PayPal, Stripe. 
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading3">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse2">
#3. What are the working hours?
</a>
</h4>
</div>
<div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
<div class="panel-body">
We are open 09 AM to 05 PM (GMT+6) 8 hours work online per day each Business day.
</div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading2">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse3">
#2. What happens if I am not satisfied with your service or product?
</a>
</h4>
</div>
<div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
<div class="panel-body">
We have own coustom messessing system for each product or services what we provide. If this happens, please send message in respective query section so that we can review it right away and get back to you with the best solution for you in the shortest possible time. There will be no added cost for the corrections. If we can not satisfi you will shall refund your payment. Tranjaction Fee, TAX/ VAT, Shipping Fee will not refunded.
</div>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading1">
<h4 class="panel-title">
<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse4">
#1. How do i pay <?php echo domain; ?>?
</a>
</h4>
</div>
<div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
<div class="panel-body">
We accept payment through PayPal, Stripe.
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