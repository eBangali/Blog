<!-- Footer -->
<footer class='footer'>
  <div class='footer-middle hidden-sm hidden-xs'>
    <div class='container'>
      <div class='row'>
        <div class='col-md-3 col-sm-6'>
          <div class='footer-column pull-left'>
            <h4>Guide</h4>
            <ul class='links'>
              <?php if(!mysqli_connect_errno()){ ?>
			  <li><a target='_self' href='<?php echo outPagesLink; ?>/faq.php' title='FAQs'><span>FAQs</span></a></li>
              <li><a target='_self' href='<?php echo outPagesLink; ?>/shipment-delivery.php' title='Shipment'><span>Shipment</span></a></li>
              <li><a target='_self' href='<?php echo outPagesLink; ?>/return-policy.php' title='Returns Policy'><span>Returns Policy</span></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class='col-md-3 col-sm-6'>
          <div class='footer-column pull-left'>
            <h4>Advisor</h4>
            <ul class='links'>
            <?php if(!mysqli_connect_errno()){ ?>
              <li><a  href='<?php echo outPagesLink; ?>/aboutus.php' title='About us'><span>About us</span></a></li>
              <li><a target='_self' href='<?php echo outAccessLink; ?>/home.php' title='Your Account'>Your Account</a></li>
              <li class='last'><a target='_self' href='<?php echo outAccessLink; ?>/access-update-account-information.php' title='Account Settings'>Account Settings </a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class='col-md-3 col-sm-6'>
          <div class='footer-column pull-left'>
            <h4>Information</h4>
            <ul class='links'>
            <?php if(!mysqli_connect_errno()){ ?>
            <li><a target='_self' href='<?php echo outPagesLink; ?>/terms-conditions.php' title='Terms of service'><span>Terms of service</span></a></li>
            <li><a target='_self' href='<?php echo outPagesLink; ?>/privacy-policy.php' title='Privacy Policy'><span>Privacy Policy</span></a></li>
            <li><a target='_self' href='<?php echo outPagesLink; ?>/our-support-team.php' title='Our Team'><span>Our Team</span></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class='col-md-3 col-sm-6'>
          <h4>Contact us</h4>
          <div class='contacts-info'>
          <?php if(!mysqli_connect_errno()){ ?>
          <?php include_once(eblogin.'/registration-page.php');
          $siteLocation = new ebapps\login\registration_page();
          $siteLocation -> site_location();
          ?>
          <?php if($siteLocation->eBData) { foreach($siteLocation->eBData as $val){ extract($val); ?>
            <address>
            <?php if(!empty($business_name)){echo $siteLocation->visulString($business_name); } ?>
            <br>
            <?php if(!empty($business_city_town)){echo $siteLocation->visulString($business_city_town); } ?>
            </address>
            <div class='phone-footer'>
              <a target='_self' href='<?php echo outPagesLink; ?>/contact.php' title='eMail us'><i class='email-icon'>eMail us</i></a>
            </div>
			      <?php }} ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class='footer-top'>
    <div class='container'>
      <div class='row'>
        <div class='col-xs-12 col-sm-6'>
          <div class='social'>
            <ul>
            <?php if(!mysqli_connect_errno()){ ?>
            <li class='rss'><a href='<?php echo hostingAndRoot."/mrss.xml"; ?>'></a></li>
            <?php include_once(eblogin.'/registration-page.php');
			      $social = new ebapps\login\registration_page();
			      $social -> site_owner_social_info();
			      ?>
            <?php if($social->eBData) { foreach($social->eBData as $val){ extract($val); ?>
            <?php if (!empty($facebook_link)) {echo "<li class='fb'><a href='https://www.facebook.com/sharer/sharer.php?u=".urlencode(hostingAndRoot)."' rel='nofollow' target='_blank'></a></li>";} ?>
            <?php if (!empty($twitter_link)) {echo "<li class='tw'><a href='https://x.com/intent/tweet?url=".urlencode(hostingAndRoot)."' target='_blank' rel='nofollow'></i></a></li>";} ?>
            <?php if (!empty($linkedin_link)) {echo "<li class='linkedin'><a href='https://www.linkedin.com/sharing/share-offsite/?url=".urlencode(hostingAndRoot)."' target='_blank' rel='nofollow'></a></li>";} ?>
            <?php }} ?>
            <?php } ?>
            </ul>
          </div>
        </div>
        <div class='col-xs-12 col-sm-6 hidden-sm hidden-xs'>
          <div class='payment-accept'> <img src='<?php echo themeResource; ?>/images/payment-2.png' alt='VISA CARD'> <img src='<?php echo themeResource; ?>/images/payment-3.png' alt='AMERICAN EXPRESS CARD'> <img src='<?php echo themeResource; ?>/images/payment-4.png' alt='MASTER CARD'> </div>
        </div>
      </div>
    </div>
  </div>
  <div class='footer-bottom'>
    <div class='container'>
      <div class='row'>
        <div class='col-xs-12'> <?php echo date('Y'); ?> <a href='<?php echo hostingAndRoot; ?>'><?php echo domain; ?></a> All Rights Reserved. Develop by <a href='https://ebangali.com'>eBangali</a></div>
      </div>
    </div>
  </div>
</footer>
<!-- End Footer -->
<?php include (eblayout.'/a-common-mobile-nav.php'); ?>
<!-- JavaScript -->
<script src='<?php echo themeResource; ?>/js/bootstrap-min.js'></script>
<script src='<?php echo themeResource; ?>/js/revslider.js'></script>  
<script src='<?php echo themeResource; ?>/js/common.js'></script> 
<script src='<?php echo themeResource; ?>/js/jquery-flexslider.js'></script> 
<script src='<?php echo themeResource; ?>/js/owl-carousel-min.js'></script> 
<script src='<?php echo themeResource; ?>/js/jquery-mobile-menu-min.js'></script>
<script src='<?php echo themeResource; ?>/js/cloud-zoom.js'></script>
<script src='<?php echo themeResource; ?>/js/jquery-waypoints-min-20-05-23.js'></script> 
<script src='<?php echo themeResource; ?>/js/main-eb-20-05-23.js'></script>
<script src='<?php echo themeResource; ?>/js/filter-bootstrap-20-05-23.js'></script>
<?php include (eblayout.'/a-common-carosoul.php'); ?>
</body>
</html>