<?php
if(isset($_GET['articleno']))
{
$articleno = strval($_GET['articleno']);
$contentsDetails = $this->item_details_contents_guest($articleno);
$category = $contentsDetails['contents_category'];
$subcategory = $contentsDetails['contents_sub_category'];
//
$cAt = new ebapps\blog\blog();
$cAt -> contents_thurmnail_subcategory_gpost($category,$subcategory);
if($cAt->eBData)
{
$newSearch  ="<div class='content-page'>";
$newSearch .="<div class='container'>"; 
$newSearch .="<div class='category-product'>";
$newSearch .="<div class='navbar nav-menu'>";
$newSearch .="<div class='navbar-collapse'>";
$newSearch .="<ul class='nav navbar-nav'>";
$newSearch .="<li>";
$newSearch .="<div class='new_title'>";
$newSearch .="<h2>".$cAt->visulString($subcategory)."</h2>";
$newSearch .="</div>";
$newSearch .="</li>";

$newSearch .="</ul>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="<div class='product-bestseller'>";
$newSearch .="<div class='product-bestseller-content'>";
$newSearch .="<div class='product-bestseller-list'>";
$newSearch .="<div class='tab-container'>";

$newSearch .="<div class='tab-panel active'>";
$newSearch .="<div class='category-products'>";
$newSearch .="<ul class='products-grid'>";
foreach($cAt->eBData as $vaLcAt): extract($vaLcAt);
$newSearch .="<li class='item col-md-3 col-xs-12'>";
$newSearch .="<div class='item-inner'>";
$newSearch .="<div class='item-title'><h2>".substr($cAt->visulString($contents_og_image_title), 0, 19)." .."."</h2></div>";
$newSearch .="<div class='item-title'><h3>".strtoupper($cAt->visulString($contents_category))."</h3></div>";
$newSearch .="<div class='item-img'>";
$newSearch .="<div class='item-img-info'>";
if(!empty($contents_og_small_image_url) and file_exists(docRoot.$contents_og_small_image_url))
{
$newSearch .="<a  class='product-image' title='".$cAt->visulString($contents_og_image_title)."' href='";
$newSearch .=outContentsLink."/contents/solve/$contents_id/".$cAt->seoUrl($contents_og_image_title)."/";
$newSearch .="'><img alt='".$cAt->visulString($contents_og_image_title)."' src='";
$newSearch .=$contents_og_small_image_url;
$newSearch .="'></a>";
}
$newSearch .="<div class='entry-content'>";
$newSearch .="<ul class='post-meta'>";

/*Like?*/
$countLikeNow = new ebapps\blog\blog();
$countLikeNow ->count_like_now($contents_id);

if($countLikeNow->eBData)
{
foreach($countLikeNow->eBData as $valcountLikeNow): extract($valcountLikeNow);
	
if(isset($_SESSION['ebusername']) and $likeNow == 0)
{
/*Logined True with hober effect */
/*Like Now*/
if(isset($_REQUEST['add_for_like']))
{
extract($_REQUEST);
$countLike = new ebapps\blog\blog();
$countLike ->add_for_like($contents_id_for_like);
}
$newSearch .="<li><form method='post' id='$contents_id'><input type='hidden' name='contents_id_for_like' value='$contents_id' /><button type='submit' onclick='buTTonSelectBlog()' name='add_for_like'><i class='fa fa-heart fa-lg'></i></button></form></li>";
}
else 
{
$newSearch .="<li><i class='fa fa-heart fa-lg'></i></li>";
}
endforeach;
}  
$newSearch .="<li class='$contents_id'>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
	
if($totalPostLikes <= 1)
{
$newSearch .=$totalPostLikes." Like";
}
elseif($totalPostLikes > 1) 
{
$newSearch .=$totalPostLikes." Likes";
}
endforeach;
}
$newSearch .="</li>";

/* */				   
$newSearch .="<li><i class='fa fa-comments fa-lg'></i>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_contents($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
if($totalPostComments <= 1)
{
$newSearch .=$totalPostComments." Comment";
}
else 
{
$newSearch .=$totalPostComments." Comments";
}
endforeach;
}
$newSearch .="</a></li>"; 
$newSearch .="<li><i class='fa fa-clock-o fa-lg'></i><span class='day'>".date('d M Y',strtotime($contents_date))."</span></li>";
$newSearch .="</ul>";
$newSearch .="<div>";

$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="<div class='item-info'>";
$newSearch .="<div class='info-inner'>";
$newSearch .="<div class='item-content'>";
$newSearch .="<div class='action'>";
$newSearch .="<a  href='";
$newSearch .=outContentsLink."/contents/solve/$contents_id/".$cAt->seoUrl($contents_og_image_title)."/";
$newSearch .="' class='eb-cart-back'><span>Read More</span></a>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</li>";
endforeach;

$newSearch .="</ul>";
$newSearch .="</div>";
$newSearch .="</div>";

$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
$newSearch .="</div>";
echo $newSearch;
}
}
?>