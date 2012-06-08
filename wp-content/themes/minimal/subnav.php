<?php
	if ($post->post_parent)	{
		$ancestors=get_post_ancestors($post->ID);
	
		$root=count($ancestors)-1;
		$parent = $ancestors[$root];
	} else {
		$parent = $post->ID;
	}
	
	$children = wp_list_pages("title_li=&child_of=". $parent ."&echo=0");
	if ($children) { ?>
	<hr />
	<ul class="sub-menu">
		<?php echo $children; ?>
	</ul>
<?php } ?>