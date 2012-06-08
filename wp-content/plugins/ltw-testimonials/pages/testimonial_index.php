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
		if (isset($_POST['ltw_tes_testimonial_item']) && count($_POST['ltw_tes_testimonial_item']) > 0)
		{
			//	Just a little ;) security thingy that wordpress offers us
			check_admin_referer('ltw_tes_index');

			foreach ($_POST['ltw_tes_testimonial_item'] as $ltw_tes_testimonial_item)
			{
				//	Delete all selected records from the table
				$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIALS_TABLE."`
						WHERE `id` = %d", $ltw_tes_testimonial_item);
				$wpdb->query($sql);

				//	Set success message
				$ltw_tes_show_success_msg = TRUE;
				$ltw_tes_set_success_msg = __('Selected client testimonials were successfully deleted.', LTW_TES_UNIQUE_NAME);
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
	check_admin_referer('ltw_tes_delete_testimonial');

	//	Delete selected record from the table
	$sql = $wpdb->prepare("DELETE FROM `".LTW_TES_TESTIMONIALS_TABLE."`
			WHERE `id` = %d
			LIMIT 1", $_GET['id']);
	$wpdb->query($sql);

	//	Set success message
	$ltw_tes_show_success_msg = TRUE;
	$ltw_tes_set_success_msg = __('Selected client testimonial was successfully deleted.', LTW_TES_UNIQUE_NAME);
}

/**
 * Group filter stuff.
 *
 */
$ltw_group_qs = isset($_GET['filter']) ? '&amp;filter='.$_GET['filter'] : '&amp;filter=0';
$ltw_group_sql_id = isset($_GET['filter']) ? $_GET['filter'] : '0';

if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'yes')
{
	if ((isset($_POST['ltw_group_filter_action']) && strlen($_POST['ltw_group_filter_action']) > 0) || (isset($_POST['ltw_group_filter_action2']) && strlen($_POST['ltw_group_filter_action2']) > 0))
	{
		//	Just a little ;) security thingy that wordpress offers us
		check_admin_referer('ltw_tes_index');

		if (isset($_POST['ltw_group_filter']) == TRUE && $_POST['ltw_group_filter'] != '0' && isset($_POST['ltw_group_filter_action']) == TRUE)
		{
			//	Create the query string for filter
			$ltw_group_qs = '&amp;filter='.$_POST['ltw_group_filter'];
			$ltw_group_sql_id = $_POST['ltw_group_filter'];
		}
		else if (isset($_POST['ltw_group_filter']) == TRUE && $_POST['ltw_group_filter'] == '0' && isset($_POST['ltw_group_filter_action']) == TRUE)
		{
			//	Create the query string for filter
			$ltw_group_qs = '';
			$ltw_group_sql_id = 0;
		}
		else if (isset($_POST['ltw_group_filter2']) == TRUE && $_POST['ltw_group_filter2'] != '0' && isset($_POST['ltw_group_filter_action2']) == TRUE)
		{
			//	Create the query string for filter
			$ltw_group_qs = '&amp;filter='.$_POST['ltw_group_filter2'];
			$ltw_group_sql_id = $_POST['ltw_group_filter2'];
		}
		else if (isset($_POST['ltw_group_filter2']) == TRUE && $_POST['ltw_group_filter2'] == '0' && isset($_POST['ltw_group_filter_action2']) == TRUE)
		{
			//	Create the query string for filter
			$ltw_group_qs = '';
			$ltw_group_sql_id = 0;
		}
	}
}
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$(".ltw_show_full_tes").live('click', function(){
		$(this).attr("class", "ltw_hide_full_tes");
		$("#more_"+$(this).attr('id')).hide();
		$("#full_"+$(this).attr('id')).show();
		$(this).html("<?php _e('Hide full testimonial', LTW_TES_UNIQUE_NAME); ?>");
	});
	$(".ltw_hide_full_tes").live('click', function(){
		$(this).attr("class", "ltw_show_full_tes");
		$("#more_"+$(this).attr('id')).show();
		$("#full_"+$(this).attr('id')).hide();
		$(this).html("<?php _e('Show full testimonial', LTW_TES_UNIQUE_NAME); ?>");
	});
});
/* ]]> */
</script>
<div class="wrap">
	<h2><?php _e('Testimonials', LTW_TES_UNIQUE_NAME); ?> <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials&amp;sp=add_new"><?php _e('Add New Testimonial', LTW_TES_UNIQUE_NAME); ?></a></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
