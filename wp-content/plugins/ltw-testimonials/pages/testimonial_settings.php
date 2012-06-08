<div class="wrap">
	<h2><?php _e('Testimonial Settings', LTW_TES_UNIQUE_NAME); ?></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
<?php
if (isset($_GET['updated']) == TRUE && $_GET['updated'] == 'true')
{
?>
	<div id="message" class="updated fade"><p><strong><?php _e('Settings Updated', LTW_TES_UNIQUE_NAME); ?></strong></p></div>
<?php
}
?>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/options.php">
		<div>
			<?php settings_fields('ltw-testimonials-settings'); ?>
		</div>
		<table class="form-table">
			<tr valign="top">
	        	<th scope="row"><?php _e('Sort testimonials on page by', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<select name="ltw_tes_sort_testimonials">
	        			<option value="1"<?php echo get_option('ltw_tes_sort_testimonials') == '1' ? ' selected="selected"' : ''; ?>><?php _e('Newest testimonials first', LTW_TES_UNIQUE_NAME); ?></option>
	        			<option value="2"<?php echo get_option('ltw_tes_sort_testimonials') == '2' ? ' selected="selected"' : ''; ?>><?php _e('Older testimonials first', LTW_TES_UNIQUE_NAME); ?></option>
	        			<option value="3"<?php echo get_option('ltw_tes_sort_testimonials') == '3' ? ' selected="selected"' : ''; ?>><?php _e('User defined', LTW_TES_UNIQUE_NAME); ?></option>
					</select>
				</td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Remove data when deactivating plugin', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<input type="checkbox" name="ltw_tes_delete_tables" value="1"<?php echo get_option('ltw_tes_delete_tables') == '1' ? ' checked="checked"' : ''; ?>/> <?php _e('All testimonials, groups and other settings will be deleted!', LTW_TES_UNIQUE_NAME); ?>
				</td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('Promote this plugin', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<input type="checkbox" name="ltw_tes_promote_plugin" value="1"<?php echo get_option('ltw_tes_promote_plugin') == '1' ? ' checked="checked"' : ''; ?>/> <?php _e('A link will be added to the end of the testimonial list.', LTW_TES_UNIQUE_NAME); ?>
				</td>
	        </tr>
	    </table>
	    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Update', LTW_TES_UNIQUE_NAME); ?>" /></p>
	</form>
</div>