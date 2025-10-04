<?PHP
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
/***************************************************************************************************************************
######################################### CHANGE OPTION STARTS  ############################################################
****************************************************************************************************************************/
/*** Database Settings ***/
const EB_HOSTNAME = "localhost";
const EB_DB_USERNAME = "";
const EB_DB_PASSWORD = "";
const EB_DATABASE = "";
/*
Image Very Small Sixe: 455X318
Image Small Size: 512X358
Image Medium Size: 1024X717
Image Big Size: 1366X956 
*/
/*
Starts of SMTP Setting For your own domain
NB: New domain required 78 hours to active smtp mailserver
/*** eMails Settings ***/
/*
Example #1 
const smtpHost = "ssl://xyz.com";
const smtpPort = 587;
const smtpUsername = "xyz@zyz.com";
const smtpPassword = "mailpassword";
const adminEmail = "xyz@zyz.com";
const contactEmail = "xyz@zyz.com";
const alertToAdmin = "xyz@zyz.com";
/*** Mobile Settings ***/
/*
const adminMobile = "CountryCodeMobileNumber";
*/
/*
/*** eMails Settings ***/
/*
Example #2
const smtpHost = "ssl://xyz.com";
const smtpPort = 465;
const smtpUsername = "xyz@zyz.com";
const smtpPassword = "mailpassword";
const adminEmail = "xyz@xyz.com";
const contactEmail = "xyz@xyz.com";
const alertToAdmin = "xyz@xyz.com";
/*** Mobile Settings ***/
/*
const adminMobile = "CountryCodeMobileNumber";
*/
/* 
Example Gmail #3
const smtpHost = "smtp.gmail.com";
const smtpPort = 587;
const smtpUsername = "yourid@gmail.com";
const smtpPassword = "yourpassword";
const adminEmail = "yourid@gmail.com";
const contactEmail = "yourid@gmail.com";
const alertToAdmin = "yourid@gmail.com";
const adminMobile = "0000000000000";

Security: For security reasons, it's recommended to use Gmail App Passwords instead of your main Gmail password. This is especially important if you have 2-Step Verification enabled.
To generate an App Password, go to your Google Account settings -> Security -> App passwords.

Less Secure Apps: If you are not using App Passwords, you need to allow "Less secure app access" in your Google Account settings, which is not recommended for security reasons. Google may also deprecate this feature, so App Passwords are the preferred method.
*/
/*** eMails Settings ***/
const smtpHost = "smtp.gmail.com";
const smtpPort = 587;
const smtpUsername = "";
const smtpPassword = "";
const adminEmail = "";
const contactEmail = "";
const alertToAdmin = "";
/*** Mobile Settings ***/
const adminMobile = "";
/* Release Version */
const version = "v25.08.01";
//
/* Never Change Currency Setings. All input will be based on USD. */
define("primaryCurrency","USD");
define("primaryCurrencySign","$");
/* Merchant will chouse what will be his secondary currency from Database . */
define("secondaryCurrency","BDT");
define("secondaryCurrencySign","৳");
//
define("convertPrimary","1.000");
define("convertSecondary","110.000");
define("primaryTosecondary",floatval(convertPrimary)*floatval(convertSecondary));
//
$exchangeRate = convertPrimary." ".primaryCurrency." = ".convertSecondary." ".secondaryCurrency;
/* License */
define("license", "Your License If Any");
//
//define("googleMapApisKey", "Google Map API Key If Any");
//
define("eBIP","Your IP");
define("addIP",$_SERVER['REMOTE_ADDR']);
/***************************************************************************************************************************
######################################### END OF CHANGE OPTION  ############################################################
****************************************************************************************************************************/
/* The BackEnd System */
define("eb", dirname(__FILE__));
define("docRoot", $_SERVER['DOCUMENT_ROOT']);
$eBscema = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://";
define("hypertext", "$eBscema");
//
define("domain", "$_SERVER[HTTP_HOST]");
define("brandName", "eBangali");
define("hostingNameImage", hypertext.domain);
define("hostingName", hypertext.domain);
define("RootOnly", str_replace(docRoot, "", eb));
define("hostingAndRoot", hostingName.RootOnly);
define("fullUrl", hostingNameImage."$_SERVER[REQUEST_URI]");
define("ebfromeb", eb."/ebapps/captcha");
//
define("domainForImagStore", str_replace("www.", "", parse_url(hostingNameImage, PHP_URL_HOST)));
define("hypertextWithOrWithoutWww", str_replace(domainForImagStore, "", hostingNameImage));
//
define("ebfromcap", hostingAndRoot."/ebapps/captcha");
define("ebbd", eb."/ebapps/dbconnection");
define("ebphpmailer", eb."/ebapps/PHPMailer");
define("ebformkeys", eb."/ebapps/formkeys");
define("ebformmail", eb."/ebapps/formmail");
define("ebHashKey", eb."/ebapps/hashpassword");

define("eblogin", eb."/ebapps/login");
define("ebsanitization", eb."/ebapps/sanitization");
define("themeSetting", eb."/ebapps/themeSetting");
define("ebimageupload", eb."/ebapps/upload");
//
define("ebfileupload", eb."/ebapps/upload");
define("ebfpdf", eb."/ebapps/fpdf");
define("ebqrcode", eb."/ebapps/qrcode");
/*############# Barcode ##################*/
define("ebBarcode", eb."/ebapps/barcode");
/*################################# Default Settings ###############################################*/
/* FrontEnd */
define("ebout", eb."/out");
define("outLink", "/out");
define("outLinkFull", hostingAndRoot."/out");
/* Access */
define("ebaccess", eb."/out/access");
define("outAccessLink", hostingAndRoot."/out/access");
define("outAccessLinkFull", hostingAndRoot."/out/access");
/* Pages */
define("ebpages", eb."/out/pages");
define("outPagesLink", hostingAndRoot."/out/pages");
define("outPagesLinkFull", hostingAndRoot."/out/pages");
/*############# Problem Solving Blog CMS ##################*/
/* Blog BacktEnd */
define("ebblog", eb."/ebapps/blog");
/* Blog FrontEnd */
define("ebcontents", eb."/out/blog");
define("outContentsLink", hostingAndRoot."/out/blog");
define("ebOutContentsRequest", eb."/out/blog/views/controller");
define("outContentsRequest", hostingAndRoot."/out/blog/views/controller");
define("outContentsLinkFull", hostingAndRoot."/out/blog");
/* Default Theme */
define('ebThemesActive', "LonthonAppOne");
/* For All Apps Theme Settings */
define("ebThemes", eb."/ebcontents/themes");
define("themeResource", RootOnly."/ebcontents/themes/".ebThemesActive);
define("themeResourceHostingRoot", hostingAndRoot."/ebcontents/themes/".ebThemesActive);
define("eblayout", eb."/ebcontents/themes/".ebThemesActive);

