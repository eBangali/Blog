<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php
if(isset($_SESSION['ebusername']))
{
session_unset();
session_destroy();
unset($_SESSION['ebusername']);
unset($_SESSION['ebpassword']);
setcookie('ebtokenusername', '', time() - 3600, '/');
?>
<script>
setTimeout(function(){
window.location.replace('<?php echo hostingAndRoot."/"; ?>');
}, 3000);
setTimeout();
</script>
<?php
}
?>
<?php include_once (eblayout.'/a-common-header-icon.php'); ?>
<meta property='og:title' content='About us - Vision, Mission, Products, Services and Tech' />
<meta property='og:description' content='About us - Vision, Mission, Products, Services and Tech' />
<meta name='twitter:card' content='summary_large_image'>
<meta name='twitter:site' content='@eBangali'>
<meta name='twitter:domain' content='ebangali.com'/>
<meta name='twitter:creator' content='@eBangali'>
<meta name='twitter:title' content='About us - Vision, Mission, Products, Services and Tech'>
<meta name='twitter:description' content='About us - Vision, Mission, Products, Services and Tech'>
<meta name='twitter:image' content='<?php echo themeResource; ?>/images/IoT-Ecosystem.jpg'/>
<meta name='twitter:url' content='<?php echo fullUrl; ?>'>
<title>About us - Vision, Mission, Products, Services and Tech</title>
<meta name='About us - Vision, Mission, Products, Services and Tech' content='About us - Vision, Mission, Products, Services and Tech' />
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
<?php include_once (ebcontents.'/views/shop/search.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class='well'>
<h2 title='Log Out'>Log Out</h2>
</div>
<div class='well'>
<b title='You have successfully logged out!'>You have successfully logged out!</b>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>   
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>