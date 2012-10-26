        <aside id="sidebar" class="widget-area">
          <?php if (!dynamic_sidebar('sidebar')): ?>
				  <div id="archives" class="widget">
					  <h3 class="widget-title">Archives</h3>
					  <ul>
						  <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					  </ul>
			  	</div>
			    <?php endif; ?>
		    </aside>
