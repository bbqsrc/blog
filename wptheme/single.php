<?php get_header(); ?>
      <div id='content'>
        <div id='blog-content'><?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <?php get_template_part( 'post' ); ?>
          <?php comments_template( '', true ); ?> 
        <?php endwhile; endif; ?>
        </div>
        
        <?php get_sidebar(); ?>   
      
      </div>
<?php get_footer(); ?>
