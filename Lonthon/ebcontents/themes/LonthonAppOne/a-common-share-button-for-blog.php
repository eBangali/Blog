<?php include_once (ebblog.'/blog.php'); ?>
<?php if(isset($_GET['articleno'])){$articleno = strval($_GET['articleno']); ?>
<?php $obj= new ebapps\blog\blog(); $obj -> content_item_details_seo($articleno); ?>
<?php  if($obj->eBData) { foreach($obj->eBData as $val){ extract($val); ?>
<div class='social-share-vertical'>
<ul>
<li class='twitter'><a target='_blank' href='https://twitter.com/share?url=<?php if(isset($_SESSION['ebusername'])){ echo fullUrl.$_SESSION['ebusername']."/"; } else {echo fullUrl;}?>' title="Share to Twitter"><i class='fa fa-twitter'></i></a></li>
<li class='facebook'><a target='_blank' href='https://facebook.com/sharer/sharer.php?u=<?php if(isset($_SESSION['ebusername'])){ echo fullUrl.$_SESSION['ebusername']."/"; } else {echo fullUrl;}?>' title="Share to Facebook"><i class='fa fa-facebook'></i></a></li>
<li class='whatsapp'><a target='_blank' href='https://api.whatsapp.com/send?phone&text=<?php if(isset($_SESSION['ebusername'])){ echo fullUrl.$_SESSION['ebusername'].'/'; } else {echo fullUrl;}?>'><i class='fa fa-whatsapp'></i></a></li>
</ul>
</div>
<?php }}} ?>