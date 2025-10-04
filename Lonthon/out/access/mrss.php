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
<?php
// Set the publication date
$pubDate = date("r");

// Start XML output with the new structure
$xml_output  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xml_output .= "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:google=\"http://base.google.com/ns/1.0\">\n";
$xml_output .= "<channel>\n";

/* Add your eCommerce website's main details */
/* Include registration page for site owner title */
include_once (eblogin.'/registration-page.php');
$obj = new ebapps\login\registration_page();
$obj->site_owner_title();

/* Check and output site owner titles */
if (!empty($obj->eBData)) {
    foreach ($obj->eBData as $val) {
        extract($val);
        if (!empty($business_title_one)) {
            $xml_output .= "<title>$business_title_one</title>\n";
        }
    }
}

/*  Output link */
$xml_output .= "<link>".hostingAndRoot."/</link>\n";

/*  Check and output site owner descriptions */
if (!empty($obj->eBData)) {
    foreach ($obj->eBData as $val) {
        extract($val);
        if (!empty($business_title_two)) {
            $xml_output .= "<description>$business_title_two</description>\n";
        }
    }
}

/* Additional channel metadata */
$xml_output .= "<language>en-us</language>\n";
$xml_output .= "<pubDate>$pubDate</pubDate>\n";
$xml_output .= "<lastBuildDate>$pubDate</lastBuildDate>\n";
$xml_output .= "<copyright>Copyright (c) ".date("Y")." ".domain."</copyright>\n";

/* ====================================================
   SOFT SECTION
   ==================================================== */
if (defined('ebSoft') && file_exists(ebSoft.'/soft.php')) {
    include_once(ebSoft.'/soft.php');

    if (class_exists('ebapps\soft\soft')) {
        $objSoft = new ebapps\soft\soft();

        if (method_exists($objSoft, 'soft_mrss')) {
            $objSoft->soft_mrss();

            if ($objSoft->eBData >= 1) {
                foreach ($objSoft->eBData as $valObjSoft):
                    extract($valObjSoft);

                    $titleDesc = $objSoft->metaVisulString($soft_subcategory_keywords_value);

                    $xml_output .= "<item>\n";
                    $xml_output .= "\t<title>$titleDesc</title>\n";
                    $xml_output .= "\t<link>".hostingAndRoot."/".strtolower($objSoft->seoUrl($soft_subcategory_keywords_value))."-$soft_add_items_id_in_subcategory_keywords.html</link>\n";
                    $xml_output .= "\t<description>$titleDesc</description>\n";
                    $xml_output .= "\t<pubDate>".htmlspecialchars($soft_appro_upload_date ?? '')."</pubDate>\n";

                    if (!empty($soft_subcategory_keywords_video)) {
                        $xml_output .= "\t<media:content url=\"".hypertext.domain."$soft_subcategory_keywords_video\" type=\"video/mp4\" medium=\"video\" duration=\"120\" height=\"720\" width=\"1280\">\n";
                        $xml_output .= "\t\t<media:description>$titleDesc</media:description>\n";
                        $xml_output .= "\t\t<media:title>$titleDesc</media:title>\n";
                        $xml_output .= "\t\t<media:thumbnail url=\"".hypertext.domain."$soft_subcategory_keywords_image\" height=\"90\" width=\"120\" />\n";
                        $xml_output .= "\t\t<media:keywords>".$objSoft->metaVisulString($soft_appro_subcategory).", ".$objSoft->metaVisulString($soft_appro_category).", $titleDesc</media:keywords>\n";
                        $xml_output .= "\t</media:content>\n";
                    }

                    $xml_output .= "\t<google:expiration_date>".date("r", strtotime("+1 year"))."</google:expiration_date>\n";
                    $xml_output .= "\t<google:rating>general</google:rating>\n";
                    $xml_output .= "\t<google:category>E-commerce</google:category>\n";
                    $xml_output .= "</item>\n";

                endforeach;
            }
        }
    }
}

/* ====================================================
   OSMAN SECTION
   ==================================================== */
