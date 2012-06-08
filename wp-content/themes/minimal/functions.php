<?php

	automatic_feed_links();
	
// Adding Custom Admin Settings Page
add_action('admin_menu', 'portfolio_settings'); 
add_action('admin_head', 'portfolio_styles');
function portfolio_settings() { 
	add_menu_page('Minimal Portfolio Settings', 'Minimal Portfolio Settings', 'edit_themes', __FILE__, 'portfolio_settings_form');
}


// Register Multiple sidebars:
// sidebar-home, sidebar-pages, sidebar-blog
if ( function_exists('register_sidebar') ) {
	register_sidebar(array('name'=>'sidebar-home',
		'before_widget' => '<div class="pod">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>',
	));
	register_sidebar(array('name'=>'sidebar-blog',
		'before_widget' => '<div class="pod">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>',
	));
	register_sidebar(array('name'=>'sidebar-pages',
		'before_widget' => '<div class="pod">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>',
	));
	register_sidebar(array('name'=>'sidebar-all',
		'before_widget' => '<div class="pod">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>',
	));
}

// Simple function to set the max promos
function get_promo_list_max()
{
	// SET THIS TO YOUR MAX PROMOS TO OVERRIDE THIS THEME DEFAULT
	$max_promos = 5;
	return $max_promos;
}

function category_ddl()
{
	echo "<option value=''>None</option>";
	$categories = get_categories('hierarchical=0&hide_empty=0'); 
	foreach ($categories as $cat) {
		$selected = "";
		if(get_option('portfolio_category_id') == $cat->cat_ID) { $selected = " selected='selected' "; }
		$option = "<option value='".$cat->cat_ID."' ".$selected.">";
		$option .= $cat->cat_name;
		$option .= ' ('.$cat->cat_ID.')';
		$option .= '</option>';
		echo $option;
	}
	
}

function list_sub_pages($submenu_parent)
{
	if(is_subpage()) { 
		echo "<ul class='sub-menu'>". wp_list_pages("title_li=&child_of=".$post->ID."&echo=0&depth=1'") ."</ul>"; 
	}else{
		echo "<ul class='sub-menu'>". wp_list_pages('title_li=&child_of='.$submenu_parent.'&echo=0&depth=1') ."</ul>";  
	}
}

// Used to display the list of promos on the home page
// will insert a comment into the HTML if no active promos set:
// <!-- no home promos set to active -->
function home_promo_list()
{
	$output_home_promos = "";
	for($i = 1; $i <= get_promo_list_max(); $i++)
	{
		if(get_option("home_promo_active_".$i) == 1)
		{
			$output_home_promos .= "<li class='line'>";
			$output_home_promos .= "<a href='".get_option("home_promo_url_".$i)."' style='background-image:url(".get_bloginfo('template_directory')."/images/large_icons/".get_option("home_promo_icon_url_".$i).");'>";
			$output_home_promos .= "<span class='heading'>". get_option("home_promo_title_".$i) . "</span>";
			$output_home_promos .= "<span class='summary'>".get_option("home_promo_description_".$i)."</span>";
			$output_home_promos .= "</a>";
			$output_home_promos .= "</li>";
		}
	}	
	if(!empty($output_home_promos))
	{
		$output_home_promos = "<ul class='large'>" . $output_home_promos . "</ul>";
	}else{
		$output_home_promos = "<!-- no home promos set to active -->";
	}
	echo $output_home_promos;
}


function company_table($side)
{	
	if($side == 'right')
		$side ='right_company';
	if($side == 'left')
		$side ='left_company';

		
	$company_id = get_category_by_slug($side)->term_id;

	query_posts('cat='.$company_id);
				
	if(have_posts()){
		
		while(have_posts()) : the_post();
		global $post;
	
			$output_home_promos = "";
			$output_home_promos .= "<li class='line'>";
			$output_home_promos .= '<a id="company">';
			$output_home_promos .= "<span class='heading'>". $post->post_title . "</span>";
			$output_home_promos .= "<span class='summary'>".$post->post_content."</span>";
			$output_home_promos .= "</a>";
			$output_home_promos .= "</li>";

			if(!empty($output_home_promos))
			{
				$output_home_promos = "<ul class='large'>" . $output_home_promos . "</ul>";
			}
			
		echo $output_home_promos;				
		endwhile;
	}
	
}


