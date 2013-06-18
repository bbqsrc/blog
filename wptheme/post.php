          <div class='post'>
            <header>
              <div class='title'>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
                <?php 
                $subtitle = get_post_meta(get_the_ID(), '_brendan_subtitle', true);
                $is_post = (get_post_type() == 'post');
                if ($subtitle != "" || $is_post): ?>
                <div class='byline'>
                  <?php if ($subtitle != '') {
                    echo "<div>" . $subtitle . "</div>";
                  }
                  if ($is_post):?>
                  <em>
                    <a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>">
                      <time datetime="<?php echo get_the_time('Y-m-d'); ?>"><?php echo get_the_time('j F, Y'); ?></time>
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
                <?php else: ?>
                </div>
                <?php endif; endif; ?>
              </div>
            </header>
            
            <article>
              <?php the_content(''); ?>
            </article>
            
            <?php if (!is_single()): if ($pos=strpos($post->post_content, '<!--more-->')): ?>
            <div class='continue'>
              <a href="<?php the_permalink(); ?>">Continue reading '<?php the_title(); ?>' &rsaquo;</a>
            </div>
            <?php endif; else: 
              $prev_post = get_previous_post();
              $next_post = get_next_post();
            ?>
            <footer>
              <?php if (!empty($next_post)): ?>
              <div class="next">
                <a href="<?php echo get_permalink($next_post->ID) ?>">&lsaquo; <?php echo $next_post->post_title ?></a>
              </div>
              <?php endif; if (!empty($prev_post)): ?>
              <div class="previous">
                <a href="<?php echo get_permalink($prev_post->ID) ?>"><?php echo $prev_post->post_title ?> &rsaquo;</a>
              </div>
              <?php endif; ?>
            </footer>
            <?php endif; ?>
          </div>
