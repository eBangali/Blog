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
<?php include_once (ebaccess."/access-permission-online-minimum.php"); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class="well">
<h2 title='Edit a Post'>Edit a Post</h2>
</div>
<?php include_once (ebblog.'/blog.php'); ?> 
<script language='javascript' type='text/javascript'>
/* Select B from A */
$(document).ready(function()
{
$("#contents_category").change(function()
{
var pic_name = $(this).val();
if(pic_name != '')  
{
$.ajax
({
type: "POST",
url: "contents-select-b-from-b.php",
data: "pic_name="+ pic_name,
success: function(option)
{
$("#contents_sub_category").html("<option value=''>Please Select</option>"+option);
}
});
}
else
{
$("#contents_sub_category").html("<option value=''>Please Select</option>");
}
return false;
});
});
</script>
<?php
$merchant = new ebapps\blog\blog();
/* Initialize valitation */
$error = 0;
$contents_category_error = "";
$contents_sub_category_error = "";
$contents_og_image_title_error = "";
$contents_og_image_what_to_do_error = "";
$contents_og_image_how_to_solve_error = "";
$contents_affiliate_link_error = "";
$contents_github_link_error = "";
$contents_preview_link_error = "";
$contents_video_link_error = "";
$captcha_error = "";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['contents_edit']))
{
extract($_REQUEST);
/* contents_category */
if(empty($_REQUEST["contents_category"]))
{
$contents_category_error = "<b class='text-warning'>Category required</b>";
$error =1;
} 
/* valitation contents_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_category))
{
$contents_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
else 
{
$contents_category = $sanitization -> test_input($_POST["contents_category"]);
}
/* contents_sub_category */
if (empty($_REQUEST["contents_sub_category"]))
{
$contents_sub_category_error = "<b class='text-warning'>Sub category required</b>";
$error =1;
} 
/* valitation contents_sub_category  */
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{1,32}$/u",$contents_sub_category))
{
$contents_sub_category_error = "<b class='text-warning'>Whitespace, single or double quotes, certain special characters are not allowed.</b>";
$error =1;
}
else 
{
$contents_sub_category = $_POST["contents_sub_category"];
}
/* contents_og_image_title */
if (empty($_REQUEST["contents_og_image_title"]))
{
$contents_og_image_title_error = "<b class='text-warning'>Title required</b>";
$error =1;
} 
elseif (!preg_match("/^[\p{L}\p{N} &\-\(\)\?]{5,59}$/u", $_POST["contents_og_image_title"]))
{
    $contents_og_image_title_error = "<b class='text-warning'>Single or double quotes, certain special characters are not allowed. Minimum characters 5 maximum characters 59</b>";
    $error =1;
}
/* SEO valitation contents_og_image_title */
elseif (strpos($_POST["contents_og_image_title"], $contents_sub_category) === false)
{
$contents_og_image_title_error = "<b class='text-warning'>Use mimimum one keyword as $contents_sub_category required</b>";
$error =1;
}
else 
{
$contents_og_image_title = $sanitization -> test_input($_POST["contents_og_image_title"]);
}

// Validate contents_og_image_what_to_do
if (empty($_POST["contents_og_image_what_to_do"]))
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Can not be empty.</b>";
$error = 1;
}

elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["contents_og_image_what_to_do"]))
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Only some tags are allowed.</b>";
$error = 1;
}
elseif(stripos($_POST["contents_og_image_what_to_do"], $contents_sub_category) === false)
{
$contents_og_image_what_to_do_error = "<b class='text-warning'>Use minimum one keyword: $contents_sub_category required.</b>";
$error = 1;
}
else
{
$contents_og_image_what_to_do_filtered = $sanitization->testArea($_POST["contents_og_image_what_to_do"]);
}

/*  Validate contents_og_image_how_to_solve */
if (empty($_POST["contents_og_image_how_to_solve"]))
{
$error = 0;
}

