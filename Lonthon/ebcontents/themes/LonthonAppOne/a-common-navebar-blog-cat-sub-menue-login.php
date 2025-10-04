<?php include_once (ebblog.'/blog.php'); ?>
<?php
$category = new ebapps\blog\blog();
$category ->menu_category_contents();
?>
<?php if($category->eBData >= 1) { ?>
<?php foreach($category->eBData as $catval): extract($catval); ?>
<?php if (!empty($contents_category)){ ?>
<?php $cat = $contents_category; ?>
<li class='dropdown-submenu'> <a  href='<?php echo outContentsLink; ?>/contents/' class='dropdown-toggle' data-toggle='dropdown'><?php echo $category->visulString($contents_category); ?></a>
<ul class='dropdown-menu'>
<?php
$subcategory = new ebapps\blog\blog();
$subcategory ->menu_sub_category_contents($cat);
?>
<?php if($subcategory->eBData >= 1) { ?>
<?php foreach($subcategory->eBData as $subval): extract($subval); ?>
<?php if (!empty($contents_category) and !empty($contents_sub_category)){ ?>
<li><a  href='<?php echo outContentsLink; ?>/contents/category/<?php echo $contents_id; ?>/'><?php echo $subcategory->visulString($contents_sub_category); ?></a></li>
<?php } ?>
<?php endforeach; } ?>
</ul>
</li>
<?php } ?>
<?php endforeach; } ?>