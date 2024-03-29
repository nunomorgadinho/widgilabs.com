<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<link rel=“shortcut icon” type=“image/gif” href=“<?php bloginfo('template_directory'); ?>/images/favicon.ico“ />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="google-site-verification" content="7rvUj_v2P_F1nZO1VvBRJNZJwjuQZhGxcUXlYDuJXfs" />
<meta name="alexaVerifyID" content="8ykgxhJWy1WME28k2ve-bXsD8TY" />
	<title><?php bloginfo('name'); ?><?php wp_title(' - ', true, 'left'); ?> - WordPress-based Web Apps & Business Solutions</title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
    <?php if(get_option("menuwidth")) { ?><style type="text/css" media="screen">.menu UL{ width:<?php echo get_option("menuwidth"); ?>; }</style><?php }?>
    <?php if(get_option("color_main")) { ?><style type="text/css" media="screen">h3, a, .tagline p a, UL.clean LI a:hover, UL.large LI a:hover span{ color:<?php echo get_option("color_main"); ?>; }</style><?php }?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/general.js"></script>
	<?php wp_head(); ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20179976-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<div id="outer">
    <div class="contentwidth main">

<div id="social">
<a href="https://twitter.com/widgilabs" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @widgilabs</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fwidgilabs&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=172621116170898" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px; top: 1px; position: relative" allowTransparency="true"></iframe>
</div>

<div class= "containerlogomenu">


<a href="/" target "_self">
		<div class="logo">
		
			<?php
			$logo_html = get_option("logo_html");
			?>

		</div>
					</a>	

		<div class="menu">
				<ul>
			<?php 
			$menu_exclude = get_option("menu_exclude");
			if($menu_exclude)
			{
				$exclude = "&exclude=".$menu_exclude;
			}
			wp_list_pages('title_li=&depth=1'.$exclude); 
			?>
			</ul>
		</div>
</div>
<?php display_tagline($post->ID); ?>