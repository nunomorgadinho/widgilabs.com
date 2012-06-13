<?php
get_header(); ?>
	
<!-- column container -->
<div class="clearfix">
	
	<?php include_once("subnav.php"); ?>
	
	<div class="column column600">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post" id="post-<?php the_ID(); ?>">
			
				<div class="entry">
					<?php the_content('<p>Read the rest of this page &raquo;</p>'); ?>
	
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	
				</div>
			</div>
            
			<?php endwhile; else: ?>
            <p class="aligncenter">Sorry, this page can't be found.</p>
            <?php endif; ?>
		<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>
	</div><!-- end column 600 -->
	
	<div class="column column300 last">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-pages') ) : ?><?php endif; ?>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-all') ) : ?><?php endif; ?>
	</div><!-- end column 300 -->
	
</div>
<!-- end column container -->

<?php get_footer(); ?>