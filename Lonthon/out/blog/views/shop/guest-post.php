<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<?php
$guestPost ="<div class='site-content' id='primary'>";
$guestPost .="<div role='main' id='content'>";
$objPost = new ebapps\blog\blog(); 
$objPost -> guestPostContentsPostAll();
if($objPost->eBData){foreach($objPost->eBData as $valPost): extract($valPost);
$guestPost .="<article class='blog_entry clearfix wow bounceInUp animated' id='post-$contents_id'>";
$guestPost .="<header class='blog_entry-header clearfix'>";
$guestPost .="<div class='blog_entry-header-inner'>";
$guestPost .="<h2 class='blog_entry-title' title='".$objPost->visulString($contents_og_image_title)."'>";
$guestPost .=strtoupper($objPost->visulString($contents_og_image_title))."</h2>";
$guestPost .="</div>";
$guestPost .="</header>";
$guestPost .="<div class='entry-content'>";
$guestPost .="<div class='featured-thumb'>";
if(!empty($contents_og_image_url) and file_exists(docRoot.$contents_og_image_url))
{
$guestPost .="<a  href='";
$guestPost .=outContentsLink."/contents/solve/$contents_id/".$objPost->seoUrl($contents_og_image_title)."/";
$guestPost .="'><img class='img-responsive' alt='".$objPost->visulString($contents_og_image_title)."' src='";
$guestPost .=$contents_og_image_url;
$guestPost .="' /></a>";
}
$guestPost .="</div>";
/**/
$guestPost .="<div class='entry-content'>";
/* ###### */
$guestPost .="<ul class='post-meta'>";
/* User Profile Pic */
include_once(eblogin.'/registration-page.php');
$userPic = new ebapps\login\registration_page();
$userPic -> content_profile_pic($username_contents);
if($userPic->eBData)
{
foreach($userPic->eBData as $valuserPic): extract($valuserPic);
if(!empty($profile_picture_link) and file_exists(docRoot.$profile_picture_link))
{
$guestPost .="<li>Posted by <a  href='";
$guestPost .=outContentsLink."/contents/writer/$contents_id/".strtolower($username_contents)."/";
$guestPost .="'><img class='img-rounded' alt='$full_name' title='$full_name' height='13' src='$profile_picture_link' />";
$guestPost .="</a></li>";
}
else
{
$guestPost .="<li><i class='fa fa-user fa-lg'></i>Posted by <a  href='";
$guestPost .=outContentsLink."/contents/writer/$contents_id/".strtolower($username_contents)."/";
$guestPost .="'>$username_contents</a></li>";
}
endforeach;
}
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
$guestPost .="<li><form method='post' id='$contents_id'><input type='hidden' name='contents_id_for_like' value='$contents_id' /><button type='submit' onclick='buTTonSelectBlog()' name='add_for_like'><i class='fa fa-heart fa-lg'></i></button></form></li>";
}
else 
{
$guestPost .="<li><i class='fa fa-heart fa-lg'></i></li>";
}
endforeach;
}  
$guestPost .="<li class='$contents_id'>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
	
if($totalPostLikes <= 1)
{
$guestPost .=$totalPostLikes." Like";
}
elseif($totalPostLikes > 1) 
{
$guestPost .=$totalPostLikes." Likes";
}
endforeach;
}
$guestPost .="</li>";

/* */				   
$guestPost .="<li><i class='fa fa-comments fa-lg'></i>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_contents($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
if($totalPostComments <= 1)
{
$guestPost .=$totalPostComments." Comment";
}
else 
{
$guestPost .=$totalPostComments." Comments";	
}
endforeach;
}
$guestPost .="</li>"; 

$guestPost .="<li><i class='fa fa-clock-o fa-lg'></i><span class='day'>".date('d M Y',strtotime($contents_date))."</span></li>";
$guestPost .="</ul>";

$guestPost .="<div>";
$guestPost .=$contents_og_image_what_to_do;
$guestPost .="</div>";
$guestPost .="</div>";
$guestPost .="<p><a  class='eb-cart-back' href='";
$guestPost .=outContentsLink."/contents/solve/$contents_id/".$objPost->seoUrl($contents_og_image_title)."/";
$guestPost .="'>Read More</a></p>";
$guestPost .="</div>";
$guestPost .="<footer class='entry-meta'> This entry was posted in <a  title='View all posts in ".$contents_category."' href='";
$guestPost .=outContentsLink."/contents/category/$contents_id/";
$guestPost .="'>".$objPost->visulString($contents_category)."</a> and <a  title='View all posts in ".$contents_sub_category."' href='";
$guestPost .=outContentsLink."/contents/subcategory/$contents_id/";
$guestPost .="'>".$objPost->visulString($contents_sub_category)."</a></footer>";
$guestPost .="</article>";
endforeach;
}
$guestPost .="</div>";
$guestPost .="</div>";
echo $guestPost;
$obj = new ebapps\blog\blog(); 
echo $obj -> guestPostContentsPagination();
?>
