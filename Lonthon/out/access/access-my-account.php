<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (eblogin.'/session-inc.php'); ?>
<aside class='col-right sidebar wow bounceInUp animated'>
  <div class='block block-account'>
    <div class='block-title'>Account Settings</div>
    <div class='block-content'>
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
    </div>
  </div>
</aside>