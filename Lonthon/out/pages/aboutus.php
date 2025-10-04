<?php include_once (dirname(dirname(dirname(__FILE__))).'/initialize.php'); ?>
<?php include_once (ebblog.'/blog.php'); ?>
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
<?php include_once (ebcontents.'/views/shop/search.php'); ?>
<div class='container'>
<div class='row row-offcanvas row-offcanvas-right'>
<div class='col-xs-12 col-md-2'>

</div>
<div class='col-xs-12 col-md-7 sidebar-offcanvas'>

<div class='well'>
<h2 title='Our Vision'>Our Vision</h2>
<p>To revolutionize industries through intelligent, technology-driven solutions in AI, SaaS, IoT, and Robotics, empowering progress and shaping the future through continuous innovation.</p>
<h2 title='Our Mission'>Our Mission</h2>
<p>To develop powerful, user-focused systems tailored for Healthcare, Agriculture, and Industrial Automation Robotics.
We deliver dynamic digital solutions—including advanced CMS, LMS, EMS, and eCommerce platforms integrated with CRM and robust POS tools—designed to boost operational efficiency and accelerate business growth.</p>
</div>

<div class='well'>
<h2 title='Our Products'>Our Products</h2>
<p>We specialize in delivering a diverse range of technology solutions, including customized software development, enterprise application development, eCommerce platforms integrated with POS (Point of Sale) systems, and custom-built Content Management Systems (CMS). Our products are designed to meet the specific needs of businesses, ensuring flexibility, scalability, and performance across various industries.</p>
</div>

<div class='well'>
<h2 title='Solutions we provide on'>Solutions we provide on</h2>
<p>We offer full-stack development solutions across a wide range of modern technologies, including MongoDB, Express.js, React.js, React Native, Node.js, TensorFlow.js, and PHP, MySQL. Our expertise also extends to cutting-edge AI and SaaS-based full-stack development, enabling us to build intelligent, scalable, and high-performance applications tailored to your business needs.</p>
</div>

</div>
<div class='col-xs-12 col-md-3 sidebar-offcanvas'>
<?php
if(isset($_SESSION['memberlevel']) >= 1)
{ 
include_once (ebaccess.'/access-my-account.php');
} 
?>
</div>
</div>
</div>
<?php include_once (eblayout.'/a-common-footer.php'); ?>