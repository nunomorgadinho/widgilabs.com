<?php
/*
Template Name: Single Column Template
*/
get_header(); ?>
<!-- column container -->
<div class="clearfix">
	
	<h2><?php the_title(); ?></h2>
	<?php include_once("subnav.php"); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>
	
</div>
<!-- end column container -->

<?php get_footer(); ?>
