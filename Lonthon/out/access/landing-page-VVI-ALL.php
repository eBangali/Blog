<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php
if (isset($_SESSION['memberlevel']) >= 2)
{
include_once (ebOutSys.'/pos.php');
}
else
{
include_once (ebOutSoft.'/copy.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebcontents.'/contents.php');
}
else
{
include_once (ebcontents.'/contents.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebOutSoft.'/copy.php');
}
else
{
include_once (ebOutSoft.'/copy.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebOutSys.'/pos.php');
}
else
{
include_once (ebOutSys.'/pos.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 8)
{
include_once (ebproduct.'/product.php');
}
else
{
include_once (ebproduct.'/product.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebOutEvent.'/manager.php');
}
else
{
include_once (ebOutEvent.'/manager.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebcorporatePages.'/project.php');
}
else
{
include_once (ebcorporatePages.'/project.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebportfolio.'/portfolio.php');
}
else
{
include_once (ebportfolio.'/portfolio.php');
}
?>
<?php
if (isset($_SESSION['memberlevel']) >= 4)
{
include_once (ebcontents.'/contents.php');
}
else
{
include_once (ebcontents.'/contents.php');
}
?>