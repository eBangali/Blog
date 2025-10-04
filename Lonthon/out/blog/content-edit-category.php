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
<h2 title='Edit Category'>Edit Category</h2>
</div> 
<?php include_once (ebblog.'/blog.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$contents_category_error = '';
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>

<?php
if(isset($_REQUEST['contents_category_submit_edit']))
{
extract($_REQUEST);

/* contents_category */
if (empty($_REQUEST['contents_category']))
{
$contents_category_error = "<b class='text-warning'>Category name required</b>";
$error =1;
} 
/* valitation contents_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_category))
{
$contents_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
/* valitation contents_category */
elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST['contents_category']))
{
$contents_category_error = "<b class='text-warning'>Special characters are not allowed</b>";
$error = 1;
}
else 
{
$contents_category = $sanitization -> test_input($_POST['contents_category']);
}
/* Submition form */
if($error ==0)
{
$user = new ebapps\blog\blog();
extract($_REQUEST);
$user->eidt_submit_category_blog($contents_category_old, $contents_category);
}
//
}
?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
$objEditCateGory = new ebapps\blog\blog();
$objEditCateGory -> eidt_category_show_by_name(); 
if($objEditCateGory->eBData)
{
foreach($objEditCateGory->eBData as $valobjEditCateGory): extract($valobjEditCateGory);
$editCategoryBlog  = "<div class='well'>";					 
$editCategoryBlog .= "<form method='post'>";
$editCategoryBlog .= "<fieldset class='group-select'>";
$editCategoryBlog .= "<div>$contents_category_error</div>";
$editCategoryBlog .= "Category : ";
$editCategoryBlog .= "<input type='hidden' name='contents_category_old' value='$contents_category'>";
$editCategoryBlog .= "<input type='text' class='form-control' name='contents_category' value='$contents_category' required autofocus />";
$editCategoryBlog .= "<div class='buttons-set'>";
$editCategoryBlog .= "<button type='submit' name='contents_category_submit_edit' title='Submit' class='button submit'> <span> Submit </span> </button>";
$editCategoryBlog .= "</div>";
$editCategoryBlog .= "</fieldset>";
$editCategoryBlog .= "</form>";
$editCategoryBlog .= "</div>";
echo $editCategoryBlog;
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