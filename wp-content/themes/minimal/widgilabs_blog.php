<?php
/*
Template Name: WidgiLabs Blog
*/
get_header(); ?>

<!-- column container -->
<div class="clearfix">
	
	<h2><?php the_title(); ?></h2>
	<?php include_once("subnav.php"); ?>
	  
	<div class="column column600">
		<?php 

		$folio_cat_ids = get_option('portfolio_category_id');
		$exclude_cats = $folio_cat_ids.','.'4'.','.'5';
		$company_cat_arr = explode(',',$exclude_cats);
	
		if(!get_option('portfolio_category_id')) { $param = "cat=-4,-5"; }else{ $param = "cat=-7,-4,-5"; } ?>
		<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
		<?php query_posts($param . "&paged=" . $paged); ?>
		
		
		<?php global $more; $more = 0; ?>
		
		<?php while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<span class="commentslink right"><small><?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></small></span>
				<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				<p class="meta"><span class="date"><?php the_time('m.d.y') ?></span> Posted in <?php the_category(', ') ?> by <?php the_author_posts_link() ?></p>
				<div class="entry">
					<?php the_content("Read More..."); ?>
				</div>
				<?php edit_post_link('Edit this Post', '<p><small>', '</small></p>'); ?>
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
	
</div>
<!-- end column container -->

<?php get_footer(); ?>
