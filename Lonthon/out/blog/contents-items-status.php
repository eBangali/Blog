<?php include_once(dirname(dirname(dirname(__FILE__))) . '/initialize.php'); ?>
<?php include_once(eblogin . '/session-inc.php'); ?>
<?php include_once(eblayout . '/a-common-header-icon.php'); ?>
<?php include_once(eblayout . '/a-common-header-title-one.php'); ?>
<?php include_once(eblayout . '/a-common-header-meta-scripts.php'); ?>
<?php include_once(eblayout . '/a-common-page-id-start.php'); ?>
<?php include_once(eblayout . '/a-common-header.php'); ?>

<nav>
  <div class='container'>
    <div>
      <?php include_once(eblayout . '/a-common-navebar.php'); ?>
      <?php include_once(eblayout . '/a-common-navebar-index-blog.php'); ?>
    </div>
  </div>
</nav>

<?php include_once(eblayout . '/a-common-page-id-end.php'); ?>
<?php include_once(ebaccess . "/access-permission-online-minimum.php"); ?>

<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'></div>

    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
      <div class="well">
        <h2 title='Post Status'>Post Status</h2>
      </div>

      <?php include_once(ebblog . '/blog.php'); ?>

      <?php
      if (isset($_REQUEST['delete_contents_items'])) {
        extract($_REQUEST);
        $obj = new ebapps\blog\blog();
        $obj->delete_contents_items($contents_id, $contents_og_image_url, $contents_og_small_image_url, $contents_video_link);
      }

      $obj = new ebapps\blog\blog();
      $obj->contents_view_items();

      if ($obj->eBData) {
        $solutionStatus = "<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";

        foreach ($obj->eBData as $val) {
          extract($val);
          $solutionStatus .= "<div class='panel panel-default'>";
          $solutionStatus .= "<div class='panel-heading' role='tab' id='heading{$contents_id}'>";
          $solutionStatus .= "<h3 class='panel-title'><a class='collapsed' data-toggle='collapse' data-parent='#accordion' href='#collapse{$contents_id}' aria-expanded='false' aria-controls='collapse{$contents_id}'>";

          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-12'><div class='row'>";

          // Image
          if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) {
            $solutionStatus .= "<div class='col-xs-12 col-md-3'><img src='{$contents_og_small_image_url}' width='100%' /></div>";
          } else {
            $solutionStatus .= "<div class='col-xs-12 col-md-3'><img src='" . themeResource . "/images/blankImage.jpg' width='100%' /></div>";
          }

          // Status
          $solutionStatus .= "<div class='col-xs-12 col-md-9'>";
          if ($contents_approved == 'NO') {
            $solutionStatus .= "<i class='fa fa-times-circle fa-lg' aria-hidden='true'></i> REVIEWING <br>";
          }
          if ($contents_approved == 'OK' || $contents_approved == 'GPOST') {
            $solutionStatus .= "<i class='fa fa-check-circle fa-lg' aria-hidden='true'></i> PUBLISHED <br>";
          }

          $solutionStatus .= "<b>Title: " . $obj->visulString($contents_og_image_title) . "</b><br>";
          $solutionStatus .= "<b>" . $obj->visulString($contents_category) . " <i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $obj->visulString($contents_sub_category) . "</b><br>";
          $solutionStatus .= "<b>ID: {$contents_id}</b>";
          $solutionStatus .= "</div></div></div></div>";
          $solutionStatus .= "</a></h3></div>";

          // Content body
          $solutionStatus .= "<div id='collapse{$contents_id}' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading{$contents_id}'>";
          $solutionStatus .= "<div class='well'>";

          // Information rows
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Title:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_title) . "</div></div>";
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Category:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_category) . "</div></div>";
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Sub Category:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_sub_category) . "</div></div>";

          // Profile image or upload
          if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) 
          {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Profile Image:</div><div class='col-xs-12 col-md-9'><img src='{$contents_og_small_image_url}' width='100%' /></div></div>";
          } else {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Profile Image:</div><div class='col-xs-12 col-md-9'>
              <form action='contents-image-upload-croper.php' method='post'>
                <input type='hidden' name='contents_id' value='{$contents_id}' />
                <div class='buttons-set'>
                  <button type='submit' name='upload_image' class='button submit'><span> Upload Profile Image </span></button>
                </div>
              </form></div></div>";
          }

          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>What to do:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_what_to_do) . "</div></div>";
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Log In required to read more?</div><div class='col-xs-12 col-md-9'><select class='form-control' name='contents_og_login_required'><option value='{$contents_og_login_required}' selected>" . $obj->visulString($contents_og_login_required) . "</option></select></div></div>";
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>How to do:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_how_to_solve) . "</div></div>";

          // Affiliate, GitHub, Preview links
          if (!empty($contents_affiliate_link)) {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Affiliate Link:</div><div class='col-xs-12 col-md-9'><p><a rel='nofollow' href='" . hypertext . $contents_github_link . "' target='_blank'><button class='button submit'><span> Affiliate Link </span></button></a></p></div></div>";
          }

          if (!empty($contents_github_link)) {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Download Link:</div><div class='col-xs-12 col-md-9'><p><a rel='nofollow' href='" . hypertext . $contents_github_link . "' target='_blank'><button class='button submit'><span> Download </span></button></a></p></div></div>";
          }

          if (!empty($contents_preview_link)) {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Preview Link:</div><div class='col-xs-12 col-md-9'><p><a rel='nofollow' href='" . hypertext . $contents_preview_link . "' target='_blank'><button class='button submit'><span> Preview </span></button></a></p></div></div>";
          }
          if(!empty($contents_video_link) and file_exists(docRoot.$contents_video_link))
          {
          $solutionStatus .="<div class='row'><div class='col-xs-12 col-md-3'>Video:</div><div class='col-xs-12 col-md-9'>";
          $solutionStatus .="<div class='video-container'>";
          $solutionStatus .="<video width='100%' controls>";
          $solutionStatus .="<source type='video/mp4' src='";
          $solutionStatus .=hostingName.$contents_video_link;
          $solutionStatus .="'>";

          $solutionStatus .="<source type='video/quicktime' src='";
          $solutionStatus .=hostingName.$contents_video_link;
          $solutionStatus .="'>";

          $solutionStatus .="<source type='video/mpeg' src='";
          $solutionStatus .=hostingName.$contents_video_link;
          $solutionStatus .="'>";
          $solutionStatus .="</video>";
          $solutionStatus .="</div>";
          //
          $solutionStatus .="</div>";
          $solutionStatus .="</div>";
          }
          elseif (!empty($contents_video_link))
          {
          $solutionStatus .="<div class='row'><div class='col-xs-12 col-md-3'>Video:</div><div class='col-xs-12 col-md-9'>";
          $solutionStatus .= "<div class='bs-example' data-example-id='responsive-embed-16by9-iframe-youtube'>";
          $solutionStatus .= "<div class='embed-responsive embed-responsive-16by9'>";
          $solutionStatus .= "<iframe class='embed-responsive-item' src='";
          $solutionStatus .= hypertext.$contents_video_link;
          $solutionStatus .= "' allowfullscreen loading='lazy'></iframe>";
          $solutionStatus .= "</div>";
          $solutionStatus .= "</div>";
          //
          $solutionStatus .="</div>";
          $solutionStatus .="</div>";
          }
          // Video Upload for admin
          if (isset($_SESSION['memberlevel']) && $_SESSION['memberlevel'] >= 9 && empty($contents_video_link) && file_exists(docRoot.$contents_video_link)) 
          {
            $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Video:</div><div class='col-xs-12 col-md-9'>
              <form action='contents-video-upload.php' method='post'>
                <input type='hidden' name='contents_id' value='{$contents_id}' />
                <div class='buttons-set'><button type='submit' name='upload_bideo_blog' class='button submit'><span> Upload Video </span></button></div>
              </form></div></div>";
          }

          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>Submit Date:</div><div class='col-xs-12 col-md-9'>{$contents_date}</div></div>";

          // Options
          $solutionStatus .= "<div class='row'><div class='col-xs-12 col-md-3'>OPTIONS:</div><div class='col-xs-12 col-md-9'>";
          $solutionStatus .= "<form action='contents-add-items-edit.php' method='get'>
            <input type='hidden' name='username_contents' value='{$username_contents}' />
            <input type='hidden' name='contents_id' value='{$contents_id}' />
            <div class='buttons-set'><button type='submit' class='button submit'><span> EDIT </span></button></div>
          </form>";

          $solutionStatus .= "<form method='post'>
            <input type='hidden' name='contents_id' value='{$contents_id}' />
            <input type='hidden' name='contents_og_image_url' value='{$contents_og_image_url}' />
            <input type='hidden' name='contents_og_small_image_url' value='{$contents_og_small_image_url}' />
            <input type='hidden' name='contents_video_link' value='{$contents_video_link}' />
            <div class='buttons-set'><button type='submit' name='delete_contents_items' class='button submit'><span> Delete </span></button></div>
          </form>";
          $solutionStatus .= "</div></div>";
          $solutionStatus .= "</div>";
          $solutionStatus .= "</div>";
          $solutionStatus .= "</div>";
        }

        $solutionStatus .= "</div>";
        echo $solutionStatus;
      }
      ?>
    </div>

    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
      <?php include_once(ebcontents."/contents-my-account.php"); ?>
    </div>
  </div>
</div>

<?php include_once(eblayout . '/a-common-footer.php'); ?>
