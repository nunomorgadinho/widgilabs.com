<?php
/*
Template Name: Portfolio Basic Template
*/
get_header(); ?>
<?php /* THIS TEMPLATE USES THE BASIC LAYOUT WITHOUT TIMTHUMB.PHP FOR IMAGE RESIZING */ ?>
<!-- column container -->
<div class="clearfix">
	
	
	<h2><?php the_title(); ?></h2>
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
	
				
		<div class="clearfix">
			<div class="column column600">
            	<?php $custom_image = ""; $custom_image = get_post_meta($post->ID, "image", true); ?>
                <?php if($custom_image) { ?>
                	<p><a href='<?php the_permalink() ?>' title="View Details for <?php the_title(); ?>"><img src="<?php echo $custom_image; ?>" alt="<?php the_title(); ?>" /></a></p>
                <?php }else{ ?>
                	<p>Please update this post with a custom field of <strong>image</strong>.</p>
                <?php } ?>
				
			</div>
			<div class="column column300 last">
				<h5><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
                <div class="excerpt"><?php the_excerpt(); ?></div>
                <p class="tags"><strong>Project tags:</strong><br /><small><?php echo the_tags('',' ',''); ?></small></p>
                <p><a href='<?php the_permalink() ?>' title="View Details for <?php the_title(); ?>"><strong>View Details</strong></a></p>
				
			</div>
		</div>
        <?php edit_post_link('Edit this Post', '<p><small>', '</small></p>'); ?>
	<?php endwhile; ?>

	<div class="navigation">
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
	</div>

	
</div>
<!-- end column container -->

<?php get_footer(); ?>


















