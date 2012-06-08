<?php
$ltw_tes_show_success_msg = FALSE;
$ltw_tes_set_success_msg = '';

/**
 * Check if "Bulk Action" was used
 *
 */
if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
{
	if ((isset($_POST['action']) && $_POST['action'] == 'delete') || (isset($_POST['action2']) && $_POST['action2'] == 'delete'))
	{
		if (isset($_POST['ltw_tes_group_item']) && count($_POST['ltw_tes_group_item']) > 0)
		{
			//	Just a little ;) security thingy that wordpress offers us
			check_admin_referer('ltw_tes_groups_index');

			foreach ($_POST['ltw_tes_group_item'] as $ltw_tes_group_item)
			{
				//	Delete all selected records from the table
				$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
						WHERE `id` = %d", $ltw_tes_group_item);
				$wpdb->query($sql);

				//	Also delete the testimonials that belong to this group
				$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIALS_TABLE."`
						WHERE `group_id` = %d", $ltw_tes_group_item);
				$wpdb->query($sql);

				//	Set success message
				$ltw_tes_show_success_msg = TRUE;
				$ltw_tes_set_success_msg = __('Selected groups were successfully deleted.', LTW_TES_UNIQUE_NAME);
			}
		}
	}
}

/**
 * Check if we are deleting a record.
 * This is available per each testimonial group.
 *
 */
if (isset($_GET['sp']) && $_GET['sp'] == 'delete' && isset($_GET['id']) && $_GET['id'] != '')
{
	//	Just a little ;) security thingy that wordpress offers us
	check_admin_referer('ltw_tes_delete_group');

	//	Delete selected record from the table
	$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
			WHERE `id` = %d
			LIMIT 1", $_GET['id']);
	$wpdb->query($sql);

	//	Also delete the testimonials that belong to this group
	$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIALS_TABLE."`
			WHERE `group_id` = %d", $_GET['id']);
	$wpdb->query($sql);

	//	Set success message
	$ltw_tes_show_success_msg = TRUE;
	$ltw_tes_set_success_msg = __('Selected record was successfully deleted.', LTW_TES_UNIQUE_NAME);
}
?>
<div class="wrap">
	<div class="metabox-holder" id="poststuff">
	<h2><?php _e('Testimonial Groups', LTW_TES_UNIQUE_NAME); ?> <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups&amp;sp=add_new"><?php _e('Add New Group', LTW_TES_UNIQUE_NAME); ?></a></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
<?php
if ($ltw_tes_show_success_msg == TRUE)
{
?>
	<div class="updated fade"><p><strong><?php echo $ltw_tes_set_success_msg; ?></strong></p></div>
<?php
}

//	Get all testimonial groups
$sql = "SELECT ltwg.*, ifnull(ltwt.`counter`, 0) AS `counter`
		FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` AS ltwg
		LEFT JOIN (
		  SELECT `group_id`, COUNT(*) AS `counter` FROM `".LTW_TES_TESTIMONIALS_TABLE."` GROUP BY `group_id`
		) AS ltwt
		ON
		ltwg.`id` = ltwt.`group_id`
		ORDER BY ltwg.`group_name` ASC";
$db_list = array();
$db_list = $wpdb->get_results($sql, ARRAY_A);

if (count($db_list) > 0)
{
?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups" method="post">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('ltw_tes_groups_index'); ?>
		</div>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action">
					<option selected="selected" value=""><?php _e('Bulk Actions', LTW_TES_UNIQUE_NAME); ?></option>
					<option value="delete"><?php _e('Delete', LTW_TES_UNIQUE_NAME); ?></option>
				</select>
				<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="<?php _e('Apply', LTW_TES_UNIQUE_NAME); ?>">
			</div>
		</div>
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="name" class="manage-column column-title"><?php _e('Name', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="shortcode" class="manage-column column-title"><?php _e('Shortcode', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="count" class="manage-column column-visible"><?php _e('# of Testimonials', LTW_TES_UNIQUE_NAME); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb2" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="name2" class="manage-column column-title"><?php _e('Name', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="shortcode2" class="manage-column column-title"><?php _e('Shortcode', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="count2" class="manage-column column-visible"><?php _e('# of Testimonials', LTW_TES_UNIQUE_NAME); ?></th>
				</tr>
			</tfoot>
			<tbody>
<?php
	$alternate_row = 0;

	foreach ($db_list as $list)
	{
		$alternate_row_class = ' alternate';
		if ($alternate_row == 1)
		{
			$alternate_row_class = '';
			$alternate_row = 0;
		}
		else
		{
			$alternate_row++;
		}
?>
				<tr class="iedit<?php echo $alternate_row_class; ?>">
					<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $list['id']; ?>" name="ltw_tes_group_item[]"></th>
					<td class="column-title">
						<strong><?php echo esc_html(stripslashes($list['group_name'])); ?></strong>
						<div class="row-actions">
							<span class="edit"><a title="<?php _e('Edit', LTW_TES_UNIQUE_NAME); ?>" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonial_groups&amp;sp=edit&amp;id=<?php echo $list['id']; ?>"><?php _e('Edit', LTW_TES_UNIQUE_NAME); ?></a> | </span>
<?php
//	Build nonce url
$ltw_tes_delete_group_url = wp_nonce_url(get_option('siteurl').'/wp-admin/admin.php?page=ltw_manage_testimonial_groups&amp;sp=delete&amp;id='.$list['id'], 'ltw_tes_delete_group');
?>
							<span class="trash"><a href="<?php echo $ltw_tes_delete_group_url; ?>" title="<?php _e('Delete', LTW_TES_UNIQUE_NAME); ?>" class="submitdelete"><?php _e('Delete', LTW_TES_UNIQUE_NAME); ?></a></span>
						</div>
					</td>
					<td class="column-title">[testimonial group=&quot;<?php echo $list['id']; ?>&quot;]</td>
					<td class="column-visible"><?php echo $list['counter']; ?></td>
				</tr>
<?php

	}
?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action2">
					<option selected="selected" value=""><?php _e('Bulk Actions', LTW_TES_UNIQUE_NAME); ?></option>
					<option value="delete"><?php _e('Delete', LTW_TES_UNIQUE_NAME); ?></option>
				</select>
				<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="<?php _e('Apply', LTW_TES_UNIQUE_NAME); ?>">
			</div>
		</div>
	</form>
	<p class="description"><?php _e('Deleting a group also deletes all the testimonials that belong to the group.', LTW_TES_UNIQUE_NAME); ?></p><br />
<?php
}
else
{
?>
	<p><?php _e('No testimonial groups found.', LTW_TES_UNIQUE_NAME); ?></p>
<?php
}
?>
	<div class="postbox">
		<div title="Click to toggle" class="handlediv"><br></div>
		<h3 class="hndle"><span>Note:</span></h3>
		<div class="inside">
            <p>To display all the testimonials from all the groups, use [show_all_testimonials] shortcode in a page or post.</p>
		</div>
	</div>

</div>
</div>