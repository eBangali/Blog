<?php $obj= new ebapps\blog\blog(); $obj -> guestContentsLikeAll();
if($obj->eBData)
{
foreach($obj->eBData as $val): extract($val);
$likeList ="<div class='row'>";
$likeList .="<div class='col-xs-12 col-md-4'>";
$likeList .="<b><a  title='".$obj->visulString($contents_og_image_title)."' href='";
$likeList .=outContentsLink."/contents/solve/$contents_id/".strtolower($contents_category)."/".strtolower($contents_sub_category)."/";
$likeList .="'>";
$likeList .=$obj->visulString($contents_og_image_title);
$likeList .="</a></b>";
$likeList .="<br>";
if(!empty($contents_og_image_url) and file_exists(docRoot.$contents_og_image_url)) {
$likeList .="<a  title='".$obj->visulString($contents_og_image_title)."' href='";
$likeList .=outContentsLink."/contents/solve/$contents_id/".$obj->seoUrl($contents_og_image_title)."/";
$likeList .="'>";
$likeList .="<img class='img-responsive' alt='".$obj->visulString($contents_og_image_title)."' src='";
$likeList .=$contents_og_image_url;
$likeList .="'>";
$likeList .="</a>";
$likeList .="<br>";
}
//
$countComment = new ebapps\blog\blog();
$countComment ->count_total_like($contents_id);
if($countComment->eBData)
{
foreach($countComment->eBData as $valcountComment): extract($valcountComment);
$likeList .="<i class='fa fa-heart fa-lg'></i>  ";
$likeList .=$totalPostLikes;
endforeach;
}
$likeList .="</div>";
//
$likeList .="<div class='col-xs-12 col-md-8'>";

$likeList .="</div>";
$likeList .="</div>";
echo $likeList;
endforeach;
}
?>