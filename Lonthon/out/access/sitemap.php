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
<?php include_once (ebaccess.'/access-permission-writer-minimum.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7'>
<div class="well">
<?php
$pubDate = date('c',time());
$xml_output  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xml_output .= "<urlset\n";
$xml_output .= "xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
$xml_output .= "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
$xml_output .= "xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
$xml_output .= "http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
/* ================================
   SOFT SECTION
   ================================ */
if (defined('ebSoft') && file_exists(ebSoft.'/soft.php')) {
    include_once(ebSoft.'/soft.php');

    if (class_exists('ebapps\soft\soft')) {
        $objDtetilsKey = new ebapps\soft\soft();

        if (method_exists($objDtetilsKey, 'soft_mrss')) {
            $objDtetilsKey->soft_mrss();
            if($objDtetilsKey->eBData >=1)
            {
            foreach($objDtetilsKey->eBData as $valobjDtetilsKey):
            extract($valobjDtetilsKey);
            $xml_output .= "<url>\n";
            $xml_output .= "\t<loc>".outSoftLink."/copy/details/$soft_appro_add_items_id/".strtolower($objDtetilsKey->seoUrl($soft_appro_category))."/".strtolower($objDtetilsKey->seoUrl($soft_appro_subcategory))."/</loc>";
            $xml_output .= "\t<lastmod>$pubDate</lastmod>";
            $xml_output .= "</url>\n";
            endforeach; 
            }
        }
    }
}

/* ================================
   OSMAN SECTION
   ================================ */
if (defined('ebbay') && file_exists(ebbay . '/ebcart.php')) {
    include_once(ebbay . '/ebcart.php');

    if (class_exists('ebapps\bay\ebcart')) {
        $objOsman = new ebapps\bay\ebcart();

        if (method_exists($objOsman, 'mrss_bay')) {
            $objOsman->mrss_bay();
            if($objOsman->eBData >=1){
            foreach($objOsman->eBData as $valobjOsman):
            extract($valobjOsman);
            $xml_output .= "<url>\n";
            $xml_output .= "\t<loc>".outBayLinkFull."/product/item-details-grid/$bay_showroom_approved_items_id/</loc>";
            $xml_output .= "\t<lastmod>$pubDate</lastmod>";
            $xml_output .= "</url>\n";
            endforeach; 
            }
        }
    }
}

/* ================================
   BLOG SECTION
   ================================ */
if (defined('ebblog') && file_exists(ebblog.'/blog.php')) {
    include_once(ebblog.'/blog.php');

    if (class_exists('ebapps\blog\blog')) {
        $objLonthon = new ebapps\blog\blog();
        if (method_exists($objLonthon, 'contents_mrss')) {
            $objLonthon->contents_mrss();
            if($objLonthon->eBData >=1)
            {
            foreach($objLonthon->eBData as $valobjLonthon):
            extract($valobjLonthon);
            $xml_output .= "<url>\n";
            $xml_output .= "\t<loc>".hostingAndRoot."/out/blog/contents/solve/$contents_id/".$objLonthon->seoUrl($contents_og_image_title)."/</loc>";
            $xml_output .= "\t<lastmod>$pubDate</lastmod>";
            $xml_output .= "</url>\n";
            endforeach; 
            }
        }
    }
}
$xml_output .=  "</urlset>";
$filenamepath =  eb."/sitemap.xml";

if (is_writable($filenamepath)) {
    $fp = fopen($filenamepath, 'w');
    fwrite($fp, $xml_output);
    fclose($fp);
}

/* Output the XML for verification/debugging */
echo $xml_output;
?>
</div>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>