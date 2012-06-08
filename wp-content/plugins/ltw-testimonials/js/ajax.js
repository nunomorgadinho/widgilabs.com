jQuery(document).ready(function($) {
	$('.ltw_tes_show_in_widget').click(function(){
		var clicked_id = $(this).val();
		var item_checked = false;

		$('#ltw_waiting_'+clicked_id).show();

		if ($(this).attr("checked") == 'checked') {
			item_checked = true;
		}

		$.post(
			ltw_tes_ajax.ajaxurl,
			{
				action: 'ltw_tes_widget_visible',
				id: $(this).val(),
				nonce: ltw_tes_ajax.nonce,
				checked: item_checked
			},
			function(data) {
				if (data == clicked_id) {
					$('#ltw_waiting_'+clicked_id).hide();
				}
			}
		);
	});

	$('.ltw_tes_update').click(function(){
		var clicked_id = $(this).attr('id');
		$('#waiting_order_'+clicked_id).show();

		$.post(
			ltw_tes_ajax.ajaxurl,
			{
				action: 'ltw_tes_update_order',
				id: clicked_id.replace("update_", ""),
				nonce: ltw_tes_ajax.nonce,
				order: $('#order_'+clicked_id).val()
			},
			function(data) {
				$('#waiting_order_'+clicked_id).hide();
			}
		);
	});
});