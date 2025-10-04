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
<h2 title='Edit Sub Category'>Edit Sub Category</h2>
</div> 
<?php include_once (ebblog.'/blog.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$contents_sub_category_new_error = '';
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>

<?php
if(isset($_REQUEST['contents_new_sub_category_submit']))
{
extract($_REQUEST);

/* contents_sub_category_new */
if (empty($_REQUEST['contents_sub_category_new']))
{
$contents_sub_category_new_error = "<b class='text-warning'>Category name required</b>";
$error =1;
} 
/* valitation contents_sub_category_new  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_sub_category_new))
{
$contents_sub_category_new_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
/* valitation contents_sub_category_new */
elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST['contents_sub_category_new']))
{
$contents_sub_category_new_error = "<b class='text-warning'>Special characters are not allowed</b>";
$error = 1;
}
else 
{
$contents_sub_category_new = $sanitization -> test_input($_POST['contents_sub_category_new']);
}
/* Submition form */
if($error ==0){
$user = new ebapps\blog\blog();
extract($_REQUEST);
$user->eidt_submit_sub_category_update($contents_category_old,$contents_sub_category_old, $contents_sub_category_new);
}
//
}
?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
$objEditSubCateGory = new ebapps\blog\blog();
$objEditSubCateGory -> eidt_category_to_show_by_name(); 
if($objEditSubCateGory->eBData)
{
foreach($objEditSubCateGory->eBData as $valobjEditSubCateGory): extract($valobjEditSubCateGory);
$editSubCategoryBlog  = "<div class='well'>";					 
$editSubCategoryBlog .= "<form method='post'>";
$editSubCategoryBlog .= "<fieldset class='group-select'>";
$editSubCategoryBlog .= "<div>$contents_sub_category_new_error</div>";
$editSubCategoryBlog .= "Category :";
$editSubCategoryBlog .= "<input type='hidden' name='contents_category_old' value='$contents_category_in_blog_sub_category'>";
$editSubCategoryBlog .= "<input type='hidden' name='contents_sub_category_old' value='$contents_sub_category'>";
$editSubCategoryBlog .= "<input type='text' class='form-control' name='contents_sub_category_new' value='$contents_sub_category' required autofocus />";
$editSubCategoryBlog .= "<div class='buttons-set'>";
$editSubCategoryBlog .= "<button type='submit' name='contents_new_sub_category_submit' title='Submit' class='button submit'> <span> Submit </span> </button>";
$editSubCategoryBlog .= "</div>";
$editSubCategoryBlog .= "</fieldset>";
$editSubCategoryBlog .= "</form>";
$editSubCategoryBlog .= "</div>";
echo $editSubCategoryBlog;
endforeach;
}
?>

</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once(ebcontents."/contents-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>