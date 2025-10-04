<div class='col-lg-2 col-md-3 col-sm-3 col-xs-12 hidden-xs nav-icon'>
  <div class='mega-container visible-lg visible-md visible-sm'>
    <div class='navleft-container'>
      <div class='mega-menu-title'>
        <h3><i class='fa fa-navicon fa-lg'></i>All Categories</h3>
      </div>
      <div class='mega-menu-category'>
        <ul class='nav'>
          <?php if (isset($_SESSION['ebusername'])){ ?>
          <!-- Strart of SATTINGS -->
          <li> <a  href='<?php echo outAccessLink; ?>/home.php'><i class='fa fa-cogs fa-lg' aria-hidden='true'></i> <?php echo $_SESSION['ebusername']; ?> </a>
            <div class='wrap-popup'>
              <div class='popup'>
                <div class='row'>
                  <div class='col-sm-6'>
                    <ul class='nav'>
                      <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                      <li><a  href='<?php echo outAccessLink; ?>/access-notify.php' title='Notifications'><i class='fa fa-bell fa-lg' aria-hidden='true'></i> Notifications</a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/access-invite-result.php' title='Invite Status'><i class='fa fa-bar-chart fa-lg' aria-hidden='true'></i> Invite Status </a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/access-invite.php' title='Invite Someone'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Invite Someone</a></li>
                      <?php } ?>
                      <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                      <li><a  href='<?php echo outAccessLink; ?>/access-update-account-information.php' title='Account Settings'><i class='fa fa-cog fa-lg' aria-hidden='true'></i> Account Settings </a></li>
                      <?php } ?>
                      <li class='last'><a  href='<?php echo outPagesLink; ?>/logout.php' title='Log Out'><i class='fa fa-sign-out fa-lg' aria-hidden='true'></i> Log Out</a></li>
                    </ul>
                  </div>
                  <div class='col-sm-6 has-sep'>
                    <ul class='nav'>                   
                      <?php if ($_SESSION['memberlevel'] >= 13) { ?>
                      <li><a  href='<?php echo outAccessLink; ?>/send-mail.php' title='Mass eMail'><i class='fa fa-envelope fa-lg' aria-hidden='true'></i> Send eMail</a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/access-all-account-information.php' title='User Info'><i class='fa fa-users fa-lg' aria-hidden='true'></i> User Info</a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/access-payment-gateways.php' title='Payment Gateways'><i class='fa fa-credit-card fa-lg' aria-hidden='true'></i> Payment Gateways</a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/access-admin-merchant-profile.php' title='Admin Business Info'><i class='fa fa-briefcase fa-lg' aria-hidden='true'></i> Admin Business Info</a></li>
                      <?php } ?>
                      <?php if ($_SESSION['memberlevel'] >= 9) { ?>
                      <li><a  href='<?php echo outAccessLink; ?>/mrss.php' title='All mRSS'><i class='fa fa-rss fa-lg' aria-hidden='true'></i> All mRSS</a></li>
                      <li><a  href='<?php echo outAccessLink; ?>/sitemap.php' title='Sitemap'><i class='fa fa-sitemap fa-lg' aria-hidden='true'></i> Sitemap</a></li>
                      <?php } ?>
                    </ul>
                  </div>
                </div>                
              </div>
            </div>
          </li>
          <!-- Ends of SATTINGS -->
          <!-- Starts of Blog -->
          <li> <a  href='<?php echo outContentsLink; ?>/contents/'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i> Blog</a>
            <div class='wrap-popup'>
              <div class='popup'>
                <div class='row'>
                  <div class='col-sm-6'>
                    <ul class='nav'>
                    <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                    <li><a  href='<?php echo outContentsLink; ?>/contents/' title='Blog'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i> Blog</a></li>
                    <?php } ?>
                    <?php if ($_SESSION['memberlevel'] >= 13) { ?>
                    <li><a  href='<?php echo outContentsLink; ?>/contents-admin-view-items.php' title='Approval'><i class='fa fa-refresh fa-lg' aria-hidden='true'></i> Approval</a></li>
                    <?php } ?>
                    <?php if ($_SESSION['memberlevel'] >= 13) { ?>
                    <li><a  href='<?php echo outContentsLink; ?>/contents-add-sub-category.php' title='Add Sub Category'><i class='fa fa-sort-amount-asc fa-lg' aria-hidden='true'></i> Add Sub Category</a></li>
                    <li class='last'><a  href='<?php echo outContentsLink; ?>/contents-add-category.php' title='Add Category'><i class='fa fa-database fa-lg' aria-hidden='true'></i> Add Category</a></li>
                    <?php } ?>   
                    </ul>
                  </div>
                  <div class='col-sm-6 has-sep'>
                    <ul class='nav'>
                    <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                    <li><a  href='<?php echo outContentsLink; ?>/contents/likelist/' title='Likelist'><i class='fa fa-heart fa-lg' aria-hidden='true'></i> Likelist</a></li>
                    <li><a  href='<?php echo outContentsLink; ?>/contents-items-status.php' title='Post Status'><i class='fa fa-tasks fa-lg' aria-hidden='true'></i> Post Status</a></li>
                    <li><a  href='<?php echo outContentsLink; ?>/contents-add-items.php' title='Add a New Post'><i class='fa fa-plus fa-lg' aria-hidden='true'></i> Add a New Post</a></li>
                    <?php } ?>
                    </ul>
                  </div>
                </div>
                <?php include_once (ebblog.'/blog.php'); ?>
                <div class='row'>
                  <?php $contentCategory = new ebapps\blog\blog(); $contentCategory ->menu_category_contents(); ?>
                  <?php if($contentCategory->eBData >= 1) { ?>
                  <?php $contentHasSep =0; foreach($contentCategory->eBData as $contentCategoryVal): extract($contentCategoryVal); ?>
                  <?php if (!empty($contents_category)){ ?>
                  <?php $conternCat = $contents_category; ?>
                  <div class='col-sm-6 <?php if($contentHasSep%2==0) { echo "has-sep"; } ?>'>
                    <h3><?php echo $contentCategory->visulString($contents_category); ?></h3>
                    <ul class='nav'>
                      <?php $contentSubCategory = new ebapps\blog\blog(); $contentSubCategory ->menu_sub_category_contents($conternCat); ?>
                      <?php if($contentSubCategory->eBData >= 1) { ?>
                      <?php foreach($contentSubCategory->eBData as $contentSubCategoryVal): extract($contentSubCategoryVal); ?>
                      <?php if (!empty($contents_category) and !empty($contents_sub_category)){ ?>
                      <li><a  href='<?php echo outContentsLink; ?>/contents/subcategory/<?php echo $contents_id; ?>/'><?php echo $contentSubCategory->visulString($contents_sub_category); ?></a></li>
                      <?php } endforeach; } ?>
                    </ul>
                  </div>
                  <?php } $contentHasSep++; endforeach; } ?>
                </div>
              </div>
            </div>
          </li>
          <!-- Ends of Blog -->
          <?php } else { ?>
          <?php if(!mysqli_connect_errno()){ ?>
          <!-- Start of Home -->
          <?php if (empty($_SESSION['ebusername'])){ ?>
          <li><a  href='<?php echo outAccessLink; ?>/home.php' title='Log In'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Log In</a></li>
          <li><a  href='<?php echo outAccessLink; ?>/signup.php' title='Sign Up'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Sign Up</a></li>
          <?php } ?>
          <!-- Ends of Home -->
          <!-- Starts of Blog -->
          <?php include_once (ebblog.'/blog.php'); ?>
          <li><a  href='<?php echo outContentsLink; ?>/contents/' title='Blog'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i> Blog</a>
            <div class='wrap-popup'>
              <div class='popup'>
                <div class='row'>
                  <?php $contentCategory = new ebapps\blog\blog(); $contentCategory ->menu_category_contents(); ?>
                  <?php if($contentCategory->eBData >= 1) { ?>
                  <?php $contentHasSep =0; foreach($contentCategory->eBData as $contentCategoryVal): extract($contentCategoryVal); ?>
                  <?php if (!empty($contents_category)){ ?>
                  <?php $conternCat = $contents_category; ?>
                  <div class='col-sm-6 <?php if($contentHasSep%2==0) { echo "has-sep"; } ?>'>
                    <h3><?php echo $contentCategory->visulString($contents_category); ?></h3>
                    <ul class='nav'>
                      <?php $contentSubCategory = new ebapps\blog\blog(); $contentSubCategory ->menu_sub_category_contents($conternCat); ?>
                      <?php if($contentSubCategory->eBData >= 1) { ?>
                      <?php foreach($contentSubCategory->eBData as $contentSubCategoryVal): extract($contentSubCategoryVal); ?>
                      <?php if (!empty($contents_category) and !empty($contents_sub_category)){ ?>
                      <li><a  href='<?php echo outContentsLink; ?>/contents/subcategory/<?php echo $contents_id; ?>/'><?php echo $contentSubCategory->visulString($contents_sub_category); ?></a></li>
                      <?php } endforeach; } ?>
                    </ul>
                  </div>
                  <?php } $contentHasSep++; endforeach; } ?>
                </div>
              </div>
            </div>
          </li>
          <!-- Ends of Blog -->
          <?php }  ?>
          <?php }  ?>
        </ul>
      </div>
    </div>
  </div>
</div>