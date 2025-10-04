<?php 
include_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/initialize.php'); 
include_once (ebblog.'/blog.php');

if(isset($_GET['articleno'])) {
    $articleno = strval($_GET['articleno']); 
    $objObj = new ebapps\blog\blog(); 
    $objObj->content_item_details_seo($articleno); 
    
    if($objObj->eBData >= 1) {
        foreach($objObj->eBData as $valobjOg) {
            extract($valobjOg); 
            $mataData = "<meta property='og:site_name' content='".domain."'>";
            $mataData .= "\n";
            $mataData .= "<meta property='og:url' content='";
            $mataData .= isset($_SESSION['ebusername']) ? fullUrl.$_SESSION['ebusername'].'/' : fullUrl;
            $mataData .= "'>";
            $mataData .= "\n";

            // Check if video exists, else fallback to image
            if(!empty($contents_video_link)) {
                $mataData .= "<meta property='og:video:type' content='video/mp4'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:video:url' content='".hostingNameImage.$contents_video_link."'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:video:secure_url' content='".hostingNameImage.$contents_video_link."'>";
                $mataData .= "\n";
            } else {
                $mataData .= "<meta property='og:image:type' content='image/jpeg'>";
                $mataData .= "\n";
                if(!empty($contents_og_image_url)) {
                    $mataData .= "<meta property='og:image' content='".hostingNameImage.$contents_og_image_url."'>";
                    $mataData .= "\n";
                    $mataData .= "<meta property='og:image:url' content='".hostingNameImage.$contents_og_image_url."'>";
                    $mataData .= "\n";
                }
                $mataData .= "<meta property='og:image:width' content='1024'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:image:height' content='717'>";
                $mataData .= "\n";
            }

            // Title and Description for both OG and Twitter
            $mataData .= "<meta property='og:title' content='".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta property='og:description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:card' content='summary_large_image'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:site' content='@eBangali'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:creator' content='@eBangali'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:title' content='".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";

            if(!empty($contents_og_image_url)) {
                $mataData .= "<meta name='twitter:image' content='".hostingNameImage.$contents_og_image_url."'>";
            }
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:url' content='";
            $mataData .= isset($_SESSION['ebusername']) ? fullUrl.$_SESSION['ebusername'].'/' : fullUrl;
            $mataData .= "'>";
            $mataData .= "\n";
            $mataData .= "<title>".$objObj->visulString($contents_og_image_title)."</title>";
            $mataData .= "\n";
            $mataData .= "<meta name='description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            
            // Output all generated meta data
            echo $mataData;
        }
    }
} else {
    // Fallback content if articleno is not set or invalid
    $objObj = new ebapps\blog\blog(); 
    $objObj->content_item_details_seo_last(); 
    if($objObj->eBData >= 1) {
        foreach($objObj->eBData as $valobjOg) {
            extract($valobjOg);
            $mataData = "<meta property='og:site_name' content='".domain."'>";
            $mataData .= "\n";
            $mataData .= "<meta property='og:url' content='";
            $mataData .= isset($_SESSION['ebusername']) ? fullUrl.$_SESSION['ebusername'].'/' : fullUrl;
            $mataData .= "'>";
            $mataData .= "\n";

            // Check if video exists, else fallback to image
            if(!empty($contents_video_link)) {
                $mataData .= "<meta property='og:video:type' content='video/mp4'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:video:url' content='".hostingNameImage.$contents_video_link."'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:video:secure_url' content='".hostingNameImage.$contents_video_link."'>";
                $mataData .= "\n";
            } else {
                $mataData .= "<meta property='og:image:type' content='image/jpeg'>";
                $mataData .= "\n";
                if(!empty($contents_og_image_url)) {
                    $mataData .= "<meta property='og:image' content='".hostingNameImage.$contents_og_image_url."'>";
                    $mataData .= "\n";
                    $mataData .= "<meta property='og:image:url' content='".hostingNameImage.$contents_og_image_url."'>";
                    $mataData .= "\n";
                }
                $mataData .= "<meta property='og:image:width' content='1024'>";
                $mataData .= "\n";
                $mataData .= "<meta property='og:image:height' content='717'>";
                $mataData .= "\n";
            }

            // Title and Description for both OG and Twitter
            $mataData .= "<meta property='og:title' content='".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta property='og:description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:card' content='summary_large_image'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:site' content='@eBangali'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:creator' content='@eBangali'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:title' content='".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";

            if(!empty($contents_og_image_url)) {
                $mataData .= "<meta name='twitter:image' content='".hostingNameImage.$contents_og_image_url."'>";
            }
            $mataData .= "\n";
            $mataData .= "<meta name='twitter:url' content='";
            $mataData .= isset($_SESSION['ebusername']) ? fullUrl.$_SESSION['ebusername'].'/' : fullUrl;
            $mataData .= "'>";
            $mataData .= "\n";
            $mataData .= "<title>".$objObj->visulString($contents_og_image_title)."</title>";
            $mataData .= "\n";
            $mataData .= "<meta name='description' content='".$objObj->metaVisulString($contents_category).", ".$objObj->metaVisulString($contents_sub_category).", ".$objObj->metaVisulString($contents_og_image_title)."'>";
            $mataData .= "\n";
            
            // Output all generated meta data
            echo $mataData; 
        }
    }
}
?>
