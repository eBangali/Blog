<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<meta name='apple-mobile-web-app-capable' content='yes'>
<meta name='mobile-web-app-capable' content='yes'>
<meta name='apple-touch-fullscreen' content='yes'>
<meta name='apple-mobile-web-app-title' content='<?php echo brandName; ?>'>
<meta name='apple-mobile-web-app-status-bar-style' content='default'>

<link rel='apple-touch-icon' sizes='120x120' href='<?php echo themeResourceHostingRoot; ?>/images/touch-icon-iphone.png'>
<link rel='apple-touch-icon' sizes='180x180' href='<?php echo themeResourceHostingRoot; ?>/images/touch-icon-iphone-retina.png'>
<link rel='apple-touch-icon' sizes='152x152' href='<?php echo themeResourceHostingRoot; ?>/images/touch-icon-ipad.png'>
<link rel='apple-touch-icon' sizes='167x167' href='<?php echo themeResourceHostingRoot; ?>/images/touch-icon-ipad-retina.png'>
<link rel='apple-touch-startup-image' href='<?php echo themeResourceHostingRoot; ?>/images/startupImage.png'>

<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/bootstrap-min.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/font-awesome-min.css' media='all'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/simple-line-icons.css' media='all'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/owl-carousel.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/owl-theme.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/jquery-bxslider.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/jquery-mobile-menu-v25.05.25.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/eb-my-style-v25.05.25.13.8.10.css' media='all'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/revslider.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/main-v25.05.25.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/cropzone.css'>
<link rel='stylesheet' type='text/css' href='<?php echo themeResource; ?>/css/croper.css'>
<script src='<?php echo themeResource; ?>/js/jquery-min-2-2-0.js'></script>
<script src='<?php echo themeResource; ?>/js/dropzone.js'></script>
<script src='<?php echo themeResource; ?>/js/cropper.js'></script>
<style>
		.image_area {
		  position: relative;
		}
		img {
		  	display: block;
		  	max-width: 100%;
		}
		.preview {
  			overflow: hidden;
  			width: 160px; 
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}
		.modal-lg{
  			max-width: 1000px !important;
		}
		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}
		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}
		.text {
		  color: #333;
		  font-size: 15px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
		</style>
<?php include_once (eblayout.'/a-common-header-meta-scripts-abobe-body.php'); ?>
</head>
<body>
<?php include_once (eblayout.'/a-common-header-meta-scripts-after-body.php'); ?>