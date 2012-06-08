<?php
/*
* Plugin Name: Custom Social Media
* Plugin URI: http://www.curtziegler.com/
* Description: Display social media links for Minimal template.
* Version: 1.0
* Author: Curt Ziegler
* Author URI: http://www.curtziegler.com/
*/

add_action( 'widgets_init', 'social_media_load_widgets' );

/* Register widget */
function social_media_load_widgets() {
	register_widget( 'Social_Media_Widget' );
}

/* Widget class: Settings, form, display, and update. */
class Social_Media_Widget extends WP_Widget {

	function Social_Media_Widget() {
		$widget_ops = array( 'classname' => 'custom_social_media', 'description' => __('A custom widget that displays social media links.', 'custom_social_media') );
		$control_ops = array( 'width' => 500, 'height' => 300, 'id_base' => 'custom_social_media-widget' );
		$this->WP_Widget( 'custom_social_media-widget', __('Custom Social Media Links', 'custom_social_media'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		$social_media_links = "";
		
		if($instance['sm_01_url'] && $instance['sm_01_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_01_icon'].");' href='".$instance['sm_01_url']."'></a></li>";
		if($instance['sm_02_url'] && $instance['sm_02_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_02_icon'].");' href='".$instance['sm_02_url']."'></a></li>";
		if($instance['sm_03_url'] && $instance['sm_03_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_03_icon'].");' href='".$instance['sm_03_url']."'></a></li>";
		if($instance['sm_04_url'] && $instance['sm_04_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_04_icon'].");' href='".$instance['sm_04_url']."'></a></li>";
		if($instance['sm_05_url'] && $instance['sm_05_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_05_icon'].");' href='".$instance['sm_05_url']."'></a></li>";
		if($instance['sm_06_url'] && $instance['sm_06_icon'])
			$social_media_links .=  "<li><a style='background-image:url(".get_bloginfo('template_directory'). "/images/social_icons/" .$instance['sm_06_icon'].");' href='".$instance['sm_06_url']."'></a></li>";

		
		echo $before_widget;
		if($title) { echo "<h6>".$title."</h6>"; }
		
		if($social_media_links)
			echo "<ul class='social clearfix'>" . $social_media_links . "</ul>";
		
		echo $after_widget;
	}

	/* Update settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		$instance['sm_01_icon'] = strip_tags( $new_instance['sm_01_icon'] );
		$instance['sm_01_url'] = strip_tags( $new_instance['sm_01_url'] );
		$instance['sm_02_icon'] = strip_tags( $new_instance['sm_02_icon'] );
		$instance['sm_02_url'] = strip_tags( $new_instance['sm_02_url'] );
		$instance['sm_03_icon'] = strip_tags( $new_instance['sm_03_icon'] );
		$instance['sm_03_url'] = strip_tags( $new_instance['sm_03_url'] );
		$instance['sm_04_icon'] = strip_tags( $new_instance['sm_04_icon'] );
		$instance['sm_04_url'] = strip_tags( $new_instance['sm_04_url'] );
		$instance['sm_05_icon'] = strip_tags( $new_instance['sm_05_icon'] );
		$instance['sm_05_url'] = strip_tags( $new_instance['sm_05_url'] );
		$instance['sm_06_icon'] = strip_tags( $new_instance['sm_06_icon'] );
		$instance['sm_06_url'] = strip_tags( $new_instance['sm_06_url'] );
		
		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Social Media Links', 'custom_social_media'), 
			'sm_01_icon' => '', 'sm_01_url' => '',
			'sm_02_icon' => '', 'sm_02_url' => '',
			'sm_03_icon' => '', 'sm_03_url' => '',
			'sm_04_icon' => '', 'sm_04_url' => '',
			'sm_05_icon' => '', 'sm_05_url' => '',
			'sm_06_icon' => '', 'sm_06_url' => ''
			);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e('Title:', 'hybrid'); ?></strong><br /><small>Leave blank to hide title.</small></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p><strong>Choose an icon, then enter the full link URL</strong><br /><small>Please use full URL, ex: http://www.twitter.com/username/</small><br /><small>Upload any alternate images to use in: images/social_icons of this theme's folder.</small></p>
		
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_01_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_01_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_01_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_01_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_01_url' ); ?>" value="<?php echo $instance['sm_01_url']; ?>" style="width:250px;" />
		</p>
		<?php //echo list_social_icons($instance["sm_01_icon"]); ?>
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_02_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_02_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_02_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_02_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_02_url' ); ?>" value="<?php echo $instance['sm_02_url']; ?>" style="width:250px;" />
		</p>
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_03_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_03_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_03_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_03_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_03_url' ); ?>" value="<?php echo $instance['sm_03_url']; ?>" style="width:250px;" />
		</p>
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_04_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_04_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_04_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_04_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_04_url' ); ?>" value="<?php echo $instance['sm_04_url']; ?>" style="width:250px;" />
		</p>
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_05_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_05_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_05_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_05_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_05_url' ); ?>" value="<?php echo $instance['sm_05_url']; ?>" style="width:250px;" />
		</p>
		<p>
			<select id="<?php echo $this->get_field_id( 'sm_06_icon' ); ?>" name="<?php echo $this->get_field_name( 'sm_06_icon' ); ?>" style="width:200px;"><?php echo list_social_icons($instance['sm_06_icon']); ?></select>
			<input id="<?php echo $this->get_field_id( 'sm_06_url' ); ?>" name="<?php echo $this->get_field_name( 'sm_06_url' ); ?>" value="<?php echo $instance['sm_06_url']; ?>" style="width:250px;" />
		</p>
		
		
		
		



	<?php
	}
}
?>