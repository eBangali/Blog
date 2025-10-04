<?php include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
if(isset($_SESSION['ebusername']) and $_POST['contents_id_for_like'])
{
$objContentPub = new ebapps\blog\blog();
$objContentPub ->ajax_add_for_like($_SESSION['ebusername'], $_POST['contents_id_for_like']);
}
?>