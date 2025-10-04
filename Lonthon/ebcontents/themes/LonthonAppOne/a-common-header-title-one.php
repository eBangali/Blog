<?php if(!mysqli_connect_errno())
{
include_once(eblogin.'/registration-page.php');
$siteTitle = new ebapps\login\registration_page();
$siteTitle -> site_owner_title();
if($siteTitle->eBData)
{ 
foreach($siteTitle->eBData as $val)
{
extract($val); 
if(!empty($business_title_one))
{
$ebmeta ="<meta property='og:image:url' content='".themeResourceHostingRoot."/images/BigLogo.jpg'>\n";
$ebmeta.="<meta property='og:image:type' content='image/jpeg'>\n";
$ebmeta.="<meta property='og:image:width' content='1366'>\n";
$ebmeta.="<meta property='og:image:height' content='956'>\n";
$ebmeta.="<meta property='og:title' content='".$siteTitle->metaVisulString($business_title_one)."'>\n";
$ebmeta.="<meta property='og:description' content='".$siteTitle->metaVisulString($business_title_one)."'>\n";
$ebmeta.="<meta name='twitter:card' content='summary_large_image'>\n";
$ebmeta.="<meta name='twitter:site' content='@eBangali'>\n";
$ebmeta.="<meta name='twitter:domain' content='".domain."'>\n";
$ebmeta.="<meta name='twitter:creator' content='@eBangali'>\n";
$ebmeta.="<meta name='twitter:title' content='".$siteTitle->metaVisulString($business_title_one)."'>\n";
$ebmeta.="<meta name='twitter:description' content='".$siteTitle->metaVisulString($business_title_one)."'>\n";
$ebmeta.="<meta name='twitter:image' content='".themeResourceHostingRoot."/images/BigLogo.jpg'>\n";
$ebmeta.="<meta name='twitter:url' content='".fullUrl."'>\n";
$ebmeta.="<title>".$siteTitle->visulString($business_title_one)."</title>\n";
$ebmeta.="<meta name='description' content='".$siteTitle->metaVisulString($business_title_one)."'>\n";
echo $ebmeta;
}
}
}
}
?>