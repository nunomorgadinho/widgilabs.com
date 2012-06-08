<?php
/**
 * Update for "Show in Widget"
 *
 */
function ltw_tes_widget_visible()
{
	global $wpdb;

	$pops_nonce = $_POST['nonce'];

	//	Check to see if the nonce matches
	if (wp_verify_nonce($pops_nonce, 'ltw_tes_ajax_nonce') == FALSE)
		die(__('Oops, you are not allowed to do that!', LTW_TES_UNIQUE_NAME));

	//	Get the ID of the testimonial
	$ltw_tes_id = isset($_POST['id']) == TRUE ? intval($_POST['id']) : 0;

	//	ID should never be 0!
	if ($ltw_tes_id != 0)
	{
		//	Is checkbox checked or not? :)
		$ltw_tes_checked = isset($_POST['checked']) == TRUE && $_POST['checked'] == 'true' ? '1' : '0';

		$sql = $wpdb->prepare("
			UPDATE `".LTW_TES_TESTIMONIALS_TABLE."`
			SET `show_in_widget` = %d
			WHERE `id` = %d
			LIMIT 1",
			array($ltw_tes_checked, $ltw_tes_id)
		);
		$wpdb->query($sql);

		echo $ltw_tes_id;
	}

	die();
}
add_action('wp_ajax_ltw_tes_widget_visible', 'ltw_tes_widget_visible');

/**
 * Update client testimonial order
 *
 */
function ltw_tes_update_order()
{
	global $wpdb;

	$pops_nonce = $_POST['nonce'];

	//	Check to see if the nonce matches
	if (wp_verify_nonce($pops_nonce, 'ltw_tes_ajax_nonce') == FALSE)
		die(__('Oops, you are not allowed to do that!', LTW_TES_UNIQUE_NAME));

	//	Get the ID of the testimonial
	$ltw_tes_id = isset($_POST['id']) == TRUE ? intval($_POST['id']) : 0;

	//	ID should never be 0!
	if ($ltw_tes_id != 0)
	{
		//	Get the order
		$ltw_tes_order = isset($_POST['order']) == TRUE ? intval($_POST['order']) : '0';

		$sql = $wpdb->prepare("
			UPDATE `".LTW_TES_TESTIMONIALS_TABLE."`
			SET `order` = %d
			WHERE `id` = %d
			LIMIT 1",
			array($ltw_tes_order, $ltw_tes_id)
		);
		$wpdb->query($sql);

		echo $ltw_tes_id;
	}

	die();
}
add_action('wp_ajax_ltw_tes_update_order', 'ltw_tes_update_order');
?>