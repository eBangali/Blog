<?php 
/*
Users are granted different levels of permissions or capabilities based on their "power" level,
* To be team member user must have minimum power 9
* To upload long video user must have minimum power 9
* To invite by email or mobile number user must have minimum power 1
* To get referal share button user must have minimum power 1
Access Level Power
#######################################
Chief Technology Officer = 13
1. Can use Tags URL
2. Can approve article, product, service and edit that
3. Can add submit article, product, services and edit that
4. Can upload long video
5. Will be shown as team member
6. Can invite to join us using email
7. Can purchase and view purchese history
8. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
Chief Executive Officer = 10
1. Can use Tags URL
2. Can approve article, product, service and edit that
3. Can add submit article, product, services and edit that
4. Can upload long video
5. Will be shown as team member
6. Can invite to join us using email
7. Can purchase and view purchese history
8. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
Content Writer = 9
1. Can approve article, product, service and edit that
2. Can add submit article, product, services and edit that
3. Can upload long video
4. Will be shown as team member
5. Can invite to join us using email
6. Can purchase and view purchese history
7. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
eCommerce Merchant = 8
1. Can approve product, service and edit that
2. Can add submit product, services and edit that
3. Can invite to join us using email
4. Can purchase and view purchese history
6. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
Remote Operations Manager = 4
1. Can approve product, service and edit that
2. Can add submit product, services and edit that
3. Can invite to join us using email
4. Can purchase and view purchese history
5. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
POS Store Owner = 4
1. Can approve product and edit that
2. Can add submit product and edit that
3. Can use POS as admin
4. Can use POS stock transfer, profit chart, multiple warehouse.
5. Can invite to join us using email
6. Can purchase and view purchese history
7. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
POS System Manager = 3
1. Can approve product and edit that
2. Can add submit product and edit that
3. Can be a POS salesman
4. Can be use POS purchase and sales module
5. Can invite to join us using email
6. Can purchase and view purchese history
7. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
POS Sales Representative = 2
1. Can be a POS salesman
2. Can invite to join us using email
3. Can purchase and view purchese history
4. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
POS Inventory Handler = 2
1. Can be a POS salesman
2. Can invite to join us using email
3. Can purchase and view purchese history
4. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
Public Viewer = 1
1. Can invite to join us using email
2. Can purchase and view purchese history
3. Can blog post articles with links, can post products with links, can share an images, can share YouTube video links, can share TikTok video link, can like a content, can comment on contents, can share a content and system will send email alerts.
#######################################
Invited Member = 1
1. Can invite to join us using email
2. Can purchase.
#######################################
Unsubscribed User = 1
1. Will never get email promotion. 
2. Can purchase.
#######################################
Blocked User = 0
1. Can not access account any more.
2. Can not be register again using that email, username.
*/
if(isset($_SESSION['memberlevel']) and isset($_SESSION['memberposition']))
{
if ($_SESSION['memberlevel'] == 2 and $_SESSION['memberposition'] == "POS Inventory Handler")
{

}
else
{
include_once (eblayout.'/a-common-footer.php');
?>
<script>
window.location.replace('<?php echo hostingAndRoot."/"; ?>');
</script>
<?php
}	
}
?>