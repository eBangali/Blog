<header class=''>
  <div class='header-container'>
    <div class='container'>
      <div class='row'>
      <div class='col-xs-12 col-md-2 logo-block'>
      <div class='logo'><a href='<?php echo hostingAndRoot."/"; ?>'><img alt='<?php echo hostingAndRoot; ?>' src='<?php echo themeResource; ?>/images/Logo.png'></a>
			<div class='logoBrandTitle'>
			<?php if(!mysqli_connect_errno())
      {
			include_once(eblogin.'/registration-page.php');
			$siteTitle = new ebapps\login\registration_page();
			$siteTitle -> site_owner_title();
			if($siteTitle->eBData >= 1) 
      {
      foreach($siteTitle->eBData as $val)
      {
      extract($val); 
			if(!empty($business_title_two))
      {
			echo $siteTitle->visulString($business_title_two);
			}
			}
			}
			}
			?>
			</div>
		</div>
        </div>
        <div class='col-xs-12 col-md-10 pull-right hidden-md hidden-sm hidden-xs'>
          <div class='collapse navbar-collapse'>
            <ul class='nav navbar-nav navbar-right'>
              <!-- Starts of Blog -->
              <?php if (isset($_SESSION['ebusername'])){ ?>
              <li class='dropdown'> <a  href='<?php echo outContentsLink; ?>/' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i> Blog <b class='caret'></b></a>
                <ul class='dropdown-menu'>
                  <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                  <li><a  href='<?php echo outContentsLink; ?>/contents/' title='Blog'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i> Blog</a></li>
                  <?php } ?>
                  <?php if ($_SESSION['memberlevel'] >= 13) { ?>
                  <li><a  href='<?php echo outContentsLink; ?>/contents-admin-view-items.php' title='Approval'><i class='fa fa-refresh fa-lg' aria-hidden='true'></i> Approval</a></li>
                  <?php } ?>
                  <?php if ($_SESSION['memberlevel'] >= 1) { ?>
                  <li><a  href='<?php echo outContentsLink; ?>/contents/likelist/' title='Likelist'><i class='fa fa-heart fa-lg' aria-hidden='true'></i> Likelist</a></li>
                  <li><a  href='<?php echo outContentsLink; ?>/contents-items-status.php' title='Post Status'><i class='fa fa-tasks fa-lg' aria-hidden='true'></i> Post Status</a></li>
                  <li><a  href='<?php echo outContentsLink; ?>/contents-add-items.php' title='Add a New Post'><i class='fa fa-plus fa-lg' aria-hidden='true'></i> Add a New Post</a></li>
                  <?php } ?>
                  <?php if ($_SESSION['memberlevel'] >= 13) { ?>
                  <li><a  href='<?php echo outContentsLink; ?>/contents-add-sub-category.php' title='Add Sub Category'><i class='fa fa-sort-amount-asc fa-lg' aria-hidden='true'></i> Add Sub Category</a></li>
                  <li class='last'><a  href='<?php echo outContentsLink; ?>/contents-add-category.php' title='Add Category'><i class='fa fa-database fa-lg' aria-hidden='true'></i> Add Category</a></li>
                  <?php } ?>
                  <?php include_once (eblayout.'/a-common-navebar-blog-cat-sub-menue-login.php'); ?>
                </ul>
              </li>
              <?php }  else { ?>
              <?php include_once (eblayout.'/a-common-navebar-blog-cat-sub-menue.php'); ?>
              <?php } ?>
              <!-- Ends of Blog -->
              <!-- Strart os SATTINGS -->
              <?php if (isset($_SESSION['ebusername'])){ ?>
              <li class='dropdown'> <a  href='<?php echo outAccessLink; ?>/' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-cogs fa-lg' aria-hidden='true'></i> <?php echo $_SESSION['ebusername']; ?> <b class='caret'></b></a>
                <ul class='dropdown-menu'>
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
              </li>
              <?php } else { ?>
              <?php
              if(!mysqli_connect_errno()){ ?>
              <!-- Start of Home -->
              <?php if (empty($_SESSION['ebusername'])){ ?>
              <li><a  href='<?php echo outAccessLink; ?>/home.php' title='Log In'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Log In</a></li>
              <li><a  href='<?php echo outAccessLink; ?>/signup.php' title='Sign Up'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Sign Up</a></li>
              <?php } ?>
              <!-- Ends of Home -->
              <?php } ?>
              <?php } ?>
              <!-- Ends of SATTINGS -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>