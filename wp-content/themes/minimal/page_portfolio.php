<?php
/*
Template Name: Portfolio Template
*/
get_header(); ?>

<!-- column container -->
<div class="clearfix">
	
	
	<?php include_once("subnav.php"); ?>
		<?php if(get_option('portfolio_category_id')) { $param = "cat=".get_option('portfolio_category_id'); }else{ $param = ""; } ?>
		
		<?php 
			// if custom field categories set, override portfolio category ID
			$categories = get_post_meta($post->ID, "categories", true);
			if($categories) { $param = "cat=".$categories; }
		?>
		
		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
		<?php query_posts($param . "&paged=" . $paged); ?>
		<?php global $more; $more = 0; ?>
		
		<?php while (have_posts()) : the_post(); ?>
	
		<?php if ( get_post_meta($post->ID, 'external_url', true) ) : ?>
                   <?php $external_url = get_post_meta($post->ID, 'external_url', true); ?>
                <?php endif; ?>

		<div class="clearfix">
			<div class="column column600">
				<p><a target=_blank href='<?php echo $external_url; ?>' title="Open <?php the_title(); ?>"><?php my_attachment_image(0, 'full', 'alt="' . $post->post_title . '""'); ?></a></p>
			</div>
			<div class="column column300 last">
				<h5><a target=_blank href="<?php echo $external_url; ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
                <div class="excerpt"><?php the_content(); ?></div>
       
                
				
			</div>
		</div>
        <?php edit_post_link('Edit this Post', '<p><small>', '</small></p>'); ?>
		<hr />
	<?php endwhile; ?>

	<div class="navigation">
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
	</div>

	
</div>
<!-- end column container -->

<?php get_footer(); ?>

