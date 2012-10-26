<?php get_header(); ?>
      <div id='content'>
        <div id='blog-content'>
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); get_template_part( 'post' ); endwhile; ?>
          <?php else: ?>
          <div id="post-0" class="post no-results not-found">
            No results were found for the requested archive. Perhaps searching will help find a related post.
          </div>  
          <?php endif; ?>
        </div>
      
        <?php get_sidebar(); ?>   
      
      </div>
<?php get_footer(); ?>