elseif(!$sanitization->checkDisallowedHTMLTagsAndValues($_POST["contents_og_image_how_to_solve"]))
{
$contents_og_image_how_to_solve_error = "<b class='text-warning'>Only some tags are allowed.</b>";
$error = 1;
}
elseif(stripos($_POST["contents_og_image_how_to_solve"], $contents_sub_category) === false)
{
$contents_og_image_how_to_solve_error = "<b class='text-warning'>Use minimum one keyword: $contents_sub_category required.</b>";
$error = 1;
}
else
{
$contents_og_image_how_to_solve_filtered = $sanitization->testArea($_POST["contents_og_image_how_to_solve"]);
}
/* contents_affiliate_link */ 
if(!empty($_REQUEST["contents_affiliate_link"]))
{
/* valitation contents_affiliate_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_affiliate_link))
{
$contents_affiliate_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_affiliate_link = $sanitization -> test_input($_POST["contents_affiliate_link"]);
}
}

/* contents_github_link */ 
if (!empty($_REQUEST["contents_github_link"]))
{
/* valitation contents_github_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_github_link))
{
$contents_github_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_github_link = $sanitization -> test_input($_POST["contents_github_link"]);
}
}

/* contents_preview_link */ 
if (!empty($_REQUEST["contents_preview_link"]))
{
/* valitation contents_preview_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.]{1,255})$/",$contents_preview_link))
{
$contents_preview_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_preview_link = $sanitization -> test_input($_POST["contents_preview_link"]);
}
}
/* contents_video_link */
if (!empty($_REQUEST["contents_video_link"]))
{
/* valitation contents_video_link  */
if (!preg_match("/^([a-zA-Z0-9\@\/\+\?\-\=\.\_]{1,255})$/",$contents_video_link))
{
$contents_video_link_error = "<b class='text-warning'>Without https:// and some characters</b>";
$error =1;
}
else 
{
$contents_video_link = $sanitization -> test_input($_POST["contents_video_link"]);
}
}
/* Captcha */
if(empty($_REQUEST["answer"]))
{
$captcha_error = "<b class='text-warning'>Captcha required</b>";
$error =1;
}
elseif (isset($_SESSION["captcha"]) and $_POST["answer"] !==$_SESSION["captcha"])
{
unset($_SESSION["captcha"]);
$captcha_error = "<b class='text-warning'>Captcha?</b>";
$error =1;
}
else
{
$sanitization->test_input($_POST["answer"]);
}
/* Submition form */
if($error == 0)
{
extract($_REQUEST);
$merchant->edit_update_contents_item($contents_id,$username_contents,$contents_approved,$contents_category,$contents_sub_category,$contents_og_image_title,$contents_og_image_what_to_do_filtered, $contents_og_login_required, $contents_og_image_how_to_solve_filtered, $contents_affiliate_link, $contents_github_link,$contents_preview_link,$contents_video_link);
}
//
}
?>
<?php
if(isset($_SESSION['memberlevel']))
{
if($_SESSION['memberlevel'] >= 9)
{
$obj = new ebapps\blog\blog();
$obj->edit_select_contents_item();
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updatecontents  ="<div class='well'>"; 
$updatecontents .="<form method='post' enctype='multipart/form-data'>";
$updatecontents .="<fieldset class='group-select'>";
$updatecontents .="<div>$contents_og_image_title_error</div>";
$updatecontents .="Title:";
$updatecontents .="<input class='form-control' type='text' name='contents_og_image_title' value='$contents_og_image_title' placeholder='Single or double quotes, certain special characters are not allowed.' required autofocus />";
$updatecontents .="<div>$contents_category_error</div>"; 
$updatecontents .="Select Category:"; 
$updatecontents .="<select class='form-control' id='contents_category' name='contents_category' required><option value='$contents_category'>".$obj->visulString($contents_category)."</option>"; 
$obj = new ebapps\blog\blog();
$obj->edit_select_contents_category();
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updatecontents .="<option value='$contents_category'>".$obj->visulString($contents_category)."</option>"; 
}
}
$updatecontents .="</select>"; 
$updatecontents .="<div>$contents_sub_category_error</div>"; 
$updatecontents .="Selected Sub Category:"; 
$updatecontents .="<select class='form-control' id='contents_sub_category' name='contents_sub_category' required><option value='$contents_sub_category'>".$obj->visulString($contents_sub_category)."</option></select>";
$updatecontents .="<div>$contents_og_image_what_to_do_error</div>";
$updatecontents .="What to do?";
$updatecontents .="<textarea id='WhatToDo' class='form-control' name='contents_og_image_what_to_do' placeholder='Certain special characters are not allowed.'>".htmlspecialchars($contents_og_image_what_to_do)."</textarea>";
$updatecontents .="Log In required to read more? <select class='form-control' name='contents_og_login_required' required><option value='$contents_og_login_required' selected>".$obj->visulString($contents_og_login_required)."</option><option value='YES'>YES</option><option value='NO'>NO</option></select>";
$updatecontents .="<div>$contents_og_image_how_to_solve_error</div>";
$updatecontents .="How to do?:";
$updatecontents .="<textarea id='HowToDoTextArea' class='form-control' name='contents_og_image_how_to_solve'>".htmlspecialchars($contents_og_image_how_to_solve)."</textarea>";
$updatecontents .="<div>$contents_affiliate_link_error</div>";
$updatecontents .="Affiliate Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_affiliate_link' value='$contents_affiliate_link' />";	
$updatecontents .="<div>$contents_github_link_error</div>";
$updatecontents .="Download Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_github_link' value='$contents_github_link' />";
$updatecontents .="<div>$contents_preview_link_error</div>";
$updatecontents .="Preview Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_preview_link' value='$contents_preview_link' />";
$updatecontents .="<div>$contents_video_link_error</div>";
$updatecontents .="Video Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_video_link' value='$contents_video_link'/>";
//
$updatecontents .="Captcha: ";
$updatecontents .= $captcha_error;
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
$updatecontents .="<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
$updatecontents .="<input class='form-control' type='text' name='answer' placeholder='Enter captcha' />";
// 
$updatecontents .="<input type='hidden' name='contents_approved' value='$contents_approved' />";
$updatecontents .="<div class='buttons-set'><button type='submit' name='contents_edit' title='Submit' class='button submit'> <span> Submit </span> </button></div>";
$updatecontents .="</fieldset>";
$updatecontents .="</form>";
$updatecontents .="</div>";
echo $updatecontents;  
}
}
}
else
{
$obj = new ebapps\blog\blog();
$obj->edit_select_contents_item();
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updatecontents  ="<div class='well'>"; 
$updatecontents .="<form method='post' enctype='multipart/form-data'>";
$updatecontents .="<fieldset class='group-select'>";
$updatecontents .="<div>$contents_og_image_title_error</div>";
$updatecontents .="Title:";
$updatecontents .="<input class='form-control' type='text' name='contents_og_image_title' value='$contents_og_image_title' placeholder='Single or double quotes, certain special characters are not allowed.' required autofocus />";
$updatecontents .="<div>$contents_category_error</div>";
$updatecontents .="Select Category: "; 
$updatecontents .="<select class='form-control' id='contents_category' name='contents_category' required><option value='$contents_category'>".$obj->visulString($contents_category)."</option>"; 
$obj = new ebapps\blog\blog();
$obj->edit_select_contents_category();
if($obj->eBData >= 1)
{
foreach($obj->eBData as $val)
{
extract($val);
$updatecontents .="<option value='$contents_category'>".$obj->visulString($contents_category)."</option>"; 
}
}
$updatecontents .="</select>"; 
$updatecontents .="<div>$contents_sub_category_error</div>"; 
$updatecontents .="Selected Sub Category:"; 
$updatecontents .="<select class='form-control' id='contents_sub_category' name='contents_sub_category' required><option value='$contents_sub_category'>".$obj->visulString($contents_sub_category)."</option></select>";
$updatecontents .="<div>$contents_og_image_what_to_do_error</div>";
$updatecontents .="What to do?";
$updatecontents .="<textarea id='WhatToDo' class='form-control' name='contents_og_image_what_to_do' placeholder='Certain special characters are not allowed.'>".htmlspecialchars($contents_og_image_what_to_do)."</textarea>";
$updatecontents .="<input type='hidden' name='contents_og_login_required' value='$contents_og_login_required' />";
$updatecontents .="<div>$contents_og_image_how_to_solve_error</div>";
$updatecontents .="How to do?:";
$updatecontents .="<textarea id='HowToDoTextArea' class='form-control' name='contents_og_image_how_to_solve'>".htmlspecialchars($contents_og_image_how_to_solve)."</textarea>";
$updatecontents .="<div>$contents_affiliate_link_error</div>";
$updatecontents .="Affiliate Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_affiliate_link' value='$contents_affiliate_link' />";	
$updatecontents .="<div>$contents_github_link_error</div>";
$updatecontents .="Download Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_github_link' value='$contents_github_link' />";
$updatecontents .="<div>$contents_preview_link_error</div>";
$updatecontents .="Preview Link:";
$updatecontents .="<input class='form-control' type='text' name='contents_preview_link' value='$contents_preview_link' />";
//
$updatecontents .="Captcha: ";
$updatecontents .= $captcha_error;
include_once(ebfromeb.'/captcha.php');
$cap = new ebapps\captcha\captchaClass();	
$captcha = $cap -> captchaFun();
$updatecontents .="<b class='btn btn-Captcha btn-sm gradient'>$captcha</b>";
$updatecontents .="<input class='form-control' type='text' name='answer' placeholder='Enter captcha' />";
// 
$updatecontents .="<input type='hidden' name='contents_approved' value='$contents_approved' />";
$updatecontents .="<div class='buttons-set'><button type='submit' name='contents_edit' title='Submit' class='button submit'> <span> Submit </span> </button></div>";
$updatecontents .="</fieldset>";
$updatecontents .="</form>";
$updatecontents .="</div>";
echo $updatecontents;  
}
}
}
}
?>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once(ebcontents."/contents-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>