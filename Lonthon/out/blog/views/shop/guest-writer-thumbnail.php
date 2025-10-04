<?php
if(isset($_GET['articleno']))
{
$articleno = strval($_GET['articleno']);
$contentsDetails = $this->item_details_contents($articleno);
$contentsbloggroup = $contentsDetails['username_contents'];
//
$wRiter= new ebapps\blog\blog();
$wRiter -> contents_thurmnail_group_guest($contentsbloggroup);
if($wRiter->eBData)
{
$writer  ="<div class='content-page'>";
$writer .="<div class='container'>"; 
$writer .="<div class='category-product'>";
$writer .="<div class='navbar nav-menu'>";
$writer .="<div class='navbar-collapse'>";
$writer .="<ul class='nav navbar-nav'>";
$writer .="<li>";
$writer .="<div class='new_title'>";
$writer .="<h2>".$wRiter->visulString($contentsbloggroup)."</h2>";
$writer .="</div>";
$writer .="</li>";
$writer .="</ul>";
$writer .="</div>";
$writer .="</div>";
$writer .="<div class='product-bestseller'>";
$writer .="<div class='product-bestseller-content'>";
$writer .="<div class='product-bestseller-list'>";
$writer .="<div class='tab-container'>";
$writer .="<div class='tab-panel active'>";
$writer .="<div class='category-products'>";
$writer .="<ul class='products-grid'>";
foreach($wRiter->eBData as $vaLwRiter): extract($vaLwRiter);
$writer .="<li class='item col-md-3 col-xs-12'>";
$writer .="<div class='item-inner'>";
$writer .="<div class='item-title'><h2>".substr($wRiter->visulString($contents_og_image_title), 0, 19)." .."."</h2></div>";
$writer .="<div class='item-title'><h3>".strtoupper($wRiter->visulString($contents_category))."</h3></div>";
$writer .="<div class='item-img'>";
$writer .="<div class='item-img-info'>";
if(!empty($contents_og_small_image_url) and file_exists(docRoot.$contents_og_small_image_url))
{
$writer .="<a  class='product-image' title='".$wRiter->visulString($contents_og_image_title)."' href='";
$writer .=outContentsLink."/contents/solve/$contents_id/".$wRiter->seoUrl($contents_og_image_title)."/";
$writer .="'><img alt='".$wRiter->visulString($contents_og_image_title)."' src='";
$writer .=$contents_og_small_image_url;
$writer .="'></a>";
}
$writer .="<div class='entry-content'>";
$writer .="<ul class='post-meta'>";

/*Like?*/
$countLikeNow = new ebapps\blog\blog();
$countLikeNow ->count_like_now($contents_id);

if($countLikeNow->eBData)
{
foreach($countLikeNow->eBData as $valcountLikeNow): extract($valcountLikeNow);
	
if(isset($_SESSION['ebusername']) and $likeNow == 0)
{
if(isset($_REQUEST['add_for_like']))
{
extract($_REQUEST);
$countLike = new ebapps\blog\blog();
$countLike ->add_for_like($contents_id_for_like);
}
$writer .="<li><form method='post' id='$contents_id'><input type='hidden' name='contents_id_for_like' value='$contents_id' /><button type='submit' onclick='buTTonSelectBlog()' name='add_for_like'><i class='fa fa-heart fa-lg'></i></button></form></li>";
}
else 
{
$writer .="<li><i class='fa fa-heart fa-lg'></i></li>";
}
endforeach;
}  
$writer .="<li class='$contents_id'>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
	
if($totalPostLikes <= 1)
{
$writer .=$totalPostLikes." Like";
}
elseif($totalPostLikes > 1) 
{
$writer .=$totalPostLikes." Likes";
}
endforeach;
}
$writer .="</li>";

/* */				   
$writer .="<i class='fa fa-comments fa-lg'></i>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_contents($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
if($totalPostComments <= 1)
{
$writer .=$totalPostComments." Comment";	
}
else 
{
$writer .=$totalPostComments." Comments";
}
endforeach;
}
$writer .="</li>"; 
$writer .="<li><i class='fa fa-clock-o fa-lg'></i><span class='day'>".date('d M Y',strtotime($contents_date))."</span></li>";
$writer .="</ul>";
$writer .="<div>";

$writer .="</div>";
$writer .="</div>";
$writer .="<div class='item-info'>";
$writer .="<div class='info-inner'>";
$writer .="<div class='item-content'>";

$writer .="<div class='action'>";
$writer .="<a  href='";
$writer .=outContentsLink."/contents/solve/$contents_id/".$wRiter->seoUrl($contents_og_image_title)."/";
$writer .="' class='eb-cart-back'><span>Read More</span></a>";
$writer .="</div>";

$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</li>";
endforeach;

$writer .="</ul>";
$writer .="</div>";
$writer .="</div>";

$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
$writer .="</div>";
echo $writer;
}
}
?>