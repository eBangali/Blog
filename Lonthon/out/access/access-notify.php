<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
<?php include_once (eblogin.'/session-inc-reffer.php'); ?>
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
<?php include_once (ebcontents.'/views/shop/search.php'); ?>
<?php include_once (ebaccess."/access-permission-online-minimum.php"); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>
</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>
<div class="well">
<h2 title='Notifications'>Notifications</h2>
</div>
<?php
if (isset($_SESSION['memberlevel']) && $_SESSION['memberlevel'] >= 1) {

    // ================================
    // eBay Cart Notifications
    // ================================
    if (defined('ebbay') && file_exists(ebbay.'/ebcart.php')) {
        include_once(ebbay.'/ebcart.php');

        if (class_exists('ebapps\bay\ebcart')) {
            $objAlert = new ebapps\bay\ebcart();

            if (method_exists($objAlert, 'getGroupedBayNotify')) {
                $objAlert->getGroupedBayNotify();

                if (!empty($objAlert->eBData)) {
                    foreach ($objAlert->eBData as $valobjAlert) {
                        if (is_array($valobjAlert)) {
                            extract($valobjAlert, EXTR_SKIP);
                        }
                        if (!empty($blogs_comment_details)) {
                            echo "<div class='well'>$blogs_comment_details</div>";
                        }
                    }
                }
            }
        }
    }

    // ================================
    // Blog Comments Notifications
    // ================================
    if (defined('ebblog') && file_exists(ebblog.'/blog.php')) {
        include_once(ebblog.'/blog.php');

        if (class_exists('ebapps\blog\blog')) {
            $objAlert = new ebapps\blog\blog();

            if (method_exists($objAlert, 'getGroupedBlogComments')) {
                $objAlert->getGroupedBlogComments();

                if (!empty($objAlert->eBData)) {
                    foreach ($objAlert->eBData as $valobjAlert) {
                        if (is_array($valobjAlert)) {
                            extract($valobjAlert, EXTR_SKIP);
                        }

                        if (!empty($contents_id) && !empty($contents_og_image_title)) {
                            $url = outContentsLink."/contents/solve/$contents_id/".$objAlert->seoUrl($contents_og_image_title)."/";
                            echo "<div class='well'><a href='$url'>$blogs_comment_details</a></div>";
                        }
                    }
                }
            }
        }
    }
}
?>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>