</div><!-- end contentwidth -->

<div class="footer">
    <div class="contentwidth">
        <p><?php $footer_copy = get_option("footer_copy"); if(!$footer_copy) { echo "<strong>". get_bloginfo('name') . " - Design by <a href='http://www.curtziegler.com/' target='_blank'>Curt Ziegler</a> - Powered by <a href='http://wordpress.org/'>WordPress</a>.</strong>"; }else{ echo $footer_copy; } ?></p>
        <ul class="footer-menu">
            <?php wp_list_pages('exclude=39&title_li=&depth=1' ); ?>
        </ul>
    </div>
</div><!-- end footer -->
    
</div><!-- end outer -->

<!-- INSERT ANALYTICS -->
<?php wp_footer(); ?>
</body>
</html>
