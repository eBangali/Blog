<?php
$obj = new ebapps\blog\blog();
$obj -> contents_detail_how_to_do($articleno);
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val): extract($val);
if(!empty($contents_og_image_what_to_do))
{
$whatToDo ="<div class='well'>";
$whatToDo .=$obj->visulString($contents_og_image_what_to_do);
$whatToDo .="</div>";
echo $whatToDo;
}
endforeach;
}
include_once("download.php");
$obj = new ebapps\blog\blog();
$obj -> contents_detail_how_to_do($articleno);
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val): extract($val);
if(!empty($contents_og_image_how_to_solve))
{
if($contents_og_login_required =='YES')
{ 
if(empty($_SESSION["memberlevel"]))
{
include_once (eblogin."/session-inc-center-read-more.php");
}
if(isset($_SESSION["memberlevel"]))
{
$howToDo ="<div class='well'>";
$howToDo .=$obj->visulString($contents_og_image_how_to_solve);
$howToDo .="</div>";
echo $howToDo; 
}
}
if($contents_og_login_required =='NO')
{ 
$howToDo ="<div class='well'>";
$howToDo .=$obj->visulString($contents_og_image_how_to_solve);
$howToDo .="</div>";
echo $howToDo;
}
include_once("comments.php");
} 
endforeach; 
} 
?>
