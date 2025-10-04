<?php
$rightColumn  ="<div role='complementary' class='widget_wrapper13'>";
$rightColumn .="<div class='popular-posts widget widget__sidebar wow bounceInUp animated'>";
$rightColumn .="<h3 class='widget-title'><span>LATEST POSTS</span></h3>";
$rightColumn .="<div class='widget-content'>";
$rightColumn .="<ul class='posts-list unstyled clearfix'>";
$rightColumn .="<li>";
$objThumb = new ebapps\blog\blog(); $objThumb -> rightBarAllCategoryPost($articleno);
if($objThumb->eBData){foreach($objThumb->eBData as $valThumb): extract($valThumb);
if(!empty($contents_og_image_url) and file_exists(docRoot.$contents_og_image_url))
{
$rightColumn .="<a  href='";
$rightColumn .=outContentsLink."/contents/solve/$contents_id/".$objThumb->seoUrl($contents_og_image_title)."/";
$rightColumn .="'><img alt='".$objThumb->metaVisulString($contents_og_image_title)."' class='img-responsive' src='";
$rightColumn .=$contents_og_image_url;
$rightColumn .="'></a>";
}
$rightColumn .="<h4><a  href='";
$rightColumn .=outContentsLink."/contents/solve/$contents_id/".$objThumb->seoUrl($contents_og_image_title)."/";
$rightColumn .="'>".substr($objThumb->visulString($contents_og_image_title), 0, 50)." .."."</a></h4>";

$rightColumn .="<div class='entry-content'>";
$rightColumn .="<ul class='post-meta'>";

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
$rightColumn .="<li><form method='post' id='$contents_id'><input type='hidden' name='contents_id_for_like' value='$contents_id' /><button type='submit' onclick='buTTonSelectBlog()' name='add_for_like'><i class='fa fa-heart fa-lg'></i></button></form></li>";
}
else 
{
$rightColumn .="<li><i class='fa fa-heart fa-lg'></i></li>";
}
endforeach;
}  
$rightColumn .="<li class='$contents_id'>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
	
if($totalPostLikes <= 1)
{
$rightColumn .=$totalPostLikes." Like";
}
elseif($totalPostLikes > 1) 
{
$rightColumn .=$totalPostLikes." Likes";
}
endforeach;
}
$rightColumn .="</li>";
/* */				   
$rightColumn .="<li>";
$rightColumn .="<i class='fa fa-comments fa-lg'></i>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_contents($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
if($totalPostComments <= 1)
{
$rightColumn .=$totalPostComments." Comment";
}
else 
{
$rightColumn .=$totalPostComments." Comments";
}
endforeach;
}
$rightColumn .="</li>"; 
$rightColumn .="<li><i class='fa fa-clock-o fa-lg'></i><span class='day'>".date('d M Y',strtotime($contents_date))."</span></li>";
$rightColumn .="</ul>";
$rightColumn .="</div>";
endforeach;
}
$rightColumn .="</ul>";
$rightColumn .="</div>";
$rightColumn .="</div>";
$rightColumn .="</div>";
echo $rightColumn;
?>


