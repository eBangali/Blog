<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
if(isset($_POST['getData']) and $_POST['getData'] == "OKDATA")
{
$postArticle ="<div class='site-content' id='primary'>";
$postArticle .="<div role='main' id='content'>";
$postNewContent = new ebapps\blog\blog(); 
$postNewContent -> postArticleAllScronDown();
if($postNewContent->eBData)
{
foreach($postNewContent->eBData as $visualpostNewConten): extract($visualpostNewConten);
$postArticle .="<article class='blog_entry clearfix wow bounceInUp animated' id='post-$contents_id'>";
$postArticle .="<header class='blog_entry-header clearfix'>";
$postArticle .="<div class='blog_entry-header-inner'>";
$postArticle .="<h1 title='".$postNewContent->visulString($contents_og_image_title)."'>";
$postArticle .=$postNewContent->visulString($contents_og_image_title)."</h1>";
$postArticle .="</div>";
$postArticle .="</header>";
$postArticle .="<div class='entry-content'>";
$postArticle .="<div class='featured-thumb'>";
if(file_exists(docRoot.$contents_og_image_url))
{
$postArticle .="<a  href='";
$postArticle .=outContentsLink."/contents/solve/$contents_id/".$postNewContent->seoUrl($contents_og_image_title)."/";
$postArticle .="'><img class='img-responsive' alt='".$postNewContent->visulString($contents_og_image_title)."' src='";
$postArticle .=$contents_og_image_url;
$postArticle .="' /></a>";
}
$postArticle .="</div>";
/**/
$postArticle .="<div class='entry-content'>";
$postArticle .="<ul class='post-meta'>";
/* User Profile Pic */
include_once(eblogin.'/registration-page.php');
$userPic = new ebapps\login\registration_page();
$userPic -> content_profile_pic($username_contents);
if($userPic->eBData)
{
foreach($userPic->eBData as $valuserPic): extract($valuserPic);
if(file_exists(docRoot.$profile_picture_link))
{
$postArticle .="<li>Posted by <a  href='";
$postArticle .=outContentsLink."/contents/writer/$contents_id/".strtolower($username_contents)."/";
$postArticle .="'><img class='img-rounded' alt='$full_name' title='$full_name' height='13' src='$profile_picture_link' />";
$postArticle .="</a></li>";
}
else
{
$postArticle .="<li><i class='fa fa-user fa-lg'></i>Posted by <a  href='";
$postArticle .=outContentsLink."/contents/writer/$contents_id/".strtolower($username_contents)."/";
$postArticle .="'>$username_contents</a></li>";
}
endforeach;
}
/* User Profile Pic */
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
$postArticle .="<li><form method='post' id='$contents_id'><input type='hidden' name='contents_id_for_like' value='$contents_id' /><button type='submit' onclick='buTTonSelectBlog()' name='add_for_like'><i class='fa fa-heart fa-lg'></i></button></form></li>";
}
else 
{
$postArticle .="<li><i class='fa fa-heart fa-lg'></i></li>";
}
endforeach;
}  

$postArticle .="<li class='$contents_id'>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
	
if($totalPostLikes <= 1)
{
$postArticle .=$totalPostLikes." Like";
}
elseif($totalPostLikes > 1) 
{
$postArticle .=$totalPostLikes." Likes";
}
endforeach;
}
$postArticle .="</li>";

/* */				   
$postArticle .="<li><i class='fa fa-comments fa-lg'></i>";
$countComment = new ebapps\blog\blog();
$countComment ->count_total_contents($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
if($totalPostComments <= 1)
{
$postArticle .=$totalPostComments." Comment";
}
else 
{
$postArticle .=$totalPostComments." Comments";
}
endforeach;
}
$postArticle .="</li>"; 

$postArticle .="<li><i class='fa fa-clock-o fa-lg'></i><span class='day'>".date('d M Y',strtotime($contents_date))."</span></li>";
$postArticle .="</ul>";
$postArticle .="<div>";
$postArticle .=$postNewContent->visulString($contents_og_image_what_to_do);
$postArticle .="</div>";
$postArticle .="</div>";
$postArticle .="<p>";
//
$postArticle .="<a  class='eb-cart-back' href='";
$postArticle .=outContentsLink."/contents/solve/$contents_id/".$postNewContent->seoUrl($contents_og_image_title)."/";
$postArticle .="'>Read More</a>";
//
$postArticle .="</p>";
$postArticle .="</div>";
$postArticle .="<footer class='entry-meta'> This entry was posted in <a  title='View all posts in ".$contents_category."' href='";
$postArticle .=outContentsLink."/contents/category/$contents_id/";
$postArticle .="'>".$postNewContent->visulString($contents_category)."</a> and <a  title='View all posts in ".$contents_sub_category."' href='";
$postArticle .=outContentsLink."/contents/subcategory/$contents_id/";
$postArticle .="'>".$postNewContent->visulString($contents_sub_category)."</a></footer>";
$postArticle .="</article>";
endforeach;
}
$postArticle .="</div>";
$postArticle .="</div>";
echo $postArticle;
}
?>