<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
/* Initialize valitation */
$error = 0;
$search_contents_error = "";
?>
<?php
/* Data Sanitization */
include_once(ebsanitization.'/sanitization.php'); 
$sanitization = new ebapps\sanitization\formSanitization();
?>
<?php
if(isset($_REQUEST['submit_search_contents']))
{
extract($_REQUEST);

/* search_contents */
if (empty($_REQUEST["search_contents"]))
{
$search_contents_error = "Keyword required";
$error =1;
} 
/* valitation search_contents  */
elseif (! preg_match("/^([A-Za-z ]+){3,48}$/",$search_contents))
{
$search_contents_error = "Keyword required only letters are allowed";
$error =1;
}
else 
{
$search_contents = $sanitization -> test_input($_POST["search_contents"]);
}
?>
<?php } ?>
<div class='col-lg-7 col-md-5 col-sm-5 col-xs-3 hidden-xs category-search-form'>
<div class='search-box'>
<form id='search_mini_form' method='post'>
<input id='search' type='text' name='search_contents' value='<?php echo $search_contents_error;  ?>' class='searchbox' required >
<button type='submit' name='submit_search_contents' title='Search' class='search-btn-bg'><span>Search</span></button>
</form>
<div id='match-list'></div>
</div>
</div>
<div class='col-lg-3 col-md-4 col-sm-4 col-xs-12 card_wishlist_area'>
<div class='mm-toggle-wrap'>
<div class='mm-toggle'><i class='fa fa-align-justify'></i><span class='mm-label'>Menu</span> </div>
</div>
<div class='mgk-ebhome'> 
<div class='mini-ebhome'>
<div class='ebhome'><a  title='My Home' href='<?php echo hostingAndRoot."/"; ?>'></a> </div>
</div>
</div>
<!--Ends Home -->
<!--Start Notify -->
<div class='mgk-notify'>
<div class='mini-notify'>
<?php
include_once (eblogin.'/registration-page.php');
$notification = new ebapps\login\registration_page();
$notification ->notificttion();
if(isset($_SESSION['notify']))
{
?>
<div class='notify'><a  title='My Notifications' href='<?php echo outAccessLink; ?>/access-notify.php'><span class='cart_count'><?php echo $_SESSION['total_notify']; ?></span> </a></div>
<?php
}
?>
</div>
</div>
<!--Ends Notify -->

</div>