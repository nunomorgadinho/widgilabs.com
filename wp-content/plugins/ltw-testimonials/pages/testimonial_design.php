<div class="wrap">
	<h2><?php _e('Testimonial Design', LTW_TES_UNIQUE_NAME); ?></h2>
	<div id="ltw_tes_quick_links">
		<?php include('quick_links_right.php'); ?>
	</div>
	<div class="error fade"><p><strong><?php _e('Be aware that by changing the HTML and CSS incorrectly it may mess up your blog layout. Be careful!<br />If you are unsure about something, ask someone who knows.', LTW_TES_UNIQUE_NAME); ?></strong></p></div>
<?php
if (isset($_GET['updated']) == TRUE && $_GET['updated'] == 'true')
{
?>
	<div id="message" class="updated fade"><p><strong><?php _e('Design Updated', LTW_TES_UNIQUE_NAME); ?></strong></p></div>
<?php
}
?>
	<form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/options.php">
		<div>
			<?php settings_fields('ltw-testimonials-design'); ?>
		</div>
		<table class="form-table">
			<tr valign="top">
	        	<th scope="row"><?php _e('HTML Code', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td>
	        		<textarea class="large-text" cols="20" rows="10" name="ltw_tes_design_html" id="ltw_tes_design_html"><?php echo get_option('ltw_tes_design_html'); ?></textarea>
					<br />
					<span class="description">
						<?php _e('There are some template variables that you need to include in the HTML.', LTW_TES_UNIQUE_NAME); ?><br /><br />
						%image% = <?php _e('It will display the URL to the image.', LTW_TES_UNIQUE_NAME); ?><br />
						%testimonial% = <?php _e('Displays the testimonial text.', LTW_TES_UNIQUE_NAME); ?><br />
						%client_name% = <?php _e('Displays the client name.', LTW_TES_UNIQUE_NAME); ?><br />
						%client_company% = <?php _e('It will display either the company name, the url to the company name or company name as a link to their website. It depends on what you have entered for this two fields.', LTW_TES_UNIQUE_NAME); ?><br />
					</span>
				</td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row"><?php _e('CSS Code', LTW_TES_UNIQUE_NAME); ?></th>
	        	<td><textarea class="large-text" cols="20" rows="10" name="ltw_tes_design_css" id="ltw_tes_design_css"><?php echo get_option('ltw_tes_design_css'); ?></textarea></td>
	        </tr>
	    </table>
	    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Update', LTW_TES_UNIQUE_NAME); ?>" /></p>
	</form>
</div>