if (defined('ebbay') && file_exists(ebbay.'/ebcart.php')) {
    include_once(ebbay.'/ebcart.php');

    if (class_exists('ebapps\bay\ebcart')) {
        $objBay = new ebapps\bay\ebcart();

        if (method_exists($objBay, 'mrss_bay')) {
            $objBay->mrss_bay();
            if($objBay->eBData >=1){
            foreach($objBay->eBData as $valobjBay):
            extract($valobjBay);
            $xml_output .= "<item>\n";
            $xml_output .= "\t<title>".$objBay->metaVisulString($s_og_image_title)."</title>\n";
            $xml_output .= "\t<link>".outBayLinkFull."/product/item-details-grid/$bay_showroom_approved_items_id/</link>\n";
            $xml_output .= "\t<description>".$objBay->metaVisulString($s_og_image_title)."</description>\n";
            $xml_output .= "\t<pubDate>$s_date</pubDate>\n";
            $xml_output .= "\t<media:content url=\"".hypertext.domain."$s_video_link\" type=\"video/mp4\" medium=\"video\" duration=\"120\" height=\"720\" width=\"1280\">\n";
            $xml_output .= "\t\t<media:description>".$objBay->metaVisulString($s_og_image_title)."</media:description>\n";
            $xml_output .= "\t\t<media:title>".$objBay->metaVisulString($s_og_image_title)."</media:title>\n";
            $xml_output .= "\t\t<media:thumbnail url=\"".hypertext.domain."$s_og_image_url\" height=\"90\" width=\"120\" />\n";
            $xml_output .= "\t\t<media:keywords>".$objBay->metaVisulString($s_category_d).", ".$objBay->metaVisulString($s_category_c)."</media:keywords>\n";
            $xml_output .= "\t</media:content>\n";
            $xml_output .= "\t<google:expiration_date>".date("r", strtotime("+1 year"))."</google:expiration_date>\n";
            $xml_output .= "\t<google:rating>general</google:rating>\n";
            $xml_output .= "\t<google:category>E-commerce</google:category>\n";
            $xml_output .= "</item>\n";
            endforeach; 
            }
        }
    }
}

/* ====================================================
   BLOG SECTION
   ==================================================== */
if (defined('ebblog') && file_exists(ebblog.'/blog.php')) {
    include_once(ebblog.'/blog.php');

    if (class_exists('ebapps\blog\blog')) {
        $objBlog = new ebapps\blog\blog();

        if (method_exists($objBlog, 'contents_mrss')) {
            $objBlog->contents_mrss();

            if ($objBlog->eBData >= 1) {
                foreach ($objBlog->eBData as $valObjBlog):
                    extract($valObjBlog);

                    $xml_output .= "<item>\n";
                    $xml_output .= "\t<title>".$objBlog->metaVisulString($contents_og_image_title)."</title>\n";
                    $xml_output .= "\t<link>".hostingAndRoot."/out/blog/contents/solve/$contents_id/".$objBlog->seoUrl($contents_og_image_title)."/</link>\n";
                    $xml_output .= "\t<description>".$objBlog->metaVisulString($contents_og_image_title)."</description>\n";
                    $xml_output .= "\t<pubDate>$contents_date</pubDate>\n";

                    if (!empty($contents_video_link)) {
                        $xml_output .= "\t<media:content url=\"".hypertext.domain."$contents_video_link\" type=\"video/mp4\" medium=\"video\" duration=\"120\" height=\"720\" width=\"1280\">\n";
                        $xml_output .= "\t\t<media:description>".$objBlog->metaVisulString($contents_og_image_title)."</media:description>\n";
                        $xml_output .= "\t\t<media:title>".$objBlog->metaVisulString($contents_og_image_title)."</media:title>\n";
                        $xml_output .= "\t\t<media:thumbnail url=\"".hypertext.domain."$contents_og_image_url\" height=\"90\" width=\"120\" />\n";
                        $xml_output .= "\t\t<media:keywords>".$objBlog->metaVisulString($contents_category).", ".$objBlog->metaVisulString($contents_sub_category)."</media:keywords>\n";
                        $xml_output .= "\t</media:content>\n";
                    }

                    $xml_output .= "\t<google:expiration_date>".date("r", strtotime("+1 year"))."</google:expiration_date>\n";
                    $xml_output .= "\t<google:rating>general</google:rating>\n";
                    $xml_output .= "\t<google:category>E-commerce</google:category>\n";
                    $xml_output .= "</item>\n";

                endforeach;
            }
        }
    }
}

$xml_output .= "</channel>\n";
$xml_output .= "</rss>";
/* Save the XML to a file */
$filenamepath = eb."/mrss.xml";

if (is_writable($filenamepath)) {
    $fp = fopen($filenamepath, 'w');
    fwrite($fp, $xml_output);
    fclose($fp);
}

/* Output the XML for verification/debugging */
echo $xml_output;
?>
</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php include_once (ebaccess."/access-my-account.php"); ?>
</div>
</div>
</div>	
<?php include_once (eblayout.'/a-common-footer.php'); ?>