<?php
include(ABSPATH.'wp-content/plugins/'.LTW_TES_FOLDER_NAME.'/pagination.class.php');

$ltw_group_sql = '';

if ($ltw_group_sql_id != '0')
{
	$ltw_group_sql = ' WHERE g.`id` = %d ';
}

//	Get all testimonials
$sql = $wpdb->prepare("SELECT COUNT(tes.`id`) AS counter
		FROM `".LTW_TES_TESTIMONIALS_TABLE."` AS tes
		LEFT JOIN `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` AS g ON (g.`id` = tes.`group_id`)
		".$ltw_group_sql,
		array($ltw_group_sql_id)
		);
$ltw_testimonials_count = '';
$ltw_testimonials_count = $wpdb->get_results($sql, ARRAY_A);

/**
* Get all available groups
*
*/
$sql = "SELECT `id`, `group_name`, `page_id`
		FROM `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."`
		ORDER BY `group_name` ASC";
$ltw_tes_group_info = array();
$ltw_tes_group_info = $wpdb->get_results($sql, ARRAY_A);

if ($ltw_testimonials_count[0]['counter'] > 0)
{
	$p = new pagination;
	$p->items($ltw_testimonials_count[0]['counter']);
	$p->limit(15); // Limit entries per page
	$p->target("admin.php?page=ltw_manage_testimonials".$ltw_group_qs);
	$p->currentPage(isset($_GET['paging']) == TRUE ? $_GET['paging'] : 0); // Gets and validates the current page
	$p->calculate(); // Calculates what to show
	$p->parameterName('paging');
	$p->adjacents(1); //No. of page away from the current page

	if(!isset($_GET['paging']))
	{
		$p->page = 1;
	}
	else
	{
		$p->page = $_GET['paging'];
	}

	//Query for limit paging
	$ltw_limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

	if (get_option('ltw_tes_sort_testimonials') == '1')
	{
		$ltw_tes_order_by = ' ORDER BY tes.`id` DESC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '2')
	{
		$ltw_tes_order_by = ' ORDER BY tes.`id` ASC ';
	}
	else if (get_option('ltw_tes_sort_testimonials') == '3')
	{
		//$ltw_tes_order_by = ' ORDER BY tes.`order` ASC ';
		$ltw_tes_order_by = ' ORDER BY IF(`order` > 0, `order`, 1000000), `order` ASC ';
	}

	$sql = $wpdb->prepare("SELECT tes.`id`, tes.`testimonial`, tes.`client_name`, tes.`client_website`, tes.`client_company`, tes.`order`, tes.`group_id`, tes.`show_in_widget`, tes.`client_pic`,
			g.`group_name`
			FROM `".LTW_TES_TESTIMONIALS_TABLE."` AS tes
			LEFT JOIN `".LTW_TES_TESTIMONIAL_GROUPS_TABLE."` AS g ON (g.`id` = tes.`group_id`)
			".$ltw_group_sql."
			".$ltw_tes_order_by."
			".$ltw_limit."",
			array($ltw_group_sql_id)
			);
	$ltw_testimonials_list = array();
	$ltw_testimonials_list = $wpdb->get_results($sql, ARRAY_A);
}
?>
	<form action="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials<?php echo $ltw_group_qs; ?>" method="post">
		<div>
			<input type="hidden" name="form_submit" value="yes"/>
			<?php wp_nonce_field('ltw_tes_index'); ?>
		</div>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action">
					<option selected="selected" value=""><?php _e('Bulk Actions', LTW_TES_UNIQUE_NAME); ?></option>
					<option value="delete"><?php _e('Delete', LTW_TES_UNIQUE_NAME); ?></option>
				</select>
				<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="<?php _e('Apply', LTW_TES_UNIQUE_NAME); ?>">
				<?php _e('Show testimonials from group', LTW_TES_UNIQUE_NAME); ?>
				<select name="ltw_group_filter">
					<option value="0"><?php _e('All', LTW_TES_UNIQUE_NAME); ?></option>
<?php
if (count($ltw_tes_group_info) > 0)
{
	foreach ($ltw_tes_group_info as $group)
	{
		$ltw_group_selected = '';
		if ($group['id'] == $ltw_group_sql_id)
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
				<input type="submit" class="button-secondary action" id="ltw_group_filter_action" name="ltw_group_filter_action" value="<?php _e('Filter', LTW_TES_UNIQUE_NAME); ?>">
			</div>
<?php
if ($ltw_testimonials_count[0]['counter'] > 0)
{
?>
			<div class="alignright">
				<?php echo $p->show(); ?>
			</div>
<?php
}
?>
		</div>
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="client_name" class="manage-column column-title"><?php _e('Client Name', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="client_testimonial" class="manage-column column-title"><?php _e('Testimonial', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="client_group" class="manage-column column-title"><?php _e('Group', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="show_in_widget" class="manage-column column-visible"><?php _e('Show in Widget', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="order" class="manage-column column-visible"><?php _e('Order', LTW_TES_UNIQUE_NAME); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="cb2" class="manage-column column-cb check-column"><input type="checkbox" /></th>
					<th scope="col" id="client_name2" class="manage-column column-title"><?php _e('Client Name', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="client_testimonial2" class="manage-column column-title"><?php _e('Testimonial', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="client_group2" class="manage-column column-title"><?php _e('Group', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="show_in_widget2" class="manage-column column-visible"><?php _e('Show in Widget', LTW_TES_UNIQUE_NAME); ?></th>
					<th scope="col" id="order2" class="manage-column column-visible"><?php _e('Order', LTW_TES_UNIQUE_NAME); ?></th>
				</tr>
			</tfoot>
			<tbody>
<?php
if ($ltw_testimonials_count[0]['counter'] > 0)
{
	$alternate_row = 0;

	foreach ($ltw_testimonials_list as $list)
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
					<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $list['id']; ?>" name="ltw_tes_testimonial_item[]"></th>
					<td class="column-title">
<?php
		if ($list['client_pic'] != '' && $list['client_pic'] != 'http://')
		{
?>
						<img style="float:left;width:48px;height:48px;margin-right:10px;" src="<?php echo esc_html(stripslashes($list['client_pic'])); ?>"/>
<?php
		}
?>
						<strong><?php echo esc_html(stripslashes($list['client_name'])); ?></strong>
<?php
		if ($list['client_website'] != '' && $list['client_website'] != 'http://')
		{
?>
						<br />
						<a href="<?php echo esc_html(stripslashes($list['client_website'])); ?>"><?php echo esc_html(stripslashes($list['client_website'])); ?></a>
<?php
		}
?>
						<div class="row-actions">
							<span class="edit"><a title="<?php _e('Edit', LTW_TES_UNIQUE_NAME); ?>" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=ltw_manage_testimonials&amp;sp=edit&amp;id=<?php echo $list['id']; ?>"><?php _e('Edit', LTW_TES_UNIQUE_NAME); ?></a> | </span>
<?php
		//	Build nonce url
		$ltw_tes_delete_group_url = wp_nonce_url(get_option('siteurl').'/wp-admin/admin.php?page=ltw_manage_testimonials&amp;sp=delete&amp;id='.$list['id'], 'ltw_tes_delete_testimonial');
?>
							<span class="trash"><a href="<?php echo $ltw_tes_delete_group_url; ?>" title="<?php _e('Delete', LTW_TES_UNIQUE_NAME); ?>" class="submitdelete"><?php _e('Delete', LTW_TES_UNIQUE_NAME); ?></a></span>
						</div>
					</td>
					<td class="column-title">
<?php
		if (strlen($list['testimonial']) > 200)
		{
?>
						<div id="ltw_testimonial_limited">
<?php	echo stripslashes(substr($list['testimonial'], 0, 200)); ?><span class="ltw_testimonial_more" id="more_ltw_<?php echo $list['id']; ?>">...</span><span class="ltw_testimonial_full" id="full_ltw_<?php echo $list['id']; ?>" style="display: none;"><?php echo substr(stripslashes($list['testimonial']), 200); ?></span>
						</div>
						<div class="row-actions" id="ltw_testimonial_action_show">
							<span class="edit"><a href="#" class="ltw_show_full_tes" id="ltw_<?php echo $list['id']; ?>" onclick="return false;"><?php _e('Show full testimonial', LTW_TES_UNIQUE_NAME); ?></a></span>
						</div>
<?php
		}
		else
		{
			echo stripslashes($list['testimonial']);
		}
?>
					</td>
					<td class="column-title"><?php echo esc_html(stripslashes($list['group_name'])); ?></td>
					<td class="column-visible">
						<input type="checkbox" name="ltw_tes_show_in_widget" class="ltw_tes_show_in_widget" value="<?php echo $list['id']; ?>"<?php echo $list['show_in_widget'] == '1' ? ' checked="checked"' : ''; ?>/>
						<br />
						<img alt="" src="<?php echo bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" id="ltw_waiting_<?php echo $list['id']; ?>" class="waiting" style="display: none;">
					</td>
					<td class="column-visible">
						<input type="text" name="ltw_tes_order" class="ltw_tes_order" id="order_update_<?php echo $list['id']; ?>" value="<?php echo esc_html(stripslashes($list['order'])); ?>" style="width: 50px;" />
						<br />
						<img alt="" src="<?php echo bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" id="waiting_order_update_<?php echo $list['id']; ?>" class="waiting" style="display: none;">
						<input type="button" value="Update" id="update_<?php echo $list['id']; ?>" name="ltw_tes_update_order" class="button-secondary ltw_tes_update">
					</td>
				</tr>
<?php
	}
}
else
{
?>
				<tr><td colspan="5"><p><strong><?php _e('No testimonials found.', LTW_TES_UNIQUE_NAME); ?></strong></p></td></tr>
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
				<input type="submit" class="button-secondary action" id="doaction2" name="doaction" value="<?php _e('Apply', LTW_TES_UNIQUE_NAME); ?>">
				<?php _e('Show testimonials from group', LTW_TES_UNIQUE_NAME); ?>
				<select name="ltw_group_filter2">
					<option value="0"><?php _e('All', LTW_TES_UNIQUE_NAME); ?></option>
<?php
if (count($ltw_tes_group_info) > 0)
{
	foreach ($ltw_tes_group_info as $group)
	{
		$ltw_group_selected = '';
		if ($group['id'] == $ltw_group_sql_id)
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
				<input type="submit" class="button-secondary action" id="ltw_group_filter_action2" name="ltw_group_filter_action2" value="<?php _e('Filter', LTW_TES_UNIQUE_NAME); ?>">
			</div>
<?php
if ($ltw_testimonials_count[0]['counter'] > 0)
{
?>
			<div class="alignright">
				<?php echo $p->show(); ?>
			</div>
<?php
}
?>
		</div>
	</form>
	<p class="description"><?php _e('Image is resized to 48x48px for this page.', LTW_TES_UNIQUE_NAME); ?></p>
</div>