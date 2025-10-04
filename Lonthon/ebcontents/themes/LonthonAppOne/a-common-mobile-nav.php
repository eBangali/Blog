<div id='mobile-menu'>
<ul>
<?php if(!mysqli_connect_errno()){ ?>
<!-- Strart of SATTINGS -->
<?php if (isset($_SESSION['ebusername'])){ ?>
<li><a href='<?php echo outAccessLink; ?>/home.php'><i class='fa fa-cogs fa-lg' aria-hidden='true'></i> <?php echo $_SESSION['ebusername']; ?> </a>
<ul>
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
<?php } ?>
<!-- Ends of SATTINGS -->
<?php if (empty($_SESSION['ebusername'])){ ?>
<li><a href='<?php echo outAccessLink; ?>/home.php' title='Log In'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Log In</a></li>
<li><a href='<?php echo outAccessLink; ?>/signup.php' title='Sign Up'><i class='fa fa-user-plus fa-lg' aria-hidden='true'></i> Sign Up</a></li>
<?php } ?>
<!-- Starts of Blog -->
<?php include_once (ebblog.'/blog.php'); ?>
  <?php $contentCategory = new ebapps\blog\blog(); $contentCategory->menu_category_contents(); ?>
  <?php if ($contentCategory->eBData) { ?>
      <?php foreach ($contentCategory->eBData as $val): extract($val); ?>
        <?php if (!empty($contents_category)) { ?>
          <li><a href='<?php echo outContentsLink; ?>/contents/category/<?php echo $contents_id; ?>/'><?php echo $contentCategory->visulString($contents_category); ?></a>
            <?php $contentSubCategory = new ebapps\blog\blog(); $contentSubCategory->menu_sub_category_contents($contents_category); ?>
            <?php if ($contentSubCategory->eBData) { ?>
              <ul>
                <?php foreach ($contentSubCategory->eBData as $sub): extract($sub); ?>
                  <?php if (!empty($contents_sub_category)) { ?>
                    <li><a href='<?php echo outContentsLink; ?>/contents/subcategory/<?php echo $contents_id; ?>/'><?php echo $contentSubCategory->visulString($contents_sub_category); ?></a></li>
                  <?php } ?>
                <?php endforeach; ?>
              </ul>
            <?php } ?>
          </li>
        <?php } ?>
      <?php endforeach; ?>
  <?php } ?>
<?php if (isset($_SESSION['ebusername'])) { ?>
  <li><a href='<?php echo outContentsLink; ?>/contents/'><i class='fa fa-pencil-square-o fa-lg'></i> Blog</a>
    <ul>
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
    </ul>
  </li>
<?php } ?>
<!-- Ends of Blog -->
<?php } ?>
</ul>
</div>
