<?php
get_header(); ?>
	
<!-- column container -->
<div class="clearfix">
	
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
		<div class="column column600">
		<h2><?php the_title(); ?></h2>
            <p class="meta"><span class="date"><?php the_time('m.d.y') ?></span> Posted in <?php the_category(', ') ?> by <?php the_author_posts_link() ?></p>
            <div class="entry">

               <?php if ( get_post_meta($post->ID, 'external_url', true) ) { ?>
                   <?php $external_url = get_post_meta($post->ID, 'external_url', true); ?>

		<p><a target=_blank href='<?php echo $external_url; ?>' title="Open <?php the_title(); ?>"><?php my_attachment_image(0, 'full', 'alt="' . $post->post_title . '""'); ?></a></p>
                <?php } ?>

                <?php the_content("Read More..."); ?>
            </div>
            <div class="clearfix"></div>
            
			<br/><br/><br/><br/>
			
            <div class="navigation clearfix">
                <div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
                <div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
            </div>
          
            <?php edit_post_link('Edit this Post', '<p><small>', '</small></p>'); ?>
            			
	<?php comments_template(); ?>

	</div><!-- end column 600 -->
	
	<div class="column column300 last">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-blog') ) : ?><?php endif; ?>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-all') ) : ?><?php endif; ?>
	</div><!-- end column 300 -->


	<?php endwhile; else: ?>
		<p class="aligncenter">Sorry, no posts matched your criteria.</p>
	<?php endif; ?>
	
</div>
<!-- end column container -->
<?php get_footer(); ?>


