<?php
/**
 * First check if ID exist with requested ID
 *
 */
$ltw_tes_group_id = isset($_GET['id']) ? $_GET['id'] : '0';

$sql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".LTW_TES_TESTIMONIAL_GROUPS_TABLE."
	WHERE `id` = %d",
	array($ltw_tes_group_id)
);
$result = '0';
$result = $wpdb->get_var($sql);

if ($result != '1')
{
?>
<div class="wrap">
	<h2><?php _e('Edit Testimonial Group', LTW_TES_UNIQUE_NAME); ?></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
	<div class="error fade"><p><strong><?php _e('Selected testimonial group doesn\'t exist.', LTW_TES_UNIQUE_NAME); ?></strong> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups"><?php _e('Click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to go back to the group overview page.', LTW_TES_UNIQUE_NAME); ?></p></div>
</div>
<?php
}
else
{
	$ltw_tes_errors = array();
	$ltw_tes_success = '';
	$ltw_tes_error_found = FALSE;

	$sql = $wpdb->prepare("
		SELECT `id`, `group_name`, `page_id`
		FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
		WHERE `id` = %d
		LIMIT 1
		",
		array($ltw_tes_group_id)
	);
	$ltw_tes_group_info = array();
	$ltw_tes_group_info = $wpdb->get_row($sql, ARRAY_A);

	/**
	 * Preset the form fields
	 *
	 */
	$form = array(
		'ltw_tes_group_name' => $ltw_tes_group_info['group_name'],
		'ltw_tes_page' => $ltw_tes_group_info['page_id']
	);

	/**
	 * Form submitted, check the data
	 *
	 */
	if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
	{
		//	Just a little ;) security thingy that wordpress offers us
		check_admin_referer('ltw_tes_edit_group_form');

		$form['ltw_tes_group_name'] = isset($_POST['ltw_tes_group_name']) ? $_POST['ltw_tes_group_name'] : '';
		if ($form['ltw_tes_group_name'] == '')
		{
			$ltw_tes_errors[] = __('Group Name field is required.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		$form['ltw_tes_page'] = isset($_POST['ltw_tes_page']) ? $_POST['ltw_tes_page'] : '';
		if ($form['ltw_tes_page'] == '')
		{
			$ltw_tes_errors[] = __('Please select the page.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		//	Check if group name already exist
		$sql = $wpdb->prepare(
			"SELECT COUNT(*) AS `count` FROM ".LTW_TES_TESTIMONIAL_GROUPS_TABLE."
			WHERE `group_name` = %s
			AND `id` <> %d",
			array($form['ltw_tes_group_name'], $ltw_tes_group_id)
		);
		$result = '0';
		$result = $wpdb->get_var($sql);

		if ($result != '0')
		{
			$ltw_tes_errors[] = __('Group with the same name already exist.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		//	No errors found, we can add this Group to the table
		if ($ltw_tes_error_found == FALSE)
		{
			$sql = $wpdb->prepare(
				"UPDATE `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
				SET `group_name` = %s,
				`page_id` = %d
				WHERE id = %d
				LIMIT 1",
				array($form['ltw_tes_group_name'], $form['ltw_tes_page'], $ltw_tes_group_id)
			);
			$wpdb->query($sql);

			$ltw_tes_success = __('Testimonial group was successfully updated.', LTW_TES_UNIQUE_NAME);
		}
	}
?>
<div class="wrap">
	<h2><?php _e('Edit Testimonial Group', LTW_TES_UNIQUE_NAME); ?></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
<?php
	if ($ltw_tes_error_found == TRUE && isset($ltw_tes_errors[0]) == TRUE)
	{
?>
	<div class="error fade"><p><strong><?php echo $ltw_tes_errors[0]; ?></strong></p></div>
<?php
	}

	if ($ltw_tes_error_found == FALSE && strlen($ltw_tes_success) > 0)
	{
?>
	<div class="updated fade"><p><strong><?php echo $ltw_tes_success; ?></strong></p><p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups"><?php _e('Click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to go back to groups overview page', LTW_TES_UNIQUE_NAME); ?> <?php _e('or', LTW_TES_UNIQUE_NAME); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups&amp;sp=add_new"><?php _e('click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to add another group.', LTW_TES_UNIQUE_NAME); ?></p></div>
<?php
	}
?>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups&amp;sp=edit&amp;id=<?php echo $ltw_tes_group_id; ?>">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('ltw_tes_edit_group_form'); ?>
		</div>
		<table class="form-table">
			<tr valign="top">
	        	<th scope="row"><?php _e('Group Name', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="text" name="ltw_tes_group_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_group_name'])); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Testimonial Page', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<select name="ltw_tes_page">
	        			<option></option>
<?php
foreach (get_pages() as $key => $value)
{
	$selected_page = '';
	if ($form['ltw_tes_page'] == $value->ID)
	{
		$selected_page = ' selected="selected"';
	}
?>
						<option value="<?php echo $value->ID; ?>"<?php echo $selected_page; ?>><?php echo $value->post_title; ?></option>
<?php
}
?>
					</select>
					<br/>
	        		<span class="description"><?php _e('This is the page where all the testimonials from this group will be shown. It is used for the &quot;Show More&quot; link in the widget.', LTW_TES_UNIQUE_NAME); ?></span>
				</td>
	        </tr>
	    </table>
	    <p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Update Group', LTW_TES_UNIQUE_NAME); ?>" />
	    </p>
	</form>
</div>
<?php
}
?>