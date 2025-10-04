<?php $obj= new ebapps\blog\blog(); $obj -> contents_detail_how_to_do_guest($articleno); ?>
<?php if($obj->eBData >= 1) { ?>
<?php foreach($obj->eBData as $val): extract($val); ?>
<?php if(!empty($contents_og_image_what_to_do)){ ?>
<?php 
$whatToDo ="<div class='well'>";
$whatToDo .=$obj->visulString($contents_og_image_what_to_do);
$whatToDo .="</div>";
echo $whatToDo;  
?>
<?php } endforeach; } ?>
<?php $obj= new ebapps\blog\blog(); $obj -> contents_detail_how_to_do_guest($articleno); ?>
<?php if($obj->eBData >= 1) { ?>
<?php foreach($obj->eBData as $val): extract($val); ?>
<?php if(!empty($contents_og_image_how_to_solve)){ ?>
<?php
if(isset($articleno))
{ 
if(empty($_SESSION["memberlevel"]) && $contents_og_login_required =='YES')
{
?>
<?php 
$loginToReadMore  ="<div class='well'>";
$loginToReadMore .="<a  href='".outAccessLink."/home.php'";
$loginToReadMore .=" class='eb-cart-back'><span>Log In to Read More</span></a>";
$loginToReadMore .="</div>";
echo $loginToReadMore;
?>
<?php	
}
else
{
$howToDo ="<div class='well'>";
$howToDo .=$obj->visulString($contents_og_image_how_to_solve);
$howToDo .="</div>";
echo $howToDo;  
}
}
?>
<?php include_once("guest-download.php"); ?>
<?php include_once("comments.php"); ?>
<?php 
} 
endforeach; 
} 
?>