// display the settings form on the custom settings admin page
function portfolio_settings_form(){ 
    if(isset($_POST['submit-updates']) && $_POST['submit-updates'] == "yes"){

		update_option("color_main", stripslashes($_POST["color_main"]));
		update_option("tagline", stripslashes($_POST["tagline"]));
		update_option("tagline_disabled", stripslashes($_POST["tagline_disabled"]));
		update_option("footer_copy", stripslashes($_POST["footer_copy"]));
		update_option("menuwidth", stripslashes($_POST["menuwidth"]));
		update_option("cf_email", stripslashes($_POST["cf_email"]));
		update_option("portfolio_category_id", stripslashes($_POST["portfolio_category_id"]));
		update_option("menu_exclude", stripslashes($_POST["menu_exclude"]));
		update_option("logo_html", stripslashes($_POST["logo_html"]));
		
		
		for($i = 1; $i <= get_promo_list_max(); $i++)
		{
			update_promo_settings($i);
		}
		
		// display confirmation message 
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>Saved Settings!</strong></p></div>";
    }
	
?>
<div class="wrap">
	<form method="post" name="brightness" target="_self" class="adminoptions">
		<h1>Theme Settings</h1>
		<p><strong>Leave any option blank and hit save to revert back to the defaults.</strong></p>
		<input type="submit" name="Submit" value="Save Settings" />
		
		
		<h2>General</h2>
        <div class="field"><label>Contact Form "to" Email Address</label>
        <small><strong>Required if you are using the contact form page template!</strong><br />Enter your email address: email@mail.com</small>
        <input name="cf_email" value="<?php echo get_option("cf_email"); ?>" class="textbox-large"></div>

		<div class="field"><label>Tagline:</label><small>The tagline appears under the logo.<br />Use basic HTML for styling. (&lt;br /&gt; and &lt;a href=''&gt;links&lt;/a&gt;)<br /><strong>You can override this on a specific post or page, add a custom field called: tagline</strong></small><textarea cols="2" rows="2" class="textarea-small" name="tagline"><?php echo get_option('tagline'); ?></textarea>
		<label><input type="checkbox" value="1" name="tagline_disabled" <?php if(get_option("tagline_disabled")){ echo "checked='checked'"; } ?> /> Disable Tagline</label></div>
		<div class="field"><label>Footer:</label><small>Use basic HTML for styling. (&lt;br /&gt; and &lt;a href=''&gt;links&lt;/a&gt;)<br />This text will be wrapped in &lt;p&gt; tags when it is displayed in the footer.</small><textarea cols="2" rows="2" class="textarea-small" name="footer_copy"><?php echo get_option('footer_copy'); ?></textarea></div>
		
        <div class="field"><label>Primary Theme Color</label>
        <small>This is the color of the links and some headings, enter a color code such as: <strong>#b86443 or #006699</strong>.</small>
        <input name="color_main" value="<?php echo get_option("color_main"); ?>" class="textbox-small"></div>
        <hr />
        
        <div class="field"><label>Cutom Logo Image URL</label>
        <small>Override the default text logo with one of your choice.</small>
        <input name="logo_html" value="<?php echo get_option("logo_html"); ?>" class="textbox-large"></div>
        <hr />
        
        <div class="field"><label>Menu Width</label>
        <small>Optional, controls the overall width of the main menu.<br />
        By default will stretch 100% across the page but if you want the narrow look, adjust to be just wide enough to fit your menu items.<br />
        Use values such as <strong>400px</strong> or <strong>75%</strong>.</small>
        <input name="menuwidth" value="<?php echo get_option("menuwidth"); ?>" class="textbox-small"></div>
		
		<div class="field"><label>Exclude from Top Menu</label>
		<small>Comma separated list of page ID's to exclude from the top menu: 44,23,93</small>
		<input type="text" name="menu_exclude" value="<?php echo get_option("menu_exclude"); ?>" class="textbox-small" />
		</div>
		<hr />
        
		<div class="field"><label>Portfolio Category:</label>
        <small>Optional, set to none if you do not have a portfolio page.<br />
        This category will be excluded from the normal blog page if set and only displayed on the portfolio page template.</small>
		<small>NEW -- You can override this with a custom field of categories on this page template, the values would be the category id's you want to include, ex: 4,5,10</small>
        <select name="portfolio_category_id"><?php category_ddl(); ?></select></div>
		<hr />
		
		<h2>Home Promos</h2>
		<p>Max currently set to <?php echo get_promo_list_max(); ?> - if you need more, just edit $max_promos in functions.php.<br />I suggest just using 3 to keep the look of the site clean.<br />Promos will only appear if the "set active" checkbox is checked.</p>
		<?php
			for($i = 1; $i <= get_promo_list_max(); $i++)
			{
				admin_page_add_promo_field($i);
			}
		?>
		
		<input type="submit" name="Submit" value="Save Settings" />
		<input name="submit-updates" type="hidden" value="yes" />
		<br /><br /><br /><br />
	</form>
</div>
<?php 
}



