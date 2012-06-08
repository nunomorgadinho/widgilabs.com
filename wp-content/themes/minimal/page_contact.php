<?php
/*
Template Name: Contact Template
*/
get_header(); ?>

<!-- column container -->
<div class="clearfix">
	
	
	<h2><?php the_title(); ?></h2>
	<?php include_once("subnav.php"); ?>
	
	<div class="column column600">
    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>
        <?php mail_form(); ?>
		<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>

	</div><!-- end column 600 -->
	
	<div class="column column300 last">
<h6>Address</h6>
    WidgiLabs Lda<br/>
    LX Factory | Rua Rodrigues Faria, 103 Edifício I - 4º Piso
    1300-501 Lisboa
<br/><br/>
Tel: 216066960
<br/><br/>
           <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-all') ) : ?><?php endif; ?>
	</div><!-- end column 300 -->
	
</div>
<!-- end column container -->

<?php get_footer(); ?>


















