<?php
get_header(); ?>
<!-- column container -->
<div class="clearfix">
		
		<?php if (have_posts()) : ?>
		<div class="column column600">
		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<p class="meta"><span class="date"><?php the_time('m.d.y') ?></span> Posted in <?php the_category(', ') ?> by <?php the_author_posts_link() ?></p>
				<div class="entry">
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
        
        </div><!-- end column 600 -->
	
	<div class="column column300 last">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-blog') ) : ?><?php endif; ?>
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-all') ) : ?><?php endif; ?>
	</div><!-- end column 300 -->
        

	<?php else : ?>

		<p class="aligncenter">Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>
		
	
	
</div>
<!-- end column container -->

<?php get_footer(); ?>

