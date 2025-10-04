<div class='breadcrumbs'>
<div class='container'>
<div class='row'>
<div class='col-xs-12'>
<ul>
<?php
if(isset($_GET['articleno']))
{
$articleno = strval($_GET['articleno']);
$contentsDetails = $this->item_details_contents($articleno);
$postApproveType = $contentsDetails['contents_approved'];
//
if($postApproveType == 'GPOST')
{
if(isset($_SESSION['memberlevel']))
{
if($_SESSION['memberlevel'] >= 1)
{
?>
<li class='home'><a  href='<?php echo outContentsLink; ?>/contents/' title='HOME'>HOME</a><span>/ </span></li>
<?php if(isset($_GET['articleno'])){$articleno = $_GET['articleno']; ?>
<?php $obj= new ebapps\blog\blog(); $obj -> itemDetailsContentsGuest($articleno); ?>
<?php  if($obj->eBData) { foreach($obj->eBData as $val){ extract($val); ?>
<li><a  href='<?php echo outContentsLink; ?>/contents/category/<?php echo $contents_id; ?>/'><strong><?php echo strtoupper($obj->visulString($contents_category)); ?></strong></a><span>/ </span></li>
<li><a  href='<?php echo outContentsLink; ?>/contents/subcategory/<?php echo $contents_id; ?>/'><strong><?php echo strtoupper($obj->visulString($contents_sub_category)); ?></strong></a></li>
<?php
}
}
}
}
}
}
elseif($postApproveType == 'OK')
{
?>
<li class='home'><a  href='<?php echo outContentsLink; ?>/contents/' title='HOME'>HOME</a><span>/ </span></li>
<?php if(isset($_GET['articleno'])){$articleno = $_GET['articleno']; ?>
<?php $obj= new ebapps\blog\blog(); $obj -> itemDetailsContents($articleno); ?>
<?php  if($obj->eBData) { foreach($obj->eBData as $val){ extract($val); ?>
<li><a  href='<?php echo outContentsLink; ?>/contents/category/<?php echo $contents_id; ?>/'><strong><?php echo strtoupper($obj->visulString($contents_category)); ?></strong></a><span>/ </span></li>
<li><a  href='<?php echo outContentsLink; ?>/contents/subcategory/<?php echo $contents_id; ?>/'><strong><?php echo strtoupper($obj->visulString($contents_sub_category)); ?></strong></a></li>
<?php
}
}
}
}
?>
<?php
}
?>
</ul>
</div>
</div>
</div>
</div>