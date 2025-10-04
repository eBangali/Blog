<?php include_once (ebblog."/blog.php"); ?>
<?php
/* Initialize valitation */
$error = 0;
$blogs_comment_details_error = "*";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST["contents_submit_query"]))
{
extract($_REQUEST);
/* blogs_comment_details */
if(empty($_POST["blogs_comment_details"]))
{
$blogs_comment_details_error = "Query required";
$error =1;
}
/* Validation bay_support_requirements */
elseif (!preg_match("/^[\p{L}\p{N}\p{P}\p{S}\s]{3,300}$/u", $_POST["blogs_comment_details"]))
{
    $blogs_comment_details_error = "<b class='text-warning'>Comment must be between 3 and 300 characters.</b>";
    $error = 1;
}
/* Valitation blogs_comment_details */
elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["blogs_comment_details"]))
{
$blogs_comment_details_error = "<b class='text-warning'>Only h2 to h6, p, b, ol, ul, li, em, strong, a tags are allowed.</b>";
$error = 1;
}
else
{
$blogs_comment_details = $sanitization -> testArea($_POST["blogs_comment_details"]);
}
/* Submition form */
if($error ==0)
{
extract($_REQUEST);
$user = new ebapps\blog\blog();
$user->submit_contents_query_mini_merchant($blogs_system_id,$blogs_comment_details);
}
}
?>
<div class='well'>
<form method='post'>
<fieldset class='group-select'>
<legend><b>Query</b></legend>
<input type='hidden' name='blogs_system_id' value='<?php echo $articleno; ?>' />
Query Description: <?php echo $blogs_comment_details_error; ?>
<textarea class='form-control' name='blogs_comment_details' rows='6'></textarea>
<div class='buttons-set'>
<button type='submit' name='contents_submit_query' title='Submit' class='button submit'><span> Submit </span> </button>
</div>
</fieldset>
</form>
</div>