// Add Dashboard Head CSS to custom settings page
function portfolio_styles() { 
	echo "<style type=\"text/css\"> 
	.adminoptions label { display: block; font-weight:bold; } 
	.adminoptions .field { padding:5px 0; } 
	.adminoptions small { display:block; } 
	.adminoptions .textbox-small { width:100px; } 
	.adminoptions .textbox-med-small { width:175px; } 
	.adminoptions .textbox-medium { width:250px; } 
	.adminoptions .textbox-large { width:350px; } 
	.adminoptions .textarea-small { width:350px; height:50px; } 
	.adminoptions .textarea-medium { width:450px; height:50px; } 
	.adminoptions .textarea-large { width:500px; height:100px; } 
	.adminoptions .inset { padding-left:20px; margin:15px 0;  border-left:2px dotted #ccc; } 
	</style>";
}


// used to add the fields for adding a promo
function admin_page_add_promo_field($id)
{
	if(get_option("home_promo_active_".$id) == 1)
	{
		$home_promo_active_checked = " checked='checked' ";
	}
	echo "<div class='field'><div class='inset'><p><strong>Promo Item ".$id."</strong></p>";
	echo "<label>Title</label><input class='textbox-medium' type='text' name='home_promo_title_".$id."' value='". get_option("home_promo_title_".$id) ."' /><br />";
	echo "<label>Description</label><small></small><textarea cols='2' rows='2' class='textarea-medium' name='home_promo_description_".$id."'>". get_option("home_promo_description_".$id) ."</textarea><br />";
	echo "<label>URL</label><input class='textbox-large' type='text' name='home_promo_url_".$id."' value='". get_option("home_promo_url_".$id) ."' /><br />";
	echo "<label>Icon</label><small>Upload any images you want to appear in the list below in: images/<strong>large_icons</strong></small>";
	echo "<select name='home_promo_icon_url_".$id ."'>". list_thumbnails($id) . "</select><br />";
	echo "<label><input type='checkbox' value='1' name='home_promo_active_".$id."' ".$home_promo_active_checked." /> Set Active</label></div></div>";
	
	
}

// used to update all fields related to a promo
function update_promo_settings($id)
{
	update_option("home_promo_title_".$id, stripslashes($_POST["home_promo_title_".$id]));
	update_option("home_promo_description_".$id, stripslashes($_POST["home_promo_description_".$id]));
	update_option("home_promo_url_".$id, stripslashes($_POST["home_promo_url_".$id]));
	update_option("home_promo_icon_url_".$id, stripslashes($_POST["home_promo_icon_url_".$id]));
	update_option("home_promo_active_".$id, stripslashes($_POST["home_promo_active_".$id]));
}

// lists all thumbnails in the "large_icons" directory
function list_thumbnails($id)
{
	$list_of_thumbnails = "";
	$list_of_thumbnails .= "<option value=''>None</option>";
	if ($handle = opendir(TEMPLATEPATH . "/images/large_icons")) {
		while (false !== ($file = readdir($handle))) {
			if (preg_match("/^.*\.(jpg|jpeg|png|gif)$/i", $file)) {
				
				if(get_option("home_promo_icon_url_".$id) == $file)
				{
					$list_of_thumbnails .= "<option selected='selected'>";
				}else{
					$list_of_thumbnails .= "<option>";
				}
				$list_of_thumbnails .= "$file</option>";
			}
			
		}
		closedir($handle);
		
		return $list_of_thumbnails;
	}
}

// lists all thumbnails in the "social_icons" directory
function list_social_icons($id)
{
	$list_of_icons = "";
	$list_of_icons .= "<option value=''>None</option>";
	if ($handle = opendir(TEMPLATEPATH . "/images/social_icons")) {
		while (false !== ($file = readdir($handle))) {
			if (preg_match("/^.*\.(jpg|jpeg|png|gif)$/i", $file)) {
				
				if($file == $id)
				{
					$list_of_icons .= "<option selected='selected'>";
				}else{
					$list_of_icons .= "<option>";
					//$list_of_icons .= "<!-- test: File: ".$file." id: " .$id. "-->";
				}
				$list_of_icons .= "$file</option>";
			}
			
		}
		closedir($handle);
		
		return $list_of_icons;
	}
}

// display the tagline heading using the custom settings page
// overridden by setting a custom field: tagline on a post/page
function display_tagline($thepostid)
{
	if(!get_option("tagline_disabled"))
	{
		$custom_tagline = get_option('tagline');
		
			if(empty($custom_tagline)) { 
				$custom_tagline = "Please update the tagline<br />in the custom admin page.";
			}
			if(get_post_meta($thepostid, "tagline", true))
			{
				$custom_tagline = get_post_meta($thepostid, "tagline", true);
			}
			if($custom_tagline != "none" && get_post_meta($thepostid, "tagline2", true))
			{
				$custom_tagline = get_post_meta($thepostid, "tagline2", true);
				echo "<div class='tagline2'><p>".$custom_tagline."</p></div>";
			} else if($custom_tagline != "none")
			{
				echo "<div class='tagline'><p>".$custom_tagline."</p></div>";
			}
	}
}


function my_attachment_image($postid=0, $size='thumbnail', $attributes='') {
	$count = 0;
	if ($postid < 1 ) $postid = get_the_ID();
	if ($images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
		foreach($images as $image) {
			$attachment=wp_get_attachment_image_src($image->ID, $size);
				echo "<img src='".get_bloginfo('template_directory'). "/images/resize/timthumb.php?src=" . $attachment[0] . "&w=590&zc=0'" . $attributes . " class='attachment-image border-thick' />";
				$count++;
		}
		if($count == 0)
		{
			echo "No image uploaded - image required in the portfolio category...";
		}
}

// used on page_contact.php page template
function mail_form()
{
	// if send is set but fake e-mail is not...
	// spam robots often fill in all form fields, so if the hidden e-mail 
	// has a value...it's probably spam.
	if(!empty($_POST['send_mail']) && empty($_POST['e-mail']))
	{
		$to = get_option('cf_email') ? get_option('cf_email') : "sitedemo@curtziegler.com";
		$subject = "Contact Form Submission";
		
		$message = "Message from your website:\n\n";
		$message .= "From: " . stripslashes($_POST["cfName"]) . "\n";
		$message .= "Email: " . $_POST["cfEmail"] . "\n";
		$message .= "Phone: " . $_POST["cfPhone"] . "\n";
		$message .= "Message: " . stripslashes($_POST["cfMessage"]) . "\n\n";
		$message .= "IP Address: " . $_SERVER["REMOTE_ADDR"] . "\n\n";
		$message .= "Sent from: " . $_SERVER['HTTP_HOST'] . "\n\n";
		
		$from = $_POST["cfEmail"];
		$headers = "From: ".$_POST["cfEmail"];
		
		if(mail($to,$subject,$message,$headers))
		{
			$sent = true;
		}else{
			$sent = false;
		}
	}

	
?>
	
	<?php if($sent == true) { ?>
		<h3>Thank You!</h3>
		<p><strong>Your message has been sent.</strong></p>
	<?php }else{ ?>
		<h3>Contact Form</h3>
	<?php } ?>
	<p id="message" class="hidden"></p>
    <form method="post" target="_self" action="" onsubmit="javascript:return validate(this);" class="standardForm">
        <div class="field"><label>Your Name</label><input type="text" name="cfName" class="textbox" /></div>
        <div class="field"><label>Your Email</label><input type="text" name="cfEmail" class="textbox" /></div>
        <div class="field"><label>Your Phone</label><input type="text" name="cfPhone" class="textbox" /></div>
        <div class="field"><label>Message</label><textarea cols="5" rows="5" class="textarea" name="cfMessage" tabindex="4">Enter your message...</textarea></div>
        <div class="field"><input type="submit" value="SEND MESSAGE" class="submit button" name="send_mail" /></div>
        <input type="hidden" name="e-mail" />
    </form>
<?php
}
?>