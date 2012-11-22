          <div class='post'>
            <header>
              <div class='title'>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
                <?php if ('post' == get_post_type()): ?>
                <div class='byline'>
                  <em>
                    <a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>">
                      <?php echo get_the_time('j F, Y'); ?>
                    </a>
                  </em>
                  &mdash;
                  <?php the_author_posts_link(); ?>
                </div>
              </div>
              <div class='info'>
                <p class="categories"><?php the_category(', ') ?></p>
                <p class="tags"><?php the_tags('', ', '); ?></p>

                <a class="comments" href="<?php echo (is_home() ? the_permalink() : '' ).'#comments' ?>">
                  <?php echo get_comments_number(); ?> <img src="<?php echo get_template_directory_uri(); ?>/img/comment.png" alt="comments">
                </a>
                <?php endif; ?>
              </div>
            </header>
            
            <article>
              <?php the_content(''); ?>
            </article>
            
            <?php if (!is_single() && $pos=strpos($post->post_content, '<!--more-->')): ?>
            <div class='continue'>
              <a href="<?php the_permalink(); ?>">Continue reading '<?php the_title(); ?>' &rsaquo;</a>
            </div>
            <?php endif; ?>
          </div>
