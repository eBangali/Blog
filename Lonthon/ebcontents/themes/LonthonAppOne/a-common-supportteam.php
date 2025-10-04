<section class='supportteam'>
<div class='container'>
<div class='row'>
<?php include_once(eblogin.'/registration-page.php');
$social = new ebapps\login\registration_page();
$social -> our_team_member_administration_minimum();
?>
<?php if($social->eBData >= 1) { foreach($social->eBData as $val){ extract($val); ?>
<div class='col-xs-6 col-sm-3'>
<div class='thumbnail'>
<div class='float-left'>
<?php
if(!empty($profile_picture_link) and file_exists(docRoot.$profile_picture_link))
{
echo $editAcc ="<img alt='$full_name' title='$full_name' height='114' src='$profile_picture_link' />";
}
else
{
echo $editAcc ="<img alt='$full_name' title='$full_name' height='114' src='".themeResource."/images/person.jpg' />";
}
?>        
<div class='staff'>
<?php if(!empty($full_name)){echo "<p>$full_name</p>";} ?>
<?php if(!empty($position_names)){echo $social->visulString($position_names);} ?>
</div>
<div class='social-follow'>
<ul>

<?php if(!empty($facebook_link)){echo "<li class='facebook'><a href='".hypertext.$facebook_link."' rel='nofollow'><i class='fa fa-facebook'></i></a></li>"; } ?>
<?php if(!empty($twitter_link)){echo "<li class='twitter'><a href='".hypertext.$twitter_link."' rel='nofollow'><i class='fa fa-twitter'></i></a></li>"; } ?>
<?php if(!empty($github_link)){echo "<li class='github'><a href='".hypertext.$github_link."' rel='nofollow'><i class='fa fa-github'></i></a></li>"; } ?>
 <?php if(!empty($linkedin_link)){echo "<li class='linkedin'><a href='".hypertext.$linkedin_link."' rel='nofollow'><i class='fa fa-linkedin'></i></a></li>"; } ?>
<?php if(!empty($pinterest_link)){echo "<li class='pinterest'><a href='".hypertext.$pinterest_link."' rel='nofollow'><i class='fa fa-pinterest'></i></a></li>"; } ?>
<?php if(!empty($youtube_link)){echo "<li class='youtube'><a href='".hypertext.$youtube_link."' rel='nofollow'><i class='fa fa-youtube-play'></i></a></li>"; } ?>
<?php if(!empty($instagram_link)){echo "<li class='instagram'><a href='".hypertext.$instagram_link."' rel='nofollow'><i class='fa fa-instagram'></i></a></li>"; } ?>
</ul>
</div>
</div>
</div>
</div>
<?php 
}
}
?>
</div>
</div>
</section>