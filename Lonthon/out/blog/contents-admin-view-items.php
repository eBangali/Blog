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
<?php include_once(ebaccess . '/access-permission-admin-minimum.php'); ?>

<div class='container'>
  <div class='row row-offcanvas row-offcanvas-right'>
    <div class='col-xs-12 col-md-2'></div>

    <div class='col-xs-12 col-md-7 sidebar-offcanvas'>
      <div class='well'>
        <h2 title='Approval'>Approval</h2> 
      </div>

      <?php include_once(ebblog . '/blog.php'); ?>

      <?php
      // PUBLISH ACTION
      if (isset($_REQUEST['approve_contents_items'])) {
          extract($_REQUEST);
          $obj = new ebapps\blog\blog();
          $obj->approve_contents_items($contents_id);
      }

      // NOT APPROVED ACTION
      if (isset($_REQUEST['notSercicesApproved'])) {
          extract($_REQUEST);
          $obj = new ebapps\blog\blog();
          $obj->notSercicesApproved($contents_id, $contents_og_image_url, $contents_video_link);
          $obj = new ebapps\blog\blog();
          $obj->notSercicesApproved_small($contents_id, $contents_og_small_image_url);
      }

      // REJECT ACTION
      if (isset($_REQUEST['reject_blogs_item'])) {
          extract($_REQUEST);
          $obj = new ebapps\blog\blog();
          $obj->delete_contents_items($contents_id, $contents_og_image_url, $contents_og_small_image_url, $contents_video_link);
      }

      // FETCH ITEMS
      $obj = new ebapps\blog\blog();
      $obj->admin_contents_view_items();

      if ($obj->eBData >= 1) {
          $contentviewitems = "<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";

          foreach ($obj->eBData as $val) {
              extract($val);
              $contentviewitems .= "<div class='panel panel-default'>";
              $contentviewitems .= "<div class='panel-heading' role='tab' id='heading$contents_id'>";
              $contentviewitems .= "<h3 class='panel-title'>
                  <a class='collapsed' data-toggle='collapse' data-parent='#accordion' href='#collapse$contents_id' aria-expanded='false' aria-controls='collapse$contents_id'>
                      <div class='row'>
                        <div class='col-xs-12 col-md-12'>
                          <div class='row'>";

              // Thumbnail Image
              if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) {
                  $contentviewitems .= "<div class='col-xs-12 col-md-3'><img width='100%' src='$contents_og_small_image_url' /></div>";
              } else {
                  $contentviewitems .= "<div class='col-xs-12 col-md-3'><img class='img-responsive' src='" . themeResource . "/images/blankImage.jpg' /></div>";
              }

              $contentviewitems .= "<div class='col-xs-12 col-md-9'>";
              if ($contents_approved == 'NO') {
                  $contentviewitems .= "<i class='fa fa-times-circle fa-lg' aria-hidden='true'></i> REVIEWING <br>";
              }
              if ($contents_approved == 'OK') {
                  $contentviewitems .= "<i class='fa fa-check-circle fa-lg' aria-hidden='true'></i> PUBLISHED <br>";
              }

              $contentviewitems .= "<b>Title: " . $obj->visulString($contents_og_image_title) . "</b><br>";
              $contentviewitems .= "<b>" . $obj->visulString($contents_category) . " <i class='fa fa-angle-double-right'></i> " . $obj->visulString($contents_sub_category) . "</b><br>";
              $contentviewitems .= "<b>ID: $contents_id</b>";
              $contentviewitems .= "</div></div></div></div></a></h3></div>";

              // Content Details
              $contentviewitems .= "<div id='collapse$contents_id' class='panel-collapse collapse' role='tabpanel' aria-labelledby='heading$contents_id'>";
              $contentviewitems .= "<div class='well'>";
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Author:</div><div class='col-xs-12 col-md-9'>$username_contents</div></div>";
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Title:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_title) . "</div></div>";
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Category:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_category) . "</div></div>";
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Sub Category:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_sub_category) . "</div></div>";

              // Profile Image
              if (!empty($contents_og_small_image_url) && file_exists(docRoot . $contents_og_small_image_url)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Profile Image:</div><div class='col-xs-12 col-md-9'><img src='$contents_og_small_image_url' width='100%' /></div></div>";
              }

              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>What to do:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_what_to_do) . "</div></div>";
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Login Required:</div><div class='col-xs-12 col-md-9'><select class='form-control' name='contents_og_login_required'><option value='$contents_og_login_required' selected>" . $obj->visulString($contents_og_login_required) . "</option></select></div></div>";

              if (!empty($contents_og_image_how_to_solve)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>How to do:</div><div class='col-xs-12 col-md-9'>" . $obj->visulString($contents_og_image_how_to_solve) . "</div></div>";
              }

              // Links
              if (!empty($contents_affiliate_link)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Buy Link:</div><div class='col-xs-12 col-md-9'><a href='".hypertext.$contents_affiliate_link."' target='_blank' rel='nofollow'><button class='button submit'><span> Buy Link </span></button></a></div></div>";
              }

              if (!empty($contents_github_link)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Download:</div><div class='col-xs-12 col-md-9'><a href='".hypertext.$contents_github_link."' target='_blank' rel='nofollow'><button class='button submit'><span> Download </span></button></a></div></div>";
              }

              if (!empty($contents_preview_link)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Preview:</div><div class='col-xs-12 col-md-9'><a href='".hypertext.$contents_preview_link."' target='_blank' rel='nofollow'><button class='button submit'><span> Preview </span></button></a></div></div>";
              }

              if(!empty($contents_video_link) and file_exists(docRoot.$contents_video_link))
          {
          $contentviewitems .="<div class='row'><div class='col-xs-12 col-md-3'>Video:</div><div class='col-xs-12 col-md-9'>";
          $contentviewitems .="<div class='video-container'>";
          $contentviewitems .="<video width='100%' controls>";
          $contentviewitems .="<source type='video/mp4' src='";
          $contentviewitems .=hostingName.$contents_video_link;
          $contentviewitems .="'>";

          $contentviewitems .="<source type='video/quicktime' src='";
          $contentviewitems .=hostingName.$contents_video_link;
          $contentviewitems .="'>";

          $contentviewitems .="<source type='video/mpeg' src='";
          $contentviewitems .=hostingName.$contents_video_link;
          $contentviewitems .="'>";
          $contentviewitems .="</video>";
          $contentviewitems .="</div>";
          //
          $contentviewitems .="</div>";
          $contentviewitems .="</div>";
          }
          elseif (!empty($contents_video_link))
          {
          $contentviewitems .="<div class='row'><div class='col-xs-12 col-md-3'>Video:</div><div class='col-xs-12 col-md-9'>";
          $contentviewitems .= "<div class='bs-example' data-example-id='responsive-embed-16by9-iframe-youtube'>";
          $contentviewitems .= "<div class='embed-responsive embed-responsive-16by9'>";
          $contentviewitems .= "<iframe class='embed-responsive-item' src='";
          $contentviewitems .= hypertext.$contents_video_link;
          $contentviewitems .= "' allowfullscreen loading='lazy'></iframe>";
          $contentviewitems .= "</div>";
          $contentviewitems .= "</div>";
          //
          $contentviewitems .="</div>";
          $contentviewitems .="</div>";
          }

              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>Submit Date:</div><div class='col-xs-12 col-md-9'>$contents_date</div></div>";

              // Approval Button
              if ($contents_approved != 'OK' && !empty($contents_og_small_image_url)) {
                  $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'></div><div class='col-xs-12 col-md-9'>
                      <form method='post'><input type='hidden' name='contents_id' value='$contents_id' />
                      <div class='buttons-set'><button type='submit' name='approve_contents_items' class='button submit'><span> PUBLISH </span></button></div>
                      </form></div></div>";
              }

              // Options
              $contentviewitems .= "<div class='row'><div class='col-xs-12 col-md-3'>OPTIONS:</div><div class='col-xs-12 col-md-9'>
                <form method='post'>
                  <input type='hidden' name='contents_id' value='$contents_id' />
                  <input type='hidden' name='contents_og_image_url' value='$contents_og_image_url' />
                  <input type='hidden' name='contents_og_small_image_url' value='$contents_og_small_image_url' />
                  <input type='hidden' name='contents_video_link' value='$contents_video_link' />
                  <div class='buttons-set'><button type='submit' name='notSercicesApproved' class='button submit'><span> Not Approved </span></button></div>
                </form>
                <form method='post'>
                  <input type='hidden' name='contents_id' value='$contents_id' />
                  <input type='hidden' name='contents_og_image_url' value='$contents_og_image_url' />
                  <input type='hidden' name='contents_og_small_image_url' value='$contents_og_small_image_url' />
                  <input type='hidden' name='contents_video_link' value='$contents_video_link' />
                  <div class='buttons-set'><button type='submit' name='reject_blogs_item' class='button submit'><span> REJECT </span></button></div>
                </form>
              </div></div>";

              $contentviewitems .= "</div></div></div>";
          }

          $contentviewitems .= "</div>"; // Close accordion
          echo $contentviewitems;
      }
      ?>
    </div>

    <div class='col-xs-12 col-md-3 sidebar-offcanvas'>
      <?php include_once(ebcontents."/contents-my-account.php"); ?>
    </div>
  </div>
</div>

<?php include_once(eblayout . '/a-common-footer.php'); ?>
