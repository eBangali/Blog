<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
$obj = new ebapps\blog\blog();
$obj ->blog_control();
?>