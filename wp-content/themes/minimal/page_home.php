<?php
/*
Template Name: Home Template
*/
get_header(); ?>
<!-- column container -->
<div class="clearfix">
	
	<div class="column column600">
		<?php home_promo_list(); ?>
	</div><!-- end column 600 -->
	
	<div class="column column300 last">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-home') ) : ?><?php endif; ?>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-all') ) : ?><?php endif; ?>
	</div><!-- end column 300 -->
	
</div>
<!-- end column container -->

<?php get_footer(); ?>

