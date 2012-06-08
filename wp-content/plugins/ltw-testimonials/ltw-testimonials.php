<?php
/*
   Plugin Name: LessThanWeb - Testimonials
   Plugin URI: http://www.lessthanweb.com/products/wp-plugin-ltw-testimonials
   Description: Display client testimonials on your blog. Easily manage client testimonials for different products on a single blog by separating them into groups.
   Version: 1.3.1
   Author: LessThanWeb
   Author URI: http://www.lessthanweb.com
   License: GPL2
*/
/*  Copyright 2010  Anze Stokelj  (email : wpplugins@lessthanweb.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $wpdb;

//	Define some stuff for plugin..
define('LTW_TES_NAME', 'Testimonials');
define('LTW_TES_FOLDER_NAME', 'ltw-testimonials');
define('LTW_TES_VERSION', '1.3.1');
define('LTW_TES_TESTIMONIALS_TABLE', $wpdb->prefix.'ltw_testimonials');
define('LTW_TES_TESTIMONIAL_GROUPS_TABLE', $wpdb->prefix.'ltw_testimonial_groups');
define('LTW_TES_UNIQUE_NAME', 'ltw_testimonials');

require(WP_PLUGIN_DIR.'/'.LTW_TES_FOLDER_NAME.'/pages/ajax.php');

/**
 * Install function.
 * It creates required tables and adds version entry to settings table.
 *
 */
function ltw_tes_install()
{
	global $wpdb;

	if($wpdb->get_var("show tables like '".LTW_TES_TESTIMONIALS_TABLE."'") != LTW_TES_TESTIMONIALS_TABLE)
	{
		$sql = "CREATE TABLE IF NOT EXISTS `".LTW_TES_TESTIMONIALS_TABLE."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_id` INT NOT NULL DEFAULT '0',
				  `testimonial` text NOT NULL,
				  `client_name` varchar(250) NOT NULL,
				  `client_pic` VARCHAR(250) NOT NULL,
				  `client_website` varchar(250) NOT NULL,
				  `client_company` varchar(250) NOT NULL,
				  `order` int(5) NOT NULL DEFAULT '0',
				   `show_in_widget` TINYINT( 1 ) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	if($wpdb->get_var("show tables like '".LTW_TES_TESTIMONIAL_GROUPS_TABLE."'") != LTW_TES_TESTIMONIAL_GROUPS_TABLE)
	{
		$sql = "CREATE TABLE IF NOT EXISTS `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_name` varchar(250) NOT NULL,
				  `page_id` INT(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//	Add "count" field to groups table if the version is older then 1.3.1
	if (get_option('ltw_tes_version') < '1.3.1')
	{
		$sql = "ALTER TABLE  `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` ADD  `count` INT NOT NULL DEFAULT  '0' AFTER  `page_id`";
		$wpdb->query($sql);
	}

	//	Add current database version to option table.
	//	This is helpful for updates in case we have some table changes in future.
	add_option('ltw_tes_version', LTW_TES_VERSION);

	add_option('ltw_tes_sort_testimonials', '1', '', 'yes');
	add_option('ltw_tes_delete_tables', '0', '', 'yes');
	add_option('ltw_tes_design_css', '
.ltw_tes_item {
	margin: 0 0 24px 0;
	overflow: hidden;
	width: 100%;
}
.ltw_tes_image_cont {
	float: left;
	height: 88px;
	margin: 4px 20px 0 5px;
	width: 88px;
}
.ltw_tes_image_cont img {
	border: 2px solid #CCCCCC;
}
.ltw_tes_content_cont {
	float: left;
	width: 330px;
}
.ltw_tes_content_cont p {
	margin-bottom: 0;
}
.ltw_tes_testimonial {
	color: #555555;
	font-size: 12px;
	line-height: 21px;
	margin: 0 0 12px;
}
.ltw_tes_client_name {
	font-weight: bold;
	margin-top: 10px;
}
.ltw_tes_client_company {
	margin: 0;
	color: #00AEEF;
	font-size: 12px;
}
.ltw_tes_client_company a {
	color: #00AEEF;
	font-size: 12px;
	text-decoration: none;
}
.ltw_tes_client_company a:hover {
	color: #555555;
}
', '', 'yes');
	add_option('ltw_tes_design_html', '
<div class="ltw_tes_item">
	<div class="ltw_tes_image_cont"><img src="%image%" alt=""/></div>
	<div class="ltw_tes_content_cont">
		<p class="ltw_tes_testimonial">%testimonial%</p>
		<p class="ltw_tes_client_name">%client_name%</p>
		<p class="ltw_tes_client_company">%client_company%</p>
	</div>
</div>', '', 'yes');

	add_option('ltw_tes_promote_plugin', '0', '', 'yes');
}
register_activation_hook(__FILE__, 'ltw_tes_install');

/**
 * Create admin menu
 *
 */
function ltw_tes_menu_pages()
{
	//	Add a new top-level menu
    add_menu_page(LTW_TES_NAME, LTW_TES_NAME, 'administrator', 'ltw_manage_testimonials', 'ltw_manage_testimonials', WP_PLUGIN_URL.'/ltw-testimonials/images/icon.png');

	//	Create subpages
	$ltw_tes_testimonials_manage = add_submenu_page('ltw_manage_testimonials', LTW_TES_NAME.__(' - Manage', LTW_TES_UNIQUE_NAME), __('Testimonials', LTW_TES_UNIQUE_NAME), 'administrator', 'ltw_manage_testimonials', 'ltw_manage_testimonials');
	$ltw_tes_testimonial_groups = add_submenu_page('ltw_manage_testimonials', LTW_TES_NAME.__(' - Groups', LTW_TES_UNIQUE_NAME), __('Groups', LTW_TES_UNIQUE_NAME), 'administrator', 'ltw_manage_testimonial_groups', 'ltw_manage_testimonial_groups');
	$ltw_tes_testimonial_design = add_submenu_page('ltw_manage_testimonials', LTW_TES_NAME.__(' - Design', LTW_TES_UNIQUE_NAME), __('Design', LTW_TES_UNIQUE_NAME), 'administrator', 'ltw_manage_testimonial_design', 'ltw_manage_testimonial_design');
	$ltw_tes_testimonial_settings = add_submenu_page('ltw_manage_testimonials', LTW_TES_NAME.__(' - Settings', LTW_TES_UNIQUE_NAME), __('Settings', LTW_TES_UNIQUE_NAME), 'administrator', 'ltw_tes_settings', 'ltw_tes_settings');

	add_action('admin_print_styles-'.$ltw_tes_testimonials_manage, 'ltw_tes_admin_style');
	add_action('admin_print_styles-'.$ltw_tes_testimonial_groups, 'ltw_tes_admin_style');
	add_action('admin_print_styles-'.$ltw_tes_testimonial_design, 'ltw_tes_admin_style');
	add_action('admin_print_styles-'.$ltw_tes_testimonial_settings, 'ltw_tes_admin_style');

	//	Register settings
	add_action('admin_init', 'ltw_tes_register_settings');
}
//	Call create admin menu function above
add_action('admin_menu', 'ltw_tes_menu_pages');

/**
 * Register settings
 *
 */
function ltw_tes_register_settings()
{
	register_setting('ltw-testimonials-settings', 'ltw_tes_sort_testimonials');
	register_setting('ltw-testimonials-settings', 'ltw_tes_delete_tables');
	register_setting('ltw-testimonials-settings', 'ltw_tes_promote_plugin');

	register_setting('ltw-testimonials-design', 'ltw_tes_design_css');
	register_setting('ltw-testimonials-design', 'ltw_tes_design_html');

	if (get_option('ltw_tes_version') != LTW_TES_VERSION)
	{
		update_option('ltw_tes_version', LTW_TES_VERSION);
	}
}

/**
 * Main function for managing testimonials.
 *
 */
function ltw_manage_testimonials()
{
	global $wpdb;

	$current_testimonial_page = isset($_GET['sp']) ? $_GET['sp'] : '';

	switch($current_testimonial_page)
	{
		case 'edit':
			include('pages/testimonial_edit.php');
		break;

		//	Add new testimonial
		case 'add_new':
			include('pages/testimonial_add_new.php');
		break;

		//	Default page, table with all available testimonials
		default:
			include('pages/testimonial_index.php');
		break;
	}
}

/**
 * Main function for managing testimonial groups
 *
 */
function ltw_manage_testimonial_groups()
{
	global $wpdb;

	$current_testimonial_group_page = isset($_GET['sp']) ? $_GET['sp'] : '';

	switch($current_testimonial_group_page)
	{
		//	Show the "Edit Group" page
		case 'edit':
			include('pages/testimonial_groups_edit.php');
		break;

		//	Show the "Add New Group" page
		case 'add_new':
			include('pages/testimonial_groups_add_new.php');
		break;

		//	Default page, table with all available testimonial groups shown
		default:
			include('pages/testimonial_groups_index.php');
		break;
	}
}

/**
 * Register admin stylesheet
 *
 */
function ltw_tes_admin_init()
{
    //	Register stylesheet
    wp_register_style('ltw_tes_stylesheet', WP_PLUGIN_URL.'/'.LTW_TES_FOLDER_NAME.'/css/style_admin.css');
}
add_action('admin_init', 'ltw_tes_admin_init');

function ltw_tes_admin_style()
{
	wp_enqueue_style('ltw_tes_stylesheet');

	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('testimonials', WP_PLUGIN_URL.'/'.LTW_TES_FOLDER_NAME.'/js/testimonials.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('testimonials');
	wp_enqueue_style('thickbox');

	// embed the javascript file that makes the AJAX request
	wp_enqueue_script('ltw_tes_ajax', WP_PLUGIN_URL.'/'.LTW_TES_FOLDER_NAME.'/js/ajax.js', array('jquery'), LTW_TES_VERSION);
	wp_localize_script('ltw_tes_ajax', 'ltw_tes_ajax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ltw_tes_ajax_nonce')
		)
	);
}

/**
 * When the plugin is deactivated, delete the junk.
 * The junk being the tables that we created and any other extra records in "options" table. :)
 *
 */
function ltw_tes_plugin_uninstall()
{
	global $wpdb;

	if (get_option('ltw_tes_delete_tables') == '1')
	{
		//	Delete groups table
		$sql = "DROP TABLE IF EXISTS `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`";
		$wpdb->query($sql);

		//	Delete testimonials table
		$sql = "DROP TABLE IF EXISTS `".LTW_TES_TESTIMONIALS_TABLE."`";
		$wpdb->query($sql);

		//	Finally, delete the extra records from "options" table
	  	delete_option('ltw_tes_version');
	  	delete_option('ltw_tes_sort_testimonials');
	  	delete_option('ltw_tes_delete_tables');
	  	delete_option('ltw_tes_design_css');
	  	delete_option('ltw_tes_design_html');
	  	delete_option('ltw_tes_promote_plugin');
	}
}
register_deactivation_hook(__FILE__, 'ltw_tes_plugin_uninstall');

/**
 * Settings page
 *
 */
function ltw_tes_settings()
{
	global $wpdb;

	include('pages/testimonial_settings.php');
}

/**
 * Shortcode stuff :)
 *
 */
function ltw_tes_shortcode($atts)
{
	global $wpdb;

	//	Check if any attribute is set
	$ltw_tes_group_id = isset($atts['group']) ? $atts['group'] : 0;

	if ($ltw_tes_group_id == 0)
	{
		return FALSE;
	}

	//	Get order for testimonials
	$ltw_testimonials_order_sql = '';
	if (get_option('ltw_tes_sort_testimonials') == '1')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `id` DESC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '2')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `id` ASC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '3')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `order` DESC ';
	}

	$sql = $wpdb->prepare("
		SELECT *
		FROM ".LTW_TES_TESTIMONIALS_TABLE."
		WHERE `group_id` = %d
		".$ltw_testimonials_order_sql,
		array($ltw_tes_group_id)
	);
	$ltw_tes_info = array();
	$ltw_tes_info = $wpdb->get_results($sql, ARRAY_A);

	if (count($ltw_tes_info) == 0)
	{
		return FALSE;
	}
	else
	{
		$ltw_testimonial_str = '';

		//	Add CSS style
		$ltw_testimonial_str = '';
		if (get_option('ltw_tes_design_css') != '')
		{
			$ltw_testimonial_str = '<style type="text/css">'.get_option('ltw_tes_design_css').'</style>';
		}

		//	Display the testimonials
		foreach ($ltw_tes_info as $testimonial)
		{
			$ltw_testimonial_str .= get_option('ltw_tes_design_html');

			if ($testimonial['client_pic'] == '')
			{
				$ltw_testimonial_str = str_replace('%image%', get_bloginfo('url').'/wp-content/plugins/'.LTW_TES_FOLDER_NAME.'/images/blank.png', $ltw_testimonial_str);
			}
			else
			{
				$ltw_testimonial_str = str_replace('%image%', $testimonial['client_pic'], $ltw_testimonial_str);
			}

			$ltw_testimonial_str = str_replace('%testimonial%', '<a name="ltw_testimonial_'.$testimonial['id'].'"></a>%testimonial%', $ltw_testimonial_str);
			$ltw_testimonial_str = str_replace('%testimonial%', stripslashes(nl2br($testimonial['testimonial'])), $ltw_testimonial_str);
			$ltw_testimonial_str = str_replace('%client_name%', stripslashes($testimonial['client_name']), $ltw_testimonial_str);

			if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) == 0)
		    {
				$ltw_testimonial_str = str_replace('%client_company%', stripslashes($testimonial['client_company']), $ltw_testimonial_str);
		    }
		    else if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) > 0)
		    {
		    	$ltw_testimonial_str = str_replace('%client_company%', '<a class="cite-link" href="'.$testimonial['client_website'].'">'.stripslashes($testimonial['client_company']).'</a>', $ltw_testimonial_str);
		    }
		    else if (strlen($testimonial['client_company']) == 0 && strlen($testimonial['client_website']) > 0)
		    {
		    	$ltw_testimonial_str = str_replace('%client_company%', '<a class="cite-link" href="'.$testimonial['client_website'].'">'.$testimonial['client_website'].'</a>', $ltw_testimonial_str);
		    }
			else
			{
				$ltw_testimonial_str = str_replace('%client_company%', '', $ltw_testimonial_str);
			}
		}

		if (get_option('ltw_tes_promote_plugin') == '1')
		{
			$ltw_testimonial_str .= '<div style="text-align: center;"><a href="http://www.lessthanweb.com/products/wp-plugin-ltw-testimonials">Powered by LTW Testimonials</a></div>';
		}

		return $ltw_testimonial_str;
	}
}
add_shortcode('testimonial', 'ltw_tes_shortcode');

