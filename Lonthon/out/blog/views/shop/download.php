<?php 
$obj = new ebapps\blog\blog(); 
$obj->contents_download($articleno);

if(!empty($obj->eBData)) {
    foreach($obj->eBData as $val):
        extract($val);
        if(!empty($contents_preview_link) || !empty($contents_affiliate_link) || !empty($contents_github_link)) { 
            echo "<div class='well'>";

            if(!empty($contents_preview_link)) {
                echo "<a class='eb-affili-back' href='".hypertext.$contents_preview_link."' target='_blank'>Preview</a>";
            }

            if(!empty($contents_affiliate_link)) {
                echo "<a class='eb-affili-back' href='".hypertext.$contents_affiliate_link."' target='_blank'>Buy Now</a>";
            }

            if(!empty($contents_github_link)) {
                echo "<a class='eb-affili-back' href='".hypertext.$contents_github_link."' target='_blank'>Download</a>";
            }

            echo "</div>";
        }

    endforeach; 
} 
?>
