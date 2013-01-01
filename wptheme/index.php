<?php get_header(); ?>
      <div id='content'>
        <div id='blog-content'>
          <?php if (have_posts()) : ?>
	  <?php while (have_posts()) : the_post(); get_template_part( 'post' ); endwhile; ?>
	  <?php 
            $newer = get_previous_posts_link('&lsaquo; Newer');
            $older = get_next_posts_link('Older &rsaquo;');
            if ($newer != null || $older != null): ?>
	  <nav id="pagination" class="post">
	    <?php if ($newer != null): ?>
	    <div class='newer'><?php echo $newer ?></div>
	    <?php endif; if ($older != null): ?>
	    <div class='older'><?php echo $older ?></div>
            <?php endif; ?>
          </nav>
            <?php endif; ?>
          <?php else: ?>
          <div id="post-0" class="post no-results not-found">
            No results were found for the requested archive. Perhaps searching will help find a related post.
          </div>  
          <?php endif; ?>
        </div>
      
        <?php get_sidebar(); ?>   
      
      </div>
<?php get_footer(); ?>