/**
 * Get a list of client testimonials for the widget
 *
 */
function ltw_tes_client_testimonials_widget($num, $group_id = '')
{
	global $wpdb;

	if ($group_id == '')
	{
		//	First let's get a random group
		$sql = $wpdb->prepare("
			SELECT ltwg.*, ifnull(ltwt.`counter`, 0) AS `counter`
			FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` AS ltwg
			LEFT JOIN (
			  SELECT `group_id`, COUNT(*) AS `counter` FROM `".LTW_TES_TESTIMONIALS_TABLE."`
			  WHERE `show_in_widget` = 1
			  GROUP BY `group_id`
			) AS ltwt
			ON
			ltwg.`id` = ltwt.`group_id`
			"
		);
		$ltw_tes_group_info = array();
		$ltw_tes_group_info = $wpdb->get_results($sql, ARRAY_A);

		$ltw_tes_rand_group = array();

		//	Remove groups with 0 testimonials
		foreach ($ltw_tes_group_info as $group)
		{
			if ($group['counter'] != 0)
			{
				$ltw_tes_rand_group[] = $group['id'];
			}
		}

		$ltw_tes_rand_group_arr = mt_rand(0, (count($ltw_tes_rand_group)-1));

		$ltw_show_group_id = $ltw_tes_rand_group[$ltw_tes_rand_group_arr];
	}
	else
	{
		$ltw_show_group_id = $group_id;
	}

	$sql = $wpdb->prepare("
		SELECT t.*, g.`group_name`, g.`page_id`
		FROM ".LTW_TES_TESTIMONIALS_TABLE." AS t
		LEFT JOIN ".LTW_TES_TESTIMONIAL_GROUPS_TABLE." AS g ON (g.`id` = t.`group_id`)
		WHERE t.`group_id` = %d
		AND t.`show_in_widget` = 1
		ORDER BY RAND()
		LIMIT ".$num."
		", $ltw_show_group_id
	);
	$ltw_tes_info = array();
	$ltw_tes_info = $wpdb->get_results($sql, ARRAY_A);

	return $ltw_tes_info;
}

/**
 * LTW Widget class
 */
global $wp_version;
if (version_compare($wp_version, '2.8', '>='))
{
	class ltw_tes_widget extends WP_Widget
	{
	    function ltw_tes_widget()
	    {
	    	$widget_ops = array('classname' => 'widget_ltw_testimonials', 'description' => __('Display one or more client testimonials', LTW_TES_UNIQUE_NAME));
	        parent::WP_Widget('ltw_testimonials', __('Testimonials', LTW_TES_UNIQUE_NAME), $widget_ops);
	    }

	    function widget($args, $instance)
	    {
	    	extract($args);
	        $ltw_title = apply_filters('widget_title', $instance['ltw_title']);

			echo $before_widget;

	        if ($ltw_title)
	            echo $before_title . $ltw_title . $after_title;

	    	$ltw_tes_info = ltw_tes_client_testimonials_widget($instance['ltw_number_of_testimonials'], $instance['ltw_one_group_only']);

	    	if (count($ltw_tes_info) > 0)
	    	{
		    	foreach ($ltw_tes_info as $testimonial)
		    	{
		    		$ltw_testimonial_split = array();

		    		//	Check if user set the word limiter
		    		if (isset($instance['ltw_set_word_limit']) == TRUE && $instance['ltw_set_word_limit'] != '0' && $instance['ltw_set_word_limit'] != '')
		    		{
		    			$ltw_testimonial_split = explode(' ', trim($testimonial['testimonial']));

		    			if (is_numeric($instance['ltw_set_word_limit']))
			    		{
		    				$ltw_testimonial_tmp = '';
			    			for ($i = 0; $i < $instance['ltw_set_word_limit']; $i++)
			    			{
			    				$ltw_testimonial_tmp .= $ltw_testimonial_split[$i].' ';
			    			}

		    				if (count($ltw_testimonial_split) > $instance['ltw_set_word_limit'])
		    				{
		    					$ltw_testimonial_tmp = trim($ltw_testimonial_tmp).'...';
		    				}
			    		}
		    		}

		    		if (isset($ltw_testimonial_tmp) == TRUE && strlen($ltw_testimonial_tmp) > 0)
		    		{
		    			$testimonial['testimonial'] = $ltw_testimonial_tmp;
		    		}

		    		if (isset($instance['ltw_show_picture']) == TRUE && $instance['ltw_show_picture'] == '1' && $testimonial['client_pic'] != '')
					{
						echo '<p><img src="'.stripslashes($testimonial['client_pic']).'" alt="'.stripslashes($testimonial['client_name']).'"/></p>';
					}

		    		echo '<p>'.stripslashes(nl2br($testimonial['testimonial'])).'</p>';
		    		echo '<p><cite>';
		    		$ltw_client_info = stripslashes($testimonial['client_name']).'<br />';

		    		if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) == 0)
		    		{
		    			$ltw_client_info .= stripslashes($testimonial['client_company']);
		    		}
		    		else if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) > 0)
		    		{
		    			$ltw_client_info .= '<a class="cite-link" href="'.$testimonial['client_website'].'">'.stripslashes($testimonial['client_company']).'</a>';
		    		}
		    		else if (strlen($testimonial['client_company']) == 0 && strlen($testimonial['client_website']) > 0)
		    		{
		    			$ltw_client_info .= '<a class="cite-link" href="'.$testimonial['client_website'].'">'.$testimonial['client_website'].'</a>';
		    		}

		    		echo $ltw_client_info;

		    		echo '</cite></p>';
		    	}

		    	if ($instance['ltw_show_more_link'] == '1')
		    	{
		    		$ltw_tes_page_data = get_page($testimonial['page_id']);

		    		if (get_option('permalink_structure') == '')
		    		{
		    			$ltw_tes_full_testimonials_url = get_bloginfo('url').'/?p='.$ltw_tes_page_data->ID;
		    		}
		    		else
		    		{
		    			$ltw_tes_full_testimonials_url = get_bloginfo('url').'/'.$ltw_tes_page_data->post_name;
		    		}

		    		echo '<p><a href="'.$ltw_tes_full_testimonials_url.'#ltw_testimonial_'.$testimonial['id'].'">'.$instance['ltw_show_more_text'].'</a></p>';
		    	}
	    	}
	    	else
	    	{
	    		echo '<p>'.__('There are no testimonial yet', LTW_TES_UNIQUE_NAME).'</p>';
	    	}

	        echo $after_widget;
	    }

	    function update($new_instance, $old_instance)
	    {
			$instance = $old_instance;
			$instance['ltw_title'] = strip_tags($new_instance['ltw_title']);
			$instance['ltw_number_of_testimonials'] = strip_tags($new_instance['ltw_number_of_testimonials']);
			$instance['ltw_show_more_link'] = strip_tags($new_instance['ltw_show_more_link']);
			$instance['ltw_show_more_text'] = strip_tags($new_instance['ltw_show_more_text']);
			$instance['ltw_set_word_limit'] = $new_instance['ltw_set_word_limit'];
			$instance['ltw_one_group_only'] = $new_instance['ltw_one_group_only'];
			$instance['ltw_show_picture'] = $new_instance['ltw_show_picture'];

	        return $instance;
	    }

	    function form($instance)
	    {
	    	global $wpdb;

	    	$instance = wp_parse_args((array) $instance, array('ltw_title' => __('Testimonials', LTW_TES_UNIQUE_NAME)));
	        $ltw_title = esc_attr($instance['ltw_title']);
	        $ltw_number_of_testimonials = esc_attr($instance['ltw_number_of_testimonials']);
	        $ltw_show_more_link = esc_attr($instance['ltw_show_more_link']);
	        $ltw_show_more_text = esc_attr($instance['ltw_show_more_text']);
	        $ltw_set_word_limit = esc_attr($instance['ltw_set_word_limit']);
	        $ltw_one_group_only = esc_attr($instance['ltw_one_group_only']);
	        $ltw_show_picture = esc_attr($instance['ltw_show_picture']);

	    	$sql = "SELECT `id`, `group_name`, `page_id`
					FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
					ORDER BY `group_name` ASC";
			$ltw_tes_group_info = array();
			$ltw_tes_group_info = $wpdb->get_results($sql, ARRAY_A);
?>
	            <p><label for="<?php echo $this->get_field_id('ltw_title'); ?>"><?php _e('Title:', LTW_TES_UNIQUE_NAME); ?> <input class="widefat" id="<?php echo $this->get_field_id('ltw_title'); ?>" name="<?php echo $this->get_field_name('ltw_title'); ?>" type="text" value="<?php echo $ltw_title; ?>" /></label></p>
	            <p><label for="<?php echo $this->get_field_id('ltw_number_of_testimonials'); ?>"><?php _e('Show', LTW_TES_UNIQUE_NAME); ?> <input size="3" id="<?php echo $this->get_field_id('ltw_number_of_testimonials'); ?>" name="<?php echo $this->get_field_name('ltw_number_of_testimonials'); ?>" type="text" value="<?php echo $ltw_number_of_testimonials == '' ? '1' : $ltw_number_of_testimonials; ?>" /> <?php _e('testimonial(s)', LTW_TES_UNIQUE_NAME); ?></label></p>
	            <p><label for="<?php echo $this->get_field_id('ltw_show_more_link'); ?>"><input id="<?php echo $this->get_field_id('ltw_show_more_link'); ?>" name="<?php echo $this->get_field_name('ltw_show_more_link'); ?>" type="checkbox" value="1" <?php echo $ltw_show_more_link == '1' ? ' checked="checked"' : ''; ?>/> <?php _e('Display &quot;Show More&quot; link:', LTW_TES_UNIQUE_NAME); ?></label></p>
	            <p><label for="<?php echo $this->get_field_id('ltw_show_more_text'); ?>"><?php _e('&quot;Show More&quot; text:', LTW_TES_UNIQUE_NAME); ?> <input class="widefat" id="<?php echo $this->get_field_id('ltw_show_more_text'); ?>" name="<?php echo $this->get_field_name('ltw_show_more_text'); ?>" type="text" value="<?php echo $ltw_show_more_text; ?>" /></label></p>
	            <p><label for="<?php echo $this->get_field_id('ltw_set_word_limit'); ?>"><?php _e('Set word limit:', LTW_TES_UNIQUE_NAME); ?> <input size="3" id="<?php echo $this->get_field_id('ltw_set_word_limit'); ?>" name="<?php echo $this->get_field_name('ltw_set_word_limit'); ?>" type="text" value="<?php echo $ltw_set_word_limit == '' ? '0' : $ltw_set_word_limit; ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('ltw_one_group_only'); ?>"><?php _e('Show only this group:', LTW_TES_UNIQUE_NAME); ?>
					<select name="<?php echo $this->get_field_name('ltw_one_group_only'); ?>" id="<?php echo $this->get_field_id('ltw_one_group_only'); ?>">
						<option></option>
<?php
if (count($ltw_tes_group_info) > 0)
{
	foreach ($ltw_tes_group_info as $group)
	{
		$ltw_group_selected = '';
		if ($group['id'] == $ltw_one_group_only)
		{
			$ltw_group_selected = ' selected="selected"';
		}
?>
						<option value="<?php echo $group['id']; ?>"<?php echo $ltw_group_selected; ?>><?php echo $group['group_name']; ?></option>
<?php
	}
}
?>
					</select>
				</label></p>
				<p><label for="<?php echo $this->get_field_id('ltw_show_picture'); ?>"><input id="<?php echo $this->get_field_id('ltw_show_picture'); ?>" name="<?php echo $this->get_field_name('ltw_show_picture'); ?>" type="checkbox" value="1" <?php echo $ltw_show_picture == '1' ? ' checked="checked"' : ''; ?>/> <?php _e('Check to display client picture', LTW_TES_UNIQUE_NAME); ?></label></p>
<?php
	    }

	}
	add_action('widgets_init', create_function('', 'return register_widget("ltw_tes_widget");'));
}

/**
 * Design page for CSS and HTML of the testimonial
 *
 */
function ltw_manage_testimonial_design()
{
	global $wpdb;

	include('pages/testimonial_design.php');
}

/**
 * Shortcode for displaying ALL the testimonials no matter which group they are in
 *
 */
function ltw_tes_shortcode_all()
{
	global $wpdb;

	//	Get order for testimonials
	$ltw_testimonials_order_sql = '';
	if (get_option('ltw_tes_sort_testimonials') == '1')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `id` DESC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '2')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `id` ASC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '3')
	{
		$ltw_testimonials_order_sql = ' ORDER BY `order` ASC ';
	}

	$sql = $wpdb->prepare("
		SELECT *
		FROM ".LTW_TES_TESTIMONIALS_TABLE."
		".$ltw_testimonials_order_sql,
		array($ltw_tes_group_id)
	);
	$ltw_tes_info = array();
	$ltw_tes_info = $wpdb->get_results($sql, ARRAY_A);

	if (count($ltw_tes_info) == 0)
	{
		return FALSE;
	}
	else
	{
		$ltw_testimonial_str = '';

		//	Add CSS style
		$ltw_testimonial_str = '<style type="text/css">'.get_option('ltw_tes_design_css').'</style>';

		//	Display the testimonials
		foreach ($ltw_tes_info as $testimonial)
		{
			$ltw_testimonial_str .= get_option('ltw_tes_design_html');

			if ($testimonial['client_pic'] == '')
			{
				$ltw_testimonial_str = str_replace('%image%', get_bloginfo('url').'/wp-content/plugins/'.LTW_TES_FOLDER_NAME.'/images/blank.png', $ltw_testimonial_str);
			}
			else
			{
				$ltw_testimonial_str = str_replace('%image%', $testimonial['client_pic'], $ltw_testimonial_str);
			}

			$ltw_testimonial_str = str_replace('%testimonial%', '<a name="ltw_testimonial_'.$testimonial['id'].'"></a>%testimonial%', $ltw_testimonial_str);
			$ltw_testimonial_str = str_replace('%testimonial%', stripslashes(nl2br($testimonial['testimonial'])), $ltw_testimonial_str);
			$ltw_testimonial_str = str_replace('%client_name%', stripslashes($testimonial['client_name']), $ltw_testimonial_str);

			if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) == 0)
		    {
				$ltw_testimonial_str = str_replace('%client_company%', stripslashes($testimonial['client_company']), $ltw_testimonial_str);
		    }
		    else if (strlen($testimonial['client_company']) > 0 && strlen($testimonial['client_website']) > 0)
		    {
		    	$ltw_testimonial_str = str_replace('%client_company%', '<a class="cite-link" href="'.$testimonial['client_website'].'">'.stripslashes($testimonial['client_company']).'</a>', $ltw_testimonial_str);
		    }
		    else if (strlen($testimonial['client_company']) == 0 && strlen($testimonial['client_website']) > 0)
		    {
		    	$ltw_testimonial_str = str_replace('%client_company%', '<a class="cite-link" href="'.$testimonial['client_website'].'">'.$testimonial['client_website'].'</a>', $ltw_testimonial_str);
		    }
			else
			{
				$ltw_testimonial_str = str_replace('%client_company%', '', $ltw_testimonial_str);
			}
		}

		if (get_option('ltw_tes_promote_plugin') == '1')
		{
			$ltw_testimonial_str .= '<div style="text-align: center;"><a href="http://www.lessthanweb.com/products/wp-plugin-ltw-testimonials">Powered by LTW Testimonials</a></div>';
		}

		return $ltw_testimonial_str;
	}
}
add_shortcode('show_all_testimonials', 'ltw_tes_shortcode_all');
?>