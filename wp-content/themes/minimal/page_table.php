<?php
/*
Template Name: Table Column Template
*/
get_header(); ?>
<!-- column container -->
<div class="clearfix">

	<h2><?php the_title(); ?></h2>
	<?php include_once("subnav.php"); ?>
	
	
	<div class="column column450">
		<?php company_table('left'); ?>
	</div><!-- end column 450 -->
	
	<div class="column column450 last">
	<?php company_table('right'); ?>
		
	</div><!-- end column 450 -->
	
</div>
<!-- end column container -->

<?php get_footer(); ?>