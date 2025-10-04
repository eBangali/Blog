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
<h2 title='Add Category'>Add Category</h2>
</div>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
/* Initialize validation */
$error = 0;
$contents_category_error = '';
?>

<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>

<?php
if (isset($_REQUEST['contents_category_submit'])) 
{
    extract($_REQUEST);

    /* contents_category */
    if (empty($_REQUEST["contents_category"])) 
    {
        $contents_category_error = "<b class='text-warning'>Category required</b>";
        $error = 1;
    } 
    elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u", $contents_category)) 
    {
        $contents_category_error = "<b class='text-warning'>Single or double quotes, certain special characters are not allowed. Minimum characters 1 maximum characters 48</b>";
        $error = 1;
    } 
    /* validation with disallowed tags/values */
    elseif (!$sanitization->checkDisallowedHTMLTagsAndValues($_REQUEST['contents_category'])) 
    {
        $contents_category_error = "<b class='text-warning'>Special characters are not allowed</b>";
        $error = 1;
    } 
    else 
    {
        $contents_category_filtered = $sanitization->test_input($_REQUEST["contents_category"]);
    }

    /* Submission form */
    if ($error == 0) 
    {
        $user = new ebapps\blog\blog();
        $user->submit_contents_category($contents_category_filtered);
    }
}
?>

<div class='well'>
<form method='post'>
<fieldset class='group-select'>
Category: <?php echo $contents_category_error;  ?>
<input class='form-control' type='text' name='contents_category' placeholder="'Long Sleeve Men-s T-shirts -and- Pent' will be shown as 'Long Sleeve Men's T shirts & Pent'" required autofocus />
<div class='buttons-set'><button type='submit' name='contents_category_submit' title='Submit' class='button submit'> <span> Submit </span> </button></div>
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
<th>EDIT</th>
</tr>
</thead>
<tbody>
<?php include_once (ebblog.'/blog.php'); ?>
<?php $obj= new ebapps\blog\blog(); $obj -> select_category_to_show_all(); ?>
<?php if($obj->eBData){ foreach($obj->eBData as $val): extract($val); ?>
<?php 
$categoryBlog = "<tr>";					 
$categoryBlog .= "<td>".$obj->visulString($contents_category)."</td>";
$categoryBlog .= "<td><form action='content-edit-category.php' method='get'><input type='hidden' name='contents_category_old' value='$contents_category' /><button type='submit' name='edit_contents_category' value='Edit' class='button submit' alt='Edit'><b>Edit</b></button></form></td>";
$categoryBlog .= "</tr>";
echo $categoryBlog;
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