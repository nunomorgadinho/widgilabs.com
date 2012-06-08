<?php
/**
 * First check if ID exist with requested ID
 *
 */
$ltw_tes_id = isset($_GET['id']) ? $_GET['id'] : '0';

$sql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".LTW_TES_TESTIMONIALS_TABLE."
	WHERE `id` = %d",
	array($ltw_tes_id)
);
$result = '0';
$result = $wpdb->get_var($sql);

if ($result != '1')
{
?>
<div class="wrap">
	<h2><?php _e('Edit Testimonial', LTW_TES_UNIQUE_NAME); ?></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
	<div class="error fade"><p><strong><?php _e('Oops, selected testimonial doesn\'t exist.', LTW_TES_UNIQUE_NAME); ?></strong> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials"><?php _e('Click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to go back to the overview page.', LTW_TES_UNIQUE_NAME); ?></p></div>
</div>
<?php
}
else
{
	$ltw_tes_errors = array();
	$ltw_tes_success = "";
	$ltw_tes_error_found = FALSE;

	/**
	 * Get all available groups
	 *
	 */
	$sql = "SELECT `id`, `group_name`, `page_id`
			FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
			ORDER BY `group_name` ASC";
	$ltw_tes_group_info = array();
	$ltw_tes_group_info = $wpdb->get_results($sql, ARRAY_A);

	$sql = $wpdb->prepare("
		SELECT *
		FROM `".LTW_TES_TESTIMONIALS_TABLE."`
		WHERE `id` = %d
		LIMIT 1
		",
		array($ltw_tes_id)
	);
	$ltw_tes_info = array();
	$ltw_tes_info = $wpdb->get_row($sql, ARRAY_A);

	/**
	 * Preset the form fields
	 *
	 */
	$form = array(
		'ltw_tes_client_testimonial' => $ltw_tes_info['testimonial'],
		'ltw_tes_group_id' => $ltw_tes_info['group_id'],
		'ltw_tes_new_group_name' => '',
		'ltw_tes_client_name' => $ltw_tes_info['client_name'],
		'ltw_tes_client_website' => $ltw_tes_info['client_website'],
		'ltw_tes_client_company' => $ltw_tes_info['client_company'],
		'ltw_tes_show_widget' => $ltw_tes_info['show_in_widget'],
		'upload_image' => $ltw_tes_info['client_pic'],
		'ltw_tes_order' => $ltw_tes_info['order']
	);

	/**
	 * Form submitted, check the data
	 *
	 */
	if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
	{
		//	Just a little ;) security thingy that wordpress offers us
		check_admin_referer('ltw_tes_edit_testimonial_form');

		$form['ltw_tes_group_id'] = isset($_POST['ltw_tes_group_id']) ? $_POST['ltw_tes_group_id'] : '';
		$form['ltw_tes_new_group_name'] = isset($_POST['ltw_tes_new_group_name']) ? $_POST['ltw_tes_new_group_name'] : '';
		if ($form['ltw_tes_group_id'] == '' && $form['ltw_tes_new_group_name'] == '')
		{
			$ltw_tes_errors[] = __('Please select a group or create a new one.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		//	Check if group already exist. This is when user wants to create new group!
		if ($form['ltw_tes_group_id'] == '' && $form['ltw_tes_new_group_name'] != '')
		{
			$sql = $wpdb->prepare(
				"SELECT COUNT(*) AS `count` FROM ".LTW_TES_TESTIMONIAL_GROUPS_TABLE."
				WHERE `group_name` = %s",
				array($form['ltw_tes_new_group_name'])
			);
			$result = '0';
			$result = $wpdb->get_var($sql);

			if ($result != '0')
			{
				$ltw_tes_errors[] = __('Group with the same name already exist.', LTW_TES_UNIQUE_NAME);
				$ltw_tes_error_found = TRUE;
			}
		}

		$form['ltw_tes_show_widget'] = isset($_POST['ltw_tes_show_widget']) ? '1' : '0';

		$form['ltw_tes_client_name'] = isset($_POST['ltw_tes_client_name']) ? $_POST['ltw_tes_client_name'] : '';
		if ($form['ltw_tes_client_name'] == '')
		{
			$ltw_tes_errors[] = __('Please enter the name of the client.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		$form['upload_image'] = isset($_POST['upload_image']) ? $_POST['upload_image'] : '';
		if ($form['upload_image'] == 'http://')
		{
			$form['upload_image'] = '';
		}

		$form['ltw_tes_client_company'] = isset($_POST['ltw_tes_client_company']) ? $_POST['ltw_tes_client_company'] : '';

		$form['ltw_tes_client_website'] = isset($_POST['ltw_tes_client_website']) ? $_POST['ltw_tes_client_website'] : '';

		$form['ltw_tes_client_testimonial'] = isset($_POST['ltw_tes_client_testimonial']) ? $_POST['ltw_tes_client_testimonial'] : '';
		if ($form['ltw_tes_client_testimonial'] == '')
		{
			$ltw_tes_errors[] = __('Please enter the clients testimonial', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		$form['ltw_tes_order'] = isset($_POST['ltw_tes_order']) ? $_POST['ltw_tes_order'] : '';
		if ($form['ltw_tes_order'] == '')
		{
			$ltw_tes_errors[] = __('Please enter the order.', LTW_TES_UNIQUE_NAME);
			$ltw_tes_error_found = TRUE;
		}

		//	No errors found, we can add this Group to the table
		if ($ltw_tes_error_found == FALSE)
		{
			//	If user wants to create a new group, let's do so then :)
			if ($form['ltw_tes_group_id'] == '' && $form['ltw_tes_new_group_name'] != '')
			{
				//	Create new group
				$sql = $wpdb->prepare(
					"INSERT INTO `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
					(`group_name`)
					VALUES(%s)",
					array($form['ltw_tes_new_group_name'])
				);
				$wpdb->query($sql);

				$form['ltw_tes_group_id'] = $wpdb->insert_id();
			}

			$sql = $wpdb->prepare(
				"UPDATE `".LTW_TES_TESTIMONIALS_TABLE."`
				SET `group_id` = %d,
				`testimonial` = %s,
				`client_name` = %s,
				`client_pic` = %s,
				`client_website` = %s,
				`client_company` = %s,
				`show_in_widget` = %d,
				`order` = %d
				WHERE id = %d
				LIMIT 1",
				array($form['ltw_tes_group_id'], $form['ltw_tes_client_testimonial'], $form['ltw_tes_client_name'], $form['upload_image'], $form['ltw_tes_client_website'], $form['ltw_tes_client_company'], $form['ltw_tes_show_widget'], $form['ltw_tes_order'], $ltw_tes_id)
			);
			$wpdb->query($sql);

			$ltw_tes_success = __('Client testimonial was successfully updated.', LTW_TES_UNIQUE_NAME);
		}
	}
?>
<div class="wrap">
	<h2><?php _e('Add New Testimonial', LTW_TES_UNIQUE_NAME); ?></h2>
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
	<div class="updated fade"><p><strong><?php echo $ltw_tes_success; ?></strong></p><p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials"><?php _e('Click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to go back to the testimonials overview page', LTW_TES_UNIQUE_NAME); ?> <?php _e('or', LTW_TES_UNIQUE_NAME); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials&amp;sp=add_new"><?php _e('click here', LTW_TES_UNIQUE_NAME); ?></a> <?php _e('to add another client testimonial.', LTW_TES_UNIQUE_NAME); ?></p></div>
<?php
	}
?>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials&amp;sp=edit&amp;id=<?php echo $ltw_tes_id; ?>">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('ltw_tes_edit_testimonial_form'); ?>
		</div>
		<table class="form-table">
			<tr valign="top">
	        	<th scope="row"><?php _e('Select Group', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
					<select name="ltw_tes_group_id">
						<option></option>
<?php
	if (count($ltw_tes_group_info) > 0)
	{
		foreach ($ltw_tes_group_info as $group)
		{
			$ltw_group_selected = '';
			if ($group['id'] == $form['ltw_tes_group_id'])
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
					<?php _e('or create new one', LTW_TES_UNIQUE_NAME); ?>
					<input type="text" name="ltw_tes_new_group_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_new_group_name'])); ?>" />
				</td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Show in Widget', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="checkbox" name="ltw_tes_show_widget" value="1"<?php echo isset($form['ltw_tes_show_widget']) && $form['ltw_tes_show_widget'] == '1' ? ' checked="checked"' : ''; ?> /> <?php _e('Show this client testimonial in the Widget', LTW_TES_UNIQUE_NAME); ?></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Client Name', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="text" name="ltw_tes_client_name" class="regular-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_client_name'])); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Picture', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo esc_html(stripslashes($form['upload_image'])); ?>" />
					<input id="upload_image_button" type="button" value="<?php _e('Upload', LTW_TES_UNIQUE_NAME); ?>" />
					<br />
					<?php _e('Enter an URL or upload an image.', LTW_TES_UNIQUE_NAME); ?>
					<br />
					<?php _e('To change the image size, change the width and height in the <a href="'.get_option('siteurl').'/wp-admin/admin.php?page=ltw_manage_testimonial_design">CSS code</a> under the class &quot;ltw_tes_image_cont&quot;.', LTW_TES_UNIQUE_NAME); ?>
					<br />
					<?php _e('Default size of the image is 88px x 88px.', LTW_TES_UNIQUE_NAME); ?>
				</td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Company', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="text" name="ltw_tes_client_company" class="regular-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_client_company'])); ?>" /> <span class="description"><?php _e('Optional', LTW_TES_UNIQUE_NAME); ?></span></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Website', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="text" name="ltw_tes_client_website" class="regular-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_client_website'])); ?>" /> <span class="description"><?php _e('Optional', LTW_TES_UNIQUE_NAME); ?></span></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row" colspan="2">
	        		<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/ltw-testimonials/js/js_quicktags.js"></script>
	        		<script type="text/javascript">edToolbar('ltw_tes_client_testimonial');</script>
	        		<textarea class="large-text code" id="ltw_tes_client_testimonial" cols="50" rows="10" name="ltw_tes_client_testimonial"><?php echo esc_html(stripslashes($form['ltw_tes_client_testimonial'])); ?></textarea>
				</th>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Order', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><input type="text" name="ltw_tes_order" class="small-text" value="<?php echo esc_html(stripslashes($form['ltw_tes_order'])); ?>" /></td>
	        </tr>
	    </table>
	    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Update Testimonial', LTW_TES_UNIQUE_NAME); ?>" /></p>
	</form>
</div>
<?php
}
?>