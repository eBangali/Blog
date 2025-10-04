<?php
if(isset($articleno))
{ 
if(isset($_SESSION["memberlevel"]))
{
if($_SESSION["memberlevel"]<=8)
{
include_once ("query-visitor.php"); 
}
elseif($_SESSION["memberlevel"]>=9)
{
include_once ("query-admin.php"); 
}
}
}
$obj = new ebapps\blog\blog();
$obj->read_all_contents_query($articleno);
if($obj->eBData)
{
foreach($obj->eBData as $val)
{
extract($val);
$queryMe  ="<div class='well'>";
$queryMe .="By $blogs_username on ".date('d M Y',strtotime($blogs_comment_date));
$queryMe .="<p>".$obj->visulString($blogs_comment_details)."</p>";
$queryMe .="</div>"; 
echo $queryMe;  
}
}
?>
