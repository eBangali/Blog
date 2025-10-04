<div class='well'>
<?php $obj= new ebapps\blog\blog();
$obj -> contents_download_guest($articleno);
?>
<?php if($obj->eBData >= 1) 
{
?>
<?php foreach($obj->eBData as $val): extract($val); ?>
<?php if(!empty($contents_preview_link)){ ?>
<a  class='eb-cart-back' href='<?php echo $contents_preview_link; ?>' target='_blank'>Preview</a>
<?php } ?>
<?php if(!empty($contents_affiliate_link)){ ?>
<a  class='eb-cart-back' href='<?php echo $contents_affiliate_link; ?>' target='_blank'>Buy Now</a>
<?php } ?>
<?php if(!empty($contents_github_link)){ ?>
<a  class='eb-cart-back' href='<?php echo $contents_github_link; ?>' target='_blank'>Download</a>
<?php 
} 
endforeach; 
?>
<?php
} 
?>
</div>