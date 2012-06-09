<?php
/*
Template Name: Contact Template
*/
get_header(); ?>

<!-- column container -->
<div class="clearfix">
	
	<iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=R.+Rodrigues+Faria+103,+1300-501+Lisboa,+Portugal&amp;aq=&amp;sll=38.726067,-9.164109&amp;sspn=0.081156,0.1478&amp;t=m&amp;ie=UTF8&amp;hq=&amp;hnear=R.+Rodrigues+de+Faria+103,+Alc%C3%A2ntara,+1300+Lisboa,+Portugal&amp;ll=38.710965,-9.178905&amp;spn=0.023441,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=R.+Rodrigues+Faria+103,+1300-501+Lisboa,+Portugal&amp;aq=&amp;sll=38.726067,-9.164109&amp;sspn=0.081156,0.1478&amp;t=m&amp;ie=UTF8&amp;hq=&amp;hnear=R.+Rodrigues+de+Faria+103,+Alc%C3%A2ntara,+1300+Lisboa,+Portugal&amp;ll=38.710965,-9.178905&amp;spn=0.023441,0.036478&amp;z=14&amp;iwloc=A" style="color:#0000FF;text-align:left"></a></small>


	
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


















