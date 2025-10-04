<div id='get-list-view'></div>
<div id='load-msg'></div>
<?php include_once (ebOutContentsRequest.'/scroll.php'); ?>
<script>
$(document).loadScrollData(0,{
limit: 1,
listingId:'#get-list-view',
loadMsgId:'#load-msg',
ajaxUrl:'<?php echo outContentsRequest; ?>/scroll-down-post-ajax-url.php',
loadingMsg:'<div class="alert alert-warning p-1 text-center"><i class="fa fa-fw fa-spin fa-spinner"></i>Please Wait ...!</div>',
loadingSpeed:1
});
</script>

