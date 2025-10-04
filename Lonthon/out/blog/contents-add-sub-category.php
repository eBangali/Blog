<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (eblogin.'/session-inc.php'); ?>
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<?php include_once (eblayout.'/a-common-header-title-one.php'); ?>
<?php include_once (eblayout.'/a-common-header-meta-scripts.php'); ?>
<?php include_once (eblayout.'/a-common-page-id-start.php'); ?>
<?php include_once (eblayout.'/a-common-header.php'); ?>
<nav>
  <div class='container'>
    <div>
      <?php include_once (eblayout.'/a-common-navebar.php'); ?>
      <?php include_once (eblayout.'/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>
<?php include_once (eblayout.'/a-common-page-id-end.php'); ?>
<?php include_once (ebaccess.'/access-permission-admin-minimum.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='Add Sub Category'>Add Sub Category</h2>
</div>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$contentCategory_error = '';
$contentsSub_category_error = '';
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>

<?php
$user = new ebapps\blog\blog();
if(isset($_REQUEST['submit_contents_sub_category']))
{
extract($_REQUEST);

/* contents_category */
if (empty($_REQUEST['contentCategory']))
{
$contentCategory_error = "<b class='text-warning'>Category name required</b>";
$error =1;
} 
/* valitation contentCategory  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u", $contentCategory))
{
$contentCategory_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
/* valitation contentCategory */
elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST['contentCategory']))
{
$contentCategory_error = "<b class='text-warning'>Special characters are not allowed</b>";
$error = 1;
}
else 
{
$contentCategory = $sanitization -> test_input($_POST['contentCategory']);
}
/* contentsSub_category */
if (empty($_REQUEST['contentsSub_category']))
{
$contentsSub_category_error = "<b class='text-warning'>Sub Category required</b>";
$error =1;
} 
/* valitation contentsSub_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u", $contentsSub_category))
{
$contentsSub_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
else 
{
$contentsSub_category = $sanitization -> test_input($_POST['contentsSub_category']);
}
/* Submition form */
if($error ==0){
$user = new ebapps\blog\blog();
extract($_REQUEST);
$user->submit_contents_sub_category($contentCategory, $contentsSub_category);
}
//
}
?>
<div class='well'>
<form method='post'>
<fieldset class='group-select'>
Select Category: <?php echo $contentCategory_error;  ?>
<select class='form-control' name='contentCategory'><option selected='selected'>Please Select</option><?php $user->select_contents_category(); ?></select>
Sub Category: <?php echo $contentsSub_category_error;  ?>
<input class='form-control' type='text' name='contentsSub_category' placeholder="'Long Sleeve Men-s T-shirts -and- Pent' will be shown as 'Long Sleeve Men's T shirts & Pent'" required autofocus />
<div class='buttons-set'><button type='submit' name='submit_contents_sub_category' title='Submit' class='button submit'> <span> Submit </span> </button></div>
</fieldset>
</form>
</div>
<div class='well'>
<article>
<div class="panel panel-default table-responsive">
<table class="table">
<thead>
<tr>
<th>CATEGORY</th>
<th>SUB CATEGORY</th>
<th>EDIT</th>
</tr>
</thead>
<tbody>
<?php include_once (ebblog.'/blog.php'); ?>
<?php $obj= new ebapps\blog\blog(); $obj -> select_sub_category_to_show_all(); ?>
<?php if($obj->eBData){ foreach($obj->eBData as $val): extract($val); ?>
<?php 
$zoneDhl = "<tr>";					 
$zoneDhl .= "<td>".$obj->visulString($contents_category_in_blog_sub_category)."</td>";
$zoneDhl .= "<td>".$obj->visulString($contents_sub_category)."</td>";
$zoneDhl .= "<td><form action='content-edit-sub-category.php' method='get'><input type='hidden' name='contents_sub_category_old' value='$contents_sub_category' /><input type='hidden' name='contents_category_in_blog_sub_category_old' value='$contents_category_in_blog_sub_category' /><button type='submit' name='contents_sub_category_eidt' value='Edit' class='button submit' alt='Edit'><b>Edit</b></button></form></td>";
$zoneDhl .= "</tr>";
echo $zoneDhl;
endforeach;
}
?>    
</tbody>
</table>
</div>
</article>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once(ebcontents."/contents-